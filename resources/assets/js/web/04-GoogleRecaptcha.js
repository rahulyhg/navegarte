/*
/!**
 * Inicia as configurações do GOOGLE RECAPTCHA V2
 *
 * https://developers.google.com/recaptcha/docs/display
 *!/

var initGoogleRecaptcha = function () {
  var sitekey = $('meta[name="recaptcha-sitekey"]').attr('content');
  var recaptchas = $('*[data-toggle="recaptcha"]');
  
  /!* Verifica se existe recaptcha *!/
  if (recaptchas.length) {
    $.each(recaptchas, function (index, element) {
      $(element).html('');
      
      try {
        grecaptcha.render(element, {
          'sitekey': sitekey,
          'theme': 'light',
          'size': 'compact '
        });
      } catch (e) {
        grecaptcha.reset(index);
      }
    });
  }
};

/!* Carrega o documento *!/
$(document).ready(function () {
  $.getScript('https://www.google.com/recaptcha/api.js?onload=initGoogleRecaptcha&render=explicit');
});
*/
