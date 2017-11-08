/**
 * Formata número similar ao php
 *
 * @param numero
 * @param decimal
 * @param decimal_separador
 * @param milhar_separador
 * @returns {string|*}
 */
function number_format (numero, decimal, decimal_separador, milhar_separador) {
  numero         = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
  var n          = !isFinite(+numero) ? 0 : +numero,
      prec       = !isFinite(+decimal) ? 0 : Math.abs(decimal),
      sep        = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
      dec        = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
      s          = '',
  
      toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
  
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  
  return s.join(dec);
}

/**
 * Retorna apenas número
 *
 * @param evt
 * @returns {boolean}
 */
function isNumeric (evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode;
  
  return !(charCode > 31 && (charCode < 48 || charCode > 57));
}

/**
 * Verifica o máximo de caracteres
 *
 * @param element
 * @param length
 * @returns {string|jQuery}
 */
function isLength (element, length) {
  if ($(element).val().length >= length) {
    return $(element).val($(element).val().substr(0, length - 1));
  }
}

(function (window) {
  /**
   * Passado pro escopo global do `window` para poder usar
   * o Storage em qualquer lugar
   *
   * @type {
   *    {
   *      set: Window.Storage.set,
   *      setObject: Window.Storage.setObject,
   *      get: Window.Storage.get,
   *      getObject: Window.Storage.
   *      getObject, r
   *      emove: Window.Storage.remove
   *    }
   *  }
   */
  window.Storage = {
    set: function (key, value) {
      window.localStorage[key] = value;
      
      return window.localStorage[key];
    },
    
    setObject: function (key, value) {
      window.localStorage[key] = JSON.stringify(value);
      
      return this.getObject(key);
    },
    
    get: function (key) {
      return window.localStorage[key] || false;
    },
    
    getObject: function (key) {
      return JSON.parse(window.localStorage[key] || null);
    },
    
    remove: function (key) {
      window.localStorage.removeItem(key);
    }
  };
  
})(window);
