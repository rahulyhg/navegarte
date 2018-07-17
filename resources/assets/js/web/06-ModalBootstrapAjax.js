/*
$(document).on('click', '*[data-modal]', function (e) {
  e.preventDefault();
  e.stopPropagation();
  
  var element = $(this);
  var modal = $(element.data('modal'));
  var options = element.data('options');
  
  if (modal !== undefined) {
    modal.modal('show');
    
    if (options !== undefined && options.url !== undefined) {
      modal.find('.modal-body').html('<p class="text-center">Aguarde...</p>');
      
      /!* Ajax *!/
      $.post(options.url, {data: options.data}, function (data) {
        // Mensagem de retorno
        if (data.trigger) {
          modal.find('.modal-body').html('<div class="alert alert-' + data.trigger[0] + '">' + data.trigger[1] + '</div>');
        }
        //
        
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
        
        // Percore os id da div preenchendo seus dados
        if (data.object) {
          if (typeof data.object === 'object') {
            $.each(data.object, function (key, value) {
              modal.find('#' + key).html(value);
            });
          }
        }
        //
      }, 'json').fail(function (xhr) {
        var parse;
        
        try {
          parse = JSON.parse(xhr.responseText);
          
          modal.find('.modal-body').html('<div class="alert alert-danger">' + parse.error.message + '</div>');
        } catch (e) {
          parse = JSON.parse(JSON.stringify(xhr.responseText));
          
          modal.find('.modal-body').html('<div class="alert alert-danger">' + parse + '</div>');
        }
      });
    }
  }
});
*/
