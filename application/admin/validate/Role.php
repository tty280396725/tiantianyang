<?php
namespace app\admin\validate;

use think\Validate;

class Role extends Validate{
    //规则
    protected $rule = [
         "id"   =>  "require|number",
         "name" =>  "require",
     ];

     protected $message =   [
         "id.require"   =>  "参数错误，请刷新重试",
         "id.number"    =>  "参数错误，请刷新重试",
         "name.require" =>  "请输入角色名称",
     ];

     //场景
    protected $scene    =   [
        "store" =>  [
            "id","name"
        ]
    ];
}