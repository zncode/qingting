<?php
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;

class CategoryController extends BaseController
{
    public $pager = 20;
    public $table = 'nj_category';
    public $url_path = 'category';
    public $module_path = 'category';
    public $module_name = '栏目';

    public function __construct()
    {
        if(input('get.url_path')){
            $this->url_path = input('get.url_path');
        }
    }

    /**
     * 列表
     */
    public function index()
    {
        $url_path = input('get.url_path');
        $pages  = Db::table($this->table)->where(array('delete'=>0, 'parent'=>0))->order('create_time desc')->paginate($this->pager);
        $render = $pages->render();
        $lists  = $pages->all();

        if(is_array($lists) && count($lists)){
            foreach($lists as $key => $value){
                $channel = Db::table('qt_channel')->where(array('id'=>$value['channel_id']))->find();
                $lists[$key]['channel_name'] = $channel['name'];
                if($value['status'] ==  1){
                    $lists[$key]['status'] = '开放';
                }
                if($value['status'] ==  2){
                    $lists[$key]['status'] = '关闭';
                }

                //查找子栏目
                $sub_category = Db::table($this->table)->where(array('delete'=>0, 'parent'=>$value['id']))->order('create_time desc')->select();
                if(is_array($sub_category) && count($sub_category)){
                    foreach($sub_category as $key1 => $value1){
                        $channel1 = Db::table($this->table)->where(array('id'=>$value1['channel_id']))->find();
                        $sub_category[$key1]['channel_name'] = $channel1['name'];
                        if($value1['status'] ==  1){
                            $sub_category[$key1]['status'] = '开放';
                        }
                        if($value1['status'] ==  2){
                            $sub_category[$key1]['status'] = '关闭';
                        }

                    }
                }
                $lists[$key]['sub_category'] = $sub_category;
            }
        }

        $data['list']           = $lists;
        $data['render']         = $render;
        $data['goback']         = url('admin/'.$this->url_path.'/add?url_path='.$url_path);
        $data['module_name']    = $this->module_name;
        $data['path']           = $this->url_path;
        $data['url_path']       = $url_path;
        return view($this->module_path.'/list', $data);
    }

    /**
     * 详情
     */
    public function info()
    {
        $id = input('get.id');
        $info = Db::table($this->table)->where(array('id'=>$id))->find();

        $channel = Db::table('zw_channel')->where(array('id'=>$info['channel_id']))->find();

        $category = Db::table('zw_category')->where(array('parent'=>$info['parent']))->find();
        $info['channel_name'] = $channel['name'];

        if(is_array($category) && count($category)){
            $info['parent'] = $category['name'];
        }else{
            $info['parent'] = null;
        }
        if($info['status'] ==  1){
            $info['status'] = '开放';
        }
        if($info['status'] ==  2){
            $info['status'] = '关闭';
        }

        $data['info'] = $info;
        $data['goback'] = url('admin/'.$this->url_path.'/list?url_path='.$this->url_path);
        $data['module_name'] = $this->module_name;
        return view($this->module_path.'/info', $data);
    }

    /**
     * 添加表单
     */
    public function add_form()
    {
        $request = request();
        $url_path = input('get.url_path');

        $channels = Db::table('zw_channel')->select();
        $categorys = Db::table('zw_category')->where(array('parent'=>0, 'delete'=>0))->select();
        $data['channels'] = $channels;

        if(is_array($categorys) && count($categorys)){
            $data['cagetory'] = $categorys;
        }else{
            $data['cagetory'] = null;
        }

        $data['goback']         = url('admin/'.$this->url_path.'/list?url_type='.$this->url_path);
        $data['action'] = url('admin/'.$url_path.'/add_submit');
        $data['module_name'] = $this->module_name;
        $data['url_path'] = $url_path;
        return view($this->module_path.'/add_form', $data);
    }

