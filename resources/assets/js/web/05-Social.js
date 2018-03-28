/*$(document).ready(function () {
  
  // data-social-share="https://www.facebook.com/sharer/sharer.php?u=URL }}"
  // data-social-share="https://twitter.com/intent/tweet?text=TEXT&url=URL&hashtags=HASHTAG&via=VIA(NOME_PAGE)"
  // data-social-share="https://plus.google.com/share?url=URL&hl=pt-BR"
  // data-social-share="https://api.whatsapp.com/send?text=TEXT|URL_ENCODE"
  
  // Open popup shared
  $('*[data-social-share]').on('click', function (event) {
    event.preventDefault(event);
    
    var url = $(this).attr('data-social-share');
    var width = 600;
    var height = 600;
    
    if (!url) {
      return;
    }
    
    var leftPosition, topPosition;
    leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
    topPosition = (window.screen.height / 2) - ((height / 2) + 100);
    
    window.open(url, 'Window2',
      'status=no,height=' + height + ',width=' + width + ',resizable=yes,left='
      + leftPosition + ',top=' + topPosition + ',screenX=' + leftPosition + ',screenY='
      + topPosition + ',toolbar=no,menubar=no,scrollbars=no,location=no,directories=no');
  });
});*/
