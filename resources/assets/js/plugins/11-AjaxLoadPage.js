/* Colocar acima dos scripts em app.twig */
var loadHtmlSuccessCallbacks = [];

/* Colocar abaixo dos scripts em app.twig */
$(window).on('load', function () {
  if (typeof loadHtmlSuccessCallbacks !== 'undefined') {
    loadHtmlSuccessCallbacks.forEach(function (callback) {
      callback();
    });
  }
});

/**
 * Função para carregar a página
 *
 * @param {Object} content
 * @param {String} location
 * @param {Boolean} pushState
 */

function loadPage (content, location, pushState) {
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
    
    success: function (html) {
      /* Retorno HTML */
      if (typeof html === 'string') {
        $('html, body').animate({scrollTop: contentHtmlOffsetTop}, 500);
        contentHtml.html(html);
        
        /* Muda URL e Histórico de Navegação */
        if (pushState) {
          window.history.pushState({content: content}, null, location);
        }
      }
      
      if (html.object) {
        if (typeof html.object === 'object') {
          $.each(html.object, function (key, value) {
            $('#' + key).html(value);
          });
        }
      }
      
      /* Redireciona para uma nova página */
      if (html.location) {
        loadPage(content, html.location, true);
      }
      
      /* Recarrega a página atual */
      if (html.reload) {
        window.location.reload();
      }
    },
    
    complete: function () {
      /* Coloca classe ativa no final da requisição no link clicado */
      var lhref = location.replace(baseUrl, '');
      
      $.each($(document).find('*[href="' + lhref + '"], *[data-href="' + lhref + '"]'), function (i, element) {
        $.each($(element).parent().parent().parent().find('*[href], *[data-href]'), function (i, elRemove) {
          var elHref = $(elRemove).attr('href') || $(elRemove).data('href');
          
          if (!elHref.match(/javascript/g)) {
            $(elRemove).removeClass('ajax-active active');
            $(elRemove).parent().removeClass('ajax-active active');
          }
        });
        
        setTimeout(function () {
          $(element).addClass('ajax-active active');
          $(element).parent().addClass('ajax-active active');
        }, 150);
      });
      
      /* Carrega JS */
      if (typeof loadHtmlSuccessCallbacks !== 'undefined') {
        loadHtmlSuccessCallbacks.forEach(function (callback) {
          callback();
        });
      }
    },
    
    error: function (xhr) {
      var parse = JSON.parse(xhr.responseText);
      
      if (parse.error) {
        alert(parse.error.message);
      }
      
      if (parse.location) {
        if (parse.location) {
          loadPage(content, parse.location, true);
        }
      }
    },
  });
}

/**
 * Função para trocar os atributos do SEO da página
 *
 * USAGE:
 *
 * Adiciona acima da tag <title> da view.
 *
 * <script>
 *   window.seoTitle = '{{ seoTitle|raw }}';
 *   window.seoDescription = '{{ config('client.description')|raw }}';
 *   window.seoKeywords = '{{ config('client.keywords')|raw }}';
 *   window.seoAbstract = '{{ config('client.abstract')|raw }}';
 *   window.seoImage = '{{ '/assets/web/images/1200x630.png' }}';
 * </script>
 *
 * @param {String} title
 * @param {String} description
 * @param {String} image
 * @param {String} keywords
 * @param {String} abstract
 */

function changeContentSeo (title, description, image, keywords, abstract) {
  var seoTitle = window.seoTitle || '';
  var titleMounted;
  
  // Monta o titulo da página
  if (title !== undefined && title !== '') {
    titleMounted = title + ' - ' + seoTitle;
  } else {
    titleMounted = seoTitle;
  }
  
  // Troca o titulo da página
  document.title = titleMounted;
  
  // Troca o link canonical da página após meio segundo
  setTimeout(function () {
    $('*link[rel="canonical"]').attr('href', window.location.href);
  }, 500);
  
  // Muda o keyword da página
  $('*meta[name="keywords"]').attr('content', keywords || window.seoKeywords);
  
  // Muda o abstract da página
  $('*meta[name="abstract"]').attr('content', abstract || window.seoAbstract);
  
  // Monta os array com as tags para troca o SEO
  var arrTitle = [
    $('*meta[itemprop="name"]'),
    $('*meta[property="og:title"]'),
    $('*meta[property="twitter:title"]'),
  ];
  
  var arrDescription = [
    $('*meta[name="description"]'),
    $('*meta[itemprop="description"]'),
    $('*meta[property="og:description"]'),
    $('*meta[property="twitter:description"]'),
  ];
  
  var arrImage = [
    $('*meta[itemprop="image"]'),
    $('*meta[property="og:image"]'),
    $('*meta[property="twitter:image"]'),
  ];
  
  // Percore os titulo
  $.each(arrTitle, function (i, element) {
    element.attr('content', titleMounted);
  });
  
  // Percore as descrição
  $.each(arrDescription, function (i, element) {
    element.attr('content', description || window.seoDescription);
  });
  
  // Percore as imagem
  $.each(arrImage, function (i, element) {
    element.attr('content', image || window.seoImage);
  });
}

/**
 * Adicionar os JS da aplicação dentro dessa função de callback
 *
 * @param {Function} callback
 */

function onLoadHtmlSuccess (callback) {
  if (!loadHtmlSuccessCallbacks.includes(callback)) {
    loadHtmlSuccessCallbacks.push(callback);
  }
}

/* jQuery */
(function ($) {
  /* Evento do click nos links */
  $(document).on('click', 'a', function (event) {
    var element = $(this);
    var location = element.attr('href') || element.data('href');
    var hash = location.substr(0, 1) === '#';
    
    if (hash) {
      return false;
    }
    
    if (!element.attr('target') && !location.match(/javascript/g)) {
      event.preventDefault();
      event.stopPropagation();
      
      loadPage('#content-ajax', location, true);
    }
  });
  
  /* Histórico de navegação */
  window.onpopstate = function (e) {
    loadPage(e.state.content || '#content-ajax', window.location.href, false);
  };
})(jQuery);