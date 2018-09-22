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
    
    use App\Models\Contracts\Model;
    
    /**
     * Class BaseModel
     *
     * @package App\Models
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class BaseModel extends Model
    {
        /**
         * Monta, Executa e retorna o Resultado
         *
         * @param boolean $fetch
         *
         * @return array
         * @throws \Exception
         */
        public function get($fetch = null)
        {
            $array = [];
            $places = [];
            
            // Query
            $query = "SELECT ...";
            
            // Where
            if (!empty($this->where) && is_array($this->where)) {
                $this->where = implode(' ', $this->where);
                $query .= "{$this->where} ";
            }
            
            // Order by
            if (empty($this->orderBy)) {
                $query .= "";
            } else {
                if (is_array($this->orderBy)) {
                    $this->orderBy = implode(', ', $this->orderBy);
                    $query .= "ORDER BY {$this->orderBy} ";
                }
            }
            
            // Limit & Offset
            if (!empty($this->limit) && is_int($this->limit)) {
                if (empty($this->offset)) {
                    $this->offset = '0';
                }
                
                $query .= "LIMIT {$this->limit} OFFSET {$this->offset}";
            }
            
            // Executa a query
            $this->read->query($query, $places);
            if ($rows = $this->read->fetchAll()) {
                foreach ($rows as $row) {
                    $array[] = $row;
                }
            }
            
            // Verifica se o id foi passado
            if (!empty($this->id) || $fetch === true) {
                $array = current($array);
            }
            
            // Limpa a propriedade da classe
            $this->clear();
            
            // Retorna resultado
            return $array;
        }
        
        /**
         * Recupera o total de registro
         *
         * @return int
         */
        public function count()
        {
            return 1;
        }
    }
}
