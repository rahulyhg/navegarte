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

namespace App\Core\Contracts;

use Slim\Container;

/**
 * Class BaseServiceProvider
 *
 * @package App\Core\Contracts
 */
abstract class BaseServiceProvider
{
  /**
   * Registers services on the given container.
   *
   * @param \Slim\Container $container
   *
   * @return mixed|void
   */
  abstract public function register(Container $container);
  
  /**
   * Register other services, such as middleware etc.
   *
   * @return mixed|void
   */
  public function boot() { }
}
