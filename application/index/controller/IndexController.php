<?php
namespace app\index\controller;

use app\admin\controller\TaxonomyController;
use app\admin\controller\SystemController;
use app\index\controller\BaseController;
use think\Db;

class IndexController extends BaseController
{
    const CHANNEL_ID = 'channel_id';

    public function index()
    {
        //热门站点
        $system = new SystemController();
        $site_popular   = $system->variable_get('site_popular');
        $site_recommend = $system->variable_get('site_recommend');

        //左侧菜单
        $left_menu   = Db::name('taxonomy')->where(array('delete'=>0,'level'=>0,'status'=>1))->order('weight asc, id desc')->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('taxonomy')->where(array('parent_id'=>$value['id'], 'delete'=>0,'status'=>1))->order('weight asc, id desc')->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        $data['left_menu']          = $left_menu;
        $data['channel_id']         = 0;
        $data['current_date']       = get_current_date();
        $data['site_popular']       = $this->get_site_popular($site_popular);
        $data['site_recommend']     = $this->get_site_recommend($site_recommend);
        $data['site_news']          = $this->get_site_news();

        return view('index/index',$data);
    }

    /**
     * 分类列表
     */
    public function taxonomy_list()
    {
        $id         = input('id');
        $sub_lists  = false;
        $system     = new SystemController();
        $lists  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.taxonomy_id,a.title,a.brief,a.create_time,a.url,b.save_path,c.name as taxonomy_name')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->join('taxonomy c', 'a.taxonomy_id = c.id', 'left')
            ->where(array('a.taxonomy_id'=>$id,'a.delete'=>0))
            ->order('create_time asc')
            ->select();

        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
                $lists[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
            }
        }

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        $taxonomyClass = new TaxonomyController();
        $parents = $taxonomyClass->get_parent($id);

        if(count($parents)){
            foreach($parents as $parent){
                $breadcrumb[] = array('path'=>url('category/id/'.$parent['id']),'title'=>$parent['name']);
            }
        }
        $taxonomy = $taxonomyClass->get_taxonomy_self($id);

        $breadcrumb[] = array('path'=>'','title'=>$taxonomy['name']);

        //左侧菜单
        $left_menu[0]               = $taxonomy;
        $childs                     = Db::name('taxonomy')->where(array('parent_id'=>$taxonomy['id'], 'delete'=>0,'status'=>1))->order('weight asc')->select();
        $left_menu[0]['child']      = $childs;
        $brothers                   = Db::name('taxonomy')->where(array('parent_id'=>$taxonomy['parent_id'], 'delete'=>0,'status'=>1))->order('weight asc')->select();
        $left_menu[0]['brother']    = $brothers;

