<?php
/**指定5.0.*版本安装tp5
 * composer create-project topthink/think=5.0.* tp5_test --prefer-dist
 * 因为5.1版里request()->module()，$request->controller()，$request->action()获取不到当前的模块，控制器，方法名
 */
namespace app\admin\controller;

use app\admin\model\Permissions;
use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize(){

        $request = Request::instance();
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', $request->controller());
        define('ACTION_NAME', $request->action());
        //判断是否登入
        $userInfo = session("user_info");
        if(CONTROLLER_NAME!='User' && ACTION_NAME!='login'){
            if ($userInfo == null) {
                $this->redirect("User/login");
            }
        }

        //是否是pjax
        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);

        //获取用户拥有权限的权限节点列表
        $hasPermissionsArr = [];
        if($userInfo["role_id"]) {
            $hasPermissionsArr = model("RolePermissionRelation")
                ->alias("rpr")
                ->join("admin_permissions p","p.id=rpr.permission_id")
                ->where("rpr.role_id", $userInfo["role_id"])
                ->column("p.url","p.id");
        }
        session("hasPermissionsArr",$hasPermissionsArr);
        $this->assign("hasPermissionsArr",$hasPermissionsArr);

        //判断用户是否有正在运行的方法的权限
        if( !\app\admin\logic\User::checkHasPermission(CONTROLLER_NAME."/".ACTION_NAME) ){
            return $this->ajaxReturn(1,"无权限");
        }

	
        //全部的权限节点列表
        $treeMenu = $this->treeMenu();
        $this->assign('treeMenu', $treeMenu);

    }

    //获取左侧导航列表
    public function treeMenu(){
        // $treeMenu = cache('DB_TREE_MENU_'.UID);
        $treeMenu = Permissions::getTree();
        // cache('DB_TREE_MENU_'.UID, $treeMenu);
        return $treeMenu;
    }


    public function ajaxReturn($err=0, $msg='', $url='', $data = []){
        $result = [
            "err"   =>  $err,
            "msg"   =>  $this->getMsg($err, $msg),
            "url"   =>  $url,
            "data"  =>  $data,
        ];
        return json($result);
    }

    private function getMsg($err, $msg){
        if(!empty($msg)){
            return $msg;
        }else{
            $errList = [
                0   =>  "成功",
                1   =>  "请输入正确的验证码",
                2   =>  "请输入正确的账号",
                3   =>  "账号异常",
                4   =>  "请输入正确的密码",
            ];
            return isset($errList[$err]) ? $errList[$err] : '';
        }
    }
}
