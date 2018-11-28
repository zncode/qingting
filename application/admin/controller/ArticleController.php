<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;
use think\Config;

class ArticleController extends BaseController
{
    public $pager = 20;
    public $table = 'article';
    public $url_path = 'article';
    public $module_name = '文章';

    /**
     * 列表
     */
    public function index()
    {
        $data['goback']         = url('admin/'.$this->url_path.'/add');
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        return view($this->url_path.'/list', $data);
    }

    /**
     * 列表
     */
    public function index_data()
    {
        $count  = Db::name($this->table)->where(array('delete'=>0))->count();
        $pages  = Db::name($this->table)->where(array('delete'=>0))->order('create_time desc')->paginate($this->pager);

        $lists  = $pages->all();
        foreach($lists as $key => $value){
            $url_view   = url('admin/'.$this->url_path.'/info', ['id'=>$value['id']]);
            $url_edit   = url('admin/'.$this->url_path.'/edit', ['id'=>$value['id']]);
            $url_delete = url('admin/'.$this->url_path.'/delete', ['id'=>$value['id']]);

            $taxonomy = Db::name('taxonomy')->where(array('id'=>$value['taxonomy_id']))->find();
            if($taxonomy){
                $lists[$key]['taxonomy_name'] = $taxonomy['name'];
            }else{
                $lists[$key]['taxonomy_name'] = '';
            }
        }
        $data = [
            'code'  => 0,
            'message' => '获取列表成功!',
            'data'=> $lists,
            'count' => $count,
        ];
        $this->json($data);
    }

    /**
     * 详情
     */
    public function info()
    {
        $id = input('get.id');
        $info = Db::name($this->table)->alias('a')
            ->field('a.*,b.save_path')
            ->join('upload b','a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();

        if($info['save_path']){
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/'.$info['save_path'];
            }else{
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].$info['save_path'];
            }
        }

        $taxonomy = db('taxonomy')->where(array('id'=>$info['taxonomy_id']))->find();
        if($taxonomy){
            $info['taxonomy_name'] = $taxonomy['name'];
        }else{
            $info['taxonomy_name'] = '';
        }

