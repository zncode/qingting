<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class VocabularyController extends BaseController
{
    public $pager = 20;
    public $table = 'vocabulary';
    public $url_path = 'vocabulary';
    public $module_name = '类型';

    /**
     * 列表
     */
    public function index()
    {
        $pages  = Db::name($this->table)->where(array('delete'=>0))->order('create_time desc')->paginate($this->pager);
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
        $lists  = Db::name($this->table)->field('id,name as title,weight')->where(array('delete'=>0))->order('create_time desc')->select();
        foreach($lists as $key => $value){
            $lists[$key]['pid'] = 0;
        }
        $categorys  = Db::name('category')->field('id,name as title,parent_id as pid')->where(array('delete'=>0))->order('create_time desc')->select();
//        print_r($lists);
//        $lists = array_merge($lists, $categorys);



        $data = [
            'code'  => 0,
            'message' => '获取列表成功!',
            'data'=> json_encode($lists),
        ];
        $this->json($data);
    }

    /**
     * 详情
     */
    public function info()
    {
        $id = input('get.id');
        $info = Db::name($this->table)->where(array('id'=>$id))->find();

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
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['action'] = url('admin/'.$this->url_path.'/add_submit');
        $data['module_name'] = $this->module_name;
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
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'create_time'   => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::name($this->table)->insert($data);
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
        $info = Db::name($this->table)->where(array('id'=>$id))->find();

        $data['info'] = $info;
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['action'] = url('admin/'.$this->url_path.'/edit_submit');
        $data['module_name'] = $this->module_name;
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
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'update_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::name($this->table)->where(array('id'=>$id))->update($data);
        if($result){
//            $this->success('编辑成功', 'admin/'.$this->url_path.'/edit?id='.$id);
            $this->json(array('code'=>0, 'msg'=>'编辑成功', 'data'=>array('id'=>$id)));
        }else{
//            $this->error('编辑失败');
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
        $result = Db::name($this->table)->where('id',$id)->update($data);
        if($result){
            $this->json(array('code'=>0, 'msg'=>'删除成功', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'删除失败', 'data'=>array()));
        }
    }
}
