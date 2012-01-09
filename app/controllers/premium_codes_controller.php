<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

class PremiumCodesController extends BaseController {
    
    function index(){
        $this->render();
    }
    
    function check($params){
        if(isset($params['code']) && !empty($params['code'])){
            $premcode = PremiumCode::find()->where(array('code' => $params['code']))->first();
            if($premcode){
                $data = array('code' => $premcode->code, 'userid' => $premcode->userid, 'type' => $premcode->for);
                if($premcode->used == '0'){
                    Event::trigger(Event::TYPE_PREMCODE_VERIFY, User::$current->account, $premcode);
                    $this->render_ajax('success', "{$premcode->code} is valid", $data);
                } else {
                    $this->render_ajax('error', "{$premcode->code} is used", $data);
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
            $premcode = PremiumCode::find()->where(array('code' => $params['code']))->first();
            if($premcode){
                if($premcode->invalidate()){
                    Event::trigger(Event::TYPE_PREMCODE_INVALIDATE, User::$current->account, $premcode);
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
            $premcode = PremiumCode::find()->where(array('code' => $params['code']))->first();
            if($premcode){
                $new_code = $premcode->renew();
                if($new_code){
                    Event::trigger(Event::TYPE_PREMCODE_RENEW, User::$current->account, $premcode, "New: " . $new_code);
                    $this->render_ajax('success', 'Trashed old code and generated new one', 'New Code: ' . $new_code);
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
    
    function reactivate($params){
        if(isset($params['code']) && !empty($params['code'])){
            $premcode = PremiumCode::find()->where(array('code' => $params['code']))->first();
            if($premcode){
                if($premcode->reactivate()){
                    Event::trigger(Event::TYPE_PREMCODE_REACTIVATE, User::$current->account, $premcode);
                    $this->render_ajax('success', 'reactivated code ' . $premcode->code);
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
    /*
    function add(){
        $this->render();
    }
    
    function create($params){
        $params['userid'] = -1;
        $params['used'] = 0;
        if(PremiumCode::create($params,$premcode)){
            Event::trigger(Event::TYPE_PREMCODE_CREATE, User::$current->account, $premcode);
            $this->render_ajax('success', "Premcode created");
        } else {
            $this->render_ajax('error', 'ERROR! ' . $this->errors[0]);
        }
    }
    */
}
