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

namespace App\Core\Providers\View;

use App\Core\Providers\View\Contracts\BaseView;
use App\Core\Providers\View\TwigExtension as ExtensionCore;
use Slim\Views\Twig as TwigSlim;
use Slim\Views\TwigExtension as ExtensionSlim;

/**
 * Class Twig
 *
 * @package App\Core\Providers\View
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
final class Twig extends BaseView
{
  /**
   * Get engine template <b>TWIG</b>
   *
   * @return \Slim\Views\Twig
   */
  public function __invoke()
  {
    $twig = new TwigSlim(config('view.path.folder'), [
      'debug' => config('view.debug', false),
      'charset' => 'UTF-8',
      'cache' => (config('view.cache', false) ? config('view.path.compiled') . '/twig' : false),
      'auto_reload' => true,
    ]);
    
    $uri = rtrim(str_ireplace('index.php', '', request()->getUri()->getBasePath()), '/');
    
    $twig->addExtension(new \Twig_Extension_Debug());
    $twig->addExtension(new ExtensionSlim(router(), $uri));
    $twig->addExtension(new ExtensionCore($this->container));
    
    return $twig;
  }
}
