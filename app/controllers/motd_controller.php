<?php

class MotdController extends BaseController {
    function edit(){
        $file = new PrivateFile('motd');
        $this->render(array(
            'content' => $file->get()
        ));
    }
    
    function update($params){
        if(isset($params['content'])){
            $params['content'] = $this->unescape($params['content']);
            $file = new PrivateFile('motd');
            $file->put($params['content']);
            if($file->get() == $params['content']){
                $this->render_ajax('success', 'MOTD successfully edited');
            } else {
                $this->render_ajax('error', 'An error occured');
            }
        }
    }
    
    private function unescape($text){
        return str_replace('\\', '', $text);
    }
}

?>
