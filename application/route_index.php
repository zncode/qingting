<?php
use think\Route;

//首页
Route::get('index',         'index/IndexController/index');
Route::get('category',      'index/IndexController/category_list');
Route::get('page',          'index/IndexController/page_info');

