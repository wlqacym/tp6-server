<?php


namespace app\service\db;


use app\model\SysGroup;
use app\BaseDbService;
use think\db\exception\DbException;
use think\Exception;

class GroupDbSer extends BaseDbService
{
    protected $modelName = 'SysGroup';

    /**
     * 获取全部分组
     *
     * @param string $fields
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getAll($fields = '*')
    {
        try {
            $data = ($this->getModel())::field($fields)->select()->toArray();
            return $data;
        } catch (DbException $e) {
            throw new Exception('角色查询异常', 400);
        }
    }


    /**
     * 分组名查询分组信息
     *
     * @param $name
     * @param int $id
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getByName($name, $id = 0)
    {
        try {
            $where = [];
            $where[] = ['name', '=', $name];
            if ($id) {
                $where[] = ['id', '<>', $id];
            }
            $data = ($this->getModel())::where($where)->find();
            return $data?$data->toArray():[];
        } catch (DbException $e) {
            throw new Exception('角色查询异常', 400);
        }
    }


    /**
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function selOption()
    {
        $option = ($this->getModel())::column('name `text`,id `value`');
        return $option;
    }

    /**
     * 获取用户角色
     *
     * @param $userId
     * @return array
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getByUserId($userId)
    {
        $data = ($this->getModel('SysUserGroup'))::where('user_id', $userId)
            ->column('group_id');
        return $data;
    }
}