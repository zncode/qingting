<?php
namespace app\webapp\controller;

use think\Controller;
use app\admin\controller\SystemController;

class BaseController extends Controller
{
    public $search_action;
    public $site_logo;

    public function __construct()
    {
        $system = new SystemController();
        $this->site_logo = $system->variable_get('site_logo');
        $this->search_action = url('/search');

        \think\View::share(['search_action'     => url('/search')]);
        \think\View::share(['site_logo'         => $this->site_logo]);
        \think\View::share(['meta_keyword'      => $system->variable_get('site_keyword')]);
        \think\View::share(['meta_description'  => $system->variable_get('site_description')]);
        \think\View::share(['title'        => $system->variable_get('site_title')]);
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
