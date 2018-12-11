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
            $url_edit   = url('admin/'.$this->url_path.'/edit', ['id'=>$value['id']]);
            $url_delete = url('admin/'.$this->url_path.'/delete', ['id'=>$value['id']]);

            $taxonomy = Db::name('taxonomy')->where(array('id'=>$value['taxonomy_id']))->find();
            if($taxonomy){
                $lists[$key]['taxonomy_name'] = $taxonomy['name'];
            }else{
                $lists[$key]['taxonomy_name'] = '';
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

}
