<?php
namespace app\api\controller;

use app\api\controller\BaseController;
use think\Db;

class UploadController extends BaseController
{

    /**
     * 网页编辑器上传图片
     */
    public function image_editor()
    {
        $category = input('post.category');
        $files = request()->file();
        if($files){
            foreach($files as $file){
                $date_dir = date('Ymd', time());
                $upload_save_path       = ROOT_PATH . 'public' . DS . 'upload' . DS . $category;
                $info = $file->move($upload_save_path);
                if($info){
                    $view_url  = 'http://'.$_SERVER['HTTP_HOST'].'/zw/public/upload/'.$category.'/'.$date_dir.'/'.$info->getFilename();
                    $data[] = $view_url;
                    $filename[] = $info->getFilename();

                    //保存数据库
                    $image['category']      = $category;
                    $image['save_path']     = $info->getSaveName();
                    $image['picture_name']  = $info->getFilename();
                    $image['size']          = $info->getSize();
                    $image['extension']     = $info->getExtension();
                    $this->insert_image($image);
                }
            }
            echo json_encode($data = ['errno'=>0,'data'=>$data, 'filename'=>$filename]);die;
        }else{
            echo json_encode($data = ['errno'=>1,'data'=>array()]);die;
        }
    }

    /**
     * 上传图片
     */
    public function image()
    {
        $category = input('post.category') ? input('post.category') : 'article';
        $file = request()->file('image');

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
                $picture['src']  = 'http://'.$_SERVER['HTTP_HOST'].'/public/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];


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
                    'save_path'     => '/public/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'],
                    'extension'     => $picture['extension'],
                    'create_time'   => date("Y-m-d H:i:s", time()),
                ];
                $upload_id = Db::table('nj_upload')->insert($data);
                $picture['upload_id'] = $upload_id;

                $data = ['code'=>0, 'message'=>'上传图片成功', 'data'=>$picture];
            }else{
                // 上传失败获取错误信息
                $data = ['code'=>1, 'message'=>$file->getError(), 'data'=>array()];
            }

            echo json_encode($data);die;
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
