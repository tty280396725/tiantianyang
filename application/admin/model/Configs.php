<?php
namespace app\admin\model;

use think\Model;

class Configs extends Model{
    public $table = 'tf_configs';
    public $pk = 'id';

    //模型事件
    protected static function init(){
        //第二个参数是否覆盖的意思是return的内容如果不是false的话，是否覆盖$cases
        self::beforeUpdate(function($config){
            if(is_array($config["value"])){
                $config["value"] = json_encode($config["value"],JSON_UNESCAPED_UNICODE);
            }
        });
    }
}