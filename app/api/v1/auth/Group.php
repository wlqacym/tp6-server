<?php


namespace app\api\v1\auth;


use app\api\Base;
use think\Exception;

class Group extends Base
{
    /**
     * 获取角色列表
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexGet()
    {
        $page = $this->request->get('page', 1);
        $size = $this->request->get('size', 10);
        $data = $this->logic->group->get($page, $size);
        return $this->json($data);
    }

    /**
     * 新增角色
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexPost()
    {
        $rule = [
            'name' => 'require',
            'ident' => 'require'
        ];
        $msg = [
            'name.require' => '缺少角色名',
            'ident.require' => '缺少角色标识'
        ];
        $this->validate($this->request->post(), $rule, $msg);
        $this->logic->group->add();
        return $this->success('新增成功');
    }

    /**
     * 编辑角色
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexPut()
    {
        $id = $this->request->get('id');
        if (!$id) {
            throw new Exception('缺少角色id');
        }
        $rule = [
            'name' => 'require',
            'ident' => 'require'
        ];
        $msg = [
            'name.require' => '缺少角色名',
            'ident.require' => '缺少角色标识'
        ];
        $this->validate($this->request->put(), $rule, $msg);
        $this->logic->group->edit($id);
        return $this->success('编辑成功');
    }

    /**
     * 删除角色
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexDelete()
    {
        $ids = $this->request->delete('ids');
        if (!$ids) {
            throw new Exception('未选则角色');
        }
        $this->logic->group->del($ids);
        return $this->success('删除成功');
    }

    /**
     * 设置权限
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function setRules()
    {
        $id = $this->request->get('id');
        $rules = $this->request->post('rules');
        $rulesShow = $this->request->post('rules_show');
        $this->logic->group->setRules($id, $rules, $rulesShow);
        return $this->success('编辑成功');
    }

    /**
     * 绑定用户
     *
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function bindUser()
    {
        $groupId = $this->request->post('id');
        $userIds = $this->request->post('user_ids');
        if (!$groupId || $userIds) {
            throw new Exception('缺少参数');
        }
        $this->logic->group->userBind($groupId, $userIds);
    }
}