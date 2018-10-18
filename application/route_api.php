<?php
use think\Route;
//上传
Route::post('upload/image',                     'api/UploadController/image');
Route::post('upload/image_editor',              'api/UploadController/image_editor');
Route::get('upload/kindeditor_file_manager',    'api/UploadController/kindeditor_file_manager');

