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

/**
 * Class HomeController
 *
 * @package App\Controllers
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
final class HomeController extends Controller
{
    public function get()
    {
        return view('home');
    }
}
