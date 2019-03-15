/**
 * Prototype variáveis
 */

const waitUntil = ExtendableEvent.prototype.waitUntil;
const respondWith = FetchEvent.prototype.respondWith;
const promisesMap = new WeakMap();

/**
 * @param promise
 * @return {*|void}
 */
ExtendableEvent.prototype.waitUntil = function (promise) {
  const extendableEvent = this;
  let promises = promisesMap.get(extendableEvent);
  
  if (promises) {
    promises.push(Promise.resolve(promise));
    return;
  }
  
  promises = [Promise.resolve(promise)];
  promisesMap.set(extendableEvent, promises);
  
  return waitUntil.call(extendableEvent, Promise.resolve().then(function processPromises () {
    const len = promises.length;
    
    return Promise.all(promises.map(p => p.catch(() => {}))).then(() => {
      if (promises.length !== len) {
        return processPromises();
      }
      
      promisesMap.delete(extendableEvent);
      
      return Promise.all(promises);
    });
  }));
};

/**
 * @param promise
 * @return {any | void}
 */
FetchEvent.prototype.respondWith = function (promise) {
  this.waitUntil(promise);
  return respondWith.call(this, promise);
};

/**
 * Variávies do sw
 */

const cacheName = 'vcweb-v1';
const filesToCache = [
  '/manifest.json',
  '/robots.txt',
  '/sw.js'
];

/**
 * Install cache sw
 */

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(cacheName)
      .then(function (cache) {
        return cache.addAll(filesToCache);
      })
  );
});

/**
 * Fetch cache sw
 */

self.addEventListener('fetch', function (event) {
  event.respondWith(caches.match(event.request).then(function (response) {
    if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') {
      return;
    }
    
    return response || fetch(event.request)
      .catch(function (err) {console.error(event.request.url, err);});
  }));
});
