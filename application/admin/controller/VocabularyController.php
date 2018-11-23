<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class VocabularyController extends BaseController
{
    public $pager = 20;
    public $table = 'vocabulary';
    public $url_path = 'vocabulary';
    public $module_name = '容器类型';

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
        $lists  = Db::name($this->table)->field('id,name as title')->where(array('delete'=>0))->order('create_time desc')->select();
        foreach($lists as $key => $value){
//            $url_view   = url('admin/channel/info', ['id'=>$value['id']]);
//            $url_edit   = url('admin/channel/edit', ['id'=>$value['id']]);
//            $url_delete = url('admin/channel/delete', ['id'=>$value['id']]);

//            $op = '<a href="'.$url_view.'" class="row_view" date-id="'.$value['id'].'" >查看</a>';
//            $op .= ' | ';
//            $op .= '<a href="'.$url_edit.'" class="row_edit" date-id="'.$value['id'].'" >编辑</a>';
//            $op .= ' | ';
//            $op .= '<a href="javascript:;" class="row_delete" date-id="'.$value['id'].'" >删除</a>';
//            $lists[$key]['op'] = $op;
            $lists[$key]['pid'] = 0;
        }
        $categorys  = Db::name('category')->field('id,name as title,parent_id as pid')->where(array('delete'=>0))->order('create_time desc')->select();
//        print_r($lists);
        $lists = array_merge($lists, $categorys);

//        print_r($lists);
//        print_r($categorys);
//        die;
//        $lists = [
//            ['id'=>1, 'pid'=>0, 'title'=>'1-1'],
//            ['id'=>2, 'pid'=>0, 'title'=>'1-2'],
//            ['id'=>3, 'pid'=>0, 'title'=>'1-3'],
//            ['id'=>4, 'pid'=>1, 'title'=>'1-1-1'],
//            ['id'=>5, 'pid'=>1, 'title'=>'1-1-2'],
//            ['id'=>6, 'pid'=>2, 'title'=>'1-2-1'],
//            ['id'=>7, 'pid'=>2, 'title'=>'1-2-2'],
//            ['id'=>8, 'pid'=>3, 'title'=>'1-3-1'],
//            ['id'=>9, 'pid'=>3, 'title'=>'1-3-2'],
//            ['id'=>10, 'pid'=>4, 'title'=>'1-1-1-1'],
//            ['id'=>11, 'pid'=>5, 'title'=>'1-1-1-2'],
//        ];

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
            'path'          => $formData['path'],
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'create_time'   => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::name($this->table)->insert($data);
//        if($result){
//            $this->success('添加成功', 'admin/'.$this->url_path.'/add');
//        }else{
//            $this->error('添加失败');
//        }
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
            'path'          => $formData['path'],
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
