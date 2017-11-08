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

/**
 * Optimize the application
 *
 * When activating, the source code is (HTML, JS, CSS, etc..)
 */
define('OPTIMIZE', true);

/**
 * Project root
 *
 * Do not change the configuration
 */
define('ROOT', str_ireplace('\\', '/', realpath(dirname(__DIR__))));

/**
 * Root folder
 *
 * Defines where the public application files will be kept
 */
define('PUBLIC_FOLDER', ROOT);

/**
 * Application folder
 *
 * Defines where protected application files will be kept
 */
define('APP_FOLDER', ROOT);

/**
 * Resource folder
 */
define('RESOURCE_FOLDER', APP_FOLDER . '/resources');

/**
 * Base url
 */
define('BASE_URL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

/**
 * Request URI
 */
define('REQUEST_URI', $_SERVER['REQUEST_URI']);

/**
 * Starting application
 *
 * Do NOT TOUCH
 */
include APP_FOLDER . '/bootstrap/app.php';
