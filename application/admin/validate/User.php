<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate{
    //规则
    protected $rule = [
        "id"        =>  "number",
        "account"   =>  "require",
        "password"  =>  "require",
        "password_original" =>  "require",
        "name"      =>  "require",
        "avatar"    =>  "require",
        "role_id"   =>  "number",
        "status"    =>  "in:1,2",
     ];

     protected $message =   [
         "id.number"        =>  "参数错误，请刷新重试",
         "account.require"  =>  "请输入账号",
         "password.require" =>  "缺少密码",
         "password_original.require"    =>  "请输入密码",
         "name.require"     =>  "请输入名称",
         "avatar.require"   =>  "请设置头像",
         "role_id.number"   =>  "角色id参数错误，请刷新重试",
         "status.in"        =>  "状态参数错误，请刷新重试",
     ];

     //场景
    protected $scene    =   [
        "create" =>  [
            "id","account","password","password_original","name","avatar","role_id","status",
        ],
        "edit"  =>  [
            "id","name","avatar","role_id","status"
        ]

    ];
}