<?php

use App\Core\Helpers\Debug;

if (!function_exists('dd')) {
  /**
   * Debug mode
   *
   * @return mixed
   */
  function dd()
  {
    array_map(function ($var) {
      (new Debug)->dump($var);
    }, func_get_args());
    
    die(1);
  }
}

if (!function_exists('env')) {
  /**
   * Get '.env' configuration
   *
   * @param string $name
   * @param null   $default
   *
   * @return array|bool|false|null|string
   */
  function env($name, $default = null)
  {
    $value = getenv($name);
    
    if ($value === false) {
      return $default;
    }
    
    switch (strtolower($value)) {
      case 'true':
      case '(true)':
        return true;
        break;
      case 'false':
      case '(false)':
        return false;
        break;
      case 'empty':
      case '(empty)':
        return '';
        break;
      case 'null':
      case '(null)':
        return null;
        break;
    }
    
    return $value;
  }
}

if (!function_exists('config')) {
  /**
   * Get config
   *
   * @param string|null $name
   * @param null        $default
   *
   * @return array|string|int
   */
  function config($name = null, $default = null)
  {
    $config = [];
    foreach (glob(ROOT . '/config/*') as $filePath) {
      $fileName = basename($filePath, '.php');
      $config[$fileName] = include "{$filePath}";
    }
    
    if (is_null($name)) {
      return $config;
    }
    
    if (array_key_exists($name, $config)) {
      return $config[$name];
    }
    
    foreach (explode('.', $name) as $key) {
      if (is_array($config) && array_key_exists($key, $config)) {
        $config = $config[$key];
      } else {
        return $default;
      }
    }
    
    return $config;
  }
}

if (!function_exists('logger')) {
  /**
   * @param string|null $file
   *
   * @return bool|\Monolog\Logger
   */
  function logger($file = null)
  {
    if (app()->resolve('logger')) {
      return app()->resolve('logger', [$file]);
    }
    
    return false;
  }
}

if (!function_exists('view')) {
  /**
   * Rendering view content
   *
   * @param string   $view
   * @param array    $data
   * @param int|null $code
   *
   * @return mixed
   */
  function view($view, $data = [], $code = null)
  {
    $response = response();
    
    if (!is_null($code)) {
      $response = $response->withStatus($code);
    }
  
    return app()->resolve('view')->render($response, $view . '.twig', $data);
  }
}

if (!function_exists('request')) {
  /**
   * Get instance request
   *
   * @return \Psr\Http\Message\ServerRequestInterface|\Slim\Http\Request
   */
  function request()
  {
    return app()->resolve('request');
  }
}

if (!function_exists('response')) {
  /**
   * Get instance response
   *
   * @return \Psr\Http\Message\ResponseInterface|\Slim\Http\Response
   */
  function response()
  {
    return app()->resolve('response');
  }
}

if (!function_exists('router')) {
  /**
   * Get instance router
   *
   * @return \Slim\Router
   */
  function router()
  {
    return app()->resolve('router');
  }
}

if (!function_exists('app')) {
  /**
   * Get instance app
   *
   * @return \App\Core\App
   */
  function app()
  {
    return App\Core\App::getInstance();
  }
}
