<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class RoleController extends BaseController
{
    public $pager = 20;
    public $table = 'role';
    public $url_path = 'role';
    public $module_name = '角色';

    /**
     * 列表
     */
    public function index()
    {

        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        return view($this->url_path.'/list', $data);
    }

    /**
     * 列表
     */
    public function index_data()
    {
        $keyword        = input('keyword') ? input('keyword') : '';
        $where          = array('delete'=>0);

        if(!empty($keyword)){
            if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keyword)>0) {
                $where['name'] = ['like', '%'.$keyword.'%'];
            } else {
                $where['name'] = ['like', '%'.$keyword.'%'];
            }
        }

        $count  = Db::name($this->table)->where($where)->count();
        $pages  = Db::name($this->table)->where($where)->order('role_id asc')->paginate($this->pager);

        $lists  = $pages->all();
        foreach($lists as $key => $value){
            $id = $value['id'];
            if($value['status'] == 1){
                $lists[$key]['status'] = '<input type="checkbox" value="1" dataid="'.$id.'" lay-skin="switch" lay-filter="status" lay-text="开启|关闭" checked>';
            }else{
                $lists[$key]['status'] = '<input type="checkbox"  value="0" dataid="'.$id.'" lay-skin="switch" lay-filter="status" lay-text="开启|关闭">';
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
//        $result = $this->validate($formData,[
//            'username|用户名'     => 'require',
//            'captcha|验证码'       => 'require|captcha',
//            'name|网站名称'        => 'require|max:30',
//            'url'                  => 'require|url',
//            'icp|备案号'           => 'require|max:100',
//            'email'                => 'email',
//            'keyword|关键字'       => 'max:150',
//            'description|描述'     => 'max:255',
//        ]);

        $info = Db::name($this->table)->where(array('name'=>$formData['name']))->find();
        if($info){
            return $this->json(['code'=>1, 'msg'=>'角色已经存在!', 'data'=>[]]);
        }

        $data = [
            'status'            => 1,
            'delete'            => 0,
            'name'              => $formData['name'],
            'role_id'           => $formData['role_id'],
            'weight'            => $formData['weight'],
            'description'       => $formData['description'],
            'create_time'   => date("Y-m-d H:i:s", time()),
        ];
        $result  = Db::name($this->table)->insert($data);
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
        $data['old_role_id'] = $info['role_id'];
        return view($this->url_path.'/edit_form', $data);
    }

    /**
     * 编辑表单提交
     */
    public function edit_form_submit()
    {
        $formData = input('request.');
        $id = $formData['id'];

        $info = Db::name($this->table)->where(array('id'=>$formData['id']))->find();
        if(($info['role_id'] == $formData['role_id']) && ($formData['old_role_id'] != $formData['role_id'])){
            return $this->json(['code'=>1, 'msg'=>'角色ID已经存在!', 'data'=>[]]);
        }

        $data = [
            'name'           => $formData['name'],
            'role_id'        => $formData['role_id'],
            'weight'         => $formData['weight'],
            'description'    => $formData['description'],
            'update_time'    => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::name($this->table)->where(array('id'=>$id))->update($data);
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
        $result = Db::name($this->table)->where('id',$id)->update($data);
        if($result){
            $this->json(array('code'=>0, 'msg'=>'删除成功', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'删除失败', 'data'=>array()));
        }
    }

    /**
     * 更新状态
     */
    public function ajax_update_status(){
        $id = input('id');
        $status = input('status');
        if($status){
            Db::name($this->table)->where(array('id'=>$id))->update(['status'=>0]);
            $this->json(array('code'=>0, 'msg'=>'关闭完成!', 'data'=>[]));
        }else{
            Db::name($this->table)->where(array('id'=>$id))->update(['status'=>1]);
            $this->json(array('code'=>0, 'msg'=>'开启完成!', 'data'=>[]));
        }
    }

    /**
     * 获取角色
     */
    public function get_role($id=null){
        if($id){
            return Db::name($this->table)->where(['role_id'=>$id])->find();
        }else{
            return Db::name($this->table)->where(['delete'=>0, 'status'=>1])->select();
        }
    }
}
