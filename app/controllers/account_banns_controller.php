<?php
class AccountBannsController extends BaseController {
    function add($params){
        $this->render(array('account_id' => $params['account_id']));
    }
    
    function create($params){
        global $current_user;
        switch($params['bantype']){
            case 'perm':
                $params['unbandate'] = 0;
                break;
            case 'time':
                $params['unbandate'] = mktime(
                        $params['hours_select'], 
                        0, 
                        0, 
                        $params['month_select'], 
                        $params['day_select'], 
                        $params['year_select']
                        );
                break;
            case 'save':
                $params['unbandate'] = 0;
                $params['banreason'] = 'Save-Ban';
                break;
        }
        $params['bandate'] = time();
        $params['active'] = 1;
        $params['bannedby'] = $current_user->id;
        if(AccountBan::create($params, $obj)){
            $this->render_ajax('success', 'Successfully banned');
        } else {
            $this->render_ajax('error', 'Error! ' . $obj->errors[0]);
        }
    }
    
    function delete($params){
        if(isset($params['id']) && !empty($params['id'])){
            $ban = AccountBan::find('first',array('conditions' => array('id' => $params['id'], 'active' => '1')));
            if($ban){
                $ban->active = 0;
                if($ban->save()){
                   $this->flash('success', 'Successfully unbanned'); 
                } else {
                   $this->flash('error', 'Error! ' . $ban->errors[0]);
                }
            } else {
                $this->flash('error', 'No Ban found!');
            }
        } else {
            $this->flash('error', 'No ID!');
        }
        //var_dump(Debug::getDebugBuffer());
        $this->redirect_back();
    }
}
