function recaptchaLoader () {
  window.top.recaptchaKey = $('meta[name="recaptchaKey"]').attr('content');
  
  $('div').find('[data-recaptcha="true"]').each(function (index, element) {
    $(element).html('');
    
    try {
      grecaptcha.render(element, {'sitekey': window.top.recaptchaKey, 'theme': 'dark', 'site': 'compact'});
    } catch (e) {
      grecaptcha.reset(index);
    }
  });
}

jQuery(function ($) {
  $.getScript('https://www.google.com/recaptcha/api.js?onload=recaptchaLoader&render=explicit');
});
