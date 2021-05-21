<?php


namespace app\service\helper;

use app\BaseHelperService;
use think\console\command\optimize\Schema;
use think\Exception;
use think\exception\HttpException;

class AdminHelperSer extends BaseHelperService
{
    /**
     * 账号密码登陆
     *
     * @param $username
     * @param $password
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function login($username, $password)
    {
        $data['logon_name'] = $username;
        $data['pwd'] = md5($password);
        $params = [
            'type' => 2,
            'logonName' => $data['logon_name'],
            'pwd' => strtoupper($data['pwd'])
        ];
        $data = $this->api->user->confirmFetch($params);
        if (!$data) {
            throw new Exception('用户名密码错误', 400);
        }
        $userInfo = $this->db->user->getByZtyId($data['userId']);
        if (!$userInfo) {
            throw new Exception('用户不存在', 400);
        }
        return $this->loginByUserInfo($userInfo);
    }

    /**
     * 用户信息登陆
     *
     * @param $userInfo
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function loginByUserInfo($userInfo)
    {
        if ($userInfo['id'] == 1) {
            //超管拥有全部角色
            $group = $this->db->group->getAll('id');
            $group = array_column($group, 'id');
        } else {
            $group = $this->db->user->getGroupByUserId($userInfo['id']);
            if (!$group) {
                throw new \Exception('没有权限', 400);
            }
        }
        $groupById = $this->db->group->getByIds($group, 'name,ident,icon');
        $config = $this->app->config;
        $key = $config->get('api.login_key');
        $expireTime = $config->get('api.expire_time');
        $time = time();
        $userInfo['group_ids'] = $group;
        $userInfo['login_time'] = $time;
        $return = [];
        //缓存登陆信息
        foreach ($group as $groupId) {
            $userInfo['now_group_id'] = $groupId;
            $token = md5($userInfo['id'].'_'.$groupId.'_'.$time.'_'.$key);
            cache('admin_login_'.$token, $userInfo, $expireTime);
            $return[] = [
                'group_id' => $groupId,
                'token' => $token,
                'group_name' => $groupById[$groupId]['name'],
                'group_icon' =>  $groupById[$groupId]['icon']?:'team'
            ];
        }
        return $return;
    }


    /**
     * 更新登陆用户信息缓存时间
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function logout()
    {
        $userInfo = $this->loginInfo;
        $key = $this->app->config->get('api.login_key');
        foreach ($this->loginInfo['group'] as $gid) {
            $token = md5($userInfo['id'].'_'.$gid.'_'.$userInfo['login_time'].'_'.$key);
            cache('admin_login_'.$token, null);
        }
        return true;
    }
}