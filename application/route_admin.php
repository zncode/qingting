<?php
use think\Route;
//后台
Route::get('admin', 				        'admin/IndexController/index');

//用户
Route::get('admin/user/login', 			        'admin/UserController/login_form');
Route::post('admin/user/login_submit', 		    'admin/UserController/login_form_submit');
Route::get('admin/user/register', 			    'admin/UserController/register_form');
Route::post('admin/user/register_submit', 		'admin/UserController/register_form_submit');
Route::get('admin/user/logout', 			    'admin/UserController/logout');


//频道
//Route::get('admin/channel/add', 			'admin/ChannelController/add_form');
//Route::post('admin/channel/add_submit', 	'admin/ChannelController/add_form_submit');
//Route::get('admin/channel/edit', 			'admin/ChannelController/edit_form');
//Route::post('admin/channel/edit_submit', 	'admin/ChannelController/edit_form_submit');
//Route::get('admin/channel/list', 			'admin/ChannelController/index');
//Route::get('admin/channel/info', 			'admin/ChannelController/info');
//Route::get('admin/channel/delete', 		    'admin/ChannelController/delete');
//Route::get('admin/channel/list_data', 		'admin/ChannelController/index_data');

//栏目
//Route::get('admin/category/add', 			'admin/CategoryController/add_form');
//Route::post('admin/category/add_submit', 	'admin/CategoryController/add_form_submit');
//Route::get('admin/category/edit', 			'admin/CategoryController/edit_form');
//Route::post('admin/category/edit_submit', 	'admin/CategoryController/edit_form_submit');
//Route::get('admin/category/list', 			'admin/CategoryController/index');
//Route::get('admin/category/info', 			'admin/CategoryController/info');
//Route::get('admin/category/delete', 		'admin/CategoryController/delete');
//Route::get('admin/category/json_data', 		'admin/CategoryController/json_data');
//Route::get('admin/category/list_data', 		'admin/CategoryController/index_data');
//Route::post('admin/category/get_category',   'admin/CategoryController/get_category');

//容器类型
Route::get('admin/vocabulary/add', 			    'admin/VocabularyController/add_form');
Route::post('admin/vocabulary/add_submit', 	    'admin/VocabularyController/add_form_submit');
Route::get('admin/vocabulary/edit', 			'admin/VocabularyController/edit_form');
Route::post('admin/vocabulary/edit_submit', 	'admin/VocabularyController/edit_form_submit');
Route::get('admin/vocabulary/list', 			'admin/VocabularyController/index');
Route::get('admin/vocabulary/info', 			'admin/VocabularyController/info');
Route::get('admin/vocabulary/delete', 		    'admin/VocabularyController/delete');
Route::get('admin/vocabulary/list_data', 		'admin/VocabularyController/index_data');

//分类
Route::get('admin/taxonomy/add', 			    'admin/TaxonomyController/add_form');
Route::post('admin/taxonomy/add_submit', 	    'admin/TaxonomyController/add_form_submit');
Route::get('admin/taxonomy/edit', 			    'admin/TaxonomyController/edit_form');
Route::post('admin/taxonomy/edit_submit', 	    'admin/TaxonomyController/edit_form_submit');
Route::get('admin/taxonomy/list', 			    'admin/TaxonomyController/index');
Route::get('admin/taxonomy/info', 			    'admin/TaxonomyController/info');
Route::get('admin/taxonomy/delete', 		    'admin/TaxonomyController/delete');
Route::get('admin/taxonomy/json_data', 		    'admin/TaxonomyController/json_data');
Route::get('admin/taxonomy/list_data', 		    'admin/TaxonomyController/index_data');
Route::post('admin/taxonomy/get_taxonomy',      'admin/TaxonomyController/get_taxonomy');
Route::post('admin/taxonomy/ajax_update_status','admin/TaxonomyController/ajax_update_status');

//内容
Route::get('admin/article/add', 			'admin/ArticleController/add_form');
Route::post('admin/article/add_submit', 	'admin/ArticleController/add_form_submit');
Route::get('admin/article/edit', 			'admin/ArticleController/edit_form');
Route::post('admin/article/edit_submit', 	'admin/ArticleController/edit_form_submit');
Route::get('admin/article/list', 			'admin/ArticleController/index');
Route::get('admin/article/info', 			'admin/ArticleController/info');
Route::get('admin/article/delete', 		    'admin/ArticleController/delete');
Route::get('admin/article/list_data', 		'admin/ArticleController/index_data');
Route::post('admin/article/get_favicon',    'admin/ArticleController/get_favicon');
Route::get('admin/article/check_url', 		'admin/ArticleController/check_url_exist');
Route::get('admin/article/copy', 			'admin/ArticleController/copy_form');
Route::post('admin/article/copy_submit', 	'admin/ArticleController/copy_form_submit');
Route::post('admin/article/copy_delete', 			'admin/ArticleController/copy_delete');
//网站申请
Route::get('admin/site_apply/list', 			'admin/SiteApplyController/index');
Route::get('admin/site_apply/info', 			'admin/SiteApplyController/info');
Route::get('admin/site_apply/delete', 		    'admin/SiteApplyController/delete');
Route::get('admin/site_apply/list_data', 		'admin/SiteApplyController/index_data');
Route::get('admin/site_apply/verify', 			    'admin/SiteApplyController/verify_form');
Route::post('admin/site_apply/verify_submit', 	    'admin/SiteApplyController/verify_form_submit');