        $data['info'] = $info;
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['module_name'] = $this->module_name;
        return view($this->url_path.'/info', $data);
    }

    /**
     * 添加表单
     */
    public function add_form()
    {
        $taxonomyClass          = new TaxonomyController();
        $taxonomy               = $taxonomyClass->get_taxonomy();
        $tree                   = $taxonomyClass->get_taxonomy_tree_wrapper($taxonomyClass->get_taxonomy_tree($taxonomy));
        $data['tree']           = $tree;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/add_submit');
        $data['get_favicon']    = url('admin/'.$this->url_path.'/get_favicon');
        $data['get_html']       = url('/api/get_html_info');
        $data['url_upload']     = url('/upload/image');
        $data['check_url']      = url('admin/'.$this->url_path.'/check_url');
        $data['module_name']    = $this->module_name;
        $data['url_upload_editor']     = url('/upload/image_editor',array('category'=>'article'));
        $data['kindeditor_file_manager']    = url('/upload/kindeditor_file_manager');
        $data['province']       = get_province();
        return view($this->url_path.'/add_form', $data);
    }

    /**
     * 添加表单提交
     */
    public function add_form_submit()
    {
        $formData   = input('request.');
        $upload_ids = $formData['upload_ids'];
        $data = [
            'title'             => $formData['title'],
            'url'               => $formData['url'],
            'taxonomy_id'        => $formData['taxonomy_id'],
            'meta_keyword'      => $formData['meta_keyword'],
            'meta_description'  => $formData['summary'],
            'summary'           => $formData['summary'],
            'content'           => $formData['content'],
            'status'            => 1,
            'thumb'             => $formData['upload_id'],
            'create_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result     = Db::name($this->table)->insert($data);
        $node_id    = Db::name($this->table)->getLastInsID();

        //更新内容上传文件
        if($upload_ids != ''){
            $upload_ids_array = explode(',', $upload_ids);
            foreach ($upload_ids_array as $upload_id){
                if($upload_id){
                    Db::name('upload')->where(array('id'=>$upload_id))->update(array('node_id'=>$node_id));
                }
            }
        }

        //更新列表图上传文件
        if($formData['upload_id']){
            Db::name('upload')->where(array('id'=>$formData['upload_id']))->update(array('node_id'=>$node_id));
            Db::name('article')->where(array('id'=>$node_id))->update(array('thumb'=>$formData['upload_id']));
        }

        if($result){
            $this->json(array('code'=>0, 'msg'=>'添加成功', 'data'=>array()));
        }else{
            $this->json(array('code'=>1, 'msg'=>'添加失败', 'data'=>array()));
        }
    }

    /**
     * 编辑表单
     */
    public function edit_form()
    {
        $id = input('get.id');
        $info = Db::name($this->table)->alias('a')
            ->field('a.*,b.save_path')
            ->join('upload b','a.thumb = b.id', 'left')
            ->where(array('a.id'=>$id))
            ->find();
        if($info['save_path']){
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/'.$info['save_path'];
            }else{
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].$info['save_path'];
            }
        }
        $taxonomyClass          = new TaxonomyController();
        $taxonomy               = $taxonomyClass->get_taxonomy();
        $tree                   = $taxonomyClass->get_taxonomy_tree_wrapper($taxonomyClass->get_taxonomy_tree($taxonomy));
        $data['tree']           = $tree;
        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/edit_submit');
        $data['get_favicon']    = url('admin/'.$this->url_path.'/get_favicon');
        $data['module_name']    = $this->module_name;
        $data['url_upload']     = url('/upload/image');
        $data['url_upload_editor']          = url('/upload/image_editor',array('category'=>'article'));
        $data['kindeditor_file_manager']    = url('/upload/kindeditor_file_manager');

        return view($this->url_path.'/edit_form', $data);
    }

    /**
     * 编辑文章表单提交
     */
    public function edit_form_submit()
    {
        $formData   = input('request.');
        $id         = $formData['id'];
        $upload_ids = $formData['upload_ids'];

        //更新内容
        $data = [
            'title'             => $formData['title'],
            'url'               => $formData['url'],
            'taxonomy_id'       => $formData['taxonomy_id'],
            'meta_keyword'      => $formData['meta_keyword'],
            'meta_description'  => $formData['summary'],
            'summary'           => $formData['summary'],
            'content'           => $formData['content'],
            'thumb'             => $formData['upload_id'],
            'update_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::name($this->table)->where(array('id'=>$id))->update($data);

        //更新内容上传文件
        if($upload_ids != ''){
            $upload_ids_array = explode(',', $upload_ids);
            foreach ($upload_ids_array as $upload_id){
                if($upload_id){
                    Db::name('upload')->where(array('id'=>$upload_id))->update(array('node_id'=>$id));
                }
            }
        }

        //更新列表图上传文件
        if($formData['upload_id']){
            Db::name('upload')->where(array('id'=>$formData['upload_id']))->update(array('node_id'=>$id));
            Db::name('article')->where(array('id'=>$id))->update(array('thumb'=>$formData['upload_id']));
        }

        if($result){
            $this->json(array('code'=>0, 'msg'=>'编辑成功', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'编辑失败', 'data'=>array()));
        }
    }

    /**
     * 删除文章
     */
    public function delete()
    {
        $id = input('get.id');
        $data = [
            'delete' => 1,
        ];
        $result = Db::name($this->table)->where('id',$id)->update($data);
        if($result){
            $this->json(array('code'=>0, 'msg'=>'删除成功', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'删除失败', 'data'=>array()));
        }
    }

    /**
     * 获取favicon
     */
    function get_favicon(){
        $url        = input('url');
        $save_path  =  ROOT_PATH . 'public' . DS . 'upload' . DS .'favicon.jpg';
        $new_icon   = "http://api.byi.pw/favicon/?url=".$url;
        copy($new_icon, $save_path);

        $curl = curl_init();
        if (class_exists('\CURLFile')) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            $data = array('file' => new \CURLFile(realpath($save_path)));//>=5.5
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
            $data = array('file' => '@' . realpath($save_path));//<=5.5
        }

        $url = 'http://'.$_SERVER['HTTP_HOST'].url('/upload/image');
//        print_r($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        curl_exec($curl);
//        $error = curl_error($curl);
//        var_dump($result);die;
//        print_r(json_decode($result));die;
//        echo $result;die;
    }

    /**
     * 检查url是否存在
     */
    public function check_url_exist($url){
        $result = Db::name('article')->where(array('url'=>$url))->find();
        if($result){
            $this->json(array('code'=>0, 'message'=>'url已经存在！', 'data'=>[]));
        }else{
            $this->json(array('code'=>1, 'message'=>'url不存在！', 'data'=>[]));
        }
    }

}
