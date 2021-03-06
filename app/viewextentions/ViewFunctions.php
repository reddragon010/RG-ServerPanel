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

use Dreamblaze\Framework\Core\Environment;
use Dreamblaze\Framework\Core\Config;
use Dreamblaze\Framework\Core\Kernel;
use Dreamblaze\Framework\Core\Router;
use Dreamblaze\Framework\Core\Permissions;
use Dreamblaze\Framework\Core\I18n;
use Dreamblaze\Helpers\Toolbox;
use Dreamblaze\HtmlElement\HtmlElement;

class ViewFunctions extends \Dreamblaze\Framework\Core\ViewExtention {

    public function getFunctions(){
        return array_merge(
            $this->generateHtmlFunctions(array(
                'var_dump',
                'insert_javascript',
                'insert_css',
                'link_to_account',
                'link_to_character_account',
                'link_to_character',
                'pagination_bar',
                'ajax_pagination_bar',
                'selectDate',
                'selectArray',
                'include_over_ajax',
                'link_to_remote_form',
                'link_to_action',
                'button',
                'link',
                'common_menu'
            )),
            $this->generateFunctions(array(
                'link_to',
                'config',
                'progressbar',
                'flushflash',
                'permitted_to',
                'selectYear',
                'selectMonths',
                'selectHours',
                'selectMinutes',
                't'
            ))
        );
    }

    function var_dump($var){
        return var_dump($var);
    }
    
    function insert_javascript($file) {
        return "<script src=\"/js/{$file}\" type=\"text/javascript\"></script>";
    }

    function insert_css($file) {
        return "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/css/{$file}\">";
    }

    function link_to($controller, $action, $params=array()) {
        if(is_object($controller)){
            $controller = get_class($controller);
            $controller_file = Toolbox::from_camel_case($controller);
            $controller_parts = explode('_',$controller_file);
            $controller = $controller_parts[0];
        }
        $url = "";
        if (Config::instance('framework')->get_value('clean_urls')) {
            $url = Kernel::$request->root_url . "/$controller/$action";
        } else {
            $url = Kernel::$request->root_url . "/index.php?url=$controller/$action";
        }
        if (!empty($params)) {
            $url .= '?';
            $url_params = array();
            foreach ($params as $key => $val) {
                $url_params[] = urlencode($key) . '=' . urlencode($val);
            }
            $url .= join('&', $url_params);
        }
        return $url;
    }
    
    function link_to_account($account){
        if(is_object($account) && isset($account->id)){
            $op = '<a href="' . $this->link_to('accounts', 'show', array('id' => $account->id)) . '">';
            $op .= $account->username;
        } elseif(isset($account->id)) {
            $op = '<a>#' . $account->id;
        } else {
            $op = '<a>N/A';
        }
        $op .= '</a>';
        return $op;
    }
    
    function link_to_character_account($char){
        if(is_object($char) && isset($char->guid)){
            if(is_object($char->accountobj)){
                $account_id = $char->accountobj->id;
                $account_name = $char->accountobj->username;
            } else {
                $account_id = $char->deleteinfos_account;
                $account_name = "DELETED - $account_id";
            }
            $op = '<a href="' . $this->link_to('accounts', 'show', array('id' => $account_id)) . '">' . $account_name;
        } elseif(isset($char->guid)) {
            $op = '<a>#' . $char->guid;
        } else {
            $op = '<a>N/A';
        }
        $op .= '</a>';
        return $op;
    }
    
    function link_to_character($char){
        if(is_object($char) && isset($char->guid)){
            $op = '<a href="' . $this->link_to('characters', 'show', array('guid' => $char->guid, 'rid' => $char->realm->id)) . '">';
            if(empty($char->name)){
                $op .= $char->deleteinfos_name;
            } else {
                $op .= $char->name;
            }
        } elseif(isset($char->guid)) {
            $op = '<a>#'. $char->guid;
        } else {
            $op = '<a>N/A';
        }
        $op .= '</a>';
        return $op;
    }
    
