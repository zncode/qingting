<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class SystemController extends BaseController
{
    public $url_path = 'system';
    public $module_name = '系统设置';

    public function __construct()
    {

    }

    /**
     * 首页设置
     */
    public function index_setup()
    {
        $data['action']         = url('admin/'.$this->url_path.'/index_setup_submit');
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        $data['site_popular']   = $this->variable_get('site_popular');
        $data['site_recommend'] = $this->variable_get('site_recommend');
        return view($this->url_path.'/index_setup', $data);
    }

    /**
     * 首页设置
     */
    public function index_setup_submit()
    {
        $formData   = input('request.');
        $this->variable_set('site_popular',   $formData['site_popular']);
        $this->variable_set('site_recommend', $formData['site_recommend']);

        $this->json(array('code'=>0, 'msg'=>'编辑成功', 'data'=>[]));
    }

    /**
     * 设置变量
     * @param $key
     * @param $value
     */
    public function variable_set($key, $value){
        $value = serialize($value);
        $variable = Db::name('variable')->where(array('keyword'=>$key))->find();
        if($variable){
            return Db::name('variable')->where(array('keyword'=>$key))->update(array('content'=>$value));
        }else{
            $data = [
                'keyword' => $key,
                'content' => $value,
            ];
            return Db::name('variable')->where(array('keyword'=>$key))->insert($data);
        }

    }

    /**
     * 获取变量
     * @param $key
     */
    public function variable_get($key){
        $varialbe = Db::name('variable')->where(array('keyword'=>$key))->find();
        $varialbe = unserialize($varialbe['content']);
        return $varialbe;
    }
}
