<?php
use think\Route;
//上传
Route::post('upload/image',         'api/UploadController/image');
Route::post('upload/image_editor',  'api/UploadController/image_editor');

