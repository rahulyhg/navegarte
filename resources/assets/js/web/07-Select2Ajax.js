/*
/!**
 * Função para realizar o select2 por ajax
 *
 * @param {Object} element
 * @param {Object} options (placeholder, url, type, dataType, delay, cache)
 *!/
function select2Ajax(element, options) {
  if (options.url === undefined || options.url === '') {
    alert('URL Inválida para a pesquisa do select2.');
    
    return;
  }
  
  var select2Options = mergeObject({
    language: 'pt-BR',
    width: 'resolve',
    placeholder: options.placeholder !== undefined ? options.placeholder : 'Pesquisar...',
    minimumInputLength: 1,
    
    ajax: {
      url: options.url,
      type: options.type !== undefined ? options.type : 'POST',
      dataType: options.dataType !== undefined ? options.dataType : 'json',
      delay: options.delay !== undefined ? options.delay : 250,
      cache: options.cache !== undefined ? options.cache : false,
      
      data: function (params) {
        return {
          term: params.term || "",
          page: params.page || 1
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
  }, options);
  
  element.select2(select2Options);
}

function initSelect2() {
  var selects2All = $('*[data-toggle="select2"]');
  
  if (selects2All.length) {
    $.each(selects2All, function (key, element) {
      var options = $(element).data('options');
      
      if (options !== undefined) {
        select2Ajax($(element), options);
      } else {
        $(element).select2({
          language: 'pt-BR',
          width: 'resolve'
        });
      }
    });
  }
}

$(document).ready(function () {
  if (typeof select2 !== undefined) {
    initSelect2();
  }
});

*/
