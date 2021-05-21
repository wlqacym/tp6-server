<?php


namespace app\api\v1\auth;


use app\api\Base;
use think\Exception;

class Manager extends Base
{
    protected $middleware = [];

    /**
     * 管理员列表
     *
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexGet()
    {
        $name = $this->request->get('name');
        $page = $this->request->get('page', 1);
        $size = $this->request->get('size', 10);
        $data = $this->logic->manager->getPage($name, $page, $size);
        return $this->json($data);
    }

    /**
     * 导入用户服务用户
     *
     * @return \think\response\Json
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function importByZtyIdsPost()
    {
        $ztyIds = $this->request->post('zty_ids');
        if (!$ztyIds) {
            throw new Exception('请选择用户');
        }
        $groupIds = $this->request->post('group_ids');
        if (!$groupIds) {
            throw new Exception('请选择角色');
        }
        $this->logic->manager->importByZtyIds($ztyIds, $groupIds);
        return $this->success('添加成功');
    }
}