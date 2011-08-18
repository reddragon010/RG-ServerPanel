<?php

class PremiumCode extends BaseModel {
    static $dbname = 'joomla';
    static $table = 'jos_rgpremium_codes';
    static $fields = array(
        'userid', 
        'code', 
        'used', 
        'for'
    );
    static $primary_key = 'code';
    
    public function validate(){
        if(empty($this->code) || empty($this->userid) || empty($this->for)){
            $this->errors[] = "All fields needs to be filled";
            return false;
        }      
        return true;
    }
    
    public function invalidate(){
        if($this->used == '1'){
            $this->errors[] = "Code already used";
            return false;
        }
        $this->used = '1';
        return $this->save();
    }
    
    public function renew(){
        if($this->used == '1'){
            $this->errors[] = "Code already used";
            return false;
        }
        $code = $this->generate_code();
        $new_code_obj = PremiumCode::create(array(
            'userid' => $this->userid, 
            'used' => 0, 
            'for' => $this->for,
            'code' => $code
        ));
        
        if($new_code_obj && $this->invalidate()){
            return $code;
        } else {
            $this->errors[] = "Can't create new code or invalidate the old one";
            return false;
        }
        
    }
    
    private function generate_code(){
	$code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'), 0, 8);
	$premcode = PremiumCode::find('first',array('conditions' => array('code' => $code)));
	
	if($premcode) 
            $code = $this->generate_code();
        
        return $code;
    }
}
