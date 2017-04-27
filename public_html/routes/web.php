<?php

/**
 * Router for web
 */
$app->route('get', '/[{name}]', 'HomeController')->setName('home');