        //获取子分类内容
        if(is_array($childs) && count($childs)){
            foreach($childs as $child){

                $sub_list  = Db::name('article')
                    ->alias('a')
                    ->field('a.id,a.taxonomy_id,a.title,a.brief,a.create_time,a.url,b.save_path,c.name as taxonomy_name')
                    ->join('upload b', 'a.thumb = b.id', 'left')
                    ->join('taxonomy c', 'a.taxonomy_id = c.id', 'left')
                    ->where(array('a.taxonomy_id'=>$child['id'],'a.delete'=>0))
                    ->order('create_time desc')
                    ->limit(20)
                    ->select();

                if(is_array($sub_list) && count($sub_list)){
                    foreach($sub_list as $key => $value){
                        $sub_list[$key]['view_url'] = get_view_url($value['save_path']);
                        $sub_list[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                    }

                    $sub_lists[$child['id']]['name'] = $child['name'];
                    $sub_lists[$child['id']]['list'] = $sub_list;
                }

                //复制站点
                $copy_list  = Db::name('article')
                    ->alias('a')
                    ->field('a.id,a.taxonomy_id,a.title,a.brief,a.create_time,a.url,b.save_path,c.name as taxonomy_name')
                    ->join('upload b', 'a.thumb = b.id', 'left')
                    ->join('taxonomy c', 'a.taxonomy_id = c.id', 'left')
                    ->join('article_copy d', 'a.id = d.article_id', 'left')
                    ->where(array('d.taxonomy_id'=>$child['id'],'a.delete'=>0))
                    ->limit(20)
                    ->select();

                if(is_array($copy_list) && count($copy_list)){
                    foreach($copy_list as $key => $value){
                        $copy_list[$key]['view_url'] = get_view_url($value['save_path']);
                        $copy_list[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                    }

                    $copy_lists[$child['id']]['name'] = $child['name'];
                    $copy_lists[$child['id']]['list'] = $copy_list;
                }

                if(isset($copy_lists)){
                    if($sub_lists === false){
                        $sub_lists = [];
                    }
                    $sub_lists = array_merge($sub_lists, $copy_lists);
                }
            }
        }else{
            $sub_lists = false;
        }

        $data['breadcrumb']         = $this->get_breadcrumb($breadcrumb);
        $data['list']               = $lists;
        $data['left_menu']          = $left_menu;
        $data['current_date']       = get_current_date();
        $data['category']           = $taxonomy;
        $data['sub_lists']          = $sub_lists;
        $data['meta_keyword']       = $taxonomy['keyword'];
        $data['meta_description']   = $taxonomy['description'];
        return view('index/category_list', $data);
    }

    /**
     * 频道列表
     */
    public function channel_list()
    {
        $channel_id = input('id');

        $pages  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.title,a.summary,a.create_time,a.channel_id,b.save_path,c.name as category_name_1, d.name as category_name_2')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->join('category c', 'a.category_1 = c.id', 'left')
            ->join('category_2 d', 'a.category_2 = d.id', 'left')
            ->where(array('a.channel_id'=>$channel_id,'a.delete'=>0))
            ->order('create_time desc')
            ->paginate(10);

        $page = $pages->render();
        $lists  = $pages->all();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        $channel    = Db::name('channel')->where(array('id'=>$channel_id))->find();

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>'','title'=>$channel['name']);
        }


