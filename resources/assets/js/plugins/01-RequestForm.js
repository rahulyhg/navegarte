/**
 * Cria uma requisição ajax
 *
 * @param {Object} element
 * @param {string} url
 * @param {Object} formData
 * @param {string} method
 * @param {Object} form
 * @param {Boolean} change
 * @param {Object} modal
 */

var vcAjax = function (element, url, formData, method, form, change, modal) {
  /* Verifica URL */
  if (!url || url === null) {
    alert('Não encontramos a URL para essa requisição.');
    
    return;
  }
  
  /* Variáveis */
  var html = element.html();
  var loadding = (element.attr('vc-loadding') ? element.attr('vc-loadding') : (change ? false : html));
  var message;
  
  /* Message */
  if (modal) {
    message = modal.find('.modal-body');
  } else {
    if (form.length > 0) {
      message = form.find('#vc-message');
      
      if (message.length <= 0) {
        message = form.parent().parent().find('#vc-message');
      }
    } else if (element.parent().parent().find('#vc-message').length > 0) {
      message = element.parent().parent().find('#vc-message');
    } else if (element.attr('vc-message')) {
      message = $(element.attr('vc-message'));
    }
  }
  
  /* Custom _METHOD */
  var _METHOD;
  
  if (formData.has('_METHOD')) {
    _METHOD = formData.get('_METHOD');
    
    formData.delete('_METHOD');
  } else {
    _METHOD = method;
  }
  
  /* Ajax */
  $.ajax({
    url: url,
    data: formData,
    dataType: 'json',
    type: method,
    enctype: 'multipart/form-data',
    headers: {
      'X-Http-Method-Override': _METHOD
    },
    processData: false,
    contentType: false,
    beforeSend: function () {
      if (message !== undefined && message.length > 0 && !modal) {
        message.fadeOut(0).html('');
      }
      
      if (loadding) {
        element.html(loadding);
      }
      
      element.attr('disabled', true);
    },
    success: function (json) {
      /* Adiciona no localStorage */
      if (json.storage) {
        if (json.storage[0] === 'remove') {
          window.Storage.remove(json.storage[1]);
        } else {
          window.Storage.set(json.storage[0], json.storage[1]);
        }
      }
      
      /* Percore os id da div preenchendo seus dados */
      if (json.object) {
        if (typeof json.object === 'object') {
          $.each(json.object, function (key, value) {
            if (modal) {
              modal.find('#' + key).html(value);
            } else {
              if ($('input[id="' + key + '"]').length > 0) {
                $('input[id="' + key + '"]').val(value);
              } else {
                $('#' + key).html(value);
              }
            }
          });
        }
        
        /* Inicia plugins caso for a modal */
        if (modal) {
        
        }
      }
      
      /* Limpa formulário */
      if (json.clear && form.length > 0) {
        form.trigger('reset');
      }
      
      /* Mensagem de retorno */
      if (json.trigger) {
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-' + json.trigger[0] + '">' + json.trigger[1] + '</div>').fadeIn(0);
        } else {
          alert(json.trigger[1]);
        }
      }
      
      /* Mensagem de error */
      if (json.error) {
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-danger">' + json.error.message + '</div>').fadeIn(0);
        } else {
          alert(json.error.message);
        }
      }
      
      /* Ações diversas */
      if (json.switch) {
        if (typeof json.switch === 'object') {
          $.each(json.switch, function (key, value) {
            switch (key) {
              /* Hide */
              case 'hide':
                $(value).hide();
                break;
              
              /* Show */
              case 'show':
                $(value).show();
                break;
              
              /* Toggle */
              case 'toggle':
                $(value).toggle();
                break;
              
              /* Eval */
              case 'eval':
                eval(value);
                break;
            }
          });
        }
      }
      
      /* Redireciona para uma nova página */
      if (json.location) {
        if (typeof loadPage !== 'undefined' && typeof loadPage === 'function') {
          loadPage(json.location, true);
        } else {
          window.location.href = json.location;
        }
      }
      
      /* Recarrega a página atual */
      if (json.reload) {
        window.location.reload();
      }
    },
    complete: function () {
      if (loadding) {
        element.html(html);
      }
      
      element.attr('disabled', false);
    },
    error: function (xhr) {
      var parse;
      
      try {
        parse = JSON.parse(xhr.responseText);
        
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-danger">' + parse.error.message + '</div>').fadeIn(0);
        } else {
          alert(parse.error.message);
        }
      } catch (e) {
        parse = JSON.parse(JSON.stringify(xhr.responseText));
        
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-danger">' + parse + '</div>').fadeIn(0);
        } else {
          alert('Não foi possível completar a requisição, tente novamente em alguns minutos.');
          
          console.log(parse);
        }
      }
    }
  });
};

