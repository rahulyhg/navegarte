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
         * @param int $id
         *
         * @return array
         * @throws \Exception
         */
        public function fetchById($id)
        {
            $id = filter_params($id)[0];
            
            return $this->where([
                "AND {$this->table}.id = :uid",
            ], "uid={$id}")->limit(1)->fetch();
        }
        
        /**
         * Adiciona registro
         *
         * @return array
         * @throws \Exception
         */
        public function save()
        {
            if (!empty($this->data['id'])) {
                // Verifica
                if (!$row = $this->reset()->fetchById($this->data['id'])) {
                    throw new \Exception("Registro não encontrado.", E_USER_ERROR);
                }
                
                // Atualiza
                $this->db->update($this->table, array_merge($this->data, [
                    'updated_at' => database_format_datetime(),
                ]), 'WHERE id = :id', "id={$this->data['id']}");
                
                // Mescla os dados
                $this->data = array_merge($row, $this->data);
            } else {
                // Adiciona
                $this->data['id'] = $this->db->create($this->table, array_merge($this->data, [
                    'created_at' => database_format_datetime(),
                ]))->lastInsertId();
            }
            
            return $this->data;
        }
        
        /**
         * Monta e verifica as colunas
         *
         * @param array $data
         * @param bool $validate
         *
         * @return void
         * @throws \Exception
         */
        public function _data(array &$data, $validate = true)
        {
            // Caso passe o id
            if (!empty($data['id'])) {
                $this->where("AND {$this->table}.id != '{$data['id']}'");
            }
            
            // Validações
            validate_params($data, [
                'name' => 'Nome não pode ser vázio.',
                'email' => 'E-mail não pode ser vázio.',
                'password' => ['message' => 'Senha não pode ser vázio.', 'id' => !empty($data['id'])],
            ]);
            
            // E-mail
            if (!empty($data['email'])) {
                if (!Helper::checkMail($data['email'])) {
                    throw new \InvalidArgumentException(
                        "O E-mail informado não é válido.", E_USER_WARNING
                    );
                }
                
                if ($this->reset()->where("AND {$this->table}.email = '{$data['email']}'")->count() > 0) {
                    throw new \InvalidArgumentException(
                        "O e-mail digitado já foi registrado.", E_USER_WARNING
                    );
                }
            }
        }
    }
}
