/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Tooltip */
  $('*[data-toggle="tooltip"]').tooltip();
  
  /* INIT :: Modal */
  $(document).on('click', '*[data-modal]', function (event) {
    event.preventDefault();
    event.stopPropagation();
    
    /* Variáveis */
    var modal = $(this).data('modal');
    var option = $(this).data('option');
    var formData = new FormData();
    
    /* Verifica se a modal existe */
    if (modal !== undefined && $(modal).length) {
      /* Abre modal */
      $(modal).modal('show');
      
      /* Verifica opções */
      if (option !== undefined) {
        $(modal).find('.modal-body').html('<p class="text-center mb-0 mbottom-0">Aguarde carregando dados...</p>');
        
        /* Insere um html caso tenha */
        if (option.html !== undefined) {
          $(modal).find('.modal-body').html(option.html);
        }
        
        /* Configuração do AJAX */
        if (option.url !== undefined) {
          /* FormData */
          var params = option.data;
          
          if (params !== undefined) {
            for (var key in params) {
              if (params.hasOwnProperty(key)) {
                formData.append(key, (params[key] !== undefined ? params[key] : ''));
              }
            }
          }
          
          /* Realiza a requisição */
          if (option.method !== undefined) {
            formData.append('_METHOD', option.method);
          }
          
          requestAjax($(this), option.url, formData, 'POST', {}, true, modal);
        }
      }
    }
  });
});
