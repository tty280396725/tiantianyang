<?php
namespace app\admin\model;

use think\Model;

class RolePermissionRelation extends Model{
    public $table = 'tf_admin_role_permission_relation';
    public $pk = 'id';
    //关闭自动时间写入
    protected $autoWriteTimestamp=false;
}