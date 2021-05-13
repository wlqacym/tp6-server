<?php


namespace app\api\v1\auth;


use app\api\Base;
use think\Exception;

class Group extends Base
{
    public function indexGet()
    {
        $data = $this->logic->group->get();
        return $this->json($data);
    }
    public function indexPost()
    {
        $this->logic->group->add();
        return $this->success('新增成功');
    }
    public function indexPut()
    {
        $id = $this->request->get('id');
        if (!$id) {
            throw new Exception('缺少角色id');
        }
        $this->logic->group->edit($id);
        return $this->success('新增成功');
    }

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