    function config($key,$scope=''){
        return Config::instance('framework')->get_value($key, $scope);
    }
    
    function pagination_bar($model, $max_items, $multi=1) {
        $action = Router::$action;
        $controller = Router::$controller;
        $params = Kernel::$request->params;

        $per_page = $model::$per_page * $multi;
        
        if(isset($params['page'])){
            $current_page = $params['page'];
        }else{
            $current_page = 1;
        }

        $op = '<div id="pagination">';

        $last_page = ceil($max_items / ($per_page));

        $delemitted = false;
        for ($page = 1; $last_page >= $page; $page++) {
            $params['page'] = $page;

            $link = 'data-link="' . $this->link_to($controller, $action, $params) . '"';

            if ($page == $current_page) {
                $class = 'class="pagination-link current_page"';
            } else {
                $class = 'class="pagination-link"';
            }
            
            if ($last_page <= 10 || $page == 1 || $page == $last_page || ($page >= ($current_page - 2) && $page <= ($current_page + 2))){
                $op .= "<a id=\"$page\" data-type=\"href\" $link $class href=\"#\">$page</a>";
                $delemitted = false;
            } elseif(!$delemitted) {
                $op .= "<a>...</a>";
                $delemitted = true;
            }
                
        }

        $op .= '<div id="pagination-info">';
        $pos1 = $per_page * ($current_page - 1) + 1;
        $pos2 = $per_page * $current_page;
        if($pos2 > $max_items) $pos2 = $max_items;
        $op .= "($pos1 - $pos2 / $max_items)";
        $op .= '</div>';
       
        $op .= '</div>';
        return $op;
    }

    function ajax_pagination_bar($model, $max_items, $params=null, $multi=1, $controller=null, $action=null, $target="") {
        if(is_null($action)) $action = Router::$action;
        if(is_null($controller)) $controller = Router::$controller;
        $current_page = 1;

        if($target != "") $target = "data-target=$target";

        $per_page = $model::$per_page * $multi;

        $op = '<div id="pagination">';

        $paramdata = "data-params='" . json_encode($params) . "'";

        $last_page = ceil($max_items / ($per_page));

        $delemitted = false;
        for ($page = 1; $last_page >= $page; $page++) {
            $params['page'] = $page;

            if ($page == $current_page) {
                $class = 'class="pagination-link current_page"';
            } else {
                $class = 'class="pagination-link"';
            }

            if ($last_page <= 10 || $page == 1 || $page == $last_page || ($page >= ($current_page - 2) && $page <= ($current_page + 2))){
                $link = 'data-link="' . $this->link_to($controller, $action) . '"';
                $op .= "<a id=\"$page\" data-type=\"ajax\" $paramdata $link $class $target href=\"#\">$page</a>";
                $delemitted = false;
            } else if(!$delemitted){
                $op .= "<a>...</a>";
                $delemitted = true;
            }

        }

        $op .= '<div id="pagination-info">';
        $pos1 = $per_page * ($current_page - 1) + 1;
        $pos2 = $per_page * $current_page;
        if($pos2 > $max_items) $pos2 = $max_items;
        $op .= "($pos1 - $pos2 / $max_items)";
        $op .= '</div>';

        $op .= '</div>';
        return $op;
    }

    function progressbar($id, $val, $max) {
        $progress = $val / $max * 100;
        echo '<div class="progressbar">';
        echo '<script>$(function() {$( "#progressbar_' . $id . '" ).progressbar({value: ' . $progress . '});});</script>';
        echo '<div id="progressbar_' . $id . '"></div>';
        echo '</div>';
    }

    function flushflash() {
        if (isset($_SESSION['flash'])) {
            if ($_SESSION['flash']['hops'] <= 0) {
                $flash = $_SESSION['flash'];
                $_SESSION['flash'] = null;
                return $flash;
            } else {
                $_SESSION['flash']['hops'] = $_SESSION['flash']['hops'] - 1;
            }
        }
    }
    
