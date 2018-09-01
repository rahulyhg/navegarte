/*
/!* Carrega o documento *!/
$(document).ready(function () {
  // data-share="https://www.facebook.com/sharer/sharer.php?u=URL }}"
  // data-share="https://twitter.com/intent/tweet?text=TEXT&url=URL&hashtags=HASHTAG&via=VIA(NOME_PAGE)"
  // data-share="https://plus.google.com/share?url=URL&hl=pt-BR"
  // data-share="https://api.whatsapp.com/send?text=TEXT|URL_ENCODE"
  
  /!* Realiza o compartilhamento para as redes sociais. *!/
  $(document).on('click', '*[data-share]', function (event) {
    event.preventDefault(event);
    
    var url = $(this).data('share');
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
});
*/
