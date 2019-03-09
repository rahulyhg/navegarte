<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 08/03/2018 Vagner Cardoso
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
         * [VIEW] /
         *
         * @return \Slim\Http\Response
         */
        public function index()
        {
            $array = [];
            
            return $this->view('index', $array, 200);
        }
    }
}
