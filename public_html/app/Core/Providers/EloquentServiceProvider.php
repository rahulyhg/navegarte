<?php

/**
 * NAVEGARTE Networks
 *
 * @package   FrontEnd
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso - NAVEGARTE
 */

namespace App\Core\Providers;

use App\Core\Contracts\BaseServiceProvider;
use Illuminate\Database\Capsule\Manager as Eloquent;
use Illuminate\Pagination\Paginator;
use Slim\Container;

/**
 * Class EloquentServiceProvider
 *
 * @package App\Core\Providers
 */
final class EloquentServiceProvider extends BaseServiceProvider
{
  /**
   * Registers services on the given container.
   *
   * @param \Slim\Container $container
   *
   * @return \Illuminate\Database\Connection|\Illuminate\Database\Query\Builder
   */
  public function register(Container $container)
  {
    /*
     * Instanceof ORM eloquent
     */
    $eloquent = new Eloquent;
    
    /*
     * Make the connection
     */
    foreach (config('database.connections') as $index => $config) {
      $eloquent->addConnection(config("database.connections.{$index}"), 'default');
    }
    
    /*
    * Initializes orm eloquent
    */
    $eloquent->setAsGlobal();
    $eloquent->bootEloquent();
    
    /**
     * @return \Illuminate\Database\Capsule\Manager
     */
    $container['db'] = function () use ($eloquent) {
      return $eloquent;
    };
  }
  
  /**
   * Register other services, such as middleware etc.
   *
   * @return mixed|void
   */
  public function boot()
  {
    $request = request();
    $currentPage = $request->getParam('page');
    
    Paginator::currentPageResolver(function () use ($currentPage) {
      if (filter_var($currentPage, FILTER_VALIDATE_INT) !== false && (int)$currentPage >= 1) {
        return $currentPage;
      }
  
      return 1;
    });
  }
}
