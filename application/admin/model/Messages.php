<?php
namespace app\admin\model;

use think\Model;

class Messages extends Model{
    public $table = 'tf_messages';
    public $pk = 'id';
    static public $jsonArr = ["images"];
    static public $is_top_list = [
        0   =>  "否",
        1   =>  "是",
    ];

    static public $status_list = [
        2   =>  "通过",
        3   =>  "未通过",
    ];

    public function user(){
        return $this->belongsTo("ClientUsers", "user_id", "id");
    }
}