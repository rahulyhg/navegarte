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
    
    if (modal !== undefined) {
      /* Abre modal */
      $(modal).modal('show');
      
      /* Configuração do AJAX */
      if (option !== undefined && option.url !== undefined) {
        $(modal).find('.modal-body').html('<p class="text-center">Aguarde...</p>');
        
        /* FormData */
        var formObj = option.data;
        
        if (formObj !== undefined) {
          for (var key in formObj) {
            if (formObj.hasOwnProperty(key)) {
              formData.append(key, (formObj[key] !== undefined ? formObj[key] : ''));
            }
          }
        }
        
        requestAjax($(this), option.url, formData, 'POST', {}, true, modal);
      }
    }
  });
});
