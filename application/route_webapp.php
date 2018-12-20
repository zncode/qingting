<?php
use think\Route;

//首页
Route::get('app/index',                     'webapp/IndexController/index');
Route::get('app/channel',                   'webapp/IndexController/channel_list');
Route::get('app/category1',                 'webapp/IndexController/category1_list');
Route::get('app/category2',                 'webapp/IndexController/category2_list');
Route::get('app/category',                  'webapp/IndexController/taxonomy_list');
Route::get('app/category_left',             'webapp/IndexController/taxonomy_menu_left');
Route::get('app/category_right',            'webapp/IndexController/taxonomy_menu_right');
Route::get('app/page',                      'webapp/IndexController/page_info');
Route::get('app/search',                    'webapp/SearchController/search_submit');
Route::get('app/recommend',                 'webapp/IndexController/recommend_list');
Route::get('app/page',                      'webapp/IndexController/page_info');
Route::get('app/site_application', 	        'webapp/IndexController/site_application_form');
Route::post('app/site_application_submit', 	'webapp/IndexController/site_application_form_submit');