    /**
     * 添加表单提交
     */
    public function add_form_submit()
    {
        $formData = input('request.');
        $data = [
            'parent'        => $formData['parent'],
            'channel_id'    => $formData['channel_id'],
            'name'          => $formData['name'],
            'status'        => $formData['status'],
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'create_time'   => date("Y-m-d H:i:s", time()),
        ];

        $result = Db::table($this->table)->insert($data);
        if($result){
            $this->success('添加成功', 'admin/'.$formData['url_path'].'/list?url_path='.$formData['url_path']);
        }else{
            $this->error('添加失败');
        }
    }

    /**
     * 编辑表单
     */
    public function edit_form()
    {
        $request = request();
        $id = input('get.id');
        $info = Db::table($this->table)->where(array('id'=>$id))->find();

        $channels = Db::table('zw_channel')->where(array('delete'=>0))->select();
        $categorys = Db::table('zw_category')->where(array('parent'=>0, 'delete'=>0))->select();
        $data['channels'] = $channels;

        if(is_array($categorys) && count($categorys)){
            $data['cagetory'] = $categorys;
        }else{
            $data['cagetory'] = null;
        }

        $data['info']           = $info;
        $data['goback']         = url('admin/'.$this->url_path.'/list?url_path='.$this->url_path);
        $data['action']         = url('admin/'.$this->url_path.'/edit_submit');
        $data['module_name']    = $this->module_name;
        $data['url_path']       = $this->url_path;

        return view($this->module_path.'/edit_form', $data);
    }

