<?php
namespace app\admin\validate;

use think\Validate;

class Permission extends Validate{
    //规则
    protected $rule = [
         "id"       =>  "number",
         "pid"      =>  "require|number",
         "title"    =>  "require",
         "sort"     =>  "number",
         "status"   =>  "in:0,1",
     ];

     protected $message =   [
         "title.require"   =>  "请输入权限名称",
         "sort.number"     =>  "请输入正确的排序值",
         "status.number"   =>  "请重新选择是否在左侧导航显示",
     ];

     //场景
    protected $scene    =   [
        "store" =>  [
            "id","pid","title","sort","status"
        ]
    ];
}