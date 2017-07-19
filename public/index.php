<?php

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
define('PUBLIC_FOLDER', ROOT . '/public');

/**
 * Application folder
 *
 * Defines where protected application files will be kept
 */
define('APP_FOLDER', ROOT . '');

/**
 * Starting application
 *
 * Do NOT TOUCH
 */
include APP_FOLDER . '/bootstrap/app.php';
