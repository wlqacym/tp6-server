<?php
declare (strict_types=1);

namespace app\api\v1\common;

use app\api\Base;

class Swagger extends Base
{
    /**
     * 生成swaagger描述文件
     *
     * @return \think\response\Json
     *
     * create by sgm
     */
    public function create()
    {

        $path = app_path('swagger');
//        $path = $dir.'/swagger/';//扫描地址
        $swagger = \OpenApi\scan($path);
        header('Content-Type: application/x-yaml');
        $swaggerInputPath = public_path().'/swagger-ui/swagger-api-json/swagger.json';
        $res = file_put_contents($swaggerInputPath, $swagger->toYaml());
        if ($res == true) {
            return $this->success('ok');
        }
    }
}