        //左侧菜单
        $left_menu   = Db::name('category')->where(array('parent_id'=>$channel_id))->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category_2 = Db::name('category_2')->where(array('parent_id'=>$value['id']))->select();
                if(is_array($category_2) && count($category_2)){
                    $left_menu[$key]['child'] = $category_2;
                }
            }
        }

        $data['breadcrumb']   = $this->get_breadcrumb($breadcrumb);
        $data['list']         = $lists;
        $data['page']         = $page;
        $data['left_menu']    = $left_menu;
        $data['channel_id']   = $channel_id;
        return view('index/category_list', $data);
    }

    /**
     * 推荐列表
     */
    public function recommend_list()
    {
        $system         = new SystemController();
        $taxonomy_id    = input('id');
        $pages  = Db::name('recommend')
            ->alias('a')
            ->field('a.id,a.title,a.summary,a.create_time,a.taxonomy_id,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->where(array('a.delete'=>0))
            ->order('create_time desc')
            ->paginate(10);

        $page = $pages->render();
        $lists  = $pages->all();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
                $counter = Db::name('counter')->where(['type'=>2, 'type_id'=>$value['id']])->find();
                if($counter){
                    $lists[$key]['reads']    = $counter['count']+50;
                }else{
                    $lists[$key]['reads']    = 50;
                }

                $create_time = $value['create_time'];
                $create_time = explode(' ', $value['create_time']);
                $create_time = $create_time[0];

                $lists[$key]['create_time'] = $create_time;
            }
        }

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        $breadcrumb[] = array('path'=>url('/recommend'),'title'=>'精彩推荐');


        //左侧菜单
        $left_menu   = Db::name('taxonomy')->where(array('delete'=>0,'level'=>0))->order('weight asc, id desc')->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('taxonomy')->where(array('parent_id'=>$value['id'], 'delete'=>0))->order('weight asc, id desc')->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        $data['breadcrumb']         = $this->get_breadcrumb($breadcrumb);
        $data['current_date']       = get_current_date();
        $data['list']               = $lists;
        $data['page']               = $page;
        $data['left_menu']          = $left_menu;
        $data['taxonomy_id']        = $taxonomy_id;

        return view('index/recommend_list', $data);
    }

    /**
     * 推荐相信
     * @return \think\response\View
     */
    public function page_info()
    {
        $system = new SystemController();
        $id     = input('id');
        $info  = Db::name('recommend')
            ->alias('a')
            ->field('a.id,a.title,a.content,a.create_time,a.taxonomy_id,a.meta_keyword,a.meta_description,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();
        $info['create_time'] = explode(' ',  $info['create_time']);
        $info['create_time'] = $info['create_time'][0];

        $counter = Db::name('counter')->where(['type'=>2, 'type_id'=>$info['id']])->find();
        if($counter){
            $info['reads']    = $counter['count']+50;
        }else{
            $info['reads']    = 50;
        }

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        $breadcrumb[] = array('path'=>url('/recommend'),'title'=>'精彩推荐');
        $breadcrumb[] = array('path'=>'','title'=>$info['title']);

        //左侧菜单
        $left_menu   = Db::name('taxonomy')->where(array('delete'=>0,'level'=>0))->order('weight asc, id desc')->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('taxonomy')->where(array('parent_id'=>$value['id'], 'delete'=>0))->order('weight asc, id desc')->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        $data['breadcrumb']         = $this->get_breadcrumb($breadcrumb);
        $info['view_url']           = get_view_url($info['save_path']);
        $data['info']               = $info;
        $data['left_menu']          = $left_menu;
        $data['taxonomy_id']        = $id;
        $data['meta_keyword']       = $info['meta_keyword'];
        $data['meta_description']   = $info['meta_description'];
        $data['site_title']         = $system->variable_get('site_title');
        $data['current_date']       = get_current_date();
        return view('index/page_info', $data);
    }

    /**
     * 一级栏目列表
     */
    public function category1_list()
    {
        $id = input('id');

        $pages  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.title,a.create_time,a.channel_id,a.url,b.save_path,c.name as category_name_1, d.name as category_name_2')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->join('category c', 'a.category_1 = c.id', 'left')
            ->join('category_2 d', 'a.category_2 = d.id', 'left')
            ->where(array('a.category_1'=>$id,'a.delete'=>0))
            ->order('create_time desc')
            ->paginate(10);

        $page = $pages->render();
        $lists  = $pages->all();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        $category_1 = Db::name('category')->where(array('id'=>$id))->find();
        $channel    = Db::name('channel')->where(array('id'=>$category_1['parent_id']))->find();

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>'','title'=>$channel['name']);
        }
        if($category_1){
            $breadcrumb[] = array('path'=>url('category1/id/'.$category_1['id']),'title'=>$category_1['name']);
        }

        //左侧菜单
