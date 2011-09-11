<?php
class AccountBannsController extends BaseController {
    function index($params){
        if(isset($params['id']) && $params['id'] == ''){
            $this->render_error(404);
            return;
        }
             
        $bans = AccountBan::find('all', array('conditions' => $params, 'order' => 'bandate DESC'));
        $bans_count = AccountBan::count(array('conditions' => $params));
        if(empty($params['render_type']))
            $params['render_type'] = 'html';
        
        $data = array(
            'bans_count' => $bans_count,
            'bans' => $bans,
        );
        
        if(isset($params['partial'])){
            $this->render_partial('shared/bans', $data);
        } else {
            $this->render($data, $params['render_type']);
        }
    }
    
    function index_partial($params){
        $bans = AccountBan::find('all', array('conditions' => $params, 'order' => 'bandate DESC'));
        $bans_count = AccountBan::count(array('conditions' => $params));
        
        $this->render(array(
            'bans_count' => $bans_count,
            'bans' => $bans,
        ));
    }
    
    function add($params){
        $this->render(array('account_id' => $params['account_id']));
    }
    
    function create($params){
        switch($params['bantype']){
            case 'perm':
                $params['unbandate'] = 0;
                break;
            case 'time':
                $params['unbandate'] = mktime(
                        $params['hours_select'], 
                        $parame['mins_select'], 
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
        $params['bannedby'] = User::$current->id;
        if(AccountBan::create($params, &$obj)){
            Event::trigger(Event::TYPE_ACCOUNT_BAN, User::$current->account, $obj);
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
                   Event::trigger(Event::TYPE_ACCOUNT_UNBAN, User::$current->account, $ban);
                } else {
                   $this->flash('error', 'Error! ' . $ban->errors[0]);
                }
            } else {
                $this->flash('error', 'No Ban found!');
            }
        } else {
            $this->flash('error', 'No ID!');
        }
        $this->redirect_back();
    }
}
