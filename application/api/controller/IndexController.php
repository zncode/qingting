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
        $meta   = get_html_meta($html);

        if(isset($meta['keywords']) && !empty($meta['keywords'])){
            $data['keywords'] = $meta['keywords'];
            $data['description'] = $meta['description'];
            $data['title']  = get_html_title($html);
            echo json_encode(array('code'=>0,'message'=>'获取成功!', 'data'=>$data)); die;
        }else{
            echo json_encode(array('code'=>0,'message'=>'获取失败!', 'data'=>[])); die;
        }

    }

    public function phpinfo(){
        phpinfo();
    }
}