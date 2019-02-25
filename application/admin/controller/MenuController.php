<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class MenuController extends BaseController
{
    public $pager = 20;
    public $table = 'menu';
    public $url_path = 'menu';
    public $module_path = 'menu';
    public $module_name = '分类';
    public $parent; //上级

    public function __construct()
    {
        parent::_initialize();
        if(input('get.url_path')){
            $this->url_path = input('get.url_path');
        }
    }

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
        $lists  = Db::name($this->table)->field('id,name as title,weight, parent_id as pid,status')->where(array('delete'=>0))->order('weight asc, id desc')->select();

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
        $id                     = input('get.id');
        $info                   = Db::name($this->table)->where(array('id'=>$id))->find();
        $parent                 = Db::name($this->table)->where(array('id'=>$info['parent_id']))->find();
        $info['parent_name']    = $parent['name'];
        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['module_name']    = $this->module_name;
        return view($this->url_path.'/info', $data);
    }

    /**
     * 添加表单
     */
    public function add_form()
    {
        $parent_id           = input('get.parent_id') ? input('get.parent_id') : '';
        $menu                = Db::name($this->table)->where(array('delete'=>0))->order("weight asc")->select();
        $tree                = $this->get_menu_tree_wrapper($this->get_menu_tree($menu));
        $data['tree']        = $tree;
        $data['goback']      = url('admin/'.$this->url_path.'/list');
        $data['action']      = url('admin/'.$this->url_path.'/add_submit');
        $data['module_name'] = $this->module_name;
        $data['parent_id']   =  $parent_id;
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
            'type'          => 1, //1=后台菜单
            'parent_id'     => $formData['parent_id'],
            'weight'        => $formData['weight'],
            'status'        => 1,
            'url'           => $formData['url'],
            'description'   => $formData['description'],
            'level'         => $this->get_level_by_parent($formData['parent_id']),
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
        $id                     = input('get.id');
        $info                   = Db::name($this->table)->where(array('id'=>$id))->find();
        $menu                   = Db::name($this->table)->where(array('delete'=>0))->select();
        $tree                   = $this->get_menu_tree_wrapper($this->get_menu_tree($menu));
        $data['tree']           = $tree;
        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list');
        $data['action']         = url('admin/'.$this->url_path.'/edit_submit');
        $data['module_name']    = $this->module_name;
        $data['self']           = $id;
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
            'parent_id'     => $formData['parent_id'],
            'weight'        => $formData['weight'],
            'url'           => $formData['url'],
            'description'   => $formData['description'],
            'level'         => $this->get_level_by_parent($formData['parent_id']),
            'update_time'   => date("Y-m-d H:i:s", time()),
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
     * 递归实现无限极分类
     * @param $array 分类数据
     * @param $pid 父ID
     * @param $level 分类级别
     * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
     */
    function get_menu_tree($array, $pid =0, $level = 0){
        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['parent_id'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->get_menu_tree($array, $value['id'], $level+1);

            }
        }
        return $list;
    }

    /**
     * 添加样式
     * @param $tree
     */
    function get_menu(){
        $taxonomy = Db::name($this->table)->where(array('delete'=>0,'status'=>1))->order('weight asc, id desc')->select();
        return $taxonomy;
    }

    /**
     * 添加样式
     * @param $tree
     */
    function get_menu_tree_wrapper($tree){
        if(is_array($tree) && count($tree)){
            $i = 1;
            foreach($tree as $key => $value ){
                $tree[$key]['name'] = $i.') '.str_repeat('--', $value['level']). $value['name'];
                $i++;
            }
        }
        return $tree;
    }

    /**
     * 获取分类层级
     * @param $parent_id
     */
    public function get_level_by_parent($parent_id = NULL){
        if($parent_id){
            $parent_level = Db::name($this->table)->where(array('id'=>$parent_id))->find();
            $level = $parent_level['level'] + 1;
        }else{
            $level = 0;
        }
        return $level;
    }

    /**
     * 获取分类
     * @param $id
     */
    public function get_menu_self($id){
        $taxonomy = Db::name($this->table)->where(array('id'=>$id))->find();
        return $taxonomy;
    }

    /**
     * 获取父级分类
     * @param $id
     */
    public function get_parent($id){
        static $parents = [];
        $self   = Db::name($this->table)->where(array('id'=>$id))->find();
        $parent = Db::name($this->table)->where(array('id'=>$self['parent_id']))->find();

        if($parent){
            $parents[] = $parent;
            $this->get_parent($parent['id']);
        }
        asort($parents);
        return $parents;
    }

    /**
     * 获取左侧菜单
     */
    public function get_site_menu(){
        $menu = Db::name($this->table)->where(array('delete'=>0, 'status'=>1, 'level'=>0))->order("weight asc")->select();
        foreach($menu as $key => $value){
            $child = Db::name($this->table)->where(array('delete'=>0, 'status'=>1, 'level'=>1,'parent_id'=>$value['id']))->order("weight asc")->select();
            $menu[$key]['child'] = $child;
        }
        return $menu;
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
}
