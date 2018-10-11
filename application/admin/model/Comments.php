<?php
namespace app\admin\model;

use think\Model;

class Comments extends Model{
    public $table = 'tf_comments';
    public $pk = 'id';
    public static $jsonArr = ["images"];
}