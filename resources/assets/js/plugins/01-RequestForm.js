/**
 * Pega href no elemento
 *
 * @param {Object} element
 * @param {String} verbo
 *
 * @returns {*|void}
 */
function getLocationFromElement (element, verbo) {
  verbo = verbo || 'get';
  var location = element.attr('vc-' + verbo);
  var href = element.attr('href') || element.data('href') || '';
  var hash = href.substr(0, 1) === '#';
  
  if (!hash && href) {
    if (!href.match(/javascript/g)) {
      location = href;
    }
  }
  
  return location;
}

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

function vcAjax (element, url, formData, method, form, change, modal) {
  /* Verifica URL */
  if (!url || url === null) {
    alert('Não encontramos a URL para essa requisição.');
    
    return;
  }
  
  /* Variáveis */
  var html = element.html();
  var loadding = (element.attr('vc-loadding') !== undefined
    ? (element.attr('vc-loadding')
      ? element.attr('vc-loadding')
      : 'Aguarde...')
    : (change ? false : html));
  var message;
  var headers = {};
  
  /* Upload file */
  var enableUpload = (element.attr('vc-upload') !== undefined);
  
  /* Cancelar requisição */
  var ajaxAbort = element.parent().parent().parent().find('*[vc-abort]');
  
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
  
  // Verifica se tem formData no element
  if (element.attr('vc-data') !== undefined && (element.attr('vc-data') === '' || element.attr('vc-data'))) {
    var vcData = getJSON(element.attr('vc-data'));
    
    if (!vcData) {
      alert('Atributo vc-data no elemento clicado não é um JSON válido.');
      
      return;
    }
    
    for (var key in vcData) {
      if (vcData.hasOwnProperty(key)) {
        if (vcData[key] !== undefined && vcData[key] !== '') {
          formData.append(key, vcData[key]);
        }
      }
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
  
  /* CSRF Protect */
  var _csrfToken = '';
  
  if (formData.has('_csrfToken')) {
    _csrfToken = formData.get('_csrfToken');
    formData.delete('_csrfToken');
  } else {
    _csrfToken = $('meta[name="_csrfToken"]').attr('content') || '';
  }
  
  if (_csrfToken !== '') {
    headers['X-Csrf-Token'] = _csrfToken;
  }
  
  /* Headers */
  headers['X-Http-Method-Override'] = _METHOD.toUpperCase();
  
  if (formData.has('_HEADERS')) {
    var jsonHeader = getJSON(formData.get('_HEADERS'));
    
    if (jsonHeader) {
      for (key in jsonHeader) {
        if (jsonHeader.hasOwnProperty(key)) {
          headers[key] = jsonHeader[key];
        }
      }
    }
    
    formData.delete('_HEADERS');
  }
  
  /* Ajax */
  var ajaxRequest = $.ajax({
    url: url,
    data: formData,
    dataType: 'json',
    type: method.toUpperCase(),
    enctype: 'multipart/form-data',
    headers: headers,
    cache: false,
    contentType: false,
    processData: false,
    
    xhr: function () {
      var xhr = $.ajaxSettings.xhr();
      
      /* Upload progress */
      if (enableUpload) {
        var startTime = new Date().getTime();
        
        xhr.upload.addEventListener('progress', function (e) {
          if (e.lengthComputable && enableUpload) {
            var diffTime = (new Date().getTime() - startTime);
            var uploadPercent = parseInt((e.loaded / e.total) * 100);
            var durationTime = (((100 - uploadPercent) * diffTime) / uploadPercent);
            // var calculateTimeFormat = calculateTimeUpload(durationTime);
            
            if (uploadPercent === 100) {
              if (ajaxAbort !== undefined) {
                ajaxAbort.fadeOut(0);
              }
              
              console.log(diffTime, uploadPercent, durationTime);
            }
          }
        }, false);
        
      }
      
      return xhr;
    },
    
    beforeSend: function () {
      /* Limpa mensagens */
      if (message !== undefined && message.length > 0 && !modal) {
        message.fadeOut(0).html('');
      }
      
      /* Adiciona mensagem do loadding */
      if (loadding) {
        element.html(loadding);
      }
      
      /* Desabilita o elemento clicado/modificado */
      element.attr('disabled', true);
      
      /* Mostra o botão/link para cancelar a requisição */
      if (ajaxAbort !== undefined) {
        ajaxAbort.fadeIn(0);
      }
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
        element.attr('disabled', false);
        
        if (typeof json.object === 'object') {
          window.setTimeout(function () {
            $.each(json.object, function (key, value) {
              if (modal) {
                modal.find('#' + key).html(value);
              } else {
                if ($(document).find('input[id="' + key + '"]').length > 0) {
                  $(document).find('input[id="' + key + '"]').val(value);
                } else {
                  $(document).find('#' + key).html(value);
                }
              }
              
              /* Masks */
              if ($(document).find('#' + key).find('*[class*="mask"]').length) {
                initMaskInput();
              }
              
              /* Select 2 */
              if ($(document).find('#' + key).find('*[data-toggle="select2"]').length) {
                initSelect2($(document).find('*[data-toggle="select2"]'));
              }
            });
          }, 500);
        }
        
        /* Inicia plugins caso for a modal */
        if (modal) {
          //
        }
      }
      
      /* Limpa formulário */
      if (json.clear && form.length > 0) {
        form.trigger('reset');
        form.find('*[data-toggle="select2"]').trigger('change');
      }
      
      /* Mensagem de retorno ou erro */
      if (json.trigger || json.error) {
        var errorMessage = '';
        var errorType = '';
        
        if (json.trigger) {
          errorMessage = json.trigger[1];
          errorType = json.trigger[0];
        } else if (json.error) {
          errorMessage = json.error.message;
          errorType = json.error.type || 'danger';
        }
        
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-' + errorType + '">' + errorMessage + '</div>').fadeIn(0);
        } else {
          alert(errorMessage);
        }
      }
      
      /* Ações diversas */
      if (json.switch) {
        if (typeof json.switch === 'object') {
          $.each(json.switch, function (key, value) {
            switch (key) {
              /* Hide */
              case 'scrolltop':
                $('html,body').animate({
                  scrollTop: ($(value).offset().top - 20),
                }, 500);
                break;
              
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
        if (typeof loadPage !== 'undefined' && typeof loadPage === 'function' && !json.noajaxpage) {
          loadPage((window.history.state && window.history.state.content) || '#content-ajax', json.location, true);
        } else {
          window.location.href = json.location;
        }
      }
      
      /* Recarrega a página atual */
      if (json.reload) {
        if (typeof loadPage !== 'undefined' && typeof loadPage === 'function' && !json.noajaxpage) {
          loadPage((window.history.state && window.history.state.content) || '#content-ajax', false, true);
        } else {
          window.location.reload();
        }
      }
    },
    
    complete: function () {
      /* Adiciona o html padrão do elemento clicado/modificado */
      if (loadding) {
        element.html(html);
      }
      
      /* Habilita novamente o elemento clicado/modificado */
      element.attr('disabled', false);
      
      /* Oculta o botão/link para cancelar a requisição */
      if (ajaxAbort !== undefined) {
        ajaxAbort.fadeOut(0);
      }
      
      /* Carrega js armazenado */
      if (typeof loadHtmlSuccessCallbacks !== 'undefined') {
        loadHtmlSuccessCallbacks.forEach(function (callback) {
          callback();
        });
      }
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
        try {
          parse = JSON.parse(JSON.stringify(xhr.responseText));
        } catch (e) {
          parse = '[JS] Erro inesperado, favor contate o suporte.';
          console.log(e);
        }
        
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-danger">' + parse + '</div>').fadeIn(0);
        } else {
          alert('Não foi possível completar a requisição, tente novamente em alguns minutos.');
        }
      }
    },
  });
  
  /* Aborta requisição */
  $(document).on('click', '*[vc-abort]', function () {
    /* Desativa o upload */
    enableUpload = false;
    
    /* Reseta formulário */
    if (form.length > 0) {
      form.trigger('reset');
    }
    
    /* Remove as mensagens caso tenha aparecido */
    if (message !== undefined && message.length > 0) {
      message.html('').fadeOut(0);
    }
    
    /* Volta os atributos do elemento clicado/modificado */
    element.attr('disabled', false).html(html);
    
    /* Oculta o botão/link para cancelar a requisição */
    if (ajaxAbort !== undefined) {
      ajaxAbort.fadeOut(0);
    }
    
    /* Aborta conexão */
    ajaxRequest.abort();
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
    var formData = new FormData();
    var json = getJSON(element.attr('vc-change'));
    
    if (!json) {
      json = {};
      
      Object.assign(json, {
        url: getLocationFromElement(element, 'change'),
        method: element.attr('vc-method') ? element.attr('vc-method').toUpperCase() : 'POST',
        data: undefined,
        name: element.attr('vc-param') ? element.attr('vc-param') : 'value',
      });
    }
    
    var method = (json.method || 'POST');
    
    if (json.data !== undefined) {
      element.attr('vc-data', JSON.stringify(json.data));
    }
    
    /* FormData */
    formData.append(json.name || 'value', (element.val() !== undefined ? element.val() : ''));
    formData.append('_METHOD', method);
    
    vcAjax(element, json.url || '', formData, 'POST', {}, true, false);
  });
  
  /* Dispara o request ao clicar (click) */
  $(document).on('click', '*', function (event) {
    var element = $(this);
    var form = '';
    var url = '';
    var method = '';
    var formData = new FormData();
    var errorCount = 0;
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
      
      // Verifica campos obrigatórios
      form.find('input, textarea, select').each(function (key, element) {
        var value = '';
        var requiredMessage = '';
        
        if ($(element).hasClass('vc-error-field')) {
          errorCount = 0;
          
          $(element)
            .removeClass('vc-error-field')
            .parent()
            .removeClass('vc-error')
            .find('.vc-error-box').remove();
        }
        
        if (element.tagName.toLowerCase() === 'textarea') {
          value = $(element).html() || '';
        } else {
          value = $(element).val() || '';
        }
        
        if ($(element).prop('required') && (value === '' && value !== '0')) {
          errorCount++;
          requiredMessage = ($(element).attr('required') !== 'required' ? $(element).attr('required') : 'Campo obrigatório.');
          
          $(element)
            .addClass('vc-error-field')
            .parent()
            .addClass('vc-error')
            .append('<div class="vc-error-box">' + requiredMessage + '</div>');
        }
      });
      
      if (errorCount !== 0) {
        form.find(':input.vc-error-field:first').focus();
        
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
              $.each(files, function (key, file) {
                formData.append($(element).attr('name'), file, file.name);
              });
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
      vcAjax(element, getLocationFromElement(element, 'get'), formData, 'GET', form, false, false);
    }
    
    /* REQUEST :: POST */
    if (element.attr('vc-post') !== undefined && (element.attr('vc-post') === '' || element.attr('vc-post'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      vcAjax(element, getLocationFromElement(element, 'post'), formData, 'POST', form, false, false);
    }
    
    /* REQUEST :: DELETE */
    if (element.attr('vc-delete') !== undefined && (element.attr('vc-delete') === '' || element.attr('vc-delete'))) {
      event.preventDefault(event);
      
      verify = confirm('Essa ação é irreversível.\nDeseja continuar?');
      if (verify === false) {
        return;
      }
      
      /* Envia requisição */
      if (element.attr('vc-method') !== undefined && element.attr('vc-method').length > 2) {
        formData.append('_METHOD', element.attr('vc-method').toUpperCase());
      } else {
        formData.append('_METHOD', 'DELETE');
      }
      
      vcAjax(element, getLocationFromElement(element, 'delete'), formData, 'POST', form, false, false);
    }
    
    /* REQUEST :: GERAL */
    if (element.attr('vc-ajax') !== undefined && (element.attr('vc-ajax') === '' || element.attr('vc-ajax'))) {
      event.preventDefault(event);
      
      /* Envia requisição */
      if (element.attr('vc-method') !== undefined && element.attr('vc-method').length > 2) {
        formData.append('_METHOD', element.attr('vc-method').toUpperCase());
      }
      
      vcAjax(element, getLocationFromElement(element, 'ajax'), formData, 'POST', form, false, false);
    }
  });
});

/* Apos carregar */
$(window).on('load', function () {
  /* REQUEST AFTER LOADING PAGE */
  var afterLoaddings = $(document).find('*[vc-after-load]');
  
  if (afterLoaddings.length) {
    $.each(afterLoaddings, function (index, element) {
      /* Formdata */
      var formData = new FormData();
      
      /* Get method */
      if ($(element).attr('vc-method') !== undefined && $(element).attr('vc-method').length > 2) {
        formData.append('_METHOD', element.attr('vc-method').toUpperCase());
      }
      
      /* Send */
      vcAjax($(element), getLocationFromElement($(element), 'after-load'), formData, 'POST', {}, false, false);
    });
  }
});
