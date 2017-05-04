<?php

/**
 * NAVEGARTE Networks
 *
 * @package   framework
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso - NAVEGARTE
 */

namespace App\Controllers\EGP\Auth;

use App\Controllers\Controller;

/**
 * Class AuthController
 *
 * @package App\Controllers\EGP\Auth
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class AuthController extends Controller
{
  public function get()
  {
    return view('egp.auth.login');
  }
  
  public function post()
  {
    dd($this->request->getParams());
  }
}
