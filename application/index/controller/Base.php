<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller{

    public function _initialize(){
        if(session("user_info")["id"]==0){
            return $this->returnJson();
        }
    }

    //返回json
    protected  function returnJson($data=[], $err = 0, $msg = ''){
        $result = $this->returnArr($data,$err,$msg);
        return json($result);
    }

    //返回数组
    protected  function returnArr($data=[], $err = 0, $msg = ''){
        $result = [
            "err"   =>  $err,
            "msg"   =>  $this->getMsg($err, $msg),
            "data"  =>  $data,
        ];
        return $result;
    }

    public function getMsg($err,$msg){
        $msgArr = [];
        if($err){
            $msg = isset($msgArr[$err]) ? $msgArr[$err] : '失败';
        }else{
            $msg = '成功';
        }
        return $msg;
    }

    //获取用户id
    public function getUserId(){
        $userId = input("server.HTTP_ID",0);
        $userInfo = [
            "id"    =>  $userId,
        ];
        session("user_info", $userInfo);
    }
}
