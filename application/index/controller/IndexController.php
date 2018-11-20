<?php
namespace app\index\controller;

use app\index\controller\BaseController;
use think\Db;

class IndexController extends BaseController
{
    const CHANNEL_ID = 'channel_id';

    public function index()
    {
        $date = date('Y年m月d日', time());

        switch (date('N', time())){
            case 1:
                $week = '周一';
                break;
            case 2:
                $week = '周二';
                break;
            case 3:
                $week = '周三';
                break;
            case 4:
                $week = '周四';
                break;
            case 5:
                $week = '周五';
                break;
            case 6:
                $week = '周六';
                break;
            case 7:
                $week = '周日';
                break;
        }
        $date = $date.'&nbsp;&nbsp;'.$week;

        //热门站点
        $hot_site  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.title,a.create_time,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->order('create_time desc')
            ->where(array('a.delete'=>0))
            ->limit(50)
            ->select();
        if(is_array($hot_site) && count($hot_site)){
            foreach($hot_site as $key => $value) {
                $hot_site[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        //左侧菜单
        $left_menu   = Db::name('channel')->where(array('delete'=>0))->select();
        if(is_array($left_menu) && count($left_menu)){
            foreach($left_menu as $key => $value){
                $category = Db::name('category')->where(array('parent_id'=>$value['id'], 'delete'=>0))->select();
                if(is_array($category) && count($category)){
                    $left_menu[$key]['child'] = $category;
                }
            }
        }

        $data['left_menu']      = $left_menu;
        $data['channel_id']     = 0;
        $data['current_date']   = $date;
        $data['hot_site']       = $hot_site;

        return view('index/index',$data);
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
     * 一级栏目列表
     */
    public function category1_list()
    {
        $id = input('id');

        $pages  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.title,a.summary,a.create_time,a.channel_id,b.save_path,c.name as category_name_1, d.name as category_name_2')
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

    public function page_info()
    {
        $id = input('id');
        $info  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.title,a.content,a.create_time,a.channel_id,a.category_1,a.category_2,a.meta_keyword,a.meta_description,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();
        $info['create_time'] = explode(' ',  $info['create_time']);
        $info['create_time'] = $info['create_time'][0];

        $channel     = Db::name('channel')->where(array('id'=>$info['channel_id']))->find();
        $category_1  = Db::name('category')->where(array('id'=>$info['category_1']))->find();
        $category_2  = Db::name('category_2')->where(array('id'=>$info['category_2']))->find();

        //导航条
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

        $data['breadcrumb']     = $this->get_breadcrumb($breadcrumb);
        $info['view_url']       = get_view_url($info['save_path']);
        $data['info']           = $info;
        $data['left_menu']    = $left_menu;
        $data['channel_id']     = $channel['id'];
        return view('index/page_info', $data);
    }

    public function resource_list()
    {
//        echo 'test';die;
        return view('index/resource_list');
    }
}
