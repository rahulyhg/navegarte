<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

namespace App\Controllers {
    
    use Core\Contracts\Controller;
    
    /**
     * Class IndexController
     *
     * @package App\Controllers
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class IndexController extends Controller
    {
        /**
         * Inicializa junto com o Controller
         */
        public function boot()
        {
        }
        
        /**
         * [VIEW] /
         *
         * @return \Slim\Http\Response
         */
        public function get()
        {
            $array = [];
            
            return $this->view('index', $array, 200);
        }
    }
}
