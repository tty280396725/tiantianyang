<?php

//获取limit数，统一整个项目，每页10条数据
function getLimitStr(){
    $page = input("get.page", 1);
    $num  = 10;
    $limitStr = sprintf("%u,%u",($page-1)*$num, $num);
    return $limitStr;
}

//过长的内容替换成省略号
function replaceToEllipsis($str, $start=0, $length=70, $charset="utf-8", $suffix=true){
    if (function_exists("mb_substr")) {
        if (mb_strlen($str) > $length) {
            $str = mb_substr($str, $start, $length, $charset);
            $str .= $suffix ? '...' : '';
            return $str;
        }
        return $str;
    } elseif (function_exists('iconv_substr')) {
        if (strlen($str) > $length) {
            $str = iconv_substr($str, $start, $length, $charset);
            $str .= $suffix ? '...' : '';
            return $str;
        }
        return $str;
    }
    return $str;
}


