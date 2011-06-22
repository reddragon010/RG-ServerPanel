<?php

class tplfunctions {

    function insert_javascript_html($file) {
        $themeurl = Environment::$app_theme_url;
        return "<script src=\"/js/{$file}\" type=\"text/javascript\"></script>";
    }

    function insert_css_html($file) {
        $themeurl = Environment::$app_theme_url;
        return "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/css/{$file}\">";
    }

    function link_to($controller, $action, $params=array()) {
        $url = "";
        if (Environment::get_config_value('clean_urls')) {
            $url = Environment::$app_url . "/$controller/$action";
        } else {
            $url = Environment::$app_url . "/index.php?url=$controller/$action";
        }
        if (!empty($params)) {
            $url .= '?';
            $url_params = array();
            foreach ($params as $key => $val) {
                $url_params[] = $key . '=' . $val;
            }
            $url .= join('&', $url_params);
        }
        return $url;
    }

    function pagination_bar_html($request, $model, $max_items) {

        $op = '<div id="pagination">';
        $last_page = ceil($max_items / $model::$per_page);

        if (isset($request['params']['page'])) {
            $current_page = $request['params']['page'];
        } else {
            $current_page = 1;
        }
        
        for ($page = 1; $last_page >= $page; $page++) {
            $request['params']['page'] = $page;

            if ($page == $current_page) {
                $link = '';
                $class = 'class="current_page"';
            } else {
                $tplf = new tplfunctions();
                $link = 'href="' . $tplf->link_to($request['controller'], $request['action'], $request['params']) . '"';
                $class = '';
            }
            
            if ($last_page <= 10 || $page == 1 || $page == $last_page || ($page >= ($current_page - 2) && $page <= ($current_page + 2))){
                $op .= "<a $link $class>$page</a>";
                $delemitted = false;
            } elseif(!$delemitted) {
                $op .= "<a>...</a>";
                $delemitted = true;
            }
                
        }


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

    // -- Form 
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
        $selected = is_null($selected) ? date('H') : $selected;

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

    function selectArray($id, $array, $selected=null) {

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($array as $key => $val) {
            $select .= "<option value=\"$key\"";
            $select .= ( $key == $selected) ? ' selected="selected"' : '';
            $select .= ">" . $val . "</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

}
