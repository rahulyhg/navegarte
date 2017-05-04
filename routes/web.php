<?php

/**
 * Router for web
 */
$app->route('get', '/', 'HomeController')->setName('home');

/**
 * Router for EGP
 */
$app->group('', function () use ($app) {
  $app->route('get,post', '/login', 'EGP/Auth/AuthController')->setName('auth.login');
  $app->route('get,post', '/recuperar-senha', 'EGP/Auth/PasswordController@recover')->setName('auth.password.recover');
});
