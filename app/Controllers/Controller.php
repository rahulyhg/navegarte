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

/**
 * Class Controller
 *
 * @package App\Controllers
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 *
 * @property \Navegarte\Providers\Hash\BcryptHasher hash
 * @property \Navegarte\Providers\Session\Session   session
 */
abstract class Controller extends BaseController
{
}
