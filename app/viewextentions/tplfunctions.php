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

class tplfunctions {
    
    function var_dump_html($var){
        return var_dump($var);
    }
    
    function insert_javascript_html($file) {
        return "<script src=\"/js/{$file}\" type=\"text/javascript\"></script>";
    }

    function insert_css_html($file) {
        return "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/css/{$file}\">";
    }

    function link_to($controller, $action, $params=array()) {
        $url = "";
        if (Environment::get_value('clean_urls')) {
            $url = Request::$base_url . "/$controller/$action";
        } else {
            $url = Request::$base_url . "/index.php?url=$controller/$action";
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
    
    function link_to_account_html($account){
        $funcs = new tplfunctions();
        if(is_object($account) && isset($account->id)){
            $op = '<a href="' . $funcs->link_to('accounts', 'show', array('id' => $account->id)) . '">';
            $op .= $account->username;
        } elseif(isset($account->id)) {
            $op = '<a>#' . $account->id;
        } else {
            $op = '<a>N/A';
        }
        $op .= '</a>';
        return $op;
    }
    
    function link_to_character_account_html($char){
        $funcs = new tplfunctions();
        if(is_object($char) && isset($char->guid)){
            if(is_object($char->accountobj)){
                $account_id = $char->accountobj->id;
                $account_name = $char->accountobj->username;
            } else {
                $account_id = $char->deleteinfos_account;
                $account_name = "DELETED - $account_id";
            }
            $op = '<a href="' . $funcs->link_to('accounts', 'show', array('id' => $account_id)) . '">' . $account_name;
        } elseif(isset($char->guid)) {
            $op = '<a>#' . $char->guid;
        } else {
            $op = '<a>N/A';
        }
        $op .= '</a>';
        return $op;
    }
    
    function link_to_character_html($char){
        $funcs = new tplfunctions();
        if(is_object($char) && isset($char->guid)){
            $op = '<a href="' . $funcs->link_to('characters', 'show', array('guid' => $char->guid, 'rid' => $char->realm->id)) . '">';
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
        return Environment::get_value($key, $scope);
    }
    
    function display_debug(){
        Debug::display();
    }
    
    function pagination_bar_html($model, $max_items, $multi=1) {
        $action = Request::$action;
        $controller = Request::$controller;
        $params = Request::$params;

        $per_page = $model::$per_page * $multi;
        
        if(isset($params['page'])){
            $current_page = $params['page'];
        }else{
            $current_page = 1;
        }

        $op = '<div id="pagination">';

        $last_page = ceil($max_items / ($per_page));

        $not_dotted = true;
        for ($page = 1; $last_page >= $page; $page++) {
            $params['page'] = $page;

            $tplf = new tplfunctions();
            $link = 'data-link="' . $tplf->link_to($controller, $action, $params) . '"';

            $not_dotted = true;
            if ($page == $current_page) {
                $class = 'class="pagination-link current_page"';
            } else {
                $class = 'class="pagination-link"';
            }
            
            if ($last_page <= 10 || $page == 1 || $page == $last_page || ($page >= ($current_page - 2) && $page <= ($current_page + 2))){
                $op .= "<a id=\"$page\" data-type=\"href\" $link $class href=\"#\">$page</a>";
                $not_dotted = true;
            } else if($not_dotted) {
                $op .= "<a>...</a>";
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

    function ajax_pagination_bar_html($model, $max_items, $params=null, $multi=1, $controller=null, $action=null, $target="") {
        if(is_null($action)) $action = Request::$action;
        if(is_null($controller)) $controller = Request::$controller;
        $current_page = 1;

        if($target != "") $target = "data-target=$target";

        $per_page = $model::$per_page * $multi;

        $op = '<div id="pagination">';

        $paramdata = "data-params='" . json_encode($params) . "'";

        $last_page = ceil($max_items / ($per_page));

        $not_dotted = true;
        for ($page = 1; $last_page >= $page; $page++) {
            $params['page'] = $page;

            if ($page == $current_page) {
                $class = 'class="pagination-link current_page"';
            } else {
                $class = 'class="pagination-link"';
            }

            if ($last_page <= 10 || $page == 1 || $page == $last_page || ($page >= ($current_page - 2) && $page <= ($current_page + 2))){
                $tplf = new tplfunctions();
                $link = 'data-link="' . $tplf->link_to($controller, $action) . '"';
                $op .= "<a id=\"$page\" data-type=\"ajax\" $paramdata $link $class $target href=\"#\">$page</a>";
                $not_dotted = true;
            } else if($not_dotted){
                $op .= "<a>...</a>";
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
            $roleid = User::$current->get_role();
            $allowed = Permissions::check_permission($controller, $action, $roleid); 
        } else {
            $allowed = Permissions::check_permission($controller, $action);
        }
        return $allowed;
    }

    // -- Form 
    function selectDate_html(){
        $curr_year = date('Y');
        $tpl_funcs = new tplfunctions();
        $select_year = $tpl_funcs->selectYears($curr_year, $curr_year + 1);
        $select_months = $tpl_funcs->selectMonths();
        $select_days = $tpl_funcs->selectDays();
        $select_hours = $tpl_funcs->selectHours();
        $select_mins = $tpl_funcs->selectMinutes();
        $select = $select_year . $select_months . $select_days . ' - ' . $select_hours . ':' . $select_mins;
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

    function selectArray_html($id, $array, $selected=null, $options_as_values=false) {
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

    function include_over_ajax_html($id, $url){
        $script = "<script type=\"text/javascript\">update_over_ajax('$url', '#$id');</script>";
        $placeholder = "<div id=\"$id\"></div>";
        $result = $script . "\n" . $placeholder;
        return $result;
    }
    
    function t(){
        $args = func_get_args();
        return call_user_func_array(array('i18n', 'get'), $args);
    }
}
