<?php
namespace app\index\controller;

use think\Config;
use think\Exception;
use think\Request;
use Aliyun\SignatureHelper;

class Codes extends Base{

    //发送短信
    private function send_note($mobile=0,$type=0,$content=[]){
        if(!$mobile || !$type){
            return $this->returnArr([],1,"缺少参数");
        }
        $result = $this->returnArr([],1,"初始化");
        $noteArr= Config::get("note");
        //如果短信发送失败，循环使用短信服务商继续发送短信
        foreach($noteArr as $noteType=>&$note){
            if($result["err"]){
                switch($noteType){
                    case "aliyun":
                        //使用阿里大鱼发送短信
                        //设置接收者手机号
                        $note["PhoneNumbers"] = $mobile;
                        if(!isset($note["TemplateCodeArr"][$type])){
                            $result = $this->returnArr([],5,"没有可使用的短信模板");
                            continue;
                        }
                        //设置短信模板
                        $note["TemplateCode"] = $note["TemplateCodeArr"][$type];
                        unset($note["TemplateCodeArr"]);
                        //设置模板参数
                        $note["TemplateParam"]["code"] = $content["code"];
                        //处理额外参数
                        if(!empty($note["TemplateParam"]) && is_array($note["TemplateParam"])) {
                            $note["TemplateParam"] = json_encode($note["TemplateParam"], JSON_UNESCAPED_UNICODE);
                        }
                        //设置短信流水号
                        $note["OutId"]  =   date("YmdHis").$mobile;
                        $helper = new SignatureHelper();
                        try{
                            $resultALiYun = $helper->request(
                                $note["accessKeyId"],
                                $note["accessKeySecret"],
                                "dysmsapi.aliyuncs.com",
                                array_merge($note, array(
                                    "RegionId" => "cn-hangzhou",
                                    "Action" => "SendSms",
                                    "Version" => "2017-05-25",
                                ))
                            // fixme 选填: 启用https
                            // ,true
                            );
                            //阿里大鱼平台发送失败，返回的错误信息
                            /*错误示例
                             * object(stdClass)#26 (3) {
                                  ["Message"] => string(18) "账户余额不足"
                                  ["RequestId"] => string(36) "4923B175-AE1B-43AB-BC7E-2D45A505F09E"
                                  ["Code"] => string(21) "isv.AMOUNT_NOT_ENOUGH"
                                }
                             */
                            if(isset($resultALiYun->Code) && $resultALiYun->Code=="OK"){
                                $result = $this->returnArr($resultALiYun,0);
                            }else{
                                $result = $this->returnArr($resultALiYun,5,$noteType."发送失败");
                            }

                        }catch (\Exception $e){
                            $result = $this->returnArr($e,5,$noteType."SDK运行异常");
                        }
                        break;
                }
            }
        }
        return $result;
    }

    //验证短信是有效性
    public function check_code($mobile="",$code=0,$type=0){
        $code = intval($code);
        $type = intval($type);
        if(empty($mobile) || !$code || !$type){
            return $this->returnArr([],6,"缺少参数");
        }
        $where = [
            "mobile"=>  $mobile,
            "code"  =>  $code,
            "type"  =>  $type,
        ];
        $code = model("Codes")->where($where)->find();
        if(!$code){
            return $this->returnArr([],6,"验证码错误");
        }
        if($code["status"] != 1){
            return $this->returnArr([],6,"该验证码已使用");
        }$timeLength = Config::get("note_time_validate_length");
        if(strtotime($code["c_time"])+$timeLength < time()){
            return $this->returnArr([],6,"该验证码已过期");
        }
        $code->status = 0;
        $code->save();
        return $this->returnArr();
    }
}
