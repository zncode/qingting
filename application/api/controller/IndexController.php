<?php
namespace app\api\controller;

use app\api\controller\BaseController;
use think\Db;
use think\Config;

class IndexController extends BaseController{
    /**
     * 获取html信息
     */
    public function get_html_info(){
        $url    = input('url');
        $html   = file_get_contents($url);

//        $fp= fopen($url,'r');
//        $header= stream_get_meta_data($fp);//获取报头信息
//        $html = '';
//        while(!feof($fp)) {
//            $html.= fgets($fp, 1024);
//        }
//        fclose($fp);
        $meta   = get_html_meta($url);
//print_r($meta);die;
        if(isset($meta['keywords']) && !empty($meta['keywords'])){
            $data['keywords'] = $meta['keywords'];
        }else{
            $data['keywords'] = '';
        }
        if(isset($meta['description']) && !empty($meta['description'])){
            $data['description'] = $meta['description'];
        }else{
            $data['description'] = '';
        }

        if($title = get_html_title($html)){
            $data['title'] = $title;
        }else{
            $data['title'] = '';
        }
//        print_r($data);die;
        echo json_encode(array('code'=>0,'message'=>'获取完成!', 'data'=>$data)); die;
    }

    public function phpinfo(){
        phpinfo();
    }
}