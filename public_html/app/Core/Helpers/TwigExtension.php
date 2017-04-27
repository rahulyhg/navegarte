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

namespace App\Core\Helpers;

use Slim\Container;

/**
 * Class ViewHelper
 *
 * @package App\Core\Helpers
 */
final class TwigExtension extends \Twig_Extension
{
  /**
   * @var \Slim\Container
   */
  protected $container;
  
  /**
   * ViewHelper constructor.
   *
   * @param \Slim\Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
  }
  
  /**
   * Get class name
   *
   * @return string
   */
  public function getName()
  {
    return __CLASS__;
  }
  
  /**
   * Returns a list of functions to add to the existing list.
   *
   * @return array
   */
  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('config', [
        $this,
        'getConfig',
      ]),
    ];
  }
  
  /**
   * Get config
   *
   * @param string $name
   * @param null   $default
   *
   * @return array|int|string
   */
  public function getConfig($name, $default = null)
  {
    return config($name, $default);
  }
}
