<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

use Core\App;

/**
 * Ob Start
 *
 * Configura a otimização dos assets e html
 */

ob_start(function ($buffer) {
    if (mb_strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '   ', '    ',], '', $buffer);
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
    die('run composer install');
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

$app->initConfigs();

/**
 * Inicia as rotas
 */

$app->initRoutes();

/**
 * Inicia os serviços
 */

$app->initProviders();

/**
 * Inicia as middlewares
 */

$app->initMiddlewares();

/**
 * Inicia
 */

$app->run();

/**
 * Finaliza o buffer de saída
 */

ob_end_flush();
