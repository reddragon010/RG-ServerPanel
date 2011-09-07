<?php

class AccountNotesController extends BaseController {
    function edit($params){
        if(isset($params['account_id'])){
            $note = AccountNote::find($params['account_id']);
            $this->render(array(
                'account_id' => $params['account_id'],
                'note' => $note
            ));
        } else {
            $this->flash('error', 'No AccountID set!');
            $this->redirect_back();
        }
    }
    
    function update($params){
        $params['updated_by'] = User::$current->id;
        $note = AccountNote::find($params['account_id']);
        if($note){
            $params['updated_by'] = User::$current->id;
            if($note->update($params)){
                $this->render_ajax('success', 'Note updated');
            } else {
                $errormsg = isset($note->errors[0]) ? $note->errors[0] : '';
                $this->render_ajax('error', 'Error on update ' . $errormsg);
            }
        } else {
            if(AccountNote::create($params)){
                $this->render_ajax('success', 'Note created');
            } else {
                $errormsg = isset($note->errors[0]) ? $note->errors[0] : '';
                $this->render_ajax('error', 'Error on creation ' . $errormsg);
            }
        }
    }
}

?>
