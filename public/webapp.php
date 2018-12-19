<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('BIND_MODULE','webapp');

//模板变量
$php_self = str_replace('webapp.php', '', $_SERVER['PHP_SELF']);
define('BASE_PATH', 'http://'.$_SERVER['HTTP_HOST'].$php_self);

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
