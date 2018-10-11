<?php
namespace app\admin\model;

use think\Model;

class Relations extends Model{
    public $table = 'tf_relations';
    public $pk = 'id';

    //1，点赞尿圈。3点赞病例
    static public function getLikeNumTotal($id=0,$type=0){
        if(!$id || !in_array($type,[1,3])){
            return 0;
        }
        $where  = [
            "to_id" =>  $id,
            "type"  =>  $type,
        ];
        $result = model("Relations")
            ->where($where)
            ->count();
        return $result;
    }
}