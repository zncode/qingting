<?php
namespace app\index\controller;

use app\admin\controller\TaxonomyController;
use app\admin\controller\SystemController;
use app\index\controller\BaseController;
use think\Db;

class SearchController extends BaseController
{

    /**
     * 搜索回调页面
     */
    public function search_submit()
    {
        $keyword        = input('keyword');
        $search_result  = '';

        $search_result  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.url,a.title,a.brief,a.create_time,b.save_path')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->order('create_time desc')
            ->where(array('a.delete'=>0,'a.title|a.brief'=>['like', '%'.$keyword.'%']))
            ->select();

        if(is_array($search_result) && count($search_result)){
            foreach($search_result as $key => $value) {
                $search_result[$key]['brief']    = mb_substr($value['brief'],0,10,"UTF-8");
                $search_result[$key]['view_url'] = get_view_url($value['save_path']);
            }
        }
        if(empty($search_result)){
            $search_result = '';
        }

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

        $data['left_menu']          = $left_menu;
        $data['current_date']       = get_current_date();
        $data['search_result']      = $search_result;
        $data['meta_keyword']       = '蜻蜓导航, 网址导航, 网站导航, APP导航, 公众号导航, 小程序导航, 手机网站导航, 好站推荐';
        $data['meta_description']   = '蜻蜓导航, 一个专业的导航网站，专注于互联网网站和手机网站导航，移动APP、公众号、小程序导航!';

        return view('search/search_result',$data);
    }

    /**
     * 分类列表
     */
    public function taxonomy_list()
    {
        $id = input('id');

        $lists  = Db::name('article')
            ->alias('a')
            ->field('a.id,a.taxonomy_id,a.title,a.brief,a.create_time,a.url,b.save_path,c.name as taxonomy_name')
            ->join('upload b', 'a.thumb = b.id', 'left')
            ->join('taxonomy c', 'a.taxonomy_id = c.id', 'left')
            ->where(array('a.taxonomy_id'=>$id,'a.delete'=>0))
            ->order('create_time desc')
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
        $left_menu[0]   = $taxonomy;
        $childs         = Db::name('taxonomy')->where(array('parent_id'=>$taxonomy['id'], 'delete'=>0))->select();
        $left_menu[0]['child'] = $childs;

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

}
