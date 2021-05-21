<?php


namespace app\service\db;


use app\BaseDbService;
use app\model\SysUser;
use app\model\SysUserGroup;
use think\Db;
use think\db\exception\DbException;
use think\Exception;
use think\facade\Log;

/**
 * Class UserDbSer
 * @package app\service\db
 * @codeCoverageIgnore
 */
class UserDbSer extends BaseDbService
{
    /**
     * 数据模型名称
     *
     * @var string
     */
    protected $modelName = 'SysUser';

    /**
     * 用户服务用户id获取用户信息
     *
     * @param $ztyId
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getByZtyId($ztyId)
    {
        $data = ($this->getModel())::where('zty_user_id', $ztyId)->find();
        return $data?$data->toArray():[];
    }

    /**
     * 用户服务用户id获取用户信息
     *
     * @param $ztyId
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getByZtyIds(array $ztyIds, $fields = '*')
    {
        $data = ($this->getModel())::where('zty_user_id', 'in', $ztyIds)->column($fields, 'zty_user_id');
        return $data;
    }
    /**
     * 获取用户角色
     *
     * @param $id
     * @return array
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getGroupByUserId($id)
    {
        $data = ($this->getModel('Group'))::where('user_id', $id)
            ->column('group_id');
        return $data;
    }

    /**
     * 获取用户角色
     *
     * @param $id
     * @return array
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getGroupByUserIds(array $ids)
    {
        $data = ($this->getModel('Group'))::alias('sug')
            ->leftJoin('sys_group sg', 'sug.group_id = sg.id')
            ->where('sug.user_id', 'in', $ids)
            ->column('sug.user_id,sug.group_id,sg.name');
        return $data;
    }
    /**
     * 统计角色下的用户数
     *
     * @param $groupId
     * @return int
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function countGroupUsers($groupId)
    {
        $data = ($this->getModel('Group'))::where('group_id', $groupId)
            ->count('user_id');
        return $data;
    }

}