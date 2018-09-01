/*
/!* Carrega o documento *!/
$(document).ready(function () {
  /!* INIT :: Tooltip *!/
  $('*[data-toggle="tooltip"]').tooltip();
  
  /!* INIT :: Modal *!/
  $(document).on('click', '*[data-modal]', function (event) {
    event.preventDefault();
    event.stopPropagation();
    
    var modal = $(this).data('modal');
    var option = $(this).data('option');
    
    if (modal !== undefined) {
      /!* Abre modal *!/
      $(modal).modal('show');
      
      /!* Configuração do AJAX *!/
      if (option !== undefined && option.url !== undefined) {
        $(modal).find('.modal-body').html('<p class="text-center">Aguarde...</p>');
        
        /!* Ajax *!/
        var data = option.data !== undefined ? option.data : {};
        
        $.post(option.url, data, function (data) {
          /!* Mensagem de erro *!/
          if (data.trigger) {
            $(modal).find('.modal-body').html('<div class="alert alert-' + data.trigger[0] + ' mb-0">' + data.trigger[1] + '</div>');
          }
          
          /!* Redireciona para uma nova página *!/
          if (data.location) {
            window.location.href = data.location;
          }
          
          /!* Recarrega a página atual *!/
          if (data.reload) {
            window.location.reload();
          }
          
          /!* Percore os id da div preenchendo seus dados *!/
          if (data.object) {
            if (typeof data.object === 'object') {
              $.each(data.object, function (key, value) {
                $(modal).find('#' + key).html(value);
              });
            }
          }
          
          /!* Carrega plugins *!/
        }, 'json').fail(function (xhr) {
          var parse;
          
          try {
            parse = JSON.parse(xhr.responseText);
            $(modal).find('.modal-body').html('<div class="alert alert-danger mb-0">' + parse.error.message + '</div>');
          } catch (e) {
            parse = JSON.parse(JSON.stringify(xhr.responseText));
            $(modal).find('.modal-body').html('<div class="alert alert-danger mb-0">' + parse + '</div>');
          }
        });
      }
    }
  });
});
*/
