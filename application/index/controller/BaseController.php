<?php
namespace app\index\controller;

use think\Controller;

class BaseController extends Controller
{
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
