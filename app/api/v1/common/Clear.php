<?php
declare (strict_types = 1);

namespace app\api\v1\common;

use app\api\Base;

class Clear extends Base
{

    public function cache($name)
    {
        $res = cache($name, NULL);
        return $this->success('清除成功');
    }
}
