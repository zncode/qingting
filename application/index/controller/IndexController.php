<?php
namespace app\index\controller;

use app\index\controller\BaseController;
use think\Db;

class IndexController extends BaseController
{
    public function index()
    {
        return view('index/index');
    }

    public function category_list()
    {
        $id = input('id');

        $pages  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b'])
            ->field('a.id,a.title,a.summary,a.create_time,a.category_id,b.save_path')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
            ->where(array('a.category_id'=>$id,'a.delete'=>0))
            ->order('create_time desc')
            ->paginate(2);

        $page = $pages->render();
        $lists  = $pages->all();
        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $lists[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }

        $category = Db::table('nj_category')->where(array('id'=>$id))->find();

        $data['category']     = $category;
        $data['list']         = $lists;
        $data['page']         = $page;
        return view('index/category_list', $data);
    }

    public function page_info()
    {
        $id = input('id');
        $info  = Db::table('nj_article')
            ->alias(['nj_article'=>'a', 'nj_upload'=>'b'])
            ->field('a.id,a.title,a.content,a.create_time,a.category_id,a.meta_keyword,a.meta_description,b.save_path')
            ->join('nj_upload', 'a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();
        $info['view_url'] = get_view_url($info['save_path']);
        $data['info'] = $info;
        return view('index/page_info', $data);
    }

    public function resource_list()
    {
//        echo 'test';die;
        return view('index/resource_list');
    }
}
