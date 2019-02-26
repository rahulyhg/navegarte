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
    
    use Core\Contracts\Model as BaseModel;
    
    /**
     * Class Model
     *
     * @package App\Models
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    abstract class Model extends BaseModel
    {
        /**
         * @var string
         */
        protected $table;
        
        /**
         * @var array
         */
        protected $select = [];
        
        /**
         * @var array
         */
        protected $join = [];
        
        /**
         * @var array
         */
        protected $where = [];
        
        /**
         * @var array
         */
        protected $group = [];
        
        /**
         * @var array
         */
        protected $having = [];
        
        /**
         * @var array
         */
        protected $order = [];
        
        /**
         * @var int
         */
        protected $limit = null;
        
        /**
         * @var int
         */
        protected $offset = null;
        
        /**
         * @var array
         */
        protected $places = [];
        
        /**
         * @var array
         */
        protected $reset = [];
        
        /**
         * @var array
         */
        protected $data = [];
        
        /**
         * Retorna um registro
         *
         * @return array
         * @throws \Exception
         */
        public function fetch()
        {
            $fetch = current(
                $this->limit(1)->fetchAll()
            );
            
            return ($fetch ?: []);
        }
        
        /**
         * Executa e retorna o resultado padrão
         *
         * @return array
         * @throws \Exception
         */
        public function fetchAll()
        {
            $rows = $this->execute()->fetchAll();
            
            foreach ($rows as $index => $row) {
                if (method_exists($this, '_row')) {
                    $this->{'_row'}($row);
                }
                
                $rows[$index] = $row;
            }
            
            return $rows;
        }
        
        /**
         * Monta e executa a query
         *
         * @param bool $count
         *
         * @return \Core\Database\Statement\Read
         * @throws \Exception
         */
        protected function execute($count = false)
        {
            if (empty($this->table)) {
                throw new \InvalidArgumentException('You need to add the table name in the `$table` property of the '.get_class($this).' class.', E_USER_ERROR);
            }
            
            // Verifica se o método está criado e executa
            if (method_exists($this, '_conditions')) {
                $this->{'_conditions'}();
            }
            
            // Select
            $this->select = ($count ? 'COUNT(1) AS count' : implode(', ', ($this->select ?: ["{$this->table}.*"])));
            $sql = "SELECT {$this->select} FROM {$this->table} ";
            
            // Join
            if (!empty($this->join) && is_array($this->join)) {
                $this->join = implode(' ', $this->join);
                $sql .= "{$this->join} ";
            }
            
            // Where
            if (!empty($this->where) && is_array($this->where)) {
                $this->where = $this->removeAndOrProperty(implode(' ', $this->where));
                $sql .= "WHERE {$this->where} ";
            }
            
            // Group BY
            if (!empty($this->group) && is_array($this->group) && !$count) {
                $this->group = implode(', ', $this->group);
                $sql .= "GROUP BY {$this->group} ";
            }
            
            // Having
            if (!empty($this->having) && is_array($this->having)) {
                $this->having = $this->removeAndOrProperty(implode(' ', $this->having));
                $sql .= "HAVING {$this->having} ";
            }
            
            // Order by
            if (!empty($this->order) && is_array($this->order)) {
                $this->order = ($count ? 'count DESC' : implode(', ', $this->order));
                $sql .= "ORDER BY {$this->order} ";
            }
            
            // Limit & Offset
            if (!empty($this->limit) && is_int($this->limit)) {
                $this->offset = $this->offset ?: '0';
                
                if (in_array(config('database.default'), ['dblib', 'sqlsrv'])) {
                    $sql .= "OFFSET {$this->offset} ROWS FETCH NEXT {$this->limit} ROWS ONLY";
                } else {
                    $sql .= "LIMIT {$this->limit} OFFSET {$this->offset}";
                }
            }
            
            // Executa a query
            $read = $this->read->query($sql, $this->places);
            
            // Limpa as propriedades da classe
            $this->clear();
            
            return $read;
        }
        
        /**
         * Remove caracteres no começo da string
         *
         * @param $string
         *
         * @return string
         */
        protected function removeAndOrProperty($string)
        {
            $chars = ['and', 'AND', 'or', 'OR'];
            
            foreach ($chars as $char) {
                $strlenChar = mb_strlen($char);
                
                if (mb_substr($string, 0, $strlenChar) === (string) $char) {
                    $string = trim(mb_substr($string, $strlenChar));
                }
            }
            
            return $string;
        }
        
        /**
         * Limpa as propriedade da classe para
         * uma nova consulta
         */
        protected function clear()
        {
            /*$this->table = '';*/
            $this->select = [];
            $this->join = [];
            $this->where = [];
            $this->group = [];
            $this->having = [];
            $this->order = [];
            $this->limit = null;
            $this->offset = null;
            $this->places = [];
            $this->reset = [];
            /*$this->data = [];*/
        }
        
        /**
         * @param int $limit
         *
         * @return $this
         */
        public function limit($limit)
        {
            $this->limit = (int) $limit;
            
            return $this;
        }
        
        /**
         * Recupera o total de registro
         *
         * @return int
         * @throws \Exception
         */
        public function count()
        {
            // Executa a query
            $stmt = $this->limit(1)->execute(true)->fetch();
            
            return (int) $stmt['count'];
        }
        
        /**
         * @param mixed $select
         *
         * @return $this
         */
        public function select($select)
        {
            if (is_string($select)) {
                $select = explode(',', $select);
            }
            
            $this->montPropertyArray($select, 'select');
            
            return $this;
        }
        
        /**
         * Monta os array
         *
         * @param string|array|null $conditions
         * @param string $property
         */
        protected function montPropertyArray($conditions, $property)
        {
            if (!is_array($this->{$property})) {
                $this->{$property} = [];
            }
            
            foreach ((array) $conditions as $condition) {
                if (!empty($condition) &&
                    !array_search($condition, $this->{$property}) &&
                    !array_search("{$this->table}.{$condition}", $this->{$property})
                ) {
                    $this->{$property}[] = trim((string) $condition);
                }
            }
        }
        
        /**
         * @param string|array|null $join
         *
         * @return $this
         */
        public function join($join)
        {
            $this->montPropertyArray($join, 'join');
            
            return $this;
        }
        
        /**
         * @param string|array|null $where
         *
         * @param string|array|null $places
         *
         * @return $this
         */
        public function where($where, $places = [])
        {
            $this->montPropertyArray($where, 'where');
            $this->places($places);
            
            return $this;
        }
        
        /**
         * @param string|array $places
         *
         * @return $this
         */
        public function places($places)
        {
            if (!empty($places)) {
                if (is_string($places)) {
                    $places = explode('&', $places);
                    
                    foreach ($places as $place) {
                        $place = explode('=', $place);
                        
                        $this->places[$place[0]] = $place[1];
                    }
                } else {
                    $this->places = $places;
                }
            }
            
            return $this;
        }
        
        /**
         * @param string|array|null $properties
         *
         * @return $this
         */
        public function reset($properties = [])
        {
            if (empty($properties)) {
                $reflection = new \ReflectionClass(get_class($this));
                
                foreach ($reflection->getProperties() as $property) {
                    if (!in_array($property->getName(), ['table', 'data'])) {
                        $this->reset[$property->getName()] = true;
                    }
                }
            } else {
                if (is_string($properties)) {
                    $properties = explode(',', $properties);
                }
                
                foreach ($properties as $property) {
                    $this->reset[trim($property)] = true;
                }
            }
            
            return $this;
        }
        
        /**
         * @param string|array|null $group
         *
         * @return $this
         */
        public function group($group)
        {
            $this->montPropertyArray($group, 'group');
            
            return $this;
        }
        
        /**
         * @param string|array|null $having
         *
         * @return $this
         */
        public function having($having)
        {
            $this->montPropertyArray($having, 'having');
            
            return $this;
        }
        
        /**
         * @param string|array|null $order
         *
         * @return $this
         */
        public function order($order)
        {
            $this->montPropertyArray($order, 'order');
            
            return $this;
        }
        
        /**
         * @param int $offset
         *
         * @return $this
         */
        public function offset($offset)
        {
            $this->offset = (int) $offset;
            
            return $this;
        }
        
        /**
         * @return string
         */
        public function table()
        {
            return $this->table;
        }
    }
}
