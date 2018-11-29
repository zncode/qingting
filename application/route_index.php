<?php
use think\Route;

//首页
Route::get('index',         'index/IndexController/index');
Route::get('channel',       'index/IndexController/channel_list');
Route::get('category1',     'index/IndexController/category1_list');
Route::get('category2',     'index/IndexController/category2_list');
Route::get('category',      'index/IndexController/taxonomy_list');
Route::get('page',          'index/IndexController/page_info');

