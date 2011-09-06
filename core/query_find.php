<?php
class QueryFind extends SqlS_QuerySelect {
    private $per_page;
    private $reload = false;
    
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
    
    public function find($type) {
        if ($type == 'all') {
            $this->find_all();
        } elseif ($type == 'first') {
            $this->find_one();
        } elseif ($type == 'last') {
            $this->order = array($this->pk, ' DESC');
            $this->find_one();
        } elseif (is_numeric($type)) {
            $this->find_by_pk(intval($type));
        } else {
            throw new Exception('Find error with ' . $type .' on ' . get_called_class());
        }
        return $this;
    }

    private function find_all() {
        $this->type = 'many';
        $this->limit($this->per_page);
    }

    private function find_one() {
        $this->limit(1);
    }

    private function find_by_pk($id) {
        $pk = $this->pk;
        $this->where(array($pk => $id));
        $this->find_one();
    }

    
}

