<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 12/01/2018 Vagner Cardoso
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
    
    'cache' => true,
    
    /**
     * View paths
     *
     * Directories that will pull the views and save the cache
     */
    
    'path' => [
        'folder' => RESOURCE_FOLDER.'/view',
        'compiled' => APP_FOLDER.'/storage/cache',
    ],

];
