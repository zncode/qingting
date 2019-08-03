<?php
namespace app\webapp\controller;

use app\admin\controller\TaxonomyController;
use app\admin\controller\SystemController;
use app\index\controller\BaseController;
use think\Db;
use think\Request;

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

        $data['menu']               = $left_menu;
        $data['current_date']       = get_current_date();
        $data['list']               = $search_result;
        $data['meta_keyword']       = '蜻蜓导航, 网址导航, 网站导航, APP导航, 公众号导航, 小程序导航, 手机网站导航, 好站推荐';
        $data['meta_description']   = '蜻蜓导航, 一个专业的导航网站，专注于互联网网站和手机网站导航，移动APP、公众号、小程序导航!';
        $request = Request::instance();
        $data['index_url'] = $request->root(true);

        return view('search/search_result',$data);
    }

}
