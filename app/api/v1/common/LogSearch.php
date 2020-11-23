<?php
declare (strict_types=1);

namespace app\api\v1\common;

use app\api\Base;

class LogSearch extends Base
{
    public function getLogList($type, $ym, $d)
    {
        $page = $this->request->get('page', 0);
        $size = $this->request->get('size', 10);
        $fileName = runtime_path('log') . "{$type}/{$ym}/{$d}.log";
        if (file_exists($fileName)) {
            $str = file_get_contents($fileName);
            $str = str_replace(array("\r", "\n"), "", $str);
            $data = '[' . str_replace('}{', '},{', $str) . ']';
            $data = json_decode($data, true);
            $total = count($data);
            $rows = [];
            for ($i = 0;$i < $size; $i++) {
                if (isset($data[$i + $page*$size])) {
                    $val = $data[$i + $page*$size];
                    $msg = json_decode($val['msg'], true);
                    json_last_error() == JSON_ERROR_NONE and $val['msg'] = $msg;
                    $rows[] = $val;
                }
            }
            return $this->json(['rows' => $rows, 'total' => $total]);
        }
        return $this->json(['code' => 400, 'msg' => '日志不存在']);
    }
}
