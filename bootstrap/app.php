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
    $file = APP_FOLDER.$url['path'];
    
    if (is_file($file)) {
        return false;
    }
}

/**
 * Composer
 *
 * Carrega o autoload
 */

$composerAutoload = APP_FOLDER.'/vendor/autoload.php';

if (!file_exists($composerAutoload)) {
    die('Run command in terminal: <br><code style="background: #000; color: #fff;">composer install</code>');
}

require_once "{$composerAutoload}";

/**
 * ENV
 *
 * Carrega as configurações do .env
 */

$envFile = APP_FOLDER.'/.env';

if (file_exists($envFile)) {
    (new \Dotenv\Dotenv(APP_FOLDER, '.env'))->load();
} else {
    $envExample = APP_FOLDER.'/.env-example';
    
    if ((file_exists($envExample) && !is_dir($envExample)) && !file_exists($envFile)) {
        $envContent = file_get_contents($envExample);
        
        file_put_contents($envFile, $envContent, FILE_APPEND);
    }
}

/**
 * App
 *
 * Instancia da classe da aplicação
 */

$app = App::getInstance();

/**
 * Inicia as configuraçoes padrões
 */

$app->registerFunctions();

/**
 * Inicia os serviços
 */

$app->registerProviders();

/**
 * Inicia as middlewares
 */

$app->registerMiddleware();

/**
 * Inicia as rotas
 */

$app->registerRouter();

/**
 * Inicia
 */

$app->run();

/**
 * Finaliza o buffer de saída
 */

ob_end_flush();
