/**
 * Inicia as configurações do select2
 *
 * @param {Object} selects2
 *
 * https://select2.org/
 */

var initSelect2 = function (selects2) {
  if (selects2.length) {
    $.each(selects2, function (key, element) {
      var option = $(element).data('option');
      var options = {};
      
      /* Configurações do AJAX */
      if (option !== undefined) {
        if (option.url !== undefined || option.url !== '') {
          options = {
            placeholder: option.placeholder !== undefined ? option.placeholder : 'Pesquisar...',
            minimumInputLength: 0,
            
            ajax: {
              url: option.url,
              type: option.type !== undefined ? option.type : 'POST',
              dataType: option.dataType !== undefined ? option.dataType : 'json',
              delay: option.delay !== undefined ? option.delay : 250,
              cache: option.cache !== undefined ? option.cache : false,
              data: function (param) {
                var params = {
                  term: param.term || '',
                  page: param.page || 1,
                };
                
                /* Monta data vindo das opções */
                if (option.data !== undefined && (option.data === '' || option.data)) {
                  var optionData = getJSON(option.data);
                  
                  if (optionData) {
                    for (var key in optionData) {
                      if (optionData.hasOwnProperty(key)) {
                        if (optionData[key] !== undefined && optionData[key] !== '') {
                          params[key] = optionData[key];
                        }
                      }
                    }
                  }
                }
                
                return params;
              },
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
            },
          };
        }
      }
      
      /* Inicia o select2 */
      $(element).select2(mergeObject({
        language: 'pt-BR',
        width: 'resolve',
        /*dropdownParent: $(element).parent(),
         minimumResultsForSearch: -1,*/
      }, options));
    });
  }
};

/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Select2 */
  var selects2 = $(document).find('*[data-toggle="select2"]');
  
  if (typeof onLoadHtmlSuccess !== 'undefined' && typeof onLoadHtmlSuccess === 'function') {
    onLoadHtmlSuccess(function () {
      initSelect2(selects2);
    });
  } else {
    initSelect2(selects2);
  }
});
