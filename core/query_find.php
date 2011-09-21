<?php
class QueryFind extends SqlS_QuerySelect {
    private $per_page;
    private $reload = false;
    
    protected $type = 'one';
    protected $additions = array();
    
    public function __construct($dbobject) {
        $this->per_page = $dbobject::$per_page;
        parent::__construct($dbobject);
    }
    
    public function execute($additions=array()){
        $cache_key = ObjectStore::gen_key(array($this->build_sql(), $this->build_sql_values()));
        if(!$this->reload)
                $cache = ObjectStore::get($cache_key);
        if ($cache) {
            return $cache;
        } else {
            $result = parent::execute();
            if(is_array($result)){
                foreach ($result as $result_key=>$result_val) {
                    foreach ($this->additions as $key => $val) {
                        $result[$result_key]->$key = $val;
                    }
                }
            } else {
                foreach ($this->additions as $key => $val) {
                    $result->$key = $val;
                }
            }
            
            ObjectStore::put($cache_key, $result);
        }
        return $result;
    }
    
    public function find($pk=null) {
        if(!is_null($pk)) {
            return $this->find_by_pk($pk);
        }
        return $this;
    }

    public function additions($additions){
        $this->additions = $additions;
        return $this;
    }
    
    public function page($page){
        if(is_numeric($page) && $page > 1){
            $offset = ($page - 1) * $this->per_page;
            $this->offset($offset);
        }
        return $this;
    }
    
    public function all() {
        $this->type = 'many';
        $this->limit($this->per_page);
        return $this->execute();
    }

    public function first() {
        $this->limit(1);
        return $this->execute();
    }
    
    public function last() {
        $this->order = array($this->pk, ' DESC');
        $this->limit(1);
        return $this->execute();
    }
    
    public function count() {
        $this->limit(1);
        $this->offset(0);
        $this->counting(true);
        return $this->execute()->c;
    }

    private function find_by_pk($id) {
        $pk = $this->pk;
        $this->where(array($pk => $id));
        return $this->first();
    }
    
    function __call($name, $arguments){
        if(method_exists($this->result_name, 'scope_' . $name)){
            array_unshift($arguments, $this);
            return call_user_func_array(array($this->result_name, 'scope_' . $name), $arguments);
        } else {
            throw new Exception('Invalid call to ' . $name . ' on Find (' . $this->result_name . ')');
        }
    }
}

