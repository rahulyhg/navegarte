<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 13/01/2018 Vagner Cardoso
 */

use Core\App;

/**
 * Ob Start
 *
 * Configura a otimização dos assets e html
 */

ob_start(function ($buffer) {
    if (!preg_match('/localhost|.dev|.local/', $_SERVER['HTTP_HOST'])) {
        // Remove comentários
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        
        // Remove espaço com mais de um espaço
        $buffer = preg_replace('/\r\n|\r|\n|\t/m', '', $buffer);
        $buffer = preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $buffer);
    }
    
    return $buffer;
});

/**
 * CLI Server
 *
 * É usado ao executar o servidor incorporado php
 */

if (PHP_SAPI == 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__.$url['path'];
    
    if (is_file($file)) {
        return false;
    }
}

/**
 * Composer
 *
 * Carrega o autoload
 */

$pathAutoload = APP_FOLDER.'/vendor/autoload.php';

if (!file_exists($pathAutoload)) {
    die(
        'Run command in terminal: <br>'.
        '<code style="background: #000; color: #fff;">composer install</code>'
    );
}

$autoload = require_once "{$pathAutoload}";

/**
 * App
 *
 * Instancia da classe da aplicação
 */

$app = App::getInstance();

/**
 * Inicia os serviços
 */

$app->initProviders();

/**
 * Inicia as middlewares
 */

$app->initMiddlewares();

/**
 * Inicia as rotas
 */

$app->initRoutes();

/**
 * Inicia
 */

$app->run();

/**
 * Finaliza o buffer de saída
 */

ob_end_flush();
