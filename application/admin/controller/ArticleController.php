<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class ArticleController extends BaseController
{
    public $pager = 20;
    public $table = 'nj_article';
    public $url_path = 'article';
    public $module_name = '文章';

    /**
     * 列表
     */
    public function index()
    {
        $pages  = Db::table($this->table)->where(array('delete'=>0))->order('create_time desc')->paginate($this->pager);
        $render = $pages->render();
        $lists  = $pages->all();

        $data['list']           = $lists;
        $data['render']         = $render;
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
        $pages  = Db::table($this->table)->where(array('delete'=>0))->order('create_time desc')->paginate($this->pager);
        $lists  = $pages->all();
        foreach($lists as $key => $value){
            $url_view   = url('admin/'.$this->url_path.'/info', ['id'=>$value['id']]);
            $url_edit   = url('admin/'.$this->url_path.'/edit', ['id'=>$value['id']]);
            $url_delete = url('admin/'.$this->url_path.'/delete', ['id'=>$value['id']]);
        }
        $data = [
            'code'  => 0,
            'message' => '获取列表成功!',
            'data'=> $lists,
        ];
        $this->json($data);
    }

    /**
     * 详情
     */
    public function info()
    {
        $id = input('get.id');
        $info = Db::table($this->table)->where(array('id'=>$id))->find();
        $category = Db::table('nj_category')->where(array('id'=>$info['category_id']))->find();
        if($category){
            $info['category_name'] = $category['name'];
        }else{
            $info['category_name'] = '';
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
        $categorys              = Db::table('nj_category')->where(array('delete'=>0))->select();
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/add_submit');
        $data['url_upload']     = url('/upload/image');
        $data['module_name']    = $this->module_name;
        $data['categorys']      =  $categorys;
        return view($this->url_path.'/add_form', $data);
    }

    /**
     * 添加表单提交
     */
    public function add_form_submit()
    {
        $formData = input('request.');
        $data = [
            'title'             => $formData['title'],
            'category_id'       => $formData['category_id'],
            'meta_keyword'      => $formData['meta_keyword'],
            'meta_description'  => $formData['summary'],
            'summary'           => $formData['summary'],
            'content'           => $formData['content'],
            'status'            => 1,
            'create_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::table($this->table)->insert($data);
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
        $info = Db::table($this->table)->where(array('id'=>$id))->find();
        $categorys              = Db::table('nj_category')->where(array('delete'=>0))->select();
        $data['categorys']      = $categorys;
        $data['info'] = $info;
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['action'] = url('admin/'.$this->url_path.'/edit_submit');
        $data['module_name'] = $this->module_name;
        return view($this->url_path.'/edit_form', $data);
    }

    /**
     * 编辑文章表单提交
     */
    public function edit_form_submit()
    {
        $formData = input('request.');
        $id = $formData['id'];
        $data = [
            'title'             => $formData['title'],
            'category_id'       => $formData['category_id'],
            'meta_keyword'      => $formData['meta_keyword'],
            'meta_description'  => $formData['summary'],
            'summary'           => $formData['summary'],
            'content'           => $formData['content'],
            'update_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::table($this->table)->where(array('id'=>$id))->update($data);
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
        $result = Db::table($this->table)->where('id',$id)->update($data);
        if($result){
            $this->json(array('code'=>0, 'msg'=>'删除成功', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'删除失败', 'data'=>array()));
        }
    }

}
