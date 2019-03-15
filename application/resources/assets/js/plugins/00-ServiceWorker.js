/**
 * Registra o service worker na aplicação
 */

/**
 * Implementação
 *
 * 2. Adicionar no app.twig:
 * <meta name="theme-color" content="THEME_COLOR">
 */

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    if (navigator.userAgent.match(/Edge\/1(?:7|8)|SamsungBrowser\//)) {
      return;
    }
    
    navigator.serviceWorker.register('/sw.js', {scope: '/'}).then(function (registration) {
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }).catch(function (err) {
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}
