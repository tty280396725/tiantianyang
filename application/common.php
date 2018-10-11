<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//发起一个post
function curl_post($url='',$data=[]){
    $data = is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $info = curl_exec($ch);

    if(curl_errno($ch)){
        return [
            "err" => 1,
            "msg" => 'Errno'.curl_error($ch),
        ];
    }
    curl_close($ch);
    return [
        "err" => 0,
        "data"=> $info,
    ];
}

function getImageUrl($url=''){
	
	
    if( !empty($url) ){
        //$url = substr($url,0,4)=="http" ? $url : "http://".$_SERVER["HTTP_HOST"].$url;
		$url = substr($url,0,4)=="http" ? $url : "http://www.adminbase.com".$url;
    }
	
	
    return $url;
}