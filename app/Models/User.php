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

namespace App\Models {
    
    use Core\Helpers\Helper;
    use Core\Providers\Database\Model;
    
    /**
     * Class User
     *
     * @package App\Models
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class User extends Model
    {
        /**
         * @var string
         */
        protected $table = 'users';
        
        /**
         * @var string
         */
        protected $primaryKey = "id";
        
        /**
         * Monta e verifica as colunas
         *
         * @param array $data
         *
         * @return void
         * @throws \Exception
         */
        public function _data(array &$data)
        {
            // Caso passe o id
            if (!empty($data['id'])) {
                //$this->where("AND {$this->table}.{$this->primaryKey} != '{$data['id']}'");
            }
            
            // Validações
            validate_params($data, [
                'name' => 'Nome não pode ser vázio.',
                'email' => 'E-mail não pode ser vázio.',
                'password' => ['message' => 'Senha não pode ser vázio.', 'force' => empty($data['id'])],
            ]);
            
            // E-mail
            if (!empty($data['email'])) {
                if (!Helper::checkMail($data['email'])) {
                    throw new \InvalidArgumentException(
                        "O E-mail informado não é válido.", E_USER_WARNING
                    );
                }
                
                if ($this->where("AND {$this->table}.email = '{$data['email']}'")->count() > 0) {
                    throw new \InvalidArgumentException(
                        "O e-mail digitado já foi registrado.", E_USER_WARNING
                    );
                }
            }
        }
    }
}
