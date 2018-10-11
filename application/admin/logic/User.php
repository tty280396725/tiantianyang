<?php
namespace app\admin\logic;

use think\Controller;

class User extends Controller{

    static public $notCheckPermissionArr = [
        "Index/index",
        "Index/test",
    ];
    //判断用户是否有该权限，ClientUser/delete,delete_one
    static public function checkHasPermission($rule){
        $hasPermissionsArr = session("hasPermissionsArr");
        if(
            !empty($hasPermissionsArr) &&
            !in_array($rule,$hasPermissionsArr) &&
            !in_array($rule,self::$notCheckPermissionArr)
        ){
            return false;
        }
        return true;
    }
}
