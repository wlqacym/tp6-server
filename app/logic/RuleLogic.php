<?php


namespace app\logic;


use app\BaseLogic;
use think\Exception;

class RuleLogic extends BaseLogic
{
    /**
     * 获取所有|指定分组的权限列表
     *
     * @param int $id
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function get($id = 0)
    {
        if ($id) {
            $groupRules = $this->helper->power->getGroupRules($id);
            $rules = $groupRules['rules_info'];
        } else {
            $rules = $this->helper->power->getRules();
        }
        return array_values($rules);
    }

    /**
     * 新增权限
     *
     * @param string $ident
     * @return bool
     * @throws Exception
     * @author wlq
     * @since 1.0   20200528
     */
    public function add(string $ident)
    {
        $checkIdent = $this->db->rule->getByIdent($ident);
        if ($checkIdent) {
            throw new Exception('权限标识已存在', 400);
        }
        $id = $this->db->rule->insertOne();
        $this->helper->power->reloadPower();
        return true;
    }

    /**
     * 编辑权限
     *
     * @param int $id
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0   20200528
     */
    public function edit(int $id)
    {
        $ident = $this->request->put('ident');
        $checkIdent = $this->db->rule->getByIdent($ident, $id);
        if ($checkIdent) {
            throw new Exception('权限标识已存在', 400);
        }
        $this->db->rule->updateById($id);
        $this->helper->power->reloadPower();
        return true;
    }

    /**
     * 删除权限
     *
     * @param $ids
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0  20200528
     */
    public function del($ids)
    {
        if (!$ids) {
            throw new Exception('缺少权限id', 400);
        }
        $res = $this->db->rule->delByIds($ids);
        $this->helper->power->reloadPower();
        if (!$res) {
            throw new Exception('删除失败', 400);
        } else {
            return true;
        }
    }

    /**
     * 重置权限缓存
     *
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210301
     */
    public function reloadRules()
    {
        $this->helper->power->reloadPower();
    }
}