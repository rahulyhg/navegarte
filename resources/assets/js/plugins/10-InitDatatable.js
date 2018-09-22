/**
 * Inicia as configurações do datatable
 *
 * @param datatables
 *
 * https://datatables.net
 */

var initDatatable = function (datatables) {
  /* Verifica se existe datatable */
  if (datatables.length) {
    /* Percore as datatable encontradas */
    $.each(datatables, function (key, element) {
      var option = $(element).data('option');
      var options = {};
      
      /* Configurações customizadas */
      if (option !== undefined) {
        /* AJAX */
        if (option.url !== undefined && option.url !== '') {
          options = {
            'processing': true,
            'serverSide': true,
            'ajax': {
              'url': option.url,
              'type': 'POST',
              'data': option.data !== undefined ? option.data : {}
            }
          };
        }
        
        /* ORDER BY */
        if (option.order !== undefined) {
          options.order = [option.order];
        }
        
        /* Ativar ORDENAÇÃO */
        if (option.ordering !== undefined) {
          options.ordering = option.ordering;
        }
      }
      
      /* Inicia o datatable */
      $(element).DataTable(mergeObject({
        'destroy': true,
        'iDisplayLength': 50,
        'pagingType': 'full_numbers',
        'lengthMenu': [
          [10, 25, 50, 100, 150, 200, 250, 300, 500, 1000, '-1'],
          [10, 25, 50, 100, 150, 200, 250, 300, 500, 1000, 'Todos']
        ]
      }, options));
    });
  }
};

/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Datatable */
  initDatatable($('*[data-toggle="datatable"]'));
});
