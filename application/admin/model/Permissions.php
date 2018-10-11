<?php
namespace app\admin\model;

use think\Model;

class Permissions extends Model{
    public $table = 'tf_admin_permissions';
    public $pk = 'id';
    //关闭自动时间写入
    protected $autoWriteTimestamp=false;
    //protected $createTime = 'create_time';
    //protected $updateTime = 'update_time';

    //where查询条件
    public static function getTree($where=[]){
        $list = model("Permissions");
        !empty($where) && $list = $list->where($where);
        $list = $list->order("sort desc,id asc")->select();
        foreach($list as &$v){
            $v = $v->toArray();
        }
        $result = toTree($list);
        return $result;
    }
}