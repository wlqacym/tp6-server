<?php


namespace app\service\db;


use app\BaseDbService;
use think\Exception;

class AdminDbSer extends BaseDbService
{

    /**
     * 获取登陆账户信息
     *
     * @return mixed
     * @throws Exception
     *
     * @author  wlq
     * @since   v1.0    20200603
     */
    public function loginCheck()
    {
        $app = app();
        $token = $app->request->header('access-token');
        !$token and $token = $app->request->get('token');
        if (!$token) {
            throw new Exception('缺少token', 400);
        }
        $userInfo = cache('admin_login_'.$token);
        if (!$userInfo) {
            throw new Exception('登陆超时，请重新登陆', 300);
        }
        return $userInfo;
    }

    /**
     * @throws Exception
     */
    public function updateTimeOut()
    {
        $app = app();
        $token = $app->request->header('access-token');
        $expireTime = $app->config->get('api.expire_time');
        $userInfo = $this->loginCheck();
        cache('admin_login_'.$token, $userInfo, $expireTime);
    }
}