    function permitted_to($action, $controller){
        if(isset(User::$current)){
            return User::$current->is_permitted_to($action, $controller);
        } else {
            return Permissions::check_permission($controller, $action);
        }
    }

    // -- Form 
    function selectDate($idprefix="", $yearspan=0, $defaultValue=null){
        if($defaultValue == null)
            $defaultValue = time();

        $defaultValues = array();
        foreach(array('year' => 'Y', 'month' => 'm', 'day' => 'd', 'hour' => 'H', 'minute' => 'i') as $type=>$format){
            $defaultValues[$type] = date($format,$defaultValue);
        }

        $curr_year = date('Y');
        $tpl_funcs = $this;
        $select_year = $tpl_funcs->selectYears($curr_year - $yearspan - 2, $curr_year + $yearspan + 3, $idprefix . 'year_select', $defaultValues['year']);
        $select_month = $tpl_funcs->selectMonths($idprefix . 'month_select', $defaultValues['month']);
        $select_day = $tpl_funcs->selectDays($idprefix . 'day_select', $defaultValues['day']);
        $select_hour = $tpl_funcs->selectHours($idprefix . 'hours_select', $defaultValues['hour']);
        $select_minute = $tpl_funcs->selectMinutes($idprefix . 'minute_select', $defaultValues['minute']);

        $select = $select_year . $select_month . $select_day . ' - ' . $select_hour . ':' . $select_minute;
        return $select;
     }
    
    
    /**
     *
     * @Create dropdown of years
     * @param int $start_year
     * @param int $end_year
     * @param string $id The name and id of the select object
     * @param int $selected
     * @return string
     *
     */
    function selectYears($start_year, $end_year, $id='year_select', $selected=null) {

        /*         * * the current year ** */
        $selected = is_null($selected) ? date('Y') : $selected;

        /*         * * range of years ** */
        $r = range($start_year, $end_year);

        /*         * * create the select ** */
        $select = '<select name="' . $id . '" id="' . $id . '">';
        foreach ($r as $year) {
            $select .= "<option value=\"$year\"";
            $select .= ( $year == $selected) ? ' selected="selected"' : '';
            $select .= ">$year</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /*
     *
     * @Create dropdown list of months
     * @param string $id The name and id of the select object
     * @param int $selected
     * @return string
     *
     */

    function selectMonths($id='month_select', $selected=null) {
        /*         * * array of months ** */
        $months = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December');

        /*         * * current month ** */
        $selected = is_null($selected) ? date('m') : $selected;

        $select = '<select name="' . $id . '" id="' . $id . '">' . "\n";
        foreach ($months as $key => $mon) {
            $select .= "<option value=\"$key\"";
            $select .= ( $key == $selected) ? ' selected="selected"' : '';
            $select .= ">$mon</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /**
     *
     * @Create dropdown list of days
     * @param string $id The name and id of the select object
     * @param int $selected
     * @return string
     *
     */
    function selectDays($id='day_select', $selected=null) {
        /*         * * range of days ** */
        $r = range(1, 31);

        /*         * * current day ** */
        $selected = is_null($selected) ? date('d') : $selected;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($r as $day) {
            $select .= "<option value=\"$day\"";
            $select .= ( $day == $selected) ? ' selected="selected"' : '';
            $select .= ">$day</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /**
     *
     * @create dropdown list of hours
     * @param string $id The name and id of the select object
     * @param int $selected
     * @return string
     *
     */
    function selectHours($id='hours_select', $selected=null) {
        /*         * * range of hours ** */
        $r = range(1, 24);

        /*         * * current hour ** */
        $selected = is_null($selected) ? date('H') + 1 : $selected;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($r as $hour) {
            $select .= "<option value=\"$hour\"";
            $select .= ( $hour == $selected) ? ' selected="selected"' : '';
            $select .= ">$hour</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /**
     *
     * @create dropdown list of minutes
     * @param string $id The name and id of the select object
     * @param int $selected
     * @return string
     *
     */
    function selectMinutes($id='minute_select', $selected=null) {
        /*         * * array of mins ** */
        $minutes = array(0, 15, 30, 45);

        $selected = in_array($selected, $minutes) ? $selected : 0;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($minutes as $min) {
            $select .= "<option value=\"$min\"";
            $select .= ( $min == $selected) ? ' selected="selected"' : '';
            $select .= ">" . str_pad($min, 2, '0') . "</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    function selectArray($id, $array, $selected=null, $options_as_values=false) {
        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($array as $key => $val) {
            $svalue = $options_as_values ?  $val : $key;
            $select .= "<option value=\"$svalue\"";
            $select .= ( $svalue == $selected && $selected != null && $selected != '') ? ' selected="selected"' : '';
            $select .= ">" . $val . "</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    function include_over_ajax($id, $url){
        $script = "<script type=\"text/javascript\">update_over_ajax('$url', '#$id');</script>";
        $placeholder = "<div id=\"$id\"></div>";
        $result = $script . "\n" . $placeholder;
        return $result;
    }
    
    function t(){
        $args = func_get_args();
        return call_user_func_array(array('\\Dreamblaze\\Framework\\Core\\I18n', 'get'), $args);
    }

    function link_to_remote_form($text, $controller, $action, $params, $icon=null, $title=null, $width=null){
        $class = array('remote_form');

        $link = $this->link_to($controller, $action, $params);
        $button = $this->button($link,$text,$icon,$title, $class);

        if($width != null)
            $button->width = $width;

        return (string)$button;
    }

    function link_to_action($text, $controller, $action, $params, $icon=null, $title=null, $confirm=false){
        $class = array('remote');

        if($confirm)
            $class[] = 'confirm';

        $link = $this->link_to($controller, $action, $params);
        $button = $this->button($link,$text,$icon,$title, $class);
        return (string)$button;
    }

    function button($link,$text,$icon,$title=null,$additional_classes=array()){
        $class = array('button');
        $class = array_merge($class, $additional_classes);
        if($icon != null){
            $html_img = new HtmlElement('img');
            $html_img->src = '/images/icons/' . $icon . '.png';
            $content = $html_img . ' ' . $text;
        } else {
            $content = $text;
        }
        return $this->link($link,$content,$class,null,$title);
    }

    function link($link, $content, $class, $id, $title){
        $button = new HtmlElement('a');
        if($id != null) $button->id = $id;
        if($class != null) $button->class = $class;
        if($title != null) $button->title = $title;
        if($link != null) $button->href = $link;
        if($content != null) $button->content = $content;
        return $button;
    }

    function common_menu($controller, $id, $rid=null){
        $menu_id = 'menu_' . $controller . '_' . $id;
        $params = array('id' => $id, 'rid' => $rid);

        $html_button = new HtmlElement('button');
        $html_button->id = $menu_id;
        $html_button->class = "menu_button";
        $html_button->content = "Menu Button";
        $html_button->style = "height: 16px";

        $html_menu = new HtmlElement('menu');
        $html_menu->id = $menu_id . "_content";
        $html_menu->style = "display: none";
        $html_menu->type = "context";
        $html_menu->content = array();

        if(User::$current->is_permitted_to('show', $controller))
            $html_menu->content[] = $this->link_to_action('Show', $controller, 'show', $params, 'show', 'Show ' . $controller . "($id)");

        if(User::$current->is_permitted_to('edit', $controller))
            $html_menu->content[] = $this->link_to_remote_form('Edit', $controller, 'edit', $params, 'edit', 'Edit ' . $controller . "($id)");

        if(User::$current->is_permitted_to('delete', $controller))
            $html_menu->content[] = $this->link_to_action('Delete', $controller, 'delete', $params, 'delete', 'Delete ' . $controller . "($id)", true);

        if($html_menu->content != null)
            return  $html_button . $html_menu;
    }
}
