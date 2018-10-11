<?php
namespace app\admin\controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends Base
{
    public function index()
    {
        //服务器信息配置
        $systemConfig = $this->systemConfig();
        $this->assign('systemConfig', $systemConfig);
        return $this->fetch();
    }

    //获取服务器配置信息
    private function systemConfig()
    {
        return $config = [
            '操作系统' => PHP_OS,
            '服务器时间' => date("Y-n-j H:i:s"),
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time').'秒',
        ];
    }

    public function test(){
        $this->exportExcel();
        echo "1233";exit;
        //生成excel
    }

    //导出为excel
    //https://www.helloweba.net/php/563.html
    public function exportExcel($list=""){
        $list = [
            [
                "name"  =>  "张三",
                "yuwen" =>  "12345678901234567890",
                "shuxue"=>  "12345678901234567890",
            ],
            [
                "name"  =>  "李四",
                "yuwen" =>  "98765432109876543210",
                "shuxue"=>  "98765432109876543210",
            ],
        ];
        $spreadsheet=   new Spreadsheet();
        $worksheet  =   $spreadsheet->getActiveSheet();
        //设置工作表标题
        $worksheet->setTitle("测试生成excel");
        //设置单元格
        $worksheet->setCellValueByColumnAndRow(1, 1, '学生成绩表');
        $worksheet->setCellValueByColumnAndRow(1, 2, '姓名');
        $worksheet->setCellValueByColumnAndRow(2, 2, '语文');
        $worksheet->setCellValueByColumnAndRow(3, 2, '数学');

        //合并单元格
        $worksheet->mergeCells('A1:E1');
        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'borders'   =>  [
                'allBorders'    =>  [
                    'borderStyle'   =>  Border::BORDER_THIN,
                    'color'         =>  ['argb' =>  '666666'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,//横向居中
            ],
        ];
        $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);
        $worksheet->getStyle('A2:E2')->applyFromArray($styleArray)->getFont()->setSize(14);
//        $writer= new Xlsx($spreadsheet);
        $writer=    IOFactory::createWriter($spreadsheet,"Xlsx");
        $writer->save("test.xlsx");
    }
}
