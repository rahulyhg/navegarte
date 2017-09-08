<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

use Core\App;

/**
 * Initializes the buffer and blocks any output to the browser
 * Compress HTML,JS,CSS etc
 */
ob_start(
    function ($buffer) {
        if ((!empty(OPTIMIZE) && OPTIMIZE === true) && (!mb_strpos($_SERVER['HTTP_HOST'], '.dev') && !mb_strpos($_SERVER['HTTP_HOST'], '.local'))) {
            $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
            $buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '   ', '    ',], '', $buffer);
        }
        
        return $buffer;
    }
);

/**
 * It is used when running the php embedded server
 */
if (PHP_SAPI == 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = APP_FOLDER . $url['path'];
    
    if (is_file($file)) {
        return false;
    }
}

/**
 * Composer autoload dependencies
 */
$composerAutoload = APP_FOLDER . '/vendor/autoload.php';

if (!file_exists($composerAutoload)) {
    die('run composer install');
}

include "{$composerAutoload}";

/**
 * Load functions custom
 */
if (file_exists(APP_FOLDER . '/app/functions.php')) {
    include APP_FOLDER . '/app/functions.php';
}

/**
 * Starting dotenv configuration
 */
if (file_exists(APP_FOLDER . '/.env')) {
    try {
        (new Dotenv\Dotenv(APP_FOLDER))->load();
    } catch (Dotenv\Exception\InvalidPathException $e) {
        //
    }
} else {
    $envContent = file_get_contents(APP_FOLDER . '/.env-example');
    file_put_contents(APP_FOLDER . '/.env', $envContent, FILE_APPEND);
}

/**
 * Instance class app
 */
$app = App::getInstance();

/**
 * Register middleware
 */
$app->registerMiddleware();

/**
 * Register container
 */
$app->registerContainer();

/**
 * Register routers
 */
$app->registerRouter();

/**
 * Initialize app
 */
$app->run();

/**
 * Finalized the buffer
 */
ob_end_flush();
