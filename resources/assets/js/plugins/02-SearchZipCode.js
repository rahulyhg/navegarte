/* Carrega o documento */
$(document).ready(function () {
  function beforeSend (text) {
    $('#vc-logradouro').val(text);
    $('#vc-complemento').val(text);
    $('#vc-bairro').val(text);
    $('#vc-localidade').val(text);
    $('#vc-uf').val(text);
    $('#vc-unidade').val(text);
    $('#vc-ibge').val(text);
    $('#vc-gia').val(text);
    $('#vc-latitude').val('');
    $('#vc-longitude').val('');
  }
  
  /* Realiza a pesquisa do dados */
  $(document).on('change', '*[data-address]', function (event) {
    var cep = $(event.currentTarget).val().replace(/\D/g, '');
    var validadeCep = /^[0-9]{8}$/;
    
    if (cep.length === 8) {
      if (validadeCep.test(cep)) {
        beforeSend('Aguarde....');
        
        $.get('/api/zipcode/' + cep, function (json) {
          if (!json.error) {
            $.each(json, function (key, value) {
              var element = $('#vc-' + key);
              
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
