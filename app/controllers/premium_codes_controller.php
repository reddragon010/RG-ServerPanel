<?php

class PremiumCodesController extends BaseController {
    
    function index(){
        $this->render();
    }
    
    function check($params){
        if(isset($params['code']) && !empty($params['code'])){
            $premcode = PremiumCode::find('first',array('conditions' => array('code' => $params['code'])));
            if($premcode){
                if($premcode->used == '0'){
                    $this->render_ajax('success', "{$premcode->code} is valid for {$premcode->for}");
                } else {
                    $this->render_ajax('error', 'code is used');
                }
            } else {
                $this->render_ajax('error', "Code ({$params['code']}) not found");
            }
        } else {
            $this->render_ajax('error', 'No code selected');
        }
    }
    
    function invalidate($params){
        if(isset($params['code']) && !empty($params['code'])){
            $premcode = PremiumCode::find('first',array('conditions' => array('code' => $params['code'])));
            if($premcode){
                if($premcode->invalidate()){
                    $this->render_ajax('success', 'Code unvalidated');
                } else {
                    $this->render_ajax('error', $premcode->errors[0]);
                }
            } else {
                $this->render_ajax('error', "Code ({$params['code']}) not found or invalid");
            }
        } else {
            $this->render_ajax('error', 'No code selected');
        }
    }
    
    function renew($params){
        if(isset($params['code']) && !empty($params['code'])){
            $premcode = PremiumCode::find('first',array('conditions' => array('code' => $params['code'])));
            if($premcode){
                $new_code = $premcode->renew();
                if($new_code){
                    $this->render_ajax('success', 'Trashed old code and generated new one: ' . $new_code);
                } else {
                    $this->render_ajax('error', $premcode->errors[0]);
                }
            } else {
                $this->render_ajax('error', "Code ({$params['code']}) not found or invalid");
            }
        } else {
            $this->render_ajax('error', 'No code selected');
        }
    }
}
