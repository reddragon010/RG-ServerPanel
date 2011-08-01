<?php
class SqlQFind extends SqlQSelect {
    private $per_page;
    
    public function __construct($table, $fields = array(), $pk = 'id', $per_page = 25) {
        $this->per_page = $per_page;
        parent::__construct($table, $fields, $pk);
    }
    
    public function find($type, $options=array()) {
        if ($type == 'all') {
            $result = $this->find_all($options);
        } elseif ($type == 'first') {
            $result = $this->find_one($options);
        } elseif ($type == 'last') {
            $this->order = array($this->pk, ' DESC');
            $result = $this->find_one($options);
        } elseif (is_numeric($type)) {
            $result = $this->find_by_pk(intval($type));
        } else {
            throw new Exception('Find error with ' . $type .' on ' . get_called_class());
        }
        return $this;
    }

    private function find_all($options) {
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
    }
}

