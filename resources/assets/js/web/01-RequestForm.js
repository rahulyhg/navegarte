$(document).ready(function () {
  /**
   * Verifica e monta a estrutura para enviar as requisições.
   */
  $('html').on('click', '*', function (event) {
    var $this  = $(this),
        form   = '',
        method = '',
        url    = '',
        data   = new FormData();
    
    // Elementos desabilitados
    if ($this.attr('disabled')) {
      return;
    }
    
    // Confirmar ação
    if ($this.attr('vc-confirm') !== undefined && ($this.attr('vc-confirm') === '' || $this.attr('vc-confirm'))) {
      var verify = confirm(($this.attr('vc-confirm').length > 0) ? $this.attr('vc-confirm') : 'Cuidado!!!\nDeseja realizar essa ação?');
      
      if (verify === false) {
        return;
      }
    }
    
    // Request FORM
    if ($this.attr('vc-form') !== undefined && ($this.attr('vc-form') === '' || $this.attr('vc-form'))) {
      event.preventDefault(event);
      
      // Variáveis
      form = ($this.attr('vc-form') && $this.attr('vc-form').length > 0) ? $('form[name="' + $this.attr('vc-form') + '"]') : $this.closest('form');
      method = form.attr('method') ? form.attr('method').toUpperCase() : 'POST';
      url = form.attr('action') ? form.attr('action') : null;
      
      // Verifica o formulário
      if (form.length <= 0) {
        alert('Formulário com ([name="' + $this.attr('vc-form') + '"]) não foi encontrado em seu documento html.');
        
        return;
      }
      
      // Append FormData
      form.find('*').each(function (key, element) {
        if ($(element).attr('name')) {
          if ($(element).prop('type') === 'checkbox') {
            if ($(element).prop('checked') !== false) {
              data.append($(element).attr('name'), $(element).val());
            }
          } else if ($(element).prop('type') === 'radio') {
            if ($(element).prop('checked') !== false) {
              data.append($(element).attr('name'), $(element).val());
            }
          } else if ($(element).prop('type') === 'file') {
            var files = $(element).prop('files');
            
            if (files !== undefined && files[0] !== undefined) {
              data.append($(element).attr('name'), files[0]);
            }
          } else if ($(element).tagName && $(element).tagName.toLowerCase() === 'textarea') {
            data.append($(element).attr('name'), $(element).html() === null ? '' : $(element).html());
          } else {
            data.append($(element).attr('name'), $(element).val() === null ? '' : $(element).val());
          }
        }
      });
      
      // Envia requisição
      ajaxForm($this, url, data, method, form);
    }
    
    // Requet GET
    if ($this.attr('vc-get') !== undefined && ($this.attr('vc-get') === '' || $this.attr('vc-get'))) {
      event.preventDefault(event);
      
      // Envia requisição
      ajaxForm($this, $this.attr('vc-get'), data, 'GET', form);
    }
    
    // Requet POST
    if ($this.attr('vc-post') !== undefined && ($this.attr('vc-post') === '' || $this.attr('vc-post'))) {
      event.preventDefault(event);
      
      // Envia requisição
      ajaxForm($this, $this.attr('vc-post'), data, 'POST', form);
    }
    
    // Requet DELETE
    if ($this.attr('vc-delete') !== undefined && ($this.attr('vc-delete') === '' || $this.attr('vc-delete'))) {
      event.preventDefault(event);
      
      verify = confirm('Cuidado!!!\nDeseja deletar esse registro?');
      if (verify === false) {
        return;
      }
      
      // Envia requisição
      ajaxForm($this, $this.attr('vc-delete'), data, 'POST', form);
    }
  });
});

/**
 * Gera as requisições em ajax
 *
 * @param {Object} click
 * @param {string} url
 * @param {Object} data
 * @param {string} method
 * @param {Object} form
 */
function ajaxForm(click, url, data, method, form) {
  // Verifica URL
  if (!url || url === null) {
    alert('Não encontramos a URL para essa requisição.');
    
    return;
  }
  
  // Button
  var html     = click.html(),
      loadding = click.attr('vc-loadding') ? click.attr('vc-loadding') : html,
      message;
  
  // Message
  if (form.length > 0) {
    message = form.find('#vc-message');
    
    if (message.length <= 0) {
      message = form.parent().find('#vc-message');
    }
  } else if (click.parent().find('#vc-message').length > 0) {
    message = click.parent().find('#vc-message');
  } else if (click.attr('vc-message')) {
    message = $(click.attr('vc-message'));
  }
  
  // Ajax
  $.ajax({
    url: url,
    data: data,
    dataType: 'json',
    type: method,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    beforeSend: function () {
      if (message !== undefined && message.length > 0) {
        message.fadeOut(0).html('');
      }
      
      click.html(loadding).attr('disabled', true);
    },
    success: function (data) {
      // Mensagem de retorno
      if (data.trigger) {
        if (message !== undefined && message.length > 0) {
          message.html('<div class="alert alert-' + data.trigger[0] + '">' + data.trigger[1] + '</div>').fadeIn(0);
        } else {
          alert(data.trigger[1]);
        }
      }
      //
      
      // Adiciona no localStorage
      if (data.storage) {
        if (data.storage[0] === 'remove') {
          window.Storage.remove(data.storage[1]);
        } else {
          window.Storage.set(data.storage[0], data.storage[1]);
        }
      }
      
      // Redirect
      if (data.location) {
        window.location.href = data.location;
      }
      //
      
      // Reload
      if (data.reload) {
        window.location.reload();
      }
      //
      
      // Limpa formulário
      if (data.clear && form.length > 0) {
        form.trigger('reset');
      }
      //
      
      // Percore os id da div preenchendo seus dados
      if (data.object) {
        if (typeof data.object === 'object') {
          $.each(data.object, function (key, value) {
            if ($('input[id="' + key + '"]').length > 0) {
              $('input[id="' + key + '"]').val(value);
            } else {
              $('#' + key).html(value);
            }
          });
        }
      }
      //
      
      // Reload datatable
      if (data.datatable) {
        $(data.datatable).DataTable().ajax.reload();
      }
      
      // Reset recaptch
      if (data.recaptcha) {
        grecaptcha.reset();
      }
      //
    },
    complete: function () {
      click.html(html).attr('disabled', false);
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
        }
      }
    }
  });
}
