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
use Slim\Container;

/**
 * Class BladeServiceProvider
 *
 * @package App\Core\Providers
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class BladeServiceProvider extends BaseServiceProvider
{
  /**
   * Registers services on the given container.
   *
   * @param \Slim\Container $container
   *
   * @return mixed|void
   */
  public function register(Container $container)
  {
    $container['view'] = function () use ($container) {
      
      return $this;
    };
  }
}
