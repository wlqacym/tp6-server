<?php
declare (strict_types = 1);

namespace app\api\v1\common;

use app\api\Base;

class Clear extends Base
{
    protected $middleware = [];
    public function cache($name)
    {
        $res = cache($name, NULL);
        return $this->success('清除成功');
    }
    public function power()
    {
        $this->logic->rule->reloadRules();
        return $this->success('重置权限缓存成功');
    }
}
