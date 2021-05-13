<?php


namespace app\logic;


use app\BaseLogic;
use think\console\command\optimize\Schema;
use think\Exception;

class GroupLogic extends BaseLogic
{
    /**
     * 获取分组列表
     *
     * @param $option
     * @param int $page
     * @param int $size
     * @return array
     * @throws Exception
     *
     * @author  wlq
     * @since   20200602
     */
    public function get($page = 1, $size = 10)
    {
        $option = $this->request->get();
        $where = [];
        !empty($option['name']) and $where[] = ['name', 'like', "%{$option['name']}%"];
        $data = $this->db->group->getPage($where, $page, $size, 'id,name,blurb,rules,rules_show,create_time,create_user');
        $userIds = array_column($data['rows'], 'create_user');
        $users = $this->db->user->getByIds($userIds, 'real_name');
        foreach ($data['rows'] as &$v) {
            $v['create_user'] = $users[$v['create_user']]??'';
        }
        return $data;
    }
    /**
     * 新增分组
     *
     * @param array $data
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0   20200528
     */
    public function add()
    {
        $name = $this->request->post('name');
        $checkName = $this->db->group->getByName($name);
        if ($checkName) {
            throw new Exception('角色名已存在', 400);
        }
        $this->db->group->insertOne();
        return true;
    }

    /**
     * 编辑分组
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0   20200528
     */
    public function edit(int $id)
    {
        $name = $this->request->put('name');
        $checkName = $this->db->group->getByName($name, $id);
        if ($checkName) {
            throw new Exception('角色名已存在', 400);
        }
        $this->db->group->updateById($id);
        $this->helper->power->reloadGroupRules($id);
        return true;
    }

    /**
     * 删除分组
     *
     * @param $ids
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since v1.0  20200528
     */
    public function del($ids)
    {
        if (!$ids) {
            throw new Exception('缺少角色id', 400);
        }
        $count = $this->db->user->countGroupUsers($ids);
        if ($count) {
            throw new Exception('用户角色下存在管理员，不可删除', 400);
        }
        !is_array($ids) and $ids = explode(',', $ids);
        if (in_array(1, $ids)) {
            throw new Exception('超级管理员角色不可删除', 400);
        }
        $res = $this->db->group->delByIds($ids);
        if (!$res) {
            throw new Exception('删除失败', 400);
        } else {
            $this->helper->power->reloadGroupRules($ids);
            return true;
        }
    }

    /**
     * 设置权限
     *
     * @param $id
     * @param $rules
     * @return bool
     * @throws Exception
     *
     * create by wlq 20200528
     */
    public function setRules($id, $rules, $rulesShow)
    {
        is_array($rules) and $rules = implode(',', $rules);
        $save = [
            'rules' => $rules?:'',
            'rules_show' => $rulesShow?:''
        ];
        $this->db->group->updateById($id, $save);
        $this->helper->power->reloadGroupRules($id);
        return true;
    }


    /**
     * 用户绑定分组
     *
     * @param $groupId
     * @param $userIds
     * @return bool
     * @throws Exception
     *
     * create by wlq 20200614
     */
    public function userBind($groupId, $userIds)
    {
        !is_array($userIds) and $userIds = explode(',', $userIds);
        $save = [];
        $delUserIds = [];
        foreach ($userIds as $v) {
            if ($v == 1) {
                continue;
            }
            $delUserIds[] = $v;
            $save[] = [
                'user_id' => $v,
                'group_id' => $groupId
            ];
        }
        $res = $this->db->user->insertAll($save, 'SysUserGroup');
        return true;
    }
}