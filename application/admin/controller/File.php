<?php
namespace app\admin\controller;

use think\Controller;
use think\Exception;
use think\Request;

class File extends Controller
{
    //目录
    private $upload_path = "uploads";

    //验证条件
    private $validate = [
//        "size"  =>  2097152,    //2*1024*1024
//        "ext"   =>  "jpg,jpeg,png"
    ];

    //自定义生成文件方法名
    private $getFileName    =   "getFileName";

    public function store(Request $request){
        $file = $request->file("file");
        if($file==null) {
            return json([
                "err"   =>  1,
                "error" =>  "没有文件上传",
            ]);
        }
        return json($this->upload($file));
    }

    //保存文件
    private function upload($file){
        $this->upload_path  .=  DS.date("Y").DS.date("m").DS.date("d");
        $fileNameOld = time().'_'.$file->getInfo()['name'];
        try {
            $info = $file->validate($this->validate)
//                ->rule($this->getFileName)
                //第三个参数为false,则不允许覆盖上传
                ->move($this->upload_path, $fileNameOld, false);
            //如果info为false，则服务器上存在同名文件
            $fileUrl = $info==false ? DS.$this->upload_path.DS.$fileNameOld : DS.$this->upload_path.DS.$info->getSaveName();
            $data = [
                "saveName"  =>  getImageUrl($fileUrl),
            ];
            return [
                "err"   =>  0,
                "msg"   =>  "成功",
                "url"   =>  "",
                "data"  =>  $data,
            ];
        }catch (Exception $e){
            return [
                "err"   =>  1,
                "msg"   =>  $e->getMessage(),
                "error" =>  $e->getMessage(),
            ];
        }
    }
}
