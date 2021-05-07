<?php


namespace app\service\helper;

use app\BaseHelperService;
use think\Exception;
use think\exception\HttpException;

class AdminHelperSer extends BaseHelperService
{
    /**
     * 获取登陆账户信息
     *
     * @return mixed
     * @throws Exception
     *
     * @codeCoverageIgnore
     *
     * @author  wlq
     * @since   v1.0    20200603
     */
    public function loginInfo()
    {
        $token = $this->request->header('access-token');
        !$token and $token = $this->request->get('token');
        if (!$token) {
            throw new Exception('缺少token', 400);
        }
        $userInfo = cache('admin_login_'.$token);
        if (!$userInfo) {
            throw new Exception('登陆超时，请重新登陆', 400);
        }
        return $userInfo;
    }

    /**
     * @throws Exception
     */
    public function updateTimeOut()
    {
        $token = $this->request->header('access-token');
        $expireTime = $this->app->config->get('api.expire_time');
        $userInfo = $this->loginInfo();
        cache('admin_login_'.$token, $userInfo, $expireTime);
    }
}