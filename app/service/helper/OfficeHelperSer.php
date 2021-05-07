<?php
namespace app\service\helper;

// 应用请求对象类
use app\BaseHelperService;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\Exception;

class OfficeHelperSer extends BaseHelperService
{

    /**
     * 获取导入excel数据
     *
     * @param string    $name           文件名
     * @param array     $fields         格式[0=>'field1',1=>'field2',2=>'field3',...]
     * @param boolean   $out_title      是否排除title,默认排除
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     *
     * @author wlq
     * @since 1.0 20200424
     */
    public function importExcel($name, $fields = [], $out_title = true)
    {
        $file = $this->app->request->file($name);
        setlocale(LC_ALL, 'zh_CN');  //csv中文乱码
        $inputFileType = IOFactory::identify($file);
        $excelReader = IOFactory::createReader($inputFileType);
        if ($inputFileType == 'Csv') {   //csv文件读取设置
            $excelReader->setInputEncoding('GBK');
            $excelReader->setDelimiter(',');
        }
        $phpExcel = $excelReader->load($file);
        $activeSheet = $phpExcel->getActiveSheet();
        $sheet = $activeSheet->toArray();
        if ($sheet && $out_title) {
            unset($sheet[0]);
        }
        $sheet = array_column($sheet, null);
        if ($fields) {
            foreach ($sheet as $k => &$v) {
                $vNew = [];
                foreach ($fields as $key => $val) {
                    $vNew[$val] = $v[$key]??'';
                }
                $v = $vNew;
            }
        }
        return $sheet;
    }

    /**
     * 导出excel-合并单元格版本
     *
     * @param string $name 文件名
     * @param array $title 格式['key1'=>'标题1','key2'=>'标题2','key3'=>'值3',...]
     * @param array $data 格式[['key1'=>'值1','key2'=>'值2','key3'=>'值3',...],['key1'=>'值1','key2'=>'值2','key3'=>'值3',...],...]
     * @param array $mustStringFields 强制转换字符串字段，格式['key1','key2',...]
     * @param int $fieldIsSpan 标题是否合并单元格
     * @param string $valSpan 值域合并单元格模式，''=不合并，col=合并列表，row=合并行
     * @param array $onlyValSpanField 合并单元格的列，空为全部列，valSpan=row时有效，格式['key1','key2',...]
     *
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201117
     */
    public function exportExcel($name, $title, $data, $mustStringFields = [], $fieldIsSpan = 0, $valSpan = '', $onlyValSpanField = [])
    {
        $head = array_values($title);
        $keys = array_keys($title);

        $count = count($head);  //计算表头数量
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            //获取标题字段对应的表头
            $fields = [];
            $fs = ['field' => '', 'val' => ''];
            $fe = ['field' => '', 'val' => ''];
            for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始，循环设置表头：
                $excelCol = strtoupper(chr($i));
                $field = $head[$i - 65];
                //添加数据到Spreadsheet实例
                $sheet->setCellValue($excelCol . '1', $field);
                //合并标题
                if ($fieldIsSpan) {
                    if (!$fs['field']) {//开始记录空
                        $fs = ['field' => $excelCol . '1', 'val' => $field];
                    }elseif ($fe['val'] != $field) {//结束记录值与当前值不同-更新开始记录，合并开始记录列至结束记录列
                        $sheet->mergeCells("{$fs['field']}:{$fe['field']}");
                        $fs = ['field' => $excelCol . '1', 'val' => $field];
                    } elseif ($i - 64 == $count) {//最后一条记录-合并开始记录列至结束记录列
                        $fe = ['field' => $excelCol . '1', 'val' => $field];
                        $sheet->mergeCells("{$fs['field']}:{$fe['field']}");
                    }
                    //更新结束记录
                    $fe = ['field' => $excelCol . '1', 'val' => $field];
                }
                $fields[$keys[$i - 65]] = $excelCol;
            }
            /*--------------开始从$data提取信息插入Excel表中------------------*/
            $log = [];
            $rowCount = count($data);
            foreach ($data as $key => $item) {             //循环设置单元格：
                //$key+2,因为第一行是表头，所以写到表格时   从第二行开始写
                $fs = ['field' => '', 'val' => ''];
                $fe = ['field' => '', 'val' => ''];
                foreach ($item as $k => $v) {
                    if (!isset($fields[$k])) {
                        continue;
                    }
                    $sheet->getColumnDimension($fields[$k])->setWidth(20); //固定列宽
                    //添加数据到Spreadsheet实例
                    if (in_array($k, $mustStringFields)) {
                        $sheet->getCell($fields[$k] . ($key + 2))->setValueExplicit($v, DataType::TYPE_STRING);
                    } else {
                        $sheet->getCell($fields[$k] . ($key + 2))->setValue($v);
                    }
                    if ($valSpan == '') {
                        continue;
                    } elseif ($valSpan == 'col') {//合并列
                        //合并标题
                        if (!$fs['field']) {//开始记录空
                            $fs = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                        }elseif ($fe['val'] != $v) {//结束记录值与当前值不同-更新开始记录，合并开始记录列至结束记录列
                            $sheet->mergeCells("{$fs['field']}:{$fe['field']}");
                            $fs = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                        } elseif ($i - 64 == $count) {//最后一条记录-合并开始记录列至结束记录列
                            $fe = ['field' => $fields[$k] . ($key + 2) . '1', 'val' => $v];
                            $sheet->mergeCells("{$fs['field']}:{$fe['field']}");
                        }
                        //更新结束记录
                        $fe = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                    } elseif ($valSpan == 'row') {//合并行
                        //合并单元格的列不为空且当前$k不在合并单元格列中，结束当前循环
                        if ($onlyValSpanField and !in_array($k, $onlyValSpanField)) {
                            continue;
                        }
                        if (!isset($log[$fields[$k]])) {//$fields[$k]列记录不存在
                            $log[$fields[$k]]  = [
                                's' => ['field' => $fields[$k] . ($key + 2), 'val' => $v],
                            ];
                        } elseif ($log[$fields[$k]]['e']['val'] != $v) {//$fields[$k]列中结束行记录值与当前值不同，合并$fields[$k]列中开始行记录与结束行记录
                            $sheet->mergeCells("{$log[$fields[$k]]['s']['field']}:{$log[$fields[$k]]['e']['field']}");
                            $log[$fields[$k]]['s'] = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                        } elseif ($rowCount == $key + 1) {//当前行是最后一行时，合并$fields[$k]列中开始行记录与结束行记录
                            $log[$fields[$k]]['e'] = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                            $sheet->mergeCells("{$log[$fields[$k]]['s']['field']}:{$log[$fields[$k]]['e']['field']}");
                        }
                        //更新$fields[$k]列中结束行记录
                        $log[$fields[$k]]['e'] = ['field' => $fields[$k] . ($key + 2), 'val' => $v];
                    }
                }
            }
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
        header('Accept-Ranges: bytes');
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            //删除清空：
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
        exit;
    }
}