//        $left_menu   = Db::name('category')->where(array('parent_id'=>$channel['id']))->select();
//        if(is_array($left_menu) && count($left_menu)){
//            foreach($left_menu as $key => $value){
//                $category_2 = Db::name('category_2')->where(array('parent_id'=>$value['id']))->select();
//                if(is_array($category_2) && count($category_2)){
//                    $left_menu[$key]['child'] = $category_2;
//                }
//            }
//        }

        $left_menu   = Db::name('channel')->where(array('delete'=>0))->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('category')->where(array('parent_id'=>$value['id'], 'delete'=>0))->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        $data['breadcrumb']     = $this->get_breadcrumb($breadcrumb);
        $data['list']           = $lists;
        $data['page']           = $page;
        $data['left_menu']      = $left_menu;
        $data['channel_id']     = $channel['id'];
        $data['current_date']   = get_current_date();
        $data['category']       = $category_1;
        return view('index/category_list', $data);
    }

    /**
     * 二级栏目列表
     */
    public function category2_list()
    {
        $id = input('id');

        $pages  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.category_1,a.category_2,a.title,a.summary,a.create_time,a.channel_id,b.save_path,c.name as category_name_1, d.name as category_name_2')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->join('category c', 'a.category_1 = c.id', 'left')
            ->join('category_2 d', 'a.category_2 = d.id', 'left')
            ->where(array('a.category_2'=>$id,'a.delete'=>0))
            ->order('a.create_time desc')
            ->paginate(10);

        $page = $pages->render();
        $lists  = $pages->all();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        $category_2 = Db::name('category_2')->where(array('id'=>$id))->find();
        $category_1 = Db::name('category')->where(array('id'=>$category_2['parent_id']))->find();
        $channel    = Db::name('channel')->where(array('id'=>$category_1['parent_id']))->find();

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>'','title'=>$channel['name']);
        }
        if($category_1){
            $breadcrumb[] = array('path'=>url('category1/id/'.$category_1['id']),'title'=>$category_1['name']);
        }
        if($category_2){
            $breadcrumb[] = array('path'=>url('category2/id/'.$category_2['id']),'title'=>$category_2['name']);
        }

        //左侧菜单
        $left_menu   = Db::name('category')->where(array('parent_id'=>$channel['id']))->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category_2 = Db::name('category_2')->where(array('parent_id'=>$value['id']))->select();
                if(is_array($category_2) && count($category_2)){
                    $left_menu[$key]['child'] = $category_2;
                }
            }
        }

        $data['breadcrumb']   = $this->get_breadcrumb($breadcrumb);
        $data['list']         = $lists;
        $data['page']         = $page;
        $data['left_menu']    = $left_menu;
        $data['channel_id']   = $channel['id'];
        return view('index/category_list', $data);
    }

    public function resource_list()
    {
//        echo 'test';die;
        return view('index/resource_list');
    }

    /**
     * 网站收录申请
     */
    public function site_application_form()
    {
        $taxonomyClass          = new TaxonomyController();
        $taxonomy               = $taxonomyClass->get_taxonomy();
        $tree                   = $taxonomyClass->get_taxonomy_tree_wrapper($taxonomyClass->get_taxonomy_tree($taxonomy));

        //左侧菜单
        $left_menu   = Db::name('taxonomy')->where(array('delete'=>0,'level'=>0,'status'=>1))->order('weight asc, id desc')->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('taxonomy')->where(array('parent_id'=>$value['id'], 'delete'=>0,'status'=>1))->order('weight asc, id desc')->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        $breadcrumb[] = array('path'=>url(''),'title'=>'网站收录');

        $data['left_menu']          = $left_menu;
        $data['breadcrumb']         = $this->get_breadcrumb($breadcrumb);
        $data['tree']               = $tree;
        $data['action']             = url('/site_application_submit');
        $data['module_name']        = '网站提交';
        $data['meta_keyword']       = '蜻蜓导航网站提交, 免费收录网站,蜻蜓360, 蜻蜓导航, 网址导航, 网站导航, 好站推荐';
        $data['meta_description']   = '蜻蜓导航网站提交, 免费收录网站, 一键提交快速审核通过!';
        $data['current_date']       = get_current_date();
        return view('index/site_application', $data);
    }

    /**
     * 网站收录申请提交
     */
    public function site_application_form_submit()
    {
        $formData   = input('request.');

        $result = $this->validate($formData,[
            'taxonomy_id|分类'     => 'require|token',
            'captcha|验证码'       => 'require|captcha',
            'name|网站名称'        => 'require|max:30',
            'url'                  => 'require|url',
//            'icp|备案号'           => 'require|max:100',
            'email'                => 'email',
//            'keyword|关键字'       => 'max:150',
//            'description|描述'     => 'max:255',
        ]);

        if($result !== true){
            echo json_encode(array('code'=>1, 'msg'=>$result, 'data'=>array()));die;
        }

        $data = [
            'name'             => $formData['name'],
            'url'               => $formData['url'],
            'taxonomy_id'       => $formData['taxonomy_id'],
            'status'            => 0,
            'email'             => $formData['email'],
            'icp'               => 'icp',
            'keyword'           => 'keyword',
            'description'       => 'description',
            'create_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result     = Db::name('application')->insert($data);

        if($result){
            echo json_encode(array('code'=>0, 'msg'=>'网站提交成功，审核通过后您的网站将会收录!', 'data'=>array()));die;
        }else{
            echo json_encode(array('code'=>0, 'msg'=>'网络忙,请稍后再试!', 'data'=>array()));die;
        }
    }

    /**
     * 获取热门站点
     */
    public function get_site_popular($site_popular=NULL){
        $site_popular   = explode(',', $site_popular);
        if(count($site_popular)){
            foreach($site_popular as $site_name){
                $site = Db::name('article')
                    ->alias('a')
                    ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
                    ->join('upload b', 'a.thumb = b.id', 'left')
                    ->order('create_time desc')
                    ->where(array('a.delete'=>0,'a.title'=>$site_name))
                    ->find();
                if($site){
                    $hot_site[]  = $site;
                }
            }
        }else{
            $hot_site  = Db::name('article')
                ->alias('a')
                ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
                ->join('upload b', 'a.thumb = b.id', 'left')
                ->order('create_time desc')
                ->where(array('a.delete'=>0))
                ->limit(50)
                ->select();
        }
        if(is_array($hot_site) && count($hot_site)){
            foreach($hot_site as $key => $value) {
                $hot_site[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                $hot_site[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }
        return $hot_site;
    }

    /**
     * 获取最新加入
     */
    public function get_site_news(){
        $site_news  = Db::name('article')
        ->alias('a')
        ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
        ->join('upload b', 'a.thumb = b.id', 'left')
        ->order('create_time desc')
        ->where(array('a.delete'=>0))
        ->limit(40)
        ->select();

        if(is_array($site_news) && count($site_news)){
            foreach($site_news as $key => $value) {
                $site_news[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                $site_news[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        return $site_news;
    }

    /**
     * 获取好站推荐
     */
    public function get_site_recommend($site_recommend=NULL){
        $lists  = Db::name('recommend')
            ->alias('a')
            ->field('a.id,a.title,a.summary,a.create_time,a.taxonomy_id,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->where(array('a.delete'=>0))
            ->order('create_time desc')
            ->limit(6)
            ->select();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $create_time = $value['create_time'];
                $create_time = explode(' ', $value['create_time']);
                $create_time = $create_time[0];

                $lists[$key]['view_url'] = get_view_url($value['save_path']);
                $lists[$key]['create_time'] = $create_time;
            }
        }

        return $lists;
    }

    /**
     * 获取精彩推荐
     */
    public function get_site_recommend1($site_recommend=NULL){
        $site_recommend   = explode(',', $site_recommend);
        if(count($site_recommend)){
            foreach($site_recommend as $site_name){
                $site  = Db::name('article')
                    ->alias('a')
                    ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
                    ->join('upload b', 'a.thumb = b.id', 'left')
                    ->order('create_time desc')
                    ->where(array('a.delete'=>0,'a.title'=>$site_name))
                    ->find();

                if($site){
                    $recommend_site[]  = $site;
                }
            }
        }else{
            $recommend_site  = Db::name('article')
                ->alias('a')
                ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
                ->join('upload b', 'a.thumb = b.id', 'left')
                ->order('create_time desc')
                ->where(array('a.delete'=>0))
                ->limit(50)
                ->select();
        }
        if(is_array($recommend_site) && count($recommend_site)){
            foreach($recommend_site as $key => $value) {
                $recommend_site[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                $recommend_site[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        return $recommend_site;
    }

}
