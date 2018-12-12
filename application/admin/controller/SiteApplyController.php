<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;
use think\Config;

class SiteApplyController extends BaseController
{
    public $pager = 20;
    public $table = 'application';
    public $url_path = 'site_apply';
    public $module_name = '网站申请';

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
        $keyword = input('keyword') ? input('keyword') : '';
        if(!empty($keyword)){
            $count  = Db::name($this->table)->where(array('title'=>['like', '%'.$keyword.'%']))->count();
            $pages  = Db::name($this->table)->where(array('title'=>['like', '%'.$keyword.'%']))->order('create_time desc')->paginate($this->pager);
        }else{
            $count  = Db::name($this->table)->count();
            $pages  = Db::name($this->table)->order('create_time desc')->paginate($this->pager);
        }

        $lists  = $pages->all();
        foreach($lists as $key => $value){
            $url_view   = url('admin/'.$this->url_path.'/info', ['id'=>$value['id']]);
            $url_edit   = url('admin/'.$this->url_path.'/verify', ['id'=>$value['id']]);
            $url_delete = url('admin/'.$this->url_path.'/delete', ['id'=>$value['id']]);

            $taxonomy = Db::name('taxonomy')->where(array('id'=>$value['taxonomy_id']))->find();
            if($taxonomy){
                $lists[$key]['taxonomy_name'] = $taxonomy['name'];
            }else{
                $lists[$key]['taxonomy_name'] = '';
            }

            if($value['status'] == 1){
                $lists[$key]['status'] = '通过';
            }elseif($value['status'] == 2){
                $lists[$key]['status'] = '已拒绝';
            }else{
                $lists[$key]['status'] = '未审核';
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
        $info = Db::name($this->table)
            ->where(array('id'=>$id))
            ->find();

        $taxonomy = db('taxonomy')->where(array('id'=>$info['taxonomy_id']))->find();
        if($taxonomy){
            $info['taxonomy_name'] = $taxonomy['name'];
        }else{
            $info['taxonomy_name'] = '';
        }

        if($info['status'] == 1){
            $info['status'] = '通过';
        }elseif($info['status'] == 2){
            $info['status'] = '已拒绝';
        }else{
            $info['status'] = '未审核';
        }

        $data['info'] = $info;
        $data['goback'] = url('admin/'.$this->url_path.'/list');
        $data['module_name'] = $this->module_name;
        return view($this->url_path.'/info', $data);
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
     * 收录审核表单
     */
    public function verify_form()
    {
        $id = input('get.id');
        $info = Db::name($this->table)
            ->where(array('id'=>$id))
            ->find();

        $taxonomy = db('taxonomy')->where(array('id'=>$info['taxonomy_id']))->find();
        if($taxonomy){
            $data['taxonomy_name'] = $taxonomy['name'];
        }else{
            $data['taxonomy_name'] = '';
        }

        $data['info']                       = $info;
        $data['goback']                     = url('admin/'.$this->url_path.'/list');
        $data['action']                     = url('admin/'.$this->url_path.'/verify_submit');
        $data['module_name']                = $this->module_name;
        $data['url_upload_editor']          = url('/upload/image_editor',array('category'=>'article'));
        $data['kindeditor_file_manager']    = url('/upload/kindeditor_file_manager');

        return view($this->url_path.'/verify_form', $data);
    }

    /**
     * 收录审核表单提交
     */
    public function verify_form_submit()
    {
        $formData   = input('request.');
        $id         = $formData['id'];

        //更新内容
        $data = [
            'user_id'       => $this->user_id,
            'status'        => $formData['status'],
            'comments'      => $formData['comments'],
            'verify_time'   => date('Y-m-d H:i:s', time()),
        ];
        $result = Db::name($this->table)->where(array('id'=>$id))->update($data);

        if($result){
            $this->json(array('code'=>0, 'msg'=>'审核通过!', 'data'=>array('id'=>$id)));
        }else{
            $this->json(array('code'=>1, 'msg'=>'审核拒绝!', 'data'=>array()));
        }
    }
}
