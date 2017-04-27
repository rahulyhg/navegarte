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

namespace App\Controllers;

use App\Core\Contracts\BaseController;

/**
 * Class HomeController
 *
 * @package App\Controllers
 */
final class HomeController extends BaseController
{
  public function get($name = 'Navegarte')
  {
    return view('home', ['name' => $name]);
  }
}
