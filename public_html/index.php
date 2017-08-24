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
define('PUBLIC_FOLDER', ROOT . '/public_html');

/**
 * Application folder
 *
 * Defines where protected application files will be kept
 */
define('APP_FOLDER', ROOT . '/application');

/**
 * Resource folder
 */
define('RESOURCE_FOLDER', APP_FOLDER . '/resources');

/**
 * Starting application
 *
 * Do NOT TOUCH
 */
include APP_FOLDER . '/bootstrap/app.php';
