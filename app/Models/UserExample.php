<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

namespace App\Models {
    
    use Core\Helpers\Helper;
    
    /**
     * Class UserExample
     *
     * @package App\Models
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class UserExample extends Model
    {
        /**
         * @var string
         */
        protected $table = 'users';
        
        /**
         * Executa e retorna o resultado
         *
         * @return array
         * @throws \Exception
         */
        public function fetchAll()
        {
            $array = [];
            
            // Executa a query
            $stmt = $this->execute();
            
            // Percore e trata os dados
            if ($rows = $stmt->fetchAll()) {
                foreach ($rows as $row) {
                    $array[] = $row;
                }
            }
            
            // Retorna o resultado
            return $array;
        }
        
        /**
         * Adiciona registro
         *
         * @return array
         * @throws \Exception
         */
        public function create()
        {
            $this->post['id'] = $this->db->create($this->table, $this->post)->lastInsertId();
            
            return $this->post;
        }
        
        /**
         * Monta e verifica as colunas
         *
         * @param array $post
         *
         * @return $this
         * @throws \Exception
         */
        public function post(array $post)
        {
            // Where
            $where = '';
            
            if (!empty($post['id'])) {
                $where = "AND {$this->table}.id != '{$post['id']}'";
            }
            
            validate_post($post, [
                'email' => 'E-mail é obrigatório.',
            ]);
            
            // E-mail
            if (!Helper::checkMail($post['email'])) {
                throw new \InvalidArgumentException("O E-mail informado não contém um formato válido.", E_USER_WARNING);
            }
            
            if ($this->where([$where, "AND {$this->table}.email = '{$post['email']}'"])->count() > 0) {
                throw new \InvalidArgumentException("O e-mail digitado já foi registrado em nosso sistema.", E_USER_WARNING);
            }
            
            // Status
            if (!isset($post['status'])) {
                throw new \InvalidArgumentException("O Status do usuário é obrigatório.", E_USER_WARNING);
            }
            
            // Monta a propriedade
            $this->post = $post;
            
            return $this;
        }
        
        /**
         * Configura as condições padrões
         *
         * @return void
         */
        protected function conditions()
        {
            //
        }
    }
}
