jQuery(function ($) {
  
  function cleanEnd() {
    $('#ENDERECO_BAIRRO_ALUNO').val('');
    $('#ENDERECO_COMPLEMENTO_ALUNO').val('');
    $('#ENDERECO_CIDADE_ALUNO').val('');
    $('#ENDERECO_RUA_ALUNO').val('');
    $('#ENDERECO_ESTADO_ALUNO').val('');
    $('#ENDERECO_NUMERO_ALUNO').val('');
  }
  
  /**
   * Get Cep
   */
  $('.jq_getCep').on('change', function (event) {
    
    var cep = $(event.currentTarget).val().replace(/\D/g, '');
    var validadeCep = /^[0-9]{8}$/;
    
    if (cep.length === 8) {
      
      if (validadeCep.test(cep)) {
        $('#ENDERECO_BAIRRO_ALUNO').val('Aguarde...');
        $('#ENDERECO_COMPLEMENTO_ALUNO').val('Aguarde...');
        $('#ENDERECO_CIDADE_ALUNO').val('Aguarde...');
        $('#ENDERECO_RUA_ALUNO').val('Aguarde...');
        $('#ENDERECO_ESTADO_ALUNO').val('Aguarde...');
        $('#ENDERECO_NUMERO_ALUNO').val('Aguarde...');
        
        $.get('https://viacep.com.br/ws/' + cep + '/json', function (data) {
          
          if (!data.erro) {
            $('#ENDERECO_BAIRRO_ALUNO').val(data.bairro);
            $('#ENDERECO_COMPLEMENTO_ALUNO').val(data.complemento);
            $('#ENDERECO_CIDADE_ALUNO').val(data.localidade);
            $('#ENDERECO_RUA_ALUNO').val(data.logradouro);
            $('#ENDERECO_ESTADO_ALUNO').val(data.uf);
            $('#ENDERECO_NUMERO_ALUNO').val('');
          }
          
          console.log(data);
          
        }, 'json');
      }
      
    } else {
      cleanEnd();
      
      alert('Cep digitado é inválido');
    }
  });
});
