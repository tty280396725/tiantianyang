<?php
namespace app\admin\model;

use think\Model;

class Roles extends Model{
    public $table = 'tf_admin_roles';
    public $pk = 'id';
    //关闭自动时间写入
    protected $autoWriteTimestamp=false;
    //protected $createTime = 'create_time';
    //protected $updateTime = 'update_time';
}