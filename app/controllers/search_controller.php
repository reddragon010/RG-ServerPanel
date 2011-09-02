<?php

class SearchController extends BaseController {
    public function index($params) {
        if(isset($params['query']) && isset($params['type']) && isset($params['search'])){
            $query = $params['query'];
            $model = $params['type'];
            $controller = strtolower($model::$plural);
            $action = 'index';
            $field = $this->get_search_field($query, $params['type']);
            $this->redirect_to(array($controller, $action),array(urlencode($field) => urlencode($query)));
        } else {
            $this->flash('error', 'Invalid Search Request!');
            $this->redirect_back();
        }
    }
    
    private function get_search_field($query, $model){
        if(is_numeric($query)){
            if(isset($model::$primary_key)){
                return $model::$primary_key;
            } else {
                return 'id';
            }
        } elseif(substr_count($query, '.') == 3 && in_array('last_ip', $model::$fields)){
            return 'last_ip';
        } elseif(strpos($query, '@') !== false && in_array('email', $model::$fields)){
            return 'email';
        } elseif(isset($model::$name_field)) {
            return $model::$name_field;
        } else {
            throw new Exception('No matching field found for this query-type combination');
        }
    }
}
