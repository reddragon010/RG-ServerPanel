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

class HomeController extends ApplicationController {

    var $before = array(
        'check_login'
    );

    function index() {
        $recent_events = Event::find()->where(array('created_at >= NOW() - INTERVAL 1 WEEK'))->order('created_at DESC')->all();

        $find_news = News::find()
            ->order(array('weight ASC', 'updated_at DESC'))
            ->limit(5);

        $this->render(array(
            'recent_events' => $recent_events,
            'news' => $find_news->all(),
        ));
    }

}
