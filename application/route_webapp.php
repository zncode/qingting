<?php
use think\Route;

//首页
Route::get('app/index',                     'webapp/IndexController/index');
Route::get('app/category',                  'webapp/IndexController/taxonomy_list');
Route::get('app/page',                      'webapp/IndexController/page_info');
Route::get('app/search',                    'webapp/SearchController/search_submit');
Route::get('app/recommend',                 'webapp/IndexController/recommend_list');

