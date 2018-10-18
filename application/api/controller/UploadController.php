<?php
namespace app\api\controller;

use app\api\controller\BaseController;
use think\Db;

class UploadController extends BaseController
{

    /**
     * 网页编辑器上传图片
     * kindeditor
     */
    public function image_editor()
    {
        $category   = input('category') ? input('category') : '';
        $file       = request()->file('imgFile');
        $date_dir   = date('Ymd', time());
        if($category){
            $upload_save_path       = ROOT_PATH . 'public' . DS . 'upload' . DS . 'kindeditor' . DS .$category;
        }else{
            $upload_save_path       = ROOT_PATH . 'public' . DS . 'upload' . DS . 'kindeditor';
        }

        $info = $file->move($upload_save_path);
        if($info) {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $view_url = 'http://' . $_SERVER['HTTP_HOST'] . '/nongjia/public/upload/kindeditor/' . $category . '/' . $date_dir . '/' . $info->getFilename();
            } else {
                $view_url = 'http://' . $_SERVER['HTTP_HOST'] . '/upload/kindeditor/' . $category . '/' . $date_dir . '/' . $info->getFilename();
            }

            $picture['extension'] = $info->getExtension();
            $picture['save_path'] = $info->getSaveName();
            $picture['filename']  = $info->getFilename();
            $picture['size']      = $info->getSize();

            //保存数据库
            $data = [
                'module'        => 'kindeditor/'.$category,
                'type'          => 1,
                'status'        => 1,
                'filename'      => $picture['filename'],
                'size'          => $picture['size'],
                'save_path'     => '/upload/kindeditor/'.$category.'/'.$date_dir.'/'.$picture['filename'],
                'extension'     => $picture['extension'],
                'create_time'   => date("Y-m-d H:i:s", time()),
            ];
            Db::table('nj_upload')->insert($data);
            $upload_id = Db::table('nj_upload')->getLastInsID();
            $picture['upload_id'] = $upload_id;

            $data= ['view_url'=>$view_url, 'upload_id'=>$upload_id];

            echo json_encode($data = ['error'=>0,'data'=>$data,'url'=>$view_url]);die;
        }else{
            echo json_encode($data = ['error'=>1,'data'=>array()]);die;
        }
    }

    /**
     * 上传图片
     */
    public function image()
    {
        $category = input('post.category') ? input('post.category') : 'article';
        $file = request()->file();
        if(is_array($file)){
            $file = $file['file'];
        }

        if($file){
            $date_dir = date('Ymd', time());
            $upload_save_path       = ROOT_PATH . 'public' . DS . 'upload' . DS . $category;
//            $upload_save_path_thumb = ROOT_PATH . 'public' . DS . 'upload' . DS . $category . DS . $date_dir . DS . 'thumb' . DS;

            $info = $file->move($upload_save_path);

            if($info){
                $picture['extension'] = $info->getExtension();
                $picture['save_path'] = $info->getSaveName();
                $picture['filename']  = $info->getFilename();
                $picture['size']      = $info->getSize();
                if($_SERVER['HTTP_HOST'] == 'localhost'){
                    $src = 'http://'.$_SERVER['HTTP_HOST'].'/nongjia/public/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];
                }else{
                    $src = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];
                }
                $picture['src']  = $src;


//                //生成缩略图
//                $image = \think\Image::open(request()->file('image'));
//                $image->thumb(150, 150)->save($upload_save_path_thumb.$picture['filename']);
//                $thumb_file_name = str_replace('.'.$picture['extension'], '_thumb.'.$picture['extension'], $picture['filename']);
//                $picture['thumb_path'] = $date_dir . DS . 'thumb'. DS . $thumb_file_name;

                $data = [
                    'module'        => $category,
                    'type'          => 1,
                    'status'        => 1,
                    'filename'      => $picture['filename'],
                    'size'          => $picture['size'],
                    'save_path'     => '/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'],
                    'extension'     => $picture['extension'],
                    'create_time'   => date("Y-m-d H:i:s", time()),
                ];
                Db::table('nj_upload')->insert($data);
                $upload_id = Db::table('nj_upload')->getLastInsID();
                $picture['upload_id'] = $upload_id;

                $data = ['code'=>0, 'message'=>'上传图片成功', 'data'=>$picture];
            }else{
                // 上传失败获取错误信息
                $data = ['code'=>1, 'message'=>$file->getError(), 'data'=>array()];
            }

            echo json_encode($data);die;
        }else{
            echo json_encode(['code'=>1, 'message'=>$file->getError(), 'data'=>array()]);die;
        }
    }

    /**
     * 保存数据库
     * @param $image
     */
    public function insert_image($image){
        switch($image['category']){
            case 'article':
                $table = 'zw_article_picture';
                break;
        }

        $data = [
            'article_id'        => 0,
            'save_path'         => $image['save_path'],
            'picture_name'      => $image['picture_name'],
            'size'              => $image['size'],
            'extension'         => $image['extension'],
            'create_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::table($table)->insert($data);
    }
}
