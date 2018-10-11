<?php
namespace app\admin\controller;

use think\Db;
use think\Exception;
use think\Request;

class Role extends Base{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $list = model("Roles")
            ->field(["id","name"])
            ->paginate('',false,page_param());
        $this->assign(compact("list"));
        return $this->fetch();
    }

    public function create(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $list = [];
        $this->assign(compact("list"));
        return $this->fetch();
    }

    public function edit(Request $request){
        if($request->isPost()){
            return $this->store($request);
        }
        $params = [
            "id"    =>  input("route.id/d",0),
        ];
        $info = model("Roles")->get($params["id"]);
        $where= [
            "role_id"   =>  $params["id"],
        ];
        $list = model("RolePermissionRelation")->where($where)->column("permission_id");
        $this->assign(compact("info","list"));
        return $this->fetch('create');
    }

    private function store(Request $request){
        $params = $request->only([
            "id","name","relation"
        ],"post");
        $permissionValidate =   model("Role","validate");
        if(!$permissionValidate->scene("store")->check($params)){
            return $this->ajaxReturn(1,$permissionValidate->getError());
        }
        Db::startTrans();
        try {
            $role = model("Roles");
            $params["id"] && $role->isUpdate(true);
            $role->allowField(true)->save($params);
            if($params["id"]) {
                model("RolePermissionRelation")
                    ->where("role_id", $role["id"])
                    ->delete();
            }
            $addArr = [];
            if (!empty($params["relation"])) {
                foreach ($params["relation"] as $v) {
                    $v = intval($v);
                    if ($v) {
                        $addArr[] = [
                            "role_id" => $role["id"],
                            "permission_id" => $v,
                            "u_time"  => date("Y-m-d H:i:s"),
                        ];
                    }
                }
            }
            if ( !empty($addArr) ) {
                model("RolePermissionRelation")->saveAll($addArr);
            }
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            return $this->ajaxReturn(1,"操作失败，请刷新重试");
        }
        return $this->ajaxReturn(0,"", url(CONTROLLER_NAME."/index"));
    }

    public function delete(){
        $params =   [
            "id"    =>  input("post.id/d",0),
        ];
        model("Roles")->where("id",$params["id"])->delete();
        return $this->ajaxReturn();
    }
}
