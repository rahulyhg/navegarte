/* Carrega o documento */
$(document).ready(function () {
  function beforeSend(text) {
    $('*[data-cep="logradouro"]').val(text);
    $('*[data-cep="complemento"]').val(text);
    $('*[data-cep="bairro"]').val(text);
    $('*[data-cep="localidade"]').val(text);
    $('*[data-cep="uf"]').val(text);
    $('*[data-cep="unidade"]').val(text);
    $('*[data-cep="ibge"]').val(text);
    $('*[data-cep="gia"]').val(text);
    $('*[data-cep="latitude"]').val('');
    $('*[data-cep="longitude"]').val('');
  }
  
  /* Realiza a pesquisa do dados */
  $(document).on('change', '*[data-cep="cep"]', function (event) {
    var cep = $(event.currentTarget).val().replace(/\D/g, '');
    var validadeCep = /^[0-9]{8}$/;
    
    if (cep.length === 8) {
      if (validadeCep.test(cep)) {
        beforeSend('Aguarde....');
        
        $.get('/api/util/zipcode/' + cep, function (json) {
          if (!json.error) {
            $.each(json, function (key, value) {
              var elementCep = $('*[data-cep="' + key + '"]');
              
              elementCep.val(value);
              
              if (value !== '' && key !== 'cep') {
                elementCep.attr('disabled', true);
              } else {
                elementCep.attr('disabled', false);
              }
            });
          } else {
            beforeSend('');
            
            alert(json.error.message);
          }
        }, 'json');
      }
    } else {
      beforeSend('');
      
      alert('O CEP ' + cep + ' não é válido.');
    }
  });
});
