<?php

return [
  
  /**
   * Template engine
   *
   * Define qual engine de template será utilizada.
   *
   * twig | blade
   */
  'template' => 'twig',
  
  /**
   * View paths
   *
   * Directories that will pull the views and save the cache
   */
  'path' => [
    'folder' => ROOT . '/resources/view',
    'compiled' => ROOT . '/storage/view',
  ],
  
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

];

