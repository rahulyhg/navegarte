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

/**
 * Diretório raiz
 */

define('ROOT', str_ireplace('\\', '/', realpath(dirname(__DIR__))));

/**
 * Diretório raiz da pasta publica
 */

define('PUBLIC_FOLDER', ROOT.'/public');

/**
 * Diretório raiz da aplicação
 *
 * OBS: Esse diretório não pode ser acesso pela URL
 */

define('APP_FOLDER', ROOT.'');

/**
 * Diretório que armazena os recursos dos assets e views
 */

define('RESOURCE_FOLDER', APP_FOLDER.'/resources');

/**
 *  Define a URL base da aplicação
 */

define('BASE_URL', "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}");

/**
 * Define a URL completa da aplicação
 */

define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('FULL_URL', BASE_URL."{$_SERVER['REQUEST_URI']}");

/**
 * Bootstrap
 *
 * Inicia a aplicação
 */

require_once APP_FOLDER.'/app/bootstrap.php';
