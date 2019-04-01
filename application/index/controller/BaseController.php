<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class BaseController extends Controller
{
    public $search_action;
    public $site_logo;

    public function __construct()
    {

        $this->site_logo = variable_get('site_logo');
        $this->search_action = url('/search');

        $count       = Db::name('article')->where(['delete'=>0,'status'=>1])->count();
        $start_time  = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)).' 00:00:00';
        $end_time    = date('Y-m-d H:i:s',time());
        $week_count = Db::name('article')->where(['delete'=>0,'status'=>1,'create_time'=>['between', [$start_time,$end_time]]])->count();
        $friendlink  = Db::name('friend_link')->where(['delete'=>0,'status'=>1])->order('weight asc')->select();
        \think\View::share(['search_action'     => url('/search')]);
        \think\View::share(['site_logo'         => $this->site_logo]);
        \think\View::share(['meta_keyword'      => variable_get('site_keyword')]);
        \think\View::share(['meta_description'  => variable_get('site_description')]);
        \think\View::share(['title'             => variable_get('site_title')]);
        \think\View::share(['site_count'        => $count]);
        \think\View::share(['site_week_count'   => $week_count]);
        \think\View::share(['friend_link'       => $friendlink]);

    }

    public function get_document_root_dir(){
        $document_root = NULL;
        $system = php_uname('s');
        if($system == 'Linux'){
            $document_root = $_SERVER['DOCUMENT_ROOT'];
        }else{
            $document_root = $_SERVER['DOCUMENT_ROOT'];
        }

        return $document_root;
    }

    /**
     * 处理导航条
     */
    public function get_breadcrumb($breadcrumb){
        $i = 1;
        foreach($breadcrumb as $key => $value){
            if($i == 1){
                $breads[] = '<a href="'.$value['path'].'" class="homepage" >'.$value['title'].'</a>';
            }else{
                $breads[] = '<a href="'.$value['path'].'">'.$value['title'].'</a>';
            }

            $i++;
        }
        $breads = implode('<span> > </span>', $breads);
        return $breads;
    }
}
