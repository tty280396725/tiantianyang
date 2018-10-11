<?php
namespace app\admin\model;

use think\Model;

class Users extends Model{
    public $table = 'tf_admin_users';
    public $pk = 'id';

    const STATUS_NORMAL =   1;
    const STATUS_ERROR  =   2;

    static public $status_list = [
        self::STATUS_NORMAL =>  "正常",
        self::STATUS_ERROR  =>  "异常",
    ];
}
