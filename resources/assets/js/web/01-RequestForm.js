jQuery(function ($) {
  
  /**
   * Envia o formulário por ajax
   */
  $('html').on('submit', '.request-form', function (event) {
    event.preventDefault();
    
    var form = $(this);
    var method = form.attr('method') ? form.attr('method').toUpperCase() : 'POST';
    var url = form.attr('action');
    
    var btn = form.find('button, .request-btn');
    var btnHTML = btn.html();
    var btnLoadding = btn.attr('data-loadding') !== undefined ? btn.attr('data-loadding') : btnHTML;
    
    var disabled = $('#request-disabled');
    var message = form.find('#request-message');
    
    if (message.length <= 0) {
      message = form.parent().find('#request-message');
    }
    
    /* Quando o elemento está disativado */
    if (btn.attr('disabled')) {
      return false;
    }
    
    /**
     * FormaData
     */
    var formData = new FormData();
    
    /*formData.append('_METHOD', method);*/
    
    form.find('*').each(function (key, element) {
      if ($(element).attr('name')) {
        if ($(element).prop('type') === 'checkbox') {
          formData.append($(element).attr('name'), $(element).prop('checked') === false ? '0' : '1');
        } else if ($(element).prop('type') === 'file') {
          var files = $(element).prop('files');
          
          if (files !== undefined && files[0] !== undefined) {
            formData.append($(element).attr('name'), files[0]);
          }
        } else if ($(element).tagName && $(element).tagName.toLowerCase() === 'textarea') {
          formData.append($(element).attr('name'), $(element).html() === null ? '' : $(element).html());
        } else {
          formData.append($(element).attr('name'), $(element).val() === null ? '' : $(element).val());
        }
      }
    });
    
    $.ajax({
      url: url,
      data: formData,
      dataType: 'json',
      type: method,
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false,
      beforeSend: function () {
        if (message.length > 0) {
          message.fadeOut(0).html('');
        }
        
        btn.html(btnLoadding).attr('disabled', true);
        
        if (disabled.length > 0) {
          disabled.addClass('disabled');
        }
      },
      success: function (data) {
        /* Mensagem de retorno */
        if (data.trigger) {
          if (message.length > 0) {
            message.html('<div class="alert alert-' + data.trigger[0] + '">' + data.trigger[1] + '</div>').fadeIn(0);
          } else {
            alert(data.trigger[1]);
          }
        }
        /**/
        
        /* Adiciona no localStorage */
        if (data.storage) {
          if (data.storage[0] === 'remove') {
            window.Storage.remove(data.storage[1]);
          } else {
            window.Storage.set(data.storage[0], data.storage[1]);
          }
        }
        
        /* Location */
        if (data.location) {
          window.location.href = data.location;
        }
        /**/
        
        /* Reload */
        if (data.reload) {
          window.location.reload();
        }
        /**/
        
        /* Limpa formulário */
        if (data.clear) {
          form.trigger('reset');
        }
        /**/
        
        /* Percore os id da div preenchendo seus dados */
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
        /**/
      },
      complete: function () {
        btn.html(btnHTML).attr('disabled', false);
        
        if (disabled.length > 0) {
          disabled.removeClass('disabled');
        }
      },
      error: function (xhr) {
        var text  = 'Erro no sistemas, favor atualize a página e tente novamente. (500)',
            error = JSON.parse(JSON.stringify(xhr.responseText));
        
        if (message.length > 0) {
          message.html('<div class="alert alert-danger">' + error + '</div>').fadeIn(0);
        } else {
          alert(text);
        }
      }
    });
  });
});
