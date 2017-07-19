<?php

return [
    
    /**
     * Template engine
     *
     * Defines which template engine will be used.
     *
     * php | twig | blade
     *
     * blade required : "composer require philo/laravel-blade"
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
        'folder' => APP_FOLDER . '/resources/view',
        'compiled' => APP_FOLDER . '/storage/view',
    ],

];
