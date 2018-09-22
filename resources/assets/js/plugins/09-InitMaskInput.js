/**
 * Inicia as máscara para os forms
 *
 * https://igorescobar.github.io/jQuery-Mask-Plugin/
 */

var initMaskInput = function () {
  $('.maskTime').mask('00:00');
  $('.maskDate').mask('00/00/0000');
  $('.maskDateTime').mask('00/00/0000 00:00');
  $('.maskMoney').mask('#.##0,00', {reverse: true});
  $('.maskCpf').mask('000.000.000-00', {reverse: true});
  $('.maskRg').mask('00.000.000-0', {reverse: true});
  $('.maskCnpj').mask('00.000.000/0000-00', {reverse: true});
  
  $('.maskCep').mask('00000-000', {
    onKeyPress: function (cep, e, field, options) {
      var masks = ['00000-000', '0-00-00-00'];
      var mask = (cep.length > 7) ? masks[0] : masks[1];
      
      $('.maskCep').mask(mask, options);
    }
  });
  
  /**
   * @return {string}
   */
  var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  }, spOptions       = {
    onKeyPress: function (val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
  };
  
  $('.maskPhone').mask(SPMaskBehavior, spOptions);
  $('.maskTelephone').mask('(00) 0000-0000');
};

/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Mask Input */
  initMaskInput();
});