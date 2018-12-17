<?php
namespace app\api\controller;

use app\api\controller\BaseController;
use think\Db;
use kindeditor\Services_JSON;
use think\Config;

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
                $view_url = 'http://' . $_SERVER['HTTP_HOST'] . '/qingting/public/upload/kindeditor/' . $category . '/' . $date_dir . '/' . $info->getFilename();
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
            Db::name('upload')->insert($data);
            $upload_id = Db::name('upload')->getLastInsID();
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
        $category       = input('post.category') ? input('post.category') : 'article';
        $old_upload_id  = input('old_upload_id') ? input('old_upload_id') : '';
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
                    $src = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];
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
                Db::name('upload')->insert($data);
                $upload_id = Db::name('upload')->getLastInsID();
                $picture['upload_id'] = $upload_id;

                //删除旧图片
                if($old_upload_id){
                    $upload =  Db::name('upload')->where(array('id'=>$old_upload_id))->find();
                    $save_path = $upload['save_path'];
                    if(check_windows()){
                        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/nongjia/public'.$save_path;
                    }else{
                        $file_path = $_SERVER['DOCUMENT_ROOT'] . $save_path;
                    }

                    if(file_exists($file_path)){
                        $result = unlink($file_path);
                        if($result){
                            Db::name('upload')->where(array('id'=>$old_upload_id))->delete();
                        }
                    }
                }
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
     * 上传图片不保存数据库
     */
    public function image_no_db()
    {
        $category       = input('post.category') ? input('post.category') : 'article';
        $filename       = input('post.filename') ? input('post.filename') : null;
        $file = request()->file();
        if(is_array($file)){
            $file = $file['file'];
        }

        if($file){
            $date_dir           = date('Ymd', time());
            $upload_save_path   = ROOT_PATH . 'public' . DS . 'upload' . DS . $category;

            $info = $file->move($upload_save_path, $filename);

            if($info){
                $picture['extension'] = $info->getExtension();
                $picture['save_path'] = $info->getSaveName();
                $picture['filename']  = $info->getFilename();
                $picture['size']      = $info->getSize();
                if($_SERVER['HTTP_HOST'] == 'localhost'){
                    if($filename){
                        $src = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/upload/'.$category.'/'.$picture['filename'];
                    }else{
                        $src = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];
                    }

                }else{
                    if($filename){
                        $src = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'.$category.'/'.$picture['filename'];
                    }else{
                        $src = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'.$category.'/'.$date_dir.'/'.$picture['filename'];
                    }
                }
                $picture['src']  = $src;

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

    /**
     * 网页编辑器文件空间查看
     */
    function kindeditor_file_manager(){
        //根目录路径，可以指定绝对路径，比如 /var/www/attached/
        if(check_windows()){
            $root_path = $_SERVER['DOCUMENT_ROOT'] . '/nongjia/public/upload/';
        }else{
            $root_path = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
        }

        //根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        if($_SERVER['HTTP_HOST'] == 'localhost'){
            $root_url = 'http://'.$_SERVER['HTTP_HOST'].'/nongjia/public/upload/';
        }else{
            $root_url = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
        }

        //图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

        //目录名
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        $dir_name = '';
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
            echo "Invalid Directory name.";
            exit;
        }
        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                mkdir($root_path);
            }
        }

        //根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path = realpath($root_path) . '/' . $_GET['path'];
            $current_url = $root_url . $_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
//        echo realpath($root_path);
        //排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

        //不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit;
        }
        //最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit;
        }
        //目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit;
        }

        //遍历目录取得文件信息
        $file_list = array();

        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') continue;
                $file = $current_path . $filename;
                if (is_dir($file)) {

                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

//        usort($file_list, $this->cmp_func());

        $result = array();
        //相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
        //相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
        //当前目录的URL
        $result['current_url'] = $current_url;
        //文件数
        $result['total_count'] = count($file_list);
        //文件列表数组
        $result['file_list'] = $file_list;

        //输出JSON字符串
        header('Content-type: application/json; charset=UTF-8');
        $json = new Services_JSON();
        echo $json->encode($result);
    }

    /**
     * 排序
     */
    function cmp_func($a, $b) {
        global $order;
        if ($a['is_dir'] && !$b['is_dir']) {
            return -1;
        } else if (!$a['is_dir'] && $b['is_dir']) {
            return 1;
        } else {
            if ($order == 'size') {
                if ($a['filesize'] > $b['filesize']) {
                    return 1;
                } else if ($a['filesize'] < $b['filesize']) {
                    return -1;
                } else {
                    return 0;
                }
            } else if ($order == 'type') {
                return strcmp($a['filetype'], $b['filetype']);
            } else {
                return strcmp($a['filename'], $b['filename']);
            }
        }
    }
}
