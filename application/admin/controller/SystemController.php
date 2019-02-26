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
        parent::_initialize();
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
     * 网站信息设置
     */
    public function site_setup()
    {
        $data['action']             = url('admin/'.$this->url_path.'/site_setup_submit');
        $data['module_name']        = $this->module_name;
        $data['path']               = $this->url_path;
        $data['site_title']         = $this->variable_get('site_title');
        $data['site_keyword']       = $this->variable_get('site_keyword');
        $data['site_description']   = $this->variable_get('site_description');
        $data['site_logo']           = $this->variable_get('site_logo');
        return view($this->url_path.'/site_setup', $data);
    }

    /**
     * 网站信息设置提交
     */
    public function site_setup_submit()
    {
        $formData   = input('request.');
        $this->variable_set('site_title',   $formData['site_title']);
        $this->variable_set('site_keyword', $formData['site_keyword']);
        $this->variable_set('site_description', $formData['site_description']);
        $this->variable_set('site_logo', $formData['site_logo']);

        $this->json(array('code'=>0, 'msg'=>'编辑成功', 'data'=>[]));
    }


    /**
     * Sitemap设置
     */
    public function sitemap_setup()
    {
        $data['action']         = url('admin/'.$this->url_path.'/sitemap_setup_submit');
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        return view($this->url_path.'/sitemap_setup', $data);
    }

    /**
     * 首页设置
     */
    public function sitemap_setup_submit()
    {
//        $formData   = input('request.');
        //"always", "hourly", "daily", "weekly", "monthly"
        $os = (DIRECTORY_SEPARATOR=='\\')? false : true;
        if($os) {
            $breakline = "\n";
        }else {
            $breakline = "\r\n";
        }

        $base_url = 'http://'.$_SERVER['HTTP_HOST'];

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.$breakline;
        $sitemap.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$breakline;

        $current_time = date('Y-m-d\TG:i:s+08:00', time());

        //更新网站首页
        $sitemap_data[]['front']=array(
            'loc'=>$base_url,
            'priority'=>'1.0',
            'lastmod'=>$current_time,
            'changefreq'=>'always'
        );
        //好站推荐
        $sitemap_data[]['recommend']=array(
            'loc'=>$base_url.url('/recommend'),
            'priority'=>'0.8',
            'lastmod'=>$current_time,
            'changefreq'=>'daily'
        );

        //更新目录
        $taxonomyParent = Db::name('taxonomy')->where(array('delete'=>0))->select();
        $taxonomy = new TaxonomyController();
        $trees = $taxonomy->get_taxonomy_tree($taxonomyParent);
        foreach($trees AS $tree){
            $category_url = $base_url.url('/category/id/'.$tree['id']);
            $sitemap_data[]['category']=array(
                'loc'=>$category_url,
                'priority'=>'0.8',
                'lastmod'=>$current_time,
                'changefreq'=>'daily'
            );
        }


        foreach($sitemap_data as $datas){
            foreach($datas AS $type => $data){
                $sitemap .= $this->sitemap_create_item($data, $type,$breakline);
            }
        }
        $sitemap.='</urlset>';
        $sitemap_save_path   = ROOT_PATH . 'public' . DS . 'sitemap.xml';
        if($this->file_create_file($sitemap_save_path,$sitemap)){
            $this->json(array('code'=>0, 'msg'=>'sitemap更新成功', 'data'=>[]));die;
        }else{
            $this->json(array('code'=>0, 'msg'=>'sitemap更新失败', 'data'=>[]));die;
        }
    }

    /**
     * Sitemap创建条目.
     */
    function sitemap_create_item($data, $type,$breakline){
        $item="<url>".$breakline;
        $item.="<loc>".$data['loc']."</loc>".$breakline;
        $item.="<priority>".$data['priority']."</priority>".$breakline;
        $item.="<lastmod>".$data['lastmod']."</lastmod>".$breakline;
        $item.="<changefreq>".$data['changefreq']."</changefreq>".$breakline;
        $item.="</url>".$breakline;

        return $item;
    }

    /**
     * 创建或者全部更新单个文件.
     */
    function file_create_file($path, $content){
        $fp=fopen($path,'w+');
        if(fwrite($fp,$content)){
            $return = true;
        }else{
            $return = false;
        }
        fclose($fp);

        return $return;
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
