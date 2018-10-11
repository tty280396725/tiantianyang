<?php
namespace app\admin\controller;

use think\Request;
use \app\index\controller\Wechat;

class ClientUser extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $where  =   [
            "status"    =>  ["gt",0],
        ];
        if($search = input("get.search")){
            $where["name|mobile"]  =   ["like", "%".$search."%"];
        }
        if($status = input("get.status")){
            $where["status"]  =    $status;
        }
        $fieldsArr  =   [
            "id","avatar","name","mobile","province_name","city_name",
            "county_name","hospital_name","duty_level","status"
        ];
        $users  =   model("ClientUsers")
            ->where($where)
            ->field($fieldsArr)
            ->order("id","desc")
            ->paginate('',false,page_param());
        $this->assign("data",$users);
        $placeHolderSearch = "姓名或手机号";
        $this->assign("placeHolderSearch",$placeHolderSearch);
        return $this->fetch();
    }

    public function edit(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $id =   input("route.id",0);
        $where  =   [
            "id"    =>  $id,
        ];
        $user   =   model("ClientUsers")
            ->where($where)
            ->find();
        $this->assign("info",$user);
        return $this->fetch();
    }

    private function store(Request $request){
        $params =   $request->only(["id","grade_num","error_msg","status"]);
        model("ClientUsers")->isUpdate(true)->save($params);
        $this->send($params["id"]);
        //发送微信模板通知
        return $this->ajaxReturn(0,'',url('ClientUser/index'));
    }

    //发送模板消息
    public function send($id=0) {
        $user = model("ClientUsers")->where(["id"=>$id])->find();
        if(!in_array($user["status"], [1,4])){
            return ["err" => 1, "msg"=>"不需要发送微信模板",];
        }
        $accessToken = (new Wechat())->getAccessToken($user["id"]);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accessToken";  
        switch($user["status"]){
            case 1:
                $value = "您好,您的账号申请审核已通过";
                $jieguo= "审核通过";
                $remark= "感谢您的使用";
                break;
            case 4:
                $value = "您好,您的账号申请审核未通过";
                $jieguo= "审核未通过";
                $remark= "请您修改您的注册信息";
                break;
            case 2:
            case 3:
            case 5:
            default:
                break;
        }
        $data = [
            "touser"      => $user["wechat_openid"],
            "template_id" => "pvjhCHN5-sBETPOX5J3clsTpZRXTObNaeEbHUpN-Gec",
            "url"         => "",
            "data"  =>  [
                "first"  =>  [
                    "value"  =>  $value,
                    "color"  =>  "#173177",
                ],
                "keyword1"  =>  [
                    "value"  =>  $user["u_time"],
                    "color"  =>  "#173177",
                ],
                "keyword2"  =>  [
                    "value"  =>  $jieguo,
                    "color"  =>  "#173177",
                ],
                "remark"  =>  [
                    "value"  =>  $remark,
                    "color"  =>  "#173177",
                ],
            ],
        ];
        $result = curl_post($url, $data);  
        return $result;
    }

    //删除用户
    public function delete(Request $request){
        $params =   [
            "id"    =>  input("post.id/d",0),
        ];
        model("ClientUsers")->where($params)->delete();
        return $this->ajaxReturn();
    }
}
