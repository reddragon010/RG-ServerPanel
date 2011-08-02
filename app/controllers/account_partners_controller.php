<?php
class AccountPartnersController extends BaseController {
    function add($params) {
        $this->render(array('account_id' => $params['account_id']));
    }
    
    function create($params){
        if(isset($params['partner']) && !empty($params['partner'])){
            $partner_name_or_id = $params['partner'];
            if(is_numeric($partner_name_or_id)){
                $partner = Account::find($partner_name_or_id);
            } else {
                $partner = Account::find('first', array('conditions' => array('username' => $partner_name_or_id)));
            }
            if($partner){
                $params['partner_id'] = $partner->id;
                if(AccountPartner::create($params,$accpartner)){
                    $this->render_ajax('success', 'Partner created');
                } else {
                    $this->render_ajax('error', 'Creation failed with ' . $accpartner->errors[0]);
                }
            } else {
                $this->render_ajax('error', 'Partner-Account not found!');
            }
        } else {
            $this->render_ajax('error', 'No Partner-ID or -Name!');
        }
    }
    
    function delete($params){
        if(isset($params['id']) && !empty($params['id'])){
            $id = $params['id'];
            $partner = AccountPartner::find($id);
            if($partner){
                if($partner->destroy()){
                    $this->flash('success', 'Partner deleted');
                } else {
                    $this->flash('error', 'Deletion failed');
                }
            } else {
                $this->flash('error', 'Partner-Account not found!');
            }
        } else {
            $this->flash('error', 'No ID!');
        }
        $this->redirect_back();
    }
}