/* Carrega o documento */
$(document).ready(function () {
  /* Dispara o request ao realizar uma mudança (change) */
  $(document).on('change', '*[vc-change]', function (event) {
    event.preventDefault();
    event.stopPropagation();
    
    /* Variáveis */
    var element = $(this);
    var url = element.attr('vc-change');
    var method = element.attr('vc-method') ? element.attr('vc-method').toUpperCase() : 'POST';
    var formData = new FormData();
    
    /* FormData */
    formData.append('value', (element.val() !== undefined ? element.val() : ''));
    
    /* Realiza a requisição */
    formData.append('_METHOD', method);
    
    vcAjax(element, url, formData, method, {}, true, false);
  });
  
  /* Dispara o request ao clicar (click) */
  $(document).on('click', '*', function (event) {
    var element = $(this);
    var form = '';
    var url = '';
    var method = '';
    var formData = new FormData();
    var inputName;
    
    /* Elementos desabilitados */
    if (element.attr('disabled')) {
      return;
    }
    
    /* Verifica se é para confirmar a ação */
    if (element.attr('vc-confirm') !== undefined && (element.attr('vc-confirm') === '' || element.attr('vc-confirm'))) {
      var verify = confirm((element.attr('vc-confirm').length > 0) ? element.attr('vc-confirm') : 'Cuidado!!!\nDeseja realizar essa ação?');
      
      if (verify === false) {
        return;
      }
    }
    
    /* REQUEST :: FORM */
    if (element.attr('vc-form') !== undefined && (element.attr('vc-form') === '' || element.attr('vc-form'))) {
      event.preventDefault(event);
      
      /* Variáveis */
      form = (element.attr('vc-form') && element.attr('vc-form').length > 0) ? $('form[name="' + element.attr('vc-form') + '"]') : element.closest('form');
      method = form.attr('method') ? form.attr('method').toUpperCase() : 'POST';
      url = form.attr('action') ? form.attr('action') : null;
      
      /* Verifica o formulário */
      if (form.length <= 0) {
        alert('Formulário com ([name="' + element.attr('vc-form') + '"]) não foi encontrado em seu documento html.');
        
        return;
      }
      
      /* Cria o FormData */
      form.find('*').each(function (key, element) {
        if ($(element).attr('name')) {
          if ($(element).prop('type') === 'checkbox') {
            if ($(element).prop('checked') !== false) {
              formData.append($(element).attr('name'), $(element).val());
            }
          } else if ($(element).prop('type') === 'radio') {
            if ($(element).prop('checked') !== false) {
              formData.append($(element).attr('name'), $(element).val());
            }
          } else if ($(element).prop('type') === 'file') {
            var files = $(element).prop('files');
            
            if (files !== undefined && files.length > 0) {
              for (var i = 0; i <= files.length; i++) {
                formData.append($(element).attr('name'), files[i]);
              }
            }
          } else if ($(element).tagName && $(element).tagName.toLowerCase() === 'textarea') {
            inputName = $(element).attr('name');
            
            if ((typeof CKEDITOR !== 'undefined') && CKEDITOR.instances[inputName] !== undefined) {
              formData.append(inputName, CKEDITOR.instances[inputName].getData());
            } else {
              formData.append(inputName, $(element).html() === null ? '' : $(element).html());
            }
          } else {
            inputName = $(element).attr('name');
            
            if ((typeof CKEDITOR !== 'undefined') && CKEDITOR.instances[inputName] !== undefined) {
              formData.append(inputName, CKEDITOR.instances[inputName].getData());
            } else {
              formData.append(inputName, $(element).val() === null ? '' : $(element).val());
            }
          }
        }
      });
      
      /* Envia requisição */
      formData.append('_METHOD', method);
      
      vcAjax(element, url, formData, 'POST', form, false, false);
    }
    
    /* REQUEST :: GET */
    if (element.attr('vc-get') !== undefined && (element.attr('vc-get') === '' || element.attr('vc-get'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      vcAjax(element, element.attr('vc-get'), formData, 'GET', form, false, false);
    }
    
    /* REQUEST :: POST */
    if (element.attr('vc-post') !== undefined && (element.attr('vc-post') === '' || element.attr('vc-post'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      vcAjax(element, element.attr('vc-post'), formData, 'POST', form, false, false);
    }
    
    /* REQUEST :: DELETE */
    if (element.attr('vc-delete') !== undefined && (element.attr('vc-delete') === '' || element.attr('vc-delete'))) {
      event.preventDefault(event);
      
      verify = confirm('Cuidado!!!\nDeseja deletar esse registro?');
      if (verify === false) {
        return;
      }
      
      /* Envia requisição */
      formData.append('_METHOD', 'DELETE');
      
      vcAjax(element, element.attr('vc-delete'), formData, 'POST', form, false, false);
    }
  });
});