//好站推荐
Route::get('admin/recommend/add', 			    'admin/RecommendController/add_form');
Route::post('admin/recommend/add_submit', 	    'admin/RecommendController/add_form_submit');
Route::get('admin/recommend/edit', 			    'admin/RecommendController/edit_form');
Route::post('admin/recommend/edit_submit', 	    'admin/RecommendController/edit_form_submit');
Route::get('admin/recommend/list', 			    'admin/RecommendController/index');
Route::get('admin/recommend/info', 			    'admin/RecommendController/info');
Route::get('admin/recommend/delete', 		    'admin/RecommendController/delete');
Route::get('admin/recommend/list_data', 		'admin/RecommendController/index_data');

//文章类型
Route::get('admin/article_type/add', 			'CategoryController/add_form');
Route::post('admin/article_type/add_submit', 	'CategoryController/add_form_submit');
Route::get('admin/article_type/edit', 			'CategoryController/edit_form');
Route::post('admin/article_type/edit_submit', 	'CategoryController/edit_form_submit');
Route::get('admin/article_type/list', 			'CategoryController/index');
Route::get('admin/article_type/info', 			'CategoryController/info');
Route::get('admin/article_type/delete', 		'CategoryController/delete');

//用户
Route::get('admin/user/add', 			        'admin/UserController/add_form');
Route::post('admin/user/add_submit', 	        'admin/UserController/add_form_submit');
Route::get('admin/user/edit', 			        'admin/UserController/edit_form');
Route::post('admin/user/edit_submit', 	        'admin/UserController/edit_form_submit');
Route::get('admin/user/list', 			        'admin/UserController/index');
Route::get('admin/user/info', 			        'admin/UserController/info');
Route::get('admin/user/delete', 		        'admin/UserController/delete');
Route::get('admin/user/json_data', 		        'admin/UserController/json_data');
Route::get('admin/user/list_data', 		        'admin/UserController/index_data');
Route::post('admin/user/ajax_update_status',    'admin/UserController/ajax_update_status');

//用户
Route::get('admin/friend_link/add', 			        'admin/FriendlinkController/add_form');
Route::post('admin/friend_link/add_submit', 	        'admin/FriendlinkController/add_form_submit');
Route::get('admin/friend_link/edit', 			        'admin/FriendlinkController/edit_form');
Route::post('admin/friend_link/edit_submit', 	        'admin/FriendlinkController/edit_form_submit');
Route::get('admin/friend_link/list', 			        'admin/FriendlinkController/index');
Route::get('admin/friend_link/info', 			        'admin/FriendlinkController/info');
Route::get('admin/friend_link/delete', 		            'admin/FriendlinkController/delete');
Route::get('admin/friend_link/json_data', 		        'admin/FriendlinkController/json_data');
Route::get('admin/friend_link/list_data', 		        'admin/FriendlinkController/index_data');
Route::post('admin/friend_link/ajax_update_status',     'admin/FriendlinkController/ajax_update_status');

//角色
Route::get('admin/role/add', 			        'admin/RoleController/add_form');
Route::post('admin/role/add_submit', 	        'admin/RoleController/add_form_submit');
Route::get('admin/role/edit', 			        'admin/RoleController/edit_form');
Route::post('admin/role/edit_submit', 	        'admin/RoleController/edit_form_submit');
Route::get('admin/role/list', 			        'admin/RoleController/index');
Route::get('admin/role/info', 			        'admin/RoleController/info');
Route::get('admin/role/delete', 		        'admin/RoleController/delete');
Route::get('admin/role/json_data', 		        'admin/RoleController/json_data');
Route::get('admin/role/list_data', 		        'admin/RoleController/index_data');
Route::post('admin/role/ajax_update_status',    'admin/RoleController/ajax_update_status');

//菜单
Route::get('admin/menu/add', 			        'admin/MenuController/add_form');
Route::post('admin/menu/add_submit', 	        'admin/MenuController/add_form_submit');
Route::get('admin/menu/edit', 			        'admin/MenuController/edit_form');
Route::post('admin/menu/edit_submit', 	        'admin/MenuController/edit_form_submit');
Route::get('admin/menu/list', 			        'admin/MenuController/index');
Route::get('admin/menu/info', 			        'admin/MenuController/info');
Route::get('admin/menu/delete', 		        'admin/MenuController/delete');
Route::get('admin/menu/json_data', 		        'admin/MenuController/json_data');
Route::get('admin/menu/list_data', 		        'admin/MenuController/index_data');
Route::post('admin/menu/ajax_update_status',    'admin/MenuController/ajax_update_status');

//权限
Route::get('admin/permission/list', 			        'admin/PermissionController/index');
Route::get('admin/permission/list_data', 		        'admin/PermissionController/index_data');
Route::post('admin/permission/list_submit', 	        'admin/PermissionController/list_setup_submit');
Route::post('admin/permission/ajax_update_status',      'admin/PermissionController/ajax_update_status');

//系统设置
Route::get('admin/system/index_setup', 			    'admin/SystemController/index_setup');
Route::post('admin/system/index_setup_submit', 	    'admin/SystemController/index_setup_submit');
Route::get('admin/system/sitemap_setup', 			'admin/SystemController/sitemap_setup');
Route::post('admin/system/sitemap_setup_submit', 	'admin/SystemController/sitemap_setup_submit');
Route::get('admin/system/site_setup', 			    'admin/SystemController/site_setup');
Route::post('admin/system/site_setup_submit', 	    'admin/SystemController/site_setup_submit');



