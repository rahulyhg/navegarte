/* Carrega o documento */
$(document).ready(function () {
  function beforeSend (text) {
    $('#cep-logradouro').val(text);
    $('#cep-complemento').val(text);
    $('#cep-bairro').val(text);
    $('#cep-localidade').val(text);
    $('#cep-uf').val(text);
    $('#cep-unidade').val(text);
    $('#cep-ibge').val(text);
    $('#cep-gia').val(text);
    $('#cep-latitude').val('');
    $('#cep-longitude').val('');
  }
  
  /* Realiza a pesquisa do dados */
  $(document).on('change', '*[data-cep]', function (event) {
    var cep = $(event.currentTarget).val().replace(/\D/g, '');
    var validadeCep = /^[0-9]{8}$/;
    
    if (cep.length === 8) {
      if (validadeCep.test(cep)) {
        beforeSend('Aguarde....');
        
        $.get('/api/util/zipcode/' + cep, function (json) {
          if (!json.error) {
            $.each(json, function (key, value) {
              var element = $('#cep-' + key);
              
              element.val(value);
              
              if (value !== '' && key !== 'cep') {
                element.attr('disabled', true);
              } else {
                element.attr('disabled', false);
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
