<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use Think\Config;
use think\Db;

// 应用公共文件
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 判断系统windows
 */
function check_windows(){
    return strtoupper(substr(PHP_OS,0,3))==='WIN'? 1: 0;
}

/**
 * 获取图片url
 */
function get_view_url($path){
    if($_SERVER['HTTP_HOST'] == 'localhost'){
        $view_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.Config::get('project_dirname').'/public/'.$path;
    }else{
        $view_url = 'http://'.$_SERVER['HTTP_HOST'].$path;
    }

    return $view_url;
}

/**
 * 获取省份
 */
function get_province(){
    return array('北京','天津','上海','重庆', '黑龙江','吉林','辽宁','河北','山西','青海','山东','河南','江苏','安徽','浙江','福建','江西','湖南','湖北','广东','台湾','海南','甘肃','陕西','四川','贵州','云南','内蒙古','西藏','宁夏','广西');
}

/**
 * 获取当前日期
 */
function get_current_date(){
    $date = date('Y年m月d日', time());

    switch (date('N', time())){
        case 1:
            $week = '周一';
            break;
        case 2:
            $week = '周二';
            break;
        case 3:
            $week = '周三';
            break;
        case 4:
            $week = '周四';
            break;
        case 5:
            $week = '周五';
            break;
        case 6:
            $week = '周六';
            break;
        case 7:
            $week = '周日';
            break;
    }
    $date = $date.'&nbsp;&nbsp;'.$week;

    return $date;
}

/**
 * 获取meta信息
 */
function get_html_meta($url)
{
//    header("content-type:text/html; charset=xxx");
//    header("content-type:text/html; charset=gb2312");
    $meta = get_meta_tags($url);

    if(count($meta)){
        foreach($meta as $key => $value){
            if(($key == 'keywords') || ($key == 'description')){
                $encode = mb_detect_encoding($value, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                $meta[$key] = mb_convert_encoding($value, 'UTF-8', $encode);
            }
        }
    }

    return $meta;
}

/**
 * 获取meta信息
 */
function get_html_meta1($html)
{
    if(is_null($html))
    {
        return NULL;
    }
    if(strlen($html)!=0)
    {
        $ret = array();

        $metapattern = '/<meta[^>]*?>/is';
        $kvpattern = '/([\w\-]+)=[\"\']?([^\s]+)["\']?/is';
        preg_match_all($metapattern, $html, $matches);
        if(!empty($matches))
        {
            $meta = $matches[0];
            foreach ($meta as $key => $value) {
                $value = preg_replace('/<meta\s+/is', "", $value);
                $value = rtrim($value,'/>');
                $value =trim($value);

                //替换;+空格
                $repattern = '/;\s+/is';
                $value = preg_replace($repattern, ';', $value);

                preg_match_all($kvpattern, $value, $kvmatches);
                if(!empty($kvmatches))
                {
                    $type = (int)count($kvmatches[0]);
                    switch ($type) {
                        case 1://meta里面只有一条语句 <meta charset=UTF-8>
                            $metakey = rtrim($kvmatches[1][0],"\"'");
                            $metakey    =   ltrim($metakey,"\"'");
                            $metavalue = rtrim($kvmatches[2][0],"\"'");
                            $metavalue  =   ltrim($metavalue,"\"'");
                            $ret[$metakey]  =   $metavalue;
                            break;
                        case 2://meta里面是参数名称/参数值的方式
                            $metakey = rtrim($kvmatches[2][0],"\"'");
                            $metakey    =   ltrim($metakey,"\"'");
                            $metavalue = rtrim($kvmatches[2][1],"\"'");
                            $metavalue  =   ltrim($metavalue,"\"'");
                            $ret[$metakey]  =   $metavalue;
                            break;
                        case 3://meta里面;+空格<meta http-equiv=mobile-agent content="format=wml;url=http://m.qidian.com
                            $metakey = rtrim($kvmatches[2][0],"\"'");
                            $metakey    =   ltrim($metakey,"\"'");
                            $tmp = rtrim($kvmatches[2][1],"\"'");
                            $tmp = ltrim($tmp,"\"'");
                            $tmp2 = rtrim($kvmatches[2][2],"\"'");
                            $tmp2 = ltrim($tmp2,"\"'");
                            $metavalue  =   $tmp.$tmp2;
                            $ret[$metakey]  =   $metavalue;
                            break;
                    }
                }
            }
            return $ret;
        }
        return NULL;
    }
}

/**
 * 获取title信息
 */
function get_html_title($html)
{
    $pos = strpos($html,'utf-8');
    if($pos===false){
        $html = iconv("gbk","utf-8",$html);
    }
    preg_match("/<title>(.*)<\/title>/i",$html, $title);
    return $title[1];

//    preg_match('/<title>(?<title>.*?)<\/title>/si', $html, $title); //获取title的正则表达式
//    $encode = mb_detect_encoding($title['title'], array('GB2312','GBK','UTF-8', 'CP936')); //得到字符串编码
//    $encode = mb_detect_encoding($title['title']); //得到字符串编码
//    print_r($encode);
//    $title['title'] = mb_convert_encoding($title['title'], 'UTF-8', 'auto');
//    $title['title'] = mb_convert_encoding($title['title'], 'UTF-8', $encode);
//    print_r($title['title']);die;
//    $file_charset = iconv_get_encoding()['internal_encoding']; //当前文件编码
//    if ( $encode != 'CP936' && $encode != $file_charset ) {
//        return iconv($encode, $file_charset, $title['title']);
//    }
//    return $title['title'];
}

/**
 * 获取远程图片保存本地
 * @param $url
 * @param $save_path
 */
function get_remote_picture($url, $save_path){

    $ch=curl_init();
    $timeout=5;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $img=curl_exec($ch);
    curl_close($ch);

    $fp2=@fopen($save_path,'a');
    fwrite($fp2,$img);
    fclose($fp2);
//    unset($img,$url);
//    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}

/**
 * 获取系统配置
 * @param $key
 * @return mixed
 */
function variable_get($key){
    $varialbe = Db::name('variable')->where(array('keyword'=>$key))->find();
    $varialbe = unserialize($varialbe['content']);
    return $varialbe;
}

/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */
function get_taxonomy_tree($array, $pid =0, $level = 0){
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
            get_taxonomy_tree($array, $value['id'], $level+1);

        }
    }
    return $list;
}

/**
 * 添加样式
 * @param $tree
 */
function get_taxonomy(){
    $taxonomy = Db::name('taxonomy')->where(array('delete'=>0,'status'=>1))->order('weight asc, id desc')->select();
    return $taxonomy;
}

/**
 * 添加样式
 * @param $tree
 */
function get_taxonomy_tree_wrapper($tree){
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
 * 获取分类
 * @param $id
 */
function get_taxonomy_self($id){
    $taxonomy = Db::name('taxonomy')->where(array('id'=>$id))->find();
    return $taxonomy;
}

/**
 * 获取父级分类
 * @param $id
 */
function taxonomy_get_parent($id){
    static $parents = [];
    $self   = Db::name('taxonomy')->where(array('id'=>$id))->find();
    $parent = Db::name('taxonomy')->where(array('id'=>$self['parent_id']))->find();

    if($parent){
        $parents[] = $parent;
        taxonomy_get_parent($parent['id']);
    }
    asort($parents);
    return $parents;
}