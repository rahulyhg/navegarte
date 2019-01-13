<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 12/01/2018 Vagner Cardoso
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
        public function get()
        {
            $array = [];
            
            return $this->view('@web.index', $array, 200);
        }
        
        /**
         * Inicia junto com o __construct da classe pai
         */
        protected function boot()
        {
            //
        }
    }
}
