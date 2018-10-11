<?php
namespace app\admin\model;

use think\Model;

class ClientUsers extends Model{
    public $table = 'tf_users';
    public $pk = 'id';

    public static $duty_level_list = [
        1   =>  '住院医师',
        2   =>  '主治医师',
        3   =>  '副主任医师',
        4   =>  '主任医师',
    ];

    public static $status_list = [
        0   =>  "已关注公众号",
        1   =>  "正常",
        2   =>  "取消关注公众",
        3   =>  "待审核",
        4   =>  "未通过审核",
        5   =>  "禁用",
    ];

}