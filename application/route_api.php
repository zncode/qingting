<?php
use think\Route;
//上传
Route::post('upload/image',                     'api/UploadController/image');
Route::post('upload/image_editor',              'api/UploadController/image_editor');
Route::get('upload/kindeditor_file_manager',    'api/UploadController/kindeditor_file_manager');
Route::post('upload/image_normal',              'api/UploadController/image_no_db');
Route::get('api/get_html_info',                 'api/IndexController/get_html_info');
Route::get('api/phpinfo',                       'api/IndexController/phpinfo');
Route::get('api/create_capcha',                 'api/IndexController/create_capcha');
Route::get('api/get_token',                     'api/IndexController/get_token');
Route::post('api/counter',                       'api/IndexController/counter');

