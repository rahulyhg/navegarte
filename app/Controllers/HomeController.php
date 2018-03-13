<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

namespace App\Controllers {
    
    use Core\Contracts\Controller;
    
    /**
     * Class HomeController
     *
     * @package App\Controllers
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    final class HomeController extends Controller
    {
        /**
         * Inicializa junto com o Controller
         */
        public function boot()
        {
        }
        
        /**
         * Template home
         *
         * @return mixed
         */
        public function get()
        {
            $array = [];
            
            return $this->view('home', $array, 200);
        }
    }
}
