<?php
function search_url($arr=[]){
    $url_path = '/'.request()->path();
    $get = input('get.');
    array_push($arr, "_pjax");
    if(!empty($get)){
        $paramStr = [];
        foreach ($get as $k=>$v){
            if(!in_array($k,$arr)) {
                $paramStr[] = $k . '=' . $v;
            }
        }
        $paramStrs = implode('&', $paramStr);
        $url_path = $url_path.'?'.$paramStrs;
    }
    return $url_path;
}

function page_param(){
    $param = request()->param();
    if (isset($param['_pjax'])){
        unset($param['_pjax']);
    }
    $res['query'] = $param;
    return $res;
}

//自定义生成文件方法名
//返回示例：49817531e904eca61b5917f5ebf12ffa.png
function getFileName(){
    $date = date("YmdHis");
    $time = microtime();
    $fileName = md5($date.'_'.current(explode(" ", $time)));
    return $fileName;
}

function authAction($rule, $cationType='create', $param=[]){
    if(!\app\admin\logic\User::checkHasPermission($rule,$cationType)){
        return '';
    }
    $cationTypes = [
        'create' => "<a href='".url($rule, $param)."' class='btn btn-sm btn-primary'><i class='fa fa-save'></i> 新增</a>",
        'edit' => "<a class='btn btn-primary btn-xs' href='".url($rule, $param)."'><i class='fa fa-edit'></i> 编辑</a>",
        'delete_one' => "<a class='btn btn-danger btn-xs delete-one' href='javascript:void(0);' data-url='".url($rule)."' data-id='".(isset($param['id'])?$param['id']:0)."'><i class='fa fa-trash'></i> 删除</a>",


        'create_arc' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> 新增文章</a>",
        'delete_all' => "<a class=\"btn btn-sm btn-danger delete-all\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" ><i class=\"fa fa-trash\"></i> ".lang('delete')."</a>",
        'save' => "<button type=\"submit\" class=\"btn btn-info pull-right submits\" data-loading-text=\"&lt;i class='fa fa-spinner fa-spin '&gt;&lt;/i&gt; ".lang('submit')."\">".lang('submit')."</button>",
        'auth_user' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('auth_user')."</a>",
        'auth_group' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('auth_group')."</a>",
        'agree' => "<a class=\"btn btn-success btn-xs\" onclick=\"return confirm('是否已确认给用户退完款？');\" href=\"".url($rule, $param)."\"><i class=\"fa fa-repeat\"></i> ".lang('agree')."</a>",
        'disagree' => "<a class=\"btn btn-danger btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-undo\"></i> ".lang('disagree')."</a>",
        'backup' => "<a class=\"btn btn-primary btn-sm delete-all\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-title=\"".lang('backup')."\"><i class=\"fa fa-save\"></i> ".lang('backup')."</a>",
//        'restore' => "<a class=\"btn btn-primary btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('restore')."\"><i class=\"fa fa-rotate-left\"></i> ".lang('restore')."</a>",
        'dowonload' => "<a class=\"btn btn-warning btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-download\"></i> ".lang('dowonload')."</a>",
//        'tokenapi' => "<a class=\"btn btn-danger btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('tokenapi')."\"><i class=\"fa fa-lock\"></i> ".lang('tokenapi')."</a>",
//        'generate_document' => "<a class=\"btn btn-danger btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('generate_document')."\"><i class=\"fa fa-book\"></i> ".lang('generate_document')."</a>",
        'view_document' => "<a class=\"btn btn-warning btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-book\"></i> ".lang('view_document')."</a>",
    ];
    $result = $cationTypes[$cationType];
    return $result;
}

//获取一条权限在权限列表生成一条tr的html
function getPermissionHtml($permission=[], $level=0, $params=[]){
    if(empty($permission) || !isset($permission["id"])){
        return '';
    }
    $prefixArr = [
        "",
        "&ensp;&ensp;&ensp;&ensp;|&ensp;",
        "&ensp;&ensp;&ensp;&ensp;|&ensp;&ensp;&ensp;&ensp;&ensp;├&ensp;"
    ];
    $type = isset($params["type"]) ? $params["type"] : 'tr';
    switch ($type){
        case 'option':
            $str =
                "<option "
                    ."value='".$permission['id']."' "
                    .($permission['status']?"":"disabled='disabled' ")
                    .($permission["id"]==$params["pid"]?"selected='selected' ":"")
                .">"
                    .$prefixArr[$level].$permission["title"]
                ."</option>";
            break;
        case 'tr':
        default:
            $str =
                "<tr>"
                    ."<td>".$prefixArr[$level].$permission["title"]."</td>"
                    ."<td>".$permission["sort"]."</td>"
                    ."<td>".($permission["status"]?"是":"否")."</td>"
                    ."<td>"
                        .authAction("/".MODULE_NAME."/".CONTROLLER_NAME.'/edit','edit',["id"=>$permission["id"]])
                        .authAction("/".MODULE_NAME."/".CONTROLLER_NAME.'/delete','delete_one',["id"=>$permission["id"]])
                    ."</td>"
                ."</tr>";
            break;
    }
    return $str;
}

//组成无限级数的数据
function toTree($arr=[],$id=0){
    $list = [];
    foreach($arr as $k=>$v){
        if($v["pid"] == $id){
            unset($arr[$k]);
            $v["list"] = toTree($arr, $v["id"]);
            $list[] = $v;
        }
    }
    return $list;
}

function table_sort($param){
    $url_path = '/'.request()->path();
    $faStr = 'fa-sort';
    $get = input('get.');
    if( isset($get['_pjax']) ){ unset($get['_pjax']); }

    if( isset($get['_sort']) ){   //判断是否存在排序字段
        $sortArr = explode(',', $get['_sort']);
        if ( $sortArr[0] == $param ){   //当前排序
            if ($sortArr[1] == 'asc'){
                $faStr = 'fa-sort-asc';
                $sort = 'desc';
            }elseif ($sortArr[1] == 'desc'){
                $faStr = 'fa-sort-desc';
                $sort = 'asc';
            }
            $get['_sort'] = $param.','.$sort;
        }else{   //非当前排序
            $get['_sort'] = $param.',asc';
        }
    }else{
        $get['_sort'] = $param.',asc';
    }
    $paramStr = [];
    foreach ($get as $k=>$v){
        $paramStr[] = $k.'='.$v;
    }
    $paramStrs = implode('&', $paramStr);
    $url_path = $url_path.'?'.$paramStrs;
    return "<a class=\"fa ".$faStr."\" href=\"".$url_path."\"></a>";
}
