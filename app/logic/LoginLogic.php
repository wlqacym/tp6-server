<?php


namespace app\logic;


use app\BaseLogic;
use think\Exception;

class LoginLogic extends BaseLogic
{
    /**
     * 账户密码登陆
     *
     * @param $username
     * @param $password
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function login($username, $password)
    {
        $data = $this->helper->admin->login($username, $password);
        return $data;
    }

    /**
     * 获取登陆信息
     *
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function loginInfo()
    {
        $info = $this->loginInfo;
        $groupRules = $this->helper->power->getGroupRules($info['now_group_id']);
        $info['role'] = [ 'permissions' => $groupRules['rules_info']?array_values($groupRules['rules_info']):[]];
        return $info;
    }

    /**
     * 生成静默登陆token
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function makeSilenceToken()
    {
        $key = $this->app->config->get('api.login_key');
        $rand = rand(0,10000);
        $rand = 10000+$rand;
        $token = md5($this->nowTime.'_'.$key.'_'.$rand);
        cache('silence_login_token_'.$token, $token, 300);
        return $token;
    }

    /**
     * 静默登陆
     *
     * @param $token
     * @param $ztyUserId
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function silence($token, $ztyUserId)
    {
        $token = cache('silence_login_token_'.$token);
        if (!$token) {
            throw new Exception('token错误或已失效');
        }
        cache('silence_login_token_'.$token, null);
        $userInfo = $this->helper->user->getIdByZtyId($ztyUserId, 3);
        $data = $this->helper->admin->loginByUserInfo($userInfo);
        return $data;
    }

    public function out()
    {
        $this->helper->admin->logout();
        return true;
    }
}