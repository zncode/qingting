<?php
namespace app\index\controller;

use app\index\controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        return view('index/index');
    }

    public function category_list()
    {
        return view('index/category_list');
    }

    public function resource_list()
    {
//        echo 'test';die;
        return view('index/resource_list');
    }
}
