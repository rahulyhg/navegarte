<?php

/**
 * NAVEGARTE Networks
 *
 * @package   framework
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-${YEAH} Vagner Cardoso - NAVEGARTE
 */

namespace App\Controllers;

use Navegarte\Contracts\BaseController;
use Slim\Exception\NotFoundException;

/**
 * Class Controller
 *
 * @package App\Controllers
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 *
 * @property \Navegarte\Providers\Hash\BcryptHasher hash
 * @property \Navegarte\Providers\Session\Session   session
 * @property \Navegarte\Providers\Mailer\Mailer     mailer
 * @property \Intervention\Image\ImageManager       image
 */
abstract class Controller extends BaseController
{
  /**
   * @param null|string $name
   *
   * @return array|mixed
   */
  public function param($name = null)
  {
    if (is_null($name)) {
      return $this->request->getParams();
    }
    
    return $this->request->getParam($name);
  }
  
  /**
   * @param null|string $name
   * @param array       $data
   * @param array       $queryParams
   *
   * @return \Slim\Http\Response
   */
  public function redirect($name = null, $data = [], $queryParams = [])
  {
    $name = !is_null($name) ? $name : 'home';
    
    return $this->response->withRedirect($this->router()->pathFor($name, $data, $queryParams));
  }
  
  /**
   * @param mixed $data
   * @param bool  $aJson
   * @param int   $status
   *
   * @return \Slim\Http\Response
   */
  public function json($data, $aJson = false, $status = 200)
  {
    if (!empty($aJson)) {
      $data = [$data];
    }
    
    return $this->response->withJson($data, $status);
  }
  
  /**
   * @throws \Slim\Exception\NotFoundException
   */
  public function notFound()
  {
    throw new NotFoundException($this->request, $this->response);
  }
  
  /**
   * @return \Slim\Router|\Slim\Route
   */
  protected function router()
  {
    return $this->container['router'];
  }
}
