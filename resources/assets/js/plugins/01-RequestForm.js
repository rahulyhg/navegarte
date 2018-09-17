/**
 * Cria uma requisição ajax
 *
 * @param {Object} click
 * @param {string} url
 * @param {Object} formData
 * @param {string} method
 * @param {Object} form
 * @param {Boolean} insertHtml
 * @param {String|Boolean} bootstrapModal
 */

var requestAjax = function (click, url, formData, method, form, insertHtml, bootstrapModal) {
  /* Verifica URL */
  if (!url || url === null) {
    alert('Não encontramos a URL para essa requisição.');
    
    return;
  }
  
  /* Button */
  var html     = click.html(),
      loadding = (click.attr('vc-loadding') ? click.attr('vc-loadding') : (insertHtml ? html : false)),
      message;
  
  /* Message */
  if (typeof bootstrapModal === 'string' && bootstrapModal.length > 1) {
    message = $(bootstrapModal).find('.modal-body');
  } else {
    if (form.length > 0) {
      message = form.find('#vc-message');
      
      if (message.length <= 0) {
        message = form.parent().parent().find('#vc-message');
      }
    } else if (click.parent().parent().find('#vc-message').length > 0) {
      message = click.parent().parent().find('#vc-message');
    } else if (click.attr('vc-message')) {
      message = $(click.attr('vc-message'));
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
      if (message !== undefined && message.length > 0 && !bootstrapModal) {
        message.fadeOut(0).html('');
      }
      
      if (loadding) {
        click.html(loadding);
      }
      
      click.attr('disabled', true);
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
            if (bootstrapModal.length > 1) {
              $(bootstrapModal).find('#' + key).html(value);
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
        if (bootstrapModal.length > 1) {
        
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
      if (json.data) {
        if (typeof json.data === 'object') {
          $.each(json.data, function (key, value) {
            var element = $(value);
            
            switch (key) {
              /* Hide */
              case 'hide':
                element.hide();
                break;
              
              /* Show */
              case 'show':
                element.show();
                break;
              
              /* Toggle */
              case 'toggle':
                element.toggle();
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
        window.location.href = json.location;
      }
      
      /* Recarrega a página atual */
      if (json.reload) {
        window.location.reload();
      }
    },
    complete: function () {
      if (loadding) {
        click.html(html);
      }
      
      click.attr('disabled', false);
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
    
    requestAjax(element, url, formData, method, {}, false, false);
  });
  
  /* Dispara o request ao clicar (click) */
  $(document).on('click', '*', function (event) {
    var $this = $(this);
    var form = '';
    var url = '';
    var method = '';
    var formData = new FormData();
    var inputName;
    
    /* Elementos desabilitados */
    if ($this.attr('disabled')) {
      return;
    }
    
    /* Verifica se é para confirmar a ação */
    if ($this.attr('vc-confirm') !== undefined && ($this.attr('vc-confirm') === '' || $this.attr('vc-confirm'))) {
      var verify = confirm(($this.attr('vc-confirm').length > 0) ? $this.attr('vc-confirm') : 'Cuidado!!!\nDeseja realizar essa ação?');
      
      if (verify === false) {
        return;
      }
    }
    
    /* REQUEST :: FORM */
    if ($this.attr('vc-form') !== undefined && ($this.attr('vc-form') === '' || $this.attr('vc-form'))) {
      event.preventDefault(event);
      
      /* Variáveis */
      form = ($this.attr('vc-form') && $this.attr('vc-form').length > 0) ? $('form[name="' + $this.attr('vc-form') + '"]') : $this.closest('form');
      method = form.attr('method') ? form.attr('method').toUpperCase() : 'POST';
      url = form.attr('action') ? form.attr('action') : null;
      
      /* Verifica o formulário */
      if (form.length <= 0) {
        alert('Formulário com ([name="' + $this.attr('vc-form') + '"]) não foi encontrado em seu documento html.');
        
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
      
      requestAjax($this, url, formData, 'POST', form, true, false);
    }
    
    /* REQUEST :: GET */
    if ($this.attr('vc-get') !== undefined && ($this.attr('vc-get') === '' || $this.attr('vc-get'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      requestAjax($this, $this.attr('vc-get'), formData, 'GET', form, true, false);
    }
    
    /* REQUEST :: POST */
    if ($this.attr('vc-post') !== undefined && ($this.attr('vc-post') === '' || $this.attr('vc-post'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      requestAjax($this, $this.attr('vc-post'), formData, 'POST', form, true, false);
    }
    
    /* REQUEST :: DELETE */
    if ($this.attr('vc-delete') !== undefined && ($this.attr('vc-delete') === '' || $this.attr('vc-delete'))) {
      event.preventDefault(event);
      
      verify = confirm('Cuidado!!!\nDeseja deletar esse registro?');
      if (verify === false) {
        return;
      }
      
      /* Envia requisição */
      formData.append('_METHOD', 'DELETE');
      
      requestAjax($this, $this.attr('vc-delete'), formData, 'POST', form, true, false);
    }
  });
});
