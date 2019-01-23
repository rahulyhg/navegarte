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
         * Adiciona registro
         *
         * @return array
         * @throws \Exception
         */
        public function save()
        {
            if (!empty($this->data['id'])) {
                // Atualiza
                if (!$row = $this->reset()->where("AND {$this->table}.id = :id", "id={$this->data['id']}")->fetch()) {
                    throw new \Exception("Registro não encontrado.", E_USER_ERROR);
                }
                
                $this->update->exec($this->table, $this->data, 'WHERE id = :id', "id={$this->data['id']}");
                $this->data = array_merge($row, $this->data);
            } else {
                // Adiciona
                $this->data['id'] = $this->create->exec($this->table, $this->data);
            }
            
            return $this->data;
        }
        
        /**
         * Monta e verifica as colunas
         *
         * @param array $data
         *
         * @return $this
         * @throws \Exception
         */
        public function data(array $data)
        {
            // Where
            $where = '';
            
            if (!empty($data['id'])) {
                $where = "AND {$this->table}.id != '{$data['id']}'";
            }
            
            validate_params($data, [
                'email' => 'E-mail é obrigatório.',
            ]);
            
            // E-mail
            if (!empty($data['email'])) {
                if (!Helper::checkMail($data['email'])) {
                    throw new \InvalidArgumentException("O E-mail informado não é válido.", E_USER_WARNING);
                }
                
                if ($this->reset()->where([$where, "AND {$this->table}.email = '{$data['email']}'"])->count() > 0) {
                    throw new \InvalidArgumentException("O e-mail digitado já foi registrado.", E_USER_WARNING);
                }
            }
            
            // Monta a propriedade
            $this->data = $data;
            
            return $this;
        }
    }
}
