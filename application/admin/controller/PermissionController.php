<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class PermissionController extends BaseController
{
    public $pager = 20;
    public $table = 'permission';
    public $url_path = 'permission';
    public $module_path = 'permission';
    public $module_name = '权限';

    public function __construct()
    {
        parent::_initialize();
    }

    /**
     * 列表
     */
    public function index()
    {
        $role_id = input('role_id') ? input('role_id') : 1;

        $role = Db::name('role')
            ->where(['delete'=>0,'status'=>1])
            ->order('weight desc')
            ->select();
        $data['goback']         = url('admin/'.$this->url_path.'/add');
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        $data['role']           = $role;
        $data['role_id']        = $role_id;
        return view($this->url_path.'/list', $data);
    }

    /**
     * 列表
     */
    public function index_data()
    {
        $role_id = input('role_id') ? input('role_id') : 1;

        $lists  = Db::name('menu')
            ->field('id, name as title,weight, parent_id as pid')
            ->where(array('delete'=>0,'status'=>1,'level'=>0))
            ->order('weight asc')
            ->select();

        foreach($lists as $key => $value){
            $permission = Db::name('permission')->where(['menu_id'=>$value['id'],'role_id'=>$role_id])->find();
            if(empty($permission)){
                $lists[$key]['status'] = 0;
            }else{
                $lists[$key]['status'] = 1;
            }
        }

        $data = [
            'code'  => 0,
            'message' => '获取列表成功!',
            'data'=> $lists,
        ];
        $this->json($data);
    }

    /**
     * 更新状态
     */
    public function ajax_update_status(){
        $menu_id = input('menu_id');
        $role_id = input('role_id');
        $status = input('status');

        //是否存在
        $result = Db::name($this->table)->where(array('menu_id'=>$menu_id,'role_id'=>$role_id))->find();
        if(empty($result)){
            Db::name($this->table)->insert(['role_id'=>$role_id, 'menu_id'=>$menu_id,'status'=>0]);
        }

        //更新状态
        if($status){
            Db::name($this->table)->where(array('menu_id'=>$menu_id,'role_id'=>$role_id))->update(['status'=>0]);
            $this->json(array('code'=>0, 'msg'=>'关闭完成!', 'data'=>[]));
        }else{
            Db::name($this->table)->where(array('menu_id'=>$menu_id,'role_id'=>$role_id))->update(['status'=>1]);
            $this->json(array('code'=>0, 'msg'=>'开启完成!', 'data'=>[]));
        }

    }
}
