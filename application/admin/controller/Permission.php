<?php
namespace app\admin\controller;

use app\admin\model\Permissions;
use think\Request;

class Permission extends Base{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $list = Permissions::getTree();
        $this->assign(compact("list"));
        return $this->fetch();
    }

    public function create(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $permissionList = Permissions::getTree();
        $this->assign(compact("permissionList"));
        return $this->fetch();
    }

    public function edit(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $params = [
            "id"    =>  input("route.id/d",0),
        ];
        $info = model("Permissions")->get($params["id"]);
        $where=[
            "id"    =>  ["neq",$info["id"]],
        ];
        $permissionList = Permissions::getTree($where);
        $this->assign(compact("permissionList","info"));
        return $this->fetch('create');
    }

    private function store(Request $request){
        $params = $request->only([
            "id","pid","title","icon","url","sort","status"
        ],"post");
        $permissionValidate =   model("Permission","validate");
        if(!$permissionValidate->scene("store")->check($params)){
            return $this->ajaxReturn(1,$permissionValidate->getError());
        }
        $params["url"] = ucfirst(strtolower($params["url"]));
        $obj = model("Permissions");
        $params["id"] && $obj->isUpdate(true);
        $obj->save($params);
        return $this->ajaxReturn(0,"", url("Permission/index"));
    }

    public function delete(){
        $params =   [
            "id"    =>  input("post.id/d",0),
        ];
        model("Permissions")->where("id",$params["id"])->delete();
        return $this->ajaxReturn();
    }
}
