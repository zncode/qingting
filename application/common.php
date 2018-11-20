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