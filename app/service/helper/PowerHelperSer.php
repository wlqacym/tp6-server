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
        $groups = $this->dbSer->group->getAll('id,name,rules');
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
     * @return bool
     * @throws Exception
     *
     * @author  wlq
     * @since   1.0    20200529
     */
    public function getGroupRules($id)
    {
        $groupRules = cache('admin_group_' . $id);
        if (!$groupRules) {
            $groupRules = $this->dbSer->group->getById($id, 'id,name,rules');
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
        $groupRules['rulesInfo'] = [];
        !$rules and $rules = $this->getRules();
        foreach ($rules as $vr) {
            if ($groupRules['id'] == 1) {
                $groupRules['rulesInfo'][] = $vr;
                continue;
            }
            if (!$groupRules['rules']) {
                continue;
            }
            if (in_array($vr['id'], $groupRules['rules']) || !$vr['auth_open']) {
                $groupRules['rulesInfo'][] = $vr;
            }
        }
        $groupRules['rulesByIdent'] = array_column($groupRules['rulesInfo'], null, 'ident');
        $groupRules['rulesByHref'] = array_column($groupRules['rulesInfo'], null, 'href');
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
            $rules = $this->dbSer->rule->getAll();
            cache('admin_rule', $rules);
            $rulesByIdent = array_column($rules, null, 'ident');
            cache('admin_rule_by_ident', $rulesByIdent);
        }
        return $isIdent ? $rulesByIdent : $rules;
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
            $userInfo = $this->dbSer->admin->loginInfo();
            $controller = $request->controller();
            $action = $request->action();
            if ($this->isAdmin($userInfo['id'])) {
                if ($ident == '') {
                    $rules = $this->getRules();
                    $rulesByHref = array_column($rules, null, 'href');
                    $href = $controller . '/' . $action;
                    if (isset($rulesByHref[$href]) == false) {
                        throw new Exception('没有权限', 403);
                    }
                    if (strtolower($rulesByHref[$href]['method']) != strtolower($request->method())) {
                        throw new Exception('错误请求', 404);
                    }
                    if ($rulesByHref[$href]['path_id'] && !$request->has('id')) {
                        throw new Exception('错误请求，缺少pathId', 404);
                    }
                }
                return ['status' => true];
            }
            $rules = $this->getGroupRules($userInfo['group_id']);
            if ($ident != '') {
                if (!isset($rules['rulesByIdent'][$ident])) {
                    throw new Exception('没有权限', 403);
                }
            } else {
                $href = $controller . '/' . $action;
                if (isset($rules['rulesByHref'][$href]) == false) {
                    throw new Exception('没有权限', 403);
                }
                if (strtolower($rules['rulesByHref'][$href]['method']) != strtolower($request->method())) {
                    throw new Exception('错误请求', 404);
                }
                if ($rules['rulesByHref'][$href]['path_id'] && !$request->has('id')) {
                    throw new Exception('错误请求，缺少pathId', 404);
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
        $id = $id ?: $this->dbSer->admin->loginInfo()['id'];
        if ($id == 1) {
            return true;
        }
        return false;
    }
}