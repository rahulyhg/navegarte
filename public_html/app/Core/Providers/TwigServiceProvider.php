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
use App\Core\Helpers\TwigExtension as TwigCore;
use Slim\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension as TwigSlim;

/**
 * Class TwigServiceProvider
 *
 * @package App\Core\Providers
 */
final class TwigServiceProvider extends BaseServiceProvider
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
      $twig = new Twig(config('view.path.folder'), [
        'debug' => config('view.debug', false),
        'charset' => 'UTF-8',
        'cache' => (config('view.cache', false) ? config('view.path.compiled') : false),
        'auto_reload' => true,
      ]);
      
      $uri = rtrim(str_ireplace('index.php', '', request()->getUri()->getBasePath()), '/');
      
      $twig->addExtension(new \Twig_Extension_Debug());
      $twig->addExtension(new TwigSlim(router(), $uri));
      $twig->addExtension(new TwigCore($container));
      
      return $twig;
    };
  }
}
