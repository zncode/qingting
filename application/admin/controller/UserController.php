<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;
use think\Session;
use app\model\UserModel;

class UserController extends BaseController
{
    public $pager = 20;
    public $table = 'user';
    public $url_path = 'user';
    public $module_name = '用户';

    /**
     * 用户登录
     */
    public function login_form(){
        $data['action'] = url('admin/'.$this->url_path.'/login_submit');
        return view($this->url_path.'/login', $data);
    }

    /**
     * 用户登录提交
     */
    public function login_form_submit(){
        $formData = input('request.');

        $info = Db::name($this->table)->where(array('username'=>$formData['username'], 'password'=>md5($formData['password'])))->find();
        Session('user_id', $info['id']);

        if($info){
            return json(['code'=>0, 'msg'=>'登录成功!', 'data'=>['uid'=>1]]);
        }else{
            return json(['code'=>1, 'msg'=>'用户名或者密码错误!', 'data'=>[]]);
        }
    }

    /**
     * 用户登出
     */
    public function logout(){
        Session::delete('user_id');
        return  json(['code'=>0, 'msg'=>'登出成功!', 'data'=>[]]);
    }

    /**
     * 用户注册
     */
    public function register_form(){
        $data['action'] = url('admin/'.$this->url_path.'/register_submit');
        return view($this->url_path.'/register', $data);
    }

    /**
     * 用户注册提交
     */
    public function register_form_submit(){
        return json(['code'=>1, 'msg'=>'注册关闭!', 'data'=>[]]);
        $formData = input('request.');

        $info = Db::name($this->table)->where(array('username'=>$formData['username']))->find();
        if($info){
            return json(['code'=>1, 'msg'=>'用户已经存在!', 'data'=>[]]);
        }

        $data = [
            'status'            => 1,
            'role_id'           => 1,
            'username'          => $formData['username'],
            'password'          => md5($formData['password']),
            'nickname'          => $formData['nickname'],
            'register_type'     => 1, //1=手机号 2=微信 3=qq
            'register_account'  => $formData['username'],
            'register_source'   => 1, //注册来源:1=PC, 2=IOS, 3=Android, 4=后台添加,5=webapp
            'register_ip'       => get_client_ip(),
            'create_time'   => date("Y-m-d H:i:s", time()),
        ];
        $result  = Db::table($this->table)->insert($data);
        $uid     = Db::table($this->table)->getLastInsID();

        if($result){
            return json(['code'=>0, 'msg'=>'注册成功!', 'data'=>['uid'=>$uid]]);
        }else{
            return json(['code'=>1, 'msg'=>'注册失败!', 'data'=>[]]);
        }
    }

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
        $pages  = Db::name($this->table)->where(array('delete'=>0))->order('create_time desc')->paginate($this->pager);
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
}
