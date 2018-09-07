/**
 * Inicia as configurações do select2
 *
 * https://select2.org/
 */

var initSelect2 = function () {
  var selects2 = $('*[data-toggle="select2"]');
  
  if (selects2.length) {
    $.each(selects2, function (key, element) {
      var option = $(element).data('option');
      var options = {};
      
      /* Configurações do AJAX */
      if (option !== undefined) {
        if (option.url !== undefined || option.url !== '') {
          options = {
            placeholder: option.placeholder !== undefined ? option.placeholder : 'Pesquisar...',
            minimumInputLength: 1,
            
            ajax: {
              url: option.url,
              type: option.type !== undefined ? option.type : 'POST',
              dataType: option.dataType !== undefined ? option.dataType : 'json',
              delay: option.delay !== undefined ? option.delay : 250,
              cache: option.cache !== undefined ? option.cache : false,
              data: function (param) {
                return {
                  term: param.term || "",
                  page: param.page || 1
                };
              }
            },
            
            escapeMarkup: function (markup) {
              return markup;
            },
            
            templateResult: function (state) {
              if (state.loading) {
                return state.text;
              }
              
              return state.name || state.text;
            },
            
            templateSelection: function (state) {
              return state.name || state.text;
            }
          };
        }
      }
      
      /* Inicia o select2 */
      $(element).select2(mergeObject({
        language: 'pt-BR',
        width: 'resolve'
      }, options));
    });
  }
};

/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Select2 */
  initSelect2();
});
