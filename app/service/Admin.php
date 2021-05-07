<?php


namespace app\service;


use think\db\exception\DbException;
use think\Exception;
use think\exception\HttpException;

class Admin
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
    public static function loginInfo()
    {
        $token = app()->request->header('access-token');
        !$token and $token = app()->request->get('token');
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
    public static function updateTimeOut()
    {
        $token = app()->request->header('access-token');
        $expireTime = app()->config->get('api.expire_time');
        $userInfo = self::loginInfo();
        cache('admin_login_'.$token, $userInfo, $expireTime);
    }
}