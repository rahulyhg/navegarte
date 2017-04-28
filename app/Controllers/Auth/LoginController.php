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

namespace App\Controllers\Auth;

use App\Controllers\Controller;

/**
 * Class LoginController
 *
 * @package App\Controllers\Auth
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class LoginController extends Controller
{
  public function get()
  {
    return view('auth/login');
  }
  
  public function post()
  {
    return '';
  }
}
