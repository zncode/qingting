<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class CategoryController extends BaseController
{
    public $pager = 20;
    public $table = 'nj_category';
    public $url_path = 'category';
    public $module_path = 'category';
    public $module_name = '栏目';

    public function __construct()
    {
        if(input('get.url_path')){
            $this->url_path = input('get.url_path');
        }
    }

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
            $url_view   = url('admin/channel/info', ['id'=>$value['id']]);
            $url_edit   = url('admin/channel/edit', ['id'=>$value['id']]);
            $url_delete = url('admin/channel/delete', ['id'=>$value['id']]);
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
        $channels = Db::table('nj_channel')->where(array('delete'=>0))->select();
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['action'] = url('admin/'.$this->url_path.'/add_submit');
        $data['module_name'] = $this->module_name;
        $data['channels'] =  $channels;
        return view($this->url_path.'/add_form', $data);
    }

    /**
     * 添加表单提交
     */
    public function add_form_submit()
    {
        $formData = input('request.');
        $data = [
            'name'          => $formData['name'],
            'channel_id'    => $formData['channel_id'],
            'path'          => $formData['path'],
            'weight'        => $formData['weight'],
            'status'        => 1,
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'create_time'   => date("Y-m-d H:i:s", time()),
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
        $id                     = input('get.id');
        $info                   = Db::table($this->table)->where(array('id'=>$id))->find();
        $channels               = Db::table('nj_channel')->where(array('delete'=>0))->select();
        $data['channels']       = $channels;
        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/edit_submit');
        $data['module_name']    = $this->module_name;
        return view($this->url_path.'/edit_form', $data);
    }

    /**
     * 编辑表单提交
     */
    public function edit_form_submit()
    {
        $formData = input('request.');
        $id = $formData['id'];
        $data = [
            'name'          => $formData['name'],
            'channel_id'    => $formData['channel_id'],
            'path'          => $formData['path'],
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
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
     * 删除
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
