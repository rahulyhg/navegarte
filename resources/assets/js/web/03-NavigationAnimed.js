/*$(document).ready(function () {
  var hash = window.location.hash.replace('#', '');
  
  /!**
   * Navegação animada dos menus
   *!/
  $('a[data-goto]').on('click', function (event) {
    event.preventDefault();
    
    scrollMenu($(this).attr('data-goto'));
  });
  
  if (hash) {
    window.history.pushState({}, '', '/');
  }
  
  $(window).load(function () {
    if (hash) {
      window.setTimeout(function () {
        scrollMenu(hash);
      }, 100);
    }
  });
  
  /!**
   * Funão para a animação
   *
   * @param id
   *!/
  function scrollMenu(id) {
    var sroll = $('[data-goto-id="' + id + '"]');
    
    $('html,body').animate({
      scrollTop: (sroll.offset().top - 120)
    }, 500);
  }
});*/