    /**
     * 编辑表单提交
     */
    public function edit_form_submit()
    {
        $formData = input('request.');
        $id = $formData['id'];
        $data = [
            'parent'        => $formData['parent'],
            'channel_id'    => $formData['channel_id'],
            'name'          => $formData['name'],
            'status'        => $formData['status'],
            'weight'        => $formData['weight'],
            'keyword'       => $formData['keyword'],
            'description'   => $formData['description'],
            'update_time'       => date("Y-m-d H:i:s", time()),
        ];
        $result = Db::table($this->table)->where(array('id'=>$id))->update($data);
        if($result){
            $this->success('编辑成功', 'admin/'.$formData['url_path'].'/edit?id='.$id.'&url_path='.$formData['url_path']);
        }else{
            $this->error('编辑失败');
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        $id = input('get.id');
        $data = [
            'delete' => 1,
        ];
        $result = Db::table($this->table)->where('id',$id)->update($data);
        if($result){
            $this->success('删除成功', 'admin/'.$this->url_path.'/list?url_path='.$this->url_path);
        }else{
            $this->error('删除失败');
        }
    }

    public function json_data(){
        echo '{
    "msg": "",
    "code": 0,
    "data": [
        {"id":"113", "pId":0,  "name":"植物(自定义图标)","lay_icon_open":"/img/1_open.png","lay_icon_close":"/img/1_close.png"},
        {"id":"114", "pId":"113",  "name":"大叶榕(自定义图标)","lay_icon":"/img/4.png"},
        {"id":"112", "pId":null,  "name":"动物(默认单选)","lay_is_radio":true},
        {"id":"115", "pId":"112",  "name":"大笨象"},
        {"id":"1", "pId":null,  "name":"水果"},
        {"id":"101", "pId":"1","name":"苹果","lay_is_open":false},
        {"id":"102", "pId":"1", "name":"香蕉"},
        {"id":"103", "pId":"1", "name":"梨"},
        {"id":"104", "pId":"101", "name":"红富士苹果"},
        {"id":"105", "pId":"101", "name":"红星苹果"},
        {"id":"106", "pId":"101", "name":"嘎拉"},
        {"id":"107", "pId":"101", "name":"桑萨"},
        {"id":"108", "pId":"102", "name":"千层蕉（禁止多选）","lay_che_disabled":true},
        {"id":"109", "pId":"102", "name":"仙人蕉","lay_is_checked":true},
        {"id":"110", "pId":"102", "name":"吕宋蕉(禁止单选)","lay_is_checked":true,"lay_rad_disabled":true},
        {"id":"111", "pId":"1", "name":"大西瓜","lay_is_open":false},



        {"id":"1000", "pId":"111", "name":"大西瓜[1000]"},
        {"id":"1001", "pId":"111", "name":"大西瓜[1001]"},
        {"id":"1002", "pId":"111", "name":"大西瓜[1002]"},
        {"id":"1003", "pId":"111", "name":"大西瓜[1003]"},
        {"id":"1004", "pId":"111", "name":"大西瓜[1004]"},
        {"id":"1005", "pId":"111", "name":"大西瓜[1005]"},
        {"id":"1006", "pId":"111", "name":"大西瓜[1006]"},
        {"id":"1007", "pId":"111", "name":"大西瓜[1007]"},
        {"id":"1008", "pId":"111", "name":"大西瓜[1008]"},
        {"id":"1009", "pId":"111", "name":"大西瓜[1009]"},
        {"id":"1010", "pId":"111", "name":"大西瓜[1010]"},
        {"id":"1011", "pId":"111", "name":"大西瓜[1011]"},
        {"id":"1012", "pId":"111", "name":"大西瓜[1012]"},
        {"id":"1013", "pId":"111", "name":"大西瓜[1013]"},
        {"id":"1014", "pId":"111", "name":"大西瓜[1014]"},
        {"id":"1015", "pId":"111", "name":"大西瓜[1015]"},
        {"id":"1016", "pId":"111", "name":"大西瓜[1016]"},
        {"id":"1017", "pId":"111", "name":"大西瓜[1017]"},
        {"id":"1018", "pId":"111", "name":"大西瓜[1018]"},
        {"id":"1019", "pId":"111", "name":"大西瓜[1019]"},
        {"id":"1020", "pId":"111", "name":"大西瓜[1020]"},
        {"id":"1021", "pId":"111", "name":"大西瓜[1021]"},
        {"id":"1022", "pId":"111", "name":"大西瓜[1022]"},
        {"id":"1023", "pId":"111", "name":"大西瓜[1023]"},
        {"id":"1024", "pId":"111", "name":"大西瓜[1024]"},
        {"id":"1025", "pId":"111", "name":"大西瓜[1025]"},
        {"id":"1026", "pId":"111", "name":"大西瓜[1026]"},
        {"id":"1027", "pId":"111", "name":"大西瓜[1027]"},
        {"id":"1028", "pId":"111", "name":"大西瓜[1028]"},
        {"id":"1029", "pId":"111", "name":"大西瓜[1029]"},
        {"id":"1030", "pId":"111", "name":"大西瓜[1030]"},
        {"id":"1031", "pId":"111", "name":"大西瓜[1031]"},
        {"id":"1032", "pId":"111", "name":"大西瓜[1032]"},
        {"id":"1033", "pId":"111", "name":"大西瓜[1033]"},
        {"id":"1034", "pId":"111", "name":"大西瓜[1034]"},
        {"id":"1035", "pId":"111", "name":"大西瓜[1035]"},
        {"id":"1036", "pId":"111", "name":"大西瓜[1036]"},
        {"id":"1037", "pId":"111", "name":"大西瓜[1037]"},
        {"id":"1038", "pId":"111", "name":"大西瓜[1038]"},
        {"id":"1039", "pId":"111", "name":"大西瓜[1039]"},
        {"id":"1040", "pId":"111", "name":"大西瓜[1040]"},
        {"id":"1041", "pId":"111", "name":"大西瓜[1041]"},
        {"id":"1042", "pId":"111", "name":"大西瓜[1042]"},
        {"id":"1043", "pId":"111", "name":"大西瓜[1043]"},
        {"id":"1044", "pId":"111", "name":"大西瓜[1044]"},
        {"id":"1045", "pId":"111", "name":"大西瓜[1045]"},
        {"id":"1046", "pId":"111", "name":"大西瓜[1046]"},
        {"id":"1047", "pId":"111", "name":"大西瓜[1047]"},
        {"id":"1048", "pId":"111", "name":"大西瓜[1048]"},
        {"id":"1049", "pId":"111", "name":"大西瓜[1049]"},
        {"id":"1050", "pId":"111", "name":"大西瓜[1050]"},
        {"id":"1051", "pId":"111", "name":"大西瓜[1051]"},
        {"id":"1052", "pId":"111", "name":"大西瓜[1052]"},
        {"id":"1053", "pId":"111", "name":"大西瓜[1053]"},
        {"id":"1054", "pId":"111", "name":"大西瓜[1054]"},
        {"id":"1055", "pId":"111", "name":"大西瓜[1055]"},
        {"id":"1056", "pId":"111", "name":"大西瓜[1056]"},
        {"id":"1057", "pId":"111", "name":"大西瓜[1057]"},
        {"id":"1058", "pId":"111", "name":"大西瓜[1058]"},
        {"id":"1059", "pId":"111", "name":"大西瓜[1059]"},
        {"id":"1060", "pId":"111", "name":"大西瓜[1060]"},
        {"id":"1061", "pId":"111", "name":"大西瓜[1061]"},
        {"id":"1062", "pId":"111", "name":"大西瓜[1062]"},
        {"id":"1063", "pId":"111", "name":"大西瓜[1063]"},
        {"id":"1064", "pId":"111", "name":"大西瓜[1064]"},
        {"id":"1065", "pId":"111", "name":"大西瓜[1065]"},
        {"id":"1066", "pId":"111", "name":"大西瓜[1066]"},
        {"id":"1067", "pId":"111", "name":"大西瓜[1067]"},
        {"id":"1068", "pId":"111", "name":"大西瓜[1068]"},
        {"id":"1069", "pId":"111", "name":"大西瓜[1069]"},
        {"id":"1070", "pId":"111", "name":"大西瓜[1070]"},
        {"id":"1071", "pId":"111", "name":"大西瓜[1071]"},
        {"id":"1072", "pId":"111", "name":"大西瓜[1072]"},
        {"id":"1073", "pId":"111", "name":"大西瓜[1073]"},
        {"id":"1074", "pId":"111", "name":"大西瓜[1074]"},
        {"id":"1075", "pId":"111", "name":"大西瓜[1075]"},
        {"id":"1076", "pId":"111", "name":"大西瓜[1076]"},
        {"id":"1077", "pId":"111", "name":"大西瓜[1077]"},
        {"id":"1078", "pId":"111", "name":"大西瓜[1078]"},
        {"id":"1079", "pId":"111", "name":"大西瓜[1079]"},
        {"id":"1080", "pId":"111", "name":"大西瓜[1080]"},
        {"id":"1081", "pId":"111", "name":"大西瓜[1081]"},
        {"id":"1082", "pId":"111", "name":"大西瓜[1082]"},
        {"id":"1083", "pId":"111", "name":"大西瓜[1083]"},
        {"id":"1084", "pId":"111", "name":"大西瓜[1084]"},
        {"id":"1085", "pId":"111", "name":"大西瓜[1085]"},
        {"id":"1086", "pId":"111", "name":"大西瓜[1086]"},
        {"id":"1087", "pId":"111", "name":"大西瓜[1087]"},
        {"id":"1088", "pId":"111", "name":"大西瓜[1088]"},
        {"id":"1089", "pId":"111", "name":"大西瓜[1089]"},
        {"id":"1090", "pId":"111", "name":"大西瓜[1090]"},
        {"id":"1091", "pId":"111", "name":"大西瓜[1091]"},
        {"id":"1092", "pId":"111", "name":"大西瓜[1092]"},
        {"id":"1093", "pId":"111", "name":"大西瓜[1093]"},
        {"id":"1094", "pId":"111", "name":"大西瓜[1094]"},
        {"id":"1095", "pId":"111", "name":"大西瓜[1095]"},
        {"id":"1096", "pId":"111", "name":"大西瓜[1096]"},
        {"id":"1097", "pId":"111", "name":"大西瓜[1097]"},
        {"id":"1098", "pId":"111", "name":"大西瓜[1098]"},
        {"id":"1099", "pId":"111", "name":"大西瓜[1099]"},
        {"id":"1100", "pId":"111", "name":"大西瓜[1100]"}
    ],
    "count": 924,
    "is": true,
    "tip": "操作成功！"
}';
    }
}
