<?php
namespace app\admin\controller;

use think\Request;

class User extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function login(Request $request)
    {
        return $this->fetch();
    }

    //接收账号密码登入账号
    public function check_login(){
        if(Request::instance()->isPost()){
            $params = input('post.');
            //if(!captcha_check($params['code'])){
                //return $this->ajaxReturn(1);
            //};
            $where  =   [
                "account"   =>  trim($params["account"]),
            ];
            $fieldsArr  =   [
                "id,name,avatar,password,role_id,status",
            ];
            $adminUser  =   model("Users")
                ->where($where)
                ->field($fieldsArr)
                ->find();
            if($adminUser == null){
                return $this->ajaxReturn(2);
            }
            if($adminUser["status"] != 1){
                return $this->ajaxReturn(3);
            }
            if($adminUser["password"] != md5($params["password"])){
                return $this->ajaxReturn(4);
            }
            $sessionUserInfo = [
                "id"        =>  $adminUser["id"],
                "name"      =>  $adminUser["name"],
                "avatar"    =>  $adminUser["avatar"],
                "role_id"   =>  $adminUser["role_id"],
            ];
            session("user_info",$sessionUserInfo);
            return $this->ajaxReturn(0,"登入成功", url('Index/index'));
        }
    }

    //退出
    public function login_out(){
        session("user_info",null);
        $this->redirect("User/login");
    }

    //管理员列表
    public function index(){
        $where = [];
        if($status = input("get.status/d",0)){
            $where["status"] = $status;
        }
        if($search = trim(input("get.search"))){
            $where["name"] = ["like","%{$search}%"];
        }
        if($s_time = input("get.s_time")){
            $where["c_time"][] = ["egt",$s_time];
        }
        if($e_time = input("get.e_time")){
            $where["c_time"][] = ["elt",$e_time];
        }
        isset($where["c_time"])&&count($where["c_time"])==1&&$where["c_time"]=$where["c_time"][0];
        $order= str_replace(","," ",input("get._sort","id,asc"));
        $list = model("Users")
            ->where($where)
            ->order($order)
            ->paginate('',false,page_param());
        $this->assign(compact("list"));
        $this->assign("hasTimepicker",1);
        return $this->fetch();
    }

    public function create(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $list = model("Roles")->select();
        $this->assign(compact("list"));
        return $this->fetch();
    }

    private function store(){
        $params = [
            "account"   =>  input("post.account"),
            "password_original" =>  input("post.password_original"),
            "name"      =>  input("post.name"),
            "role_id"   =>  input("post.role_id/d"),
            "status"    =>  input("post.status",'off'),
        ];
        $params = array_filter($params);
        $params["id"]   =   input("post.id/d",0);
        $params["status"]   =   $params["status"]=='on' ? 1 : 2;
        $params["avatar"]   =   isset($_POST["avatar"][0]) ? $_POST["avatar"][0] : "";
        if(isset($params["password_original"])) {
            $params["password"] = md5($params["password_original"]);
            $params["c_time"] = date("Y-m-d H:i:s");
        }
        $userValidate   =   model("User","validate");
        $scene  =   $params["id"] ? 'edit' : 'create';
        if(!$userValidate->scene($scene)->check($params)){
            return $this->ajaxReturn(1,$userValidate->getError());
        }
        //验证账号的唯一性
        if(isset($params["account"])){
            $user = model("Users")->where("account",$params["account"])->find();
            if($user != null){
                return $this->ajaxReturn(1,"该账号已存在");
            }
        }
        $obj = model("Users");
        if($params["id"]){
            $obj = $obj->isUpdate(true);
        }
        $obj->save($params);
        return $this->ajaxReturn(0,"添加成功", url(CONTROLLER_NAME.'/index'));
    }

    public function edit(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $params =   [
            "id"    =>  input("route.id/d",0),
        ];
        $info = model("Users")->get($params["id"]);
        $list = model("Roles")->select();
        $this->assign(compact("info","list"));
        return $this->fetch("create");
    }

    public function delete(){
        $params =   [
            "id"    =>  input("post.id/d",0)
        ];
        model("Users")->where("id",$params["id"])->delete();
        return $this->ajaxReturn();
    }
}
