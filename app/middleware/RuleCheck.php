<?php
declare (strict_types = 1);

namespace app\middleware;

use app\middle\service\Db;
use app\service\db\AdminDbSer;
use app\service\helper\PowerHelperSer;
use think\Exception;

class RuleCheck
{
    /**
     * 处理请求
     *
     * @param $request
     * @param \Closure $next
     * @return mixed|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle($request, \Closure $next)
    {
        $power = new PowerHelperSer();
        $db = new Db();
        $power->setAttrDb($db);
        $power->initAttrRequest();
        $power->initAttrLoginInfo();
        $powerCheck = $power->checkPower();
        if (!$powerCheck['status']) {
            return json(['code' => $powerCheck['code']??400, 'msg' => $powerCheck['msg'], 'desc' => $powerCheck['msg']], 400);
        }
        //更新过期时间
        try {
            $db->admin->updateTimeOut();
        } catch (Exception $e) {}
        //
        return $next($request);
    }
}
