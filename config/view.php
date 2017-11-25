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

return [

    /**
     * Template engine
     *
     * Defines which template engine will be used.
     *
     * php | twig
     */
    'engine' => 'twig',

    /**
     * View debug
     *
     * When enabled, any type of error will be
     */
    'debug' => true,

    /**
     * View cache
     *
     * When enabled, it will be saved as configured by configuring [path.compiled]
     */
    'cache' => false,

    /**
     * View paths
     *
     * Directories that will pull the views and save the cache
     */
    'path' => [
        'folder' => RESOURCE_FOLDER . '/view',
        'compiled' => APP_FOLDER . '/storage/cache',
    ],

];
