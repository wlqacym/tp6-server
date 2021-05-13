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
        $data['logonName'] = $username;
        $data['pwd'] = md5($password);
        try {
            $params = [
                'type' => 2,
                'logonName' => $data['logonName'],
                'pwd' => strtoupper($data['pwd'])
            ];
            $data = $this->api->user->confirmFetch($params);
            if (!$data) {
                throw new HttpException(400, '用户名密码错误');
            }
            $userInfo = $this->db->user->getByZtyId($data['userId']);
            if (!$userInfo) {
                throw new \Exception('用户不存在', 400);
            }
            if ($userInfo['id'] == 1) {
                //超管拥有全部角色
                $group = $this->db->group->getAll('id');
                $group = array_column($group, 'id');
            } else {
                $group = $this->db->group->getByUserId($userInfo['id']);
                if (!$group) {
                    throw new \Exception('没有权限', 400);
                }
            }
            $groupById = $this->db->group->getByIds($group, 'name');
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
                    'group_name' => $groupById[$groupId]
                ];
            }
            return $return;
        } catch (HttpException $e) {
            throw new Exception($e->getMessage(), $e->getStatusCode());
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}