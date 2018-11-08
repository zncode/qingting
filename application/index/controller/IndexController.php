<?php
namespace app\index\controller;

use app\index\controller\BaseController;
use think\Db;

class IndexController extends BaseController
{
    const CHANNEL_ID = 'channel_id';

    public function index()
    {
        return view('index/index');
    }

    /**
     * 频道列表
     */
    public function channel_list()
    {
        $channel_id = input('id');

        $pages  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b','nj_category'=>'c', 'nj_category_2'=>'d'])
            ->field('a.id,a.title,a.summary,a.create_time,a.channel_id,b.save_path,c.name as category_name_1, d.name as category_name_2')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
            ->join('nj_category', 'a.category_1 = c.id', 'left')
            ->join('nj_category_2', 'a.category_2 = d.id', 'left')
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

        $channel    = Db::table('nj_channel')->where(array('id'=>$channel_id))->find();
        $category   = Db::table('nj_category')->where(array('parent_id'=>$channel_id))->select();
        if(is_array($category) && count($category)){
            foreach($category as $key => $value){
                $category_2 = Db::table('nj_category_2')->where(array('parent_id'=>$value['id']))->select();
                if(is_array($category_2) && count($category_2)){
                    $category[$key]['child'] = $category_2;
                }
            }
        }

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>'','title'=>$channel['name']);
        }

        $data['breadcrumb']   = $this->get_breadcrumb($breadcrumb);
        $data['list']         = $lists;
        $data['page']         = $page;
        $data['category']     = $category;
        return view('index/category_list', $data);
    }

    /**
     * 一级栏目列表
     */
    public function category1_list()
    {
        $id = input('id');

        $pages  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b'])
            ->field('a.id,a.title,a.summary,a.create_time,a.channel_id,b.save_path')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
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

        $category_1 = Db::table('nj_category')->where(array('id'=>$id))->find();
        $channel    = Db::table('nj_channel')->where(array('id'=>$category_1['parent_id']))->find();

        //导航条
        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>'','title'=>$channel['name']);
        }
        if($category_1){
            $breadcrumb[] = array('path'=>url('category1/id/'.$category_1['id']),'title'=>$category_1['name']);
        }

        $data['breadcrumb']   = $this->get_breadcrumb($breadcrumb);
        $data['list']         = $lists;
        $data['page']         = $page;
        return view('index/category_list', $data);
    }

    /**
     * 二级栏目列表
     */
    public function category2_list()
    {
        $id = input('id');

        $pages  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b'])
            ->field('a.id,a.title,a.summary,a.create_time,a.channel_id,b.save_path')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
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

        $category_2 = Db::table('nj_category_2')->where(array('id'=>$id))->find();
        $category_1 = Db::table('nj_category')->where(array('parent_id'=>$id))->find();
        $channel    = Db::table('nj_channel')->where(array('id'=>$category_1['parent_id']))->find();

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

        $data['breadcrumb']   = $this->get_breadcrumb($breadcrumb);
        $data['list']         = $lists;
        $data['page']         = $page;
        return view('index/category_list', $data);
    }

    public function page_info()
    {
        $id = input('id');
        $info  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b'])
            ->field('a.id,a.title,a.content,a.create_time,a.channel_id,a.category_1,a.category_2,a.meta_keyword,a.meta_description,b.save_path')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();

        $channel     = Db::table('nj_channel')->where(array('id'=>$info['channel_id']))->find();
        $category_1  = Db::table('nj_category')->where(array('id'=>$info['category_1']))->find();
        $category_2  = Db::table('nj_category_2')->where(array('id'=>$info['category_2']))->find();

        $breadcrumb[] = array('path'=>url('/'),'title'=>'首页');
        if($channel){
            $breadcrumb[] = array('path'=>url('channel/id/'.$channel['id']),'title'=>$channel['name']);
        }
        if($category_1){
            $breadcrumb[] = array('path'=>url('category1/id/'.$category_1['id']),'title'=>$category_1['name']);
        }
        if($category_2){
            $breadcrumb[] = array('path'=>url('category2/id/'.$category_2['id']),'title'=>$category_2['name']);
        }
        $breadcrumb[] = array('path'=>'','title'=>$info['title']);

        $data['breadcrumb']     = $this->get_breadcrumb($breadcrumb);
        $info['view_url']       = get_view_url($info['save_path']);
        $data['info']           = $info;
        return view('index/page_info', $data);
    }

    public function resource_list()
    {
//        echo 'test';die;
        return view('index/resource_list');
    }
}
