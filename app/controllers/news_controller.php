<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 22:10
 * To change this template use File | Settings | File Templates.
 */
class NewsController extends BaseController
{
    public function index($params){
        $find = News::find()
            ->order('updated_at DESC')
            ->limit(5)
            ->where($params);

        if(isset($params['page']))
            $find->page($params['page']);

        $find_sticky = News::find()->sticky();

        $data = array(
            'news' => $find->all(),
            'sticky_news' => $find_sticky->all(),
            'news_count' => $find->count()
        );

        $this->render($data);
    }

    function add(){
        $this->render();
    }

    function create($params){
        $params['author_id'] = User::$current->id;
        if(News::create($params, &$obj)){
            //Event::trigger(Event::TYPE_ACCOUNT_BAN, User::$current->account, $obj->account);
            $this->render_ajax('success', 'Successfully added');
        } else {
            $this->render_ajax('error', 'Error! ' . $obj->errors[0]);
        }
    }

    function edit($params){
        $news = News::find($params['id']);
        $this->render(array('news' => $news));
    }

    function update($params) {
        $news = News::find($params['id']);
        if (!empty($news)) {
            $news->title = $params['title'];
            $news->content = $params['content'];

            if ($news->save()) {
                $this->render_ajax('success', 'Successfully Saved');
            } else {
                $this->render_ajax('error', 'Can\'t save News! ' . $news->errors[0]);
            }
        } else {
            $this->render_ajax('error', 'News not found!');
        }
    }

    function delete($params){
        $news = News::find($params['id']);
        if (!empty($news)) {
            if ($news->destroy()) {
                $this->flash('success', 'Successfully Destroyed');
            } else {
                $this->flash('error', 'Can\'t destroy News! ' . $news->errors[0]);
            }
        } else {
            $this->flash('error', 'News not found!');
        }
        $this->redirect_back();
    }
}
