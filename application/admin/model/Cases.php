<?php
namespace app\admin\model;

use think\Model;

class Cases extends Model{
    public $table = 'tf_cases';
    public $pk = 'id';
    public static $jsonArr = ["describe_images", "new_history_images", "old_history_images", "shenghua_images", "yingxiang_images", "process_images"];

    static public $type_one_list = [
        1   =>  "结石",
        2   =>  "肿瘤",
        3   =>  "前列腺",
    ];
    static public $type_list = [
        "1" =>  [
            "1" =>  "病例求助",
            "2" =>  "输尿管镜",
            "4" =>  "经皮肾镜",
            "6" =>  "多镜联合",
            "8" =>  "体外碎石",
            "10"=>  "保守治疗",
            "12"=>  "腹腔镜",
            "14"=>  "开放手术",
        ],
        "2" =>  [
            "0" =>  "分享",
            "1" =>  "病例求助",
        ],
        "3" =>  [
            "0" =>  "分享",
            "1" =>  "病例求助",
        ],
    ];

    public static $sex_list =   [
        1   =>  "男",
        2   =>  "女",
    ];

    public static $status_list = [
        1   =>  "未审核",
        2   =>  "审核通过",
        3   =>  "审核未通过",
    ];

    public static $shenghua_list = [
        0   =>  "血常规",
        1   =>  "肾功能",
        2   =>  "尿常规",
        3   =>  "尿培养",
        4   =>  "其他",
    ];

    public static $yingxiang_list = [
        0   =>  "泌尿彩照",
        1   =>  "X光片",
        2   =>  "CT",
        3   =>  "SPECT肾显像",
        4   =>  "其他",
    ];

    public function user(){
        return $this->belongsTo("ClientUsers", "user_id", "id");
    }
}