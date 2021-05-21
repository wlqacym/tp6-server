<?php

namespace app\service\helper;

// 应用请求对象类

use app\BaseHelperService;
use think\Exception;
use think\facade\Log;

/**
 * Class PowerHelperSer
 * @package app\service\helper
 */
class PowerHelperSer extends BaseHelperService
{

    /**
     * 重置权限缓存
     *
     * @author wlq
     * @since 1.0 20201010
     */
    public function reloadRules()
    {
        cache('admin_rule', NULL);
        cache('admin_rule_by_ident', NULL);
    }

    /**
     * 重置权限与分组权限缓存
     *
     * @return bool
     * @throws Exception
     *
     * @author  wlq
     * @since   v1.0    20200529
     */
    public function reloadPower()
    {
        $groups = $this->db->group->getAll('id,name,rules');
        $this->reloadRules();
        $rules = $this->getRules();
        foreach ($groups as $kg => $vg) {
            $this->cacheGroupRules($vg, $rules);
        }
        return true;
    }

    /**
     * 重置指定分组权限
     *
     * @param $id
     *
     * @author wlq
     * @since 1.0 20201010
     */
    public function reloadGroupRules($id)
    {
        !is_array($id) and $id = explode(',', $id);
        foreach ($id as $v) {
            cache('admin_group_' . $v, Null);
        }
    }

    /**
     * 获取分组权限
     *
     * @param $id
     * @return mixed|object|\think\App
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function getGroupRules($id)
    {
        $groupRules = cache('admin_group_' . $id);
        if (!$groupRules) {
            $groupRules = $this->db->group->getById($id);
            if (!$groupRules) {
                throw new Exception('角色不存在', 400);
            }
            $groupRules = $this->cacheGroupRules($groupRules);
        }
        return $groupRules;
    }

    /**
     * 生成分组权限缓存
     *
     * @param $groupRules
     * @param array $rules
     * @return mixed
     *
     * @author wlq
     * @since 1.0 20201009
     */
    private function cacheGroupRules($groupRules, $rules = [])
    {
        $groupRules['rules'] = $groupRules['rules'] ? explode(',', $groupRules['rules']) : [];
        $groupRules['rules_info'] = [];
        $groupRules['rules_by_ident'] = [];
        $groupRules['rules_by_href'] = [];
        !$rules and $rules = $this->getRules();
        foreach ($rules as $vr) {
            if ($groupRules['id'] == 1) {
                $groupRules['rules_info'][] = $vr;
                $groupRules['rules_by_ident'][$vr['ident']] = $vr;
                $groupRules['rules_by_href'][$vr['href']] = $groupRules['rules_by_href'][$vr['href']]??[];
                $groupRules['rules_by_href'][$vr['href']][strtolower($vr['href'])] = $vr;
                continue;
            }
            if (!$groupRules['rules']) {
                continue;
            }
            if (in_array($vr['id'], $groupRules['rules']) || !$vr['auth_open']) {
                $groupRules['rules_info'][] = $vr;
                $groupRules['rules_by_ident'][$vr['ident']] = $vr;
                $groupRules['rules_by_href'][$vr['href']] = $groupRules['rules_by_href'][$vr['href']]??[];
                $groupRules['rules_by_href'][$vr['href']][strtolower($vr['href'])] = $vr;
            }
        }
        cache('admin_group_' . $groupRules['id'], $groupRules);
        return $groupRules;
    }

    /**
     * 获取全部权限
     *
     * @param bool $isIdent
     * @return array|mixed
     *
     * @author  wlq
     * @since   1.0    20200529
     */
    public function getRules($isIdent = false)
    {
        $rules = cache('admin_rule');
        $rulesByIdent = cache('admin_rule_by_ident');
        if (!$rules || !$rulesByIdent) {
            $rules = $this->db->rule->getAll();
            cache('admin_rule', $rules);
            $rulesByIdent = array_column($rules, null, 'ident');
            cache('admin_rule_by_ident', $rulesByIdent);
        }
        return $isIdent ? $rulesByIdent : $rules;
    }

    /**
     * 获取全部权限-href为键名
     * @return array
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function getRulesByHref()
    {
        $rules = $this->getRules();
        $ruleByHref = [];
        foreach ($rules as $rule) {
            $ruleByHref[$rule['href']] = $ruleByHref[$rule['href']]??[];
            $ruleByHref[$rule['href']][strtolower($rule['method'])] = $rule;
        }
        return $ruleByHref;
    }

    /**
     * 判断权限
     *
     * @param string $ident
     * @return array|bool[]
     *
     * @author  wlq
     * @since   1.0    20200529
     */
    public function checkPower($ident = '')
    {
        try {
            $request = $this->request;
            $userInfo = $this->db->admin->loginCheck();
            $this->loginInfo = $userInfo;
            $controller = $request->controller();
            $action = $request->action();
            if ($this->isAdmin($userInfo['id'])) {
                if ($ident == '') {
                    $rulesByHref = $this->getRulesByHref();
                    $href = 'api/'.$controller . '/' . $action;
                    if (!isset($rulesByHref[$href])) {
                        throw new Exception('没有权限', 403);
                    }
                    $method = strtolower($request->method());
                    if (!isset($rulesByHref[$href][$method])) {
                        throw new Exception('错误请求', 404);
                    }
                }
                return ['status' => true];
            }
            $rules = $this->getGroupRules($userInfo['now_group_id']);
            if ($ident != '') {
                if (!isset($rules['rules_by_ident'][$ident])) {
                    throw new Exception('没有权限', 403);
                }
            } else {
                $href = 'api/'.$controller . '/' . $action;
                if (!isset($rules['rules_by_href'][$href])) {
                    throw new Exception('没有权限', 403);
                }
                $method = strtolower($request->method());
                if (!isset($rules['rules_by_href'][$href][$method])) {
                    throw new Exception('错误请求', 404);
                }
            }
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode()];
        }
    }

    /**
     * 是否为超管
     *
     * @param int $id
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201010
     */
    public function isAdmin($id = 0)
    {
        $id = $id ?: $this->loginInfo['id'];
        if ($id == 1) {
            return true;
        }
        return false;
    }

    /**
     * 更新登陆用户信息缓存时间
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function updateTimeOut()
    {
        $userInfo = $this->loginInfo;
        $key = $this->app->config->get('api.login_key');
        $expireTime = $this->app->config->get('api.expire_time');
        foreach ($this->loginInfo['group'] as $gid) {
            $userInfo['now_group_id'] = $gid;
            $token = md5($userInfo['id'].'_'.$gid.'_'.$userInfo['login_time'].'_'.$key);
            cache('admin_login_'.$token, $userInfo, $expireTime);
        }

    }
}
