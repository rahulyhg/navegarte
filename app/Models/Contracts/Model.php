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

namespace App\Models\Contracts {
    
    use Core\Contracts\Model as BaseModel;
    
    /**
     * User
     *
     * @package App\Models\Contracts
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    abstract class Model extends BaseModel
    {
        /**
         * @var int
         */
        protected $id;
        
        /**
         * @var array
         */
        protected $where = [];
        
        /**
         * @var array
         */
        protected $orderBy = [];
        
        /**
         * @var int
         */
        protected $limit;
        
        /**
         * @var int
         */
        protected $offset;
        
        /**
         * @var array
         */
        protected $post = [];
        
        /**
         * @param mixed $id
         *
         * @return $this
         */
        public function id($id)
        {
            $this->id = $id;
            
            return $this;
        }
        
        /**
         * @param array|string $where
         *
         * @return $this
         */
        public function where($where)
        {
            if (!empty($where) && is_array($this->where)) {
                if (!is_array($where)) {
                    $where = [$where];
                }
                
                foreach ($where as $item) {
                    if (!empty($item)) {
                        $this->where[] = $item;
                    }
                }
            }
            
            return $this;
        }
        
        /**
         * @param array|string $orderBy
         *
         * @return $this
         */
        public function orderBy($orderBy)
        {
            if (!empty($orderBy) && is_array($this->orderBy)) {
                if (!is_array($orderBy)) {
                    $orderBy = [$orderBy];
                }
                
                foreach ($orderBy as $item) {
                    if (!empty($item)) {
                        $this->orderBy[] = $item;
                    }
                }
            }
            
            return $this;
        }
        
        /**
         * @param mixed $limit
         *
         * @return $this
         */
        public function limit($limit)
        {
            $this->limit = $limit;
            
            return $this;
        }
        
        /**
         * @param mixed $offset
         *
         * @return $this
         */
        public function offset($offset)
        {
            $this->offset = $offset;
            
            return $this;
        }
        
        /**
         * Limpa as propriedade da classe para
         * uma nova consulta
         */
        protected function clear()
        {
            $this->id = null;
            $this->where = [];
            $this->orderBy = [];
            $this->limit = null;
            $this->offset = null;
        }
    }
}
