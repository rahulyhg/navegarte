/*$(document).ready(function () {
  function beforeSend(text) {
    $('*[data-cep="logradouro"]').val(text);
    $('*[data-cep="complemento"]').val(text);
    $('*[data-cep="bairro"]').val(text);
    $('*[data-cep="localidade"]').val(text);
    $('*[data-cep="uf"]').val(text);
    $('*[data-cep="unidade"]').val(text);
    $('*[data-cep="ibge"]').val(text);
    $('*[data-cep="gia"]').val(text);
  }
  
  /!**
   * Busca CEP
   *!/
  $(document).on('change', '*[data-cep="cep"]', function (event) {
    var cep = $(event.currentTarget).val().replace(/\D/g, '');
    var validadeCep = /^[0-9]{8}$/;
    
    if (cep.length === 8) {
      if (validadeCep.test(cep)) {
        beforeSend('Aguarde....');
        
        $.get('https://viacep.com.br/ws/' + cep + '/json', function (data) {
          if (!data.erro) {
            $.each(data, function (key, value) {
              $('*[data-cep="' + key + '"]').val(value);
              
              if (value !== '' && key !== 'cep') {
                $('*[data-cep="' + key + '"]').attr('disabled', true);
              } else {
                $('*[data-cep="' + key + '"]').attr('disabled', false);
              }
            });
          } else {
            beforeSend('');
            
            alert('Cep digitado é inválido');
          }
        }, 'json');
      }
    } else {
      beforeSend('');
      
      alert('Cep digitado é inválido');
    }
  });
});*/
