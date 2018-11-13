<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

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

            $channel = Db::name('channel')->where(array('id'=>$value['channel_id']))->find();
            if($channel){
                $lists[$key]['channel_name'] = $channel['name'];
            }else{
                $lists[$key]['channel_name'] = '';
            }
            $category_1 = Db::name('category')->where(array('id'=>$value['category_1']))->find();
            if($category_1){
                $lists[$key]['category_1'] = $category_1['name'];
            }else{
                $lists[$key]['category_1'] = '';
            }
            $category_2 = Db::name('category_2')->where(array('id'=>$value['category_2']))->find();
            if($category_2){
                $lists[$key]['category_2'] = $category_2['name'];
            }else{
                $lists[$key]['category_2'] = '';
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
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/qingting/public/'.$info['save_path'];
            }else{
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].$info['save_path'];
            }
        }

        $channel = db('channel')->where(array('id'=>$info['channel_id']))->find();
        if($channel){
            $info['channel_name'] = $channel['name'];
        }else{
            $info['channel_name'] = '';
        }
        $category_1 = db('category')->where(array('id'=>$info['category_1']))->find();
        if($category_1){
            $info['category_1'] = $category_1['name'];
        }else{
            $info['category_1'] = '';
        }
        $category_2 = db('category_2')->where(array('id'=>$info['category_2']))->find();
        if($category_2){
            $info['category_2'] = $category_2['name'];
        }else{
            $info['category_2'] = '';
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
        $channel              = Db::name('channel')->where(array('delete'=>0))->select();
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/add_submit');
        $data['url_upload']     = url('/upload/image');
        $data['module_name']    = $this->module_name;
        $data['channel']        =  $channel;
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
            'channel_id'        => $formData['channel_id'],
            'category_1'        => $formData['category_1'],
            'category_2'        => $formData['category_2'],
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
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/qingting/public/'.$info['save_path'];
            }else{
                $info['thumb_image'] = 'http://'.$_SERVER['HTTP_HOST'].$info['save_path'];
            }
        }

        if($info['channel_id']){
            $channel = Db::name('channel')->where(array('delete'=>0))->select();
            $data['channel'] = $channel;
        }else{
            $data['channel'] = '';
        }

        if($info['category_1']){
            $category_1 = Db::name('category')->where(array('delete'=>0))->select();
            $data['category_1'] = $category_1;
        }else{
            $data['category_1'] = '';
        }

        if($info['category_2']){
            $category_2 = Db::name('category_2')->where(array('parent_id'=>$info['category_1'], 'delete'=>0))->select();
            $data['category_2'] = $category_2;
        }else{
            $data['category_2'] = '';
        }

        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/edit_submit');
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
            'channel_id'        => $formData['channel_id'],
            'category_1'        => $formData['category_1'],
            'category_2'        => $formData['category_2'],
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
}
