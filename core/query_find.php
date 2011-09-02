<?php
class QueryFind extends SqlS_QuerySelect {
    private $per_page;
    private $reload = false;
    
    private $find_type;
    private $find_options;
    
    protected $type = 'one';
    
    public function __construct($dbobject) {
        $this->per_page = $dbobject::$per_page;
        parent::__construct($dbobject);
    }
    
    public function execute($additions=array()){
        $cache = false;
        $cache_key = ObjectStore::gen_key(array($this->build_sql(), $this->build_sql_values()));
        if(!$this->reload)
                $cache = ObjectStore::get($cache_key);
        if ($cache) {
            return $cache;
        } else {
            $result = parent::execute();
            if(is_array($result)){
                foreach ($result as $result_key=>$result_val) {
                    foreach ($additions as $key => $val) {
                        $result[$result_key]->$key = $val;
                    }
                }
            } else {
                foreach ($additions as $key => $val) {
                    $result->$key = $val;
                }
            }
            
            ObjectStore::put($cache_key, $result);
        }
        return $result;
    }
    
    public function find($type,$options) {
        $this->find_type = $type;
        $this->find_options = $options;
        if ($type == 'all') {
            $this->type = 'many';
            $this->find_all($options);
        } elseif ($type == 'first') {
            $this->find_one($options);
        } elseif ($type == 'last') {
            $this->order = array($this->pk, ' DESC');
            $this->find_one($options);
        } elseif (is_numeric($type)) {
            $this->find_by_pk(intval($type));
        } else {
            throw new Exception('Find error with ' . $type .' on ' . get_called_class());
        }
        return $this;
    }

    private function find_all($options) {
        $this->type = 'many';
        if (!isset($options['limit'])) {
            $options['limit'] = $this->per_page;
        }
        if (!isset($options['offset']) && isset($options['conditions']) && isset($options['conditions']['page']) && $options['conditions']['page'] > 0) {
            $options['offset'] = ($options['conditions']['page'] - 1) * $this->per_page;
        }
        $this->parse_options($options);
    }

    private function find_one($options) {
        $options['limit'] = 1;
        $this->parse_options($options);
    }

    private function find_by_pk($id) {
        $pk = $this->pk;
        $options['conditions'] = array("{$pk}=:pk", 'pk' => $id);
        return $this->find_one($options);
    }

    private function parse_options($options) {
        if (isset($options['conditions']))
            $this->where($options['conditions']);
        if (isset($options['order']))
            $this->order($options['order']);
        if (isset($options['limit']))
            $this->limit($options['limit']);
        if (isset($options['offset']))
            $this->offset($options['offset']);
        if (isset($options['group_by']))
            $this->group_by($options['group_by']);
    }
}

