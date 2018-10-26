/* Colocar acima dos scripts em app.twig */
var loadHtmlSuccessCallbacks = [];

/* Colocar abaixo dos scripts em app.twig */
$(window).load(function () {
  loadHtmlSuccessCallbacks.forEach(function (callback) {
    return callback(document);
  });
});

/* Adicionar os JS da aplicação dentro dessa função de callback */
function onLoadHtmlSuccess(callback) {
  if (!loadHtmlSuccessCallbacks.includes(callback)) {
    loadHtmlSuccessCallbacks.push(callback);
  }
}

/* Função para carregar a página */
function loadPage(content, location, pushState) {
  var contentHtml = $(content);
  var contentHtmlOffsetTop = contentHtml.offset().top;
  
  if (!location) {
    location = window.location.href;
  }
  
  var baseUrl = $('link[rel="base"]').attr('href');
  var urlRegex = new RegExp(baseUrl.replace(/https?:\/\/(www\.)?/g, ''), 'g');
  
  if (!location.match(urlRegex)) {
    location = baseUrl + location;
  }
  
  $.ajax({
    url: location,
    headers: {'vcAjaxPage': true},
    cache: false,
    success: function (html) {
      /* Retorno HTML */
      if (typeof html === 'string') {
        contentHtml.html(html);
        $('html, body').animate({scrollTop: contentHtmlOffsetTop}, 500);
        
        if (pushState) {
          window.history.pushState(null, null, location);
        }
        
        loadHtmlSuccessCallbacks.forEach(function (callback) {
          return callback(html);
        });
      }
      
      /* Redireciona para uma nova página */
      if (html.location) {
        loadPage(html.location, true);
      }
      
      /* Recarrega a página atual */
      if (html.reload) {
        window.location.reload();
      }
    },
    complete: function () {
    }
  });
}

/* Evento do click nos links */
$(document).ready(function () {
  $(document).on('click', 'a', function (event) {
    var element = $(this);
    var location = element.attr('href') || element.data('href');
    var hash = location.substr(0, 1) === '#';
    
    if (!element.attr('target') && !location.match(/javascript/g) && !hash) {
      event.preventDefault();
      event.stopPropagation();
      
      loadPage(location, true);
    }
  });
  
  window.onpopstate = function () {
    loadPage(window.location.href, false);
  };
});
