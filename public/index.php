<?php

/**
 * Optimize the application
 *
 * When activating, the source code is (HTML, JS, CSS, etc..)
 */
define('OPTIMIZE', false);

/**
 * Project root
 *
 * Do not change the configuration
 */
define('ROOT', str_ireplace('\\', '/', realpath(dirname(__DIR__))));

/**
 * Starting application
 *
 * Do NOT TOUCH
 */
include ROOT . '/bootstrap/app.php';
