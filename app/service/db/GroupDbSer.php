<?php


namespace app\service\db;


use app\model\SysGroup;
use app\BaseDbService;
use think\db\exception\DbException;
use think\Exception;

class GroupDbSer extends BaseDbService
{
    /**
     * 分组id获取分组信息
     *
     * @param $gid
     * @param string $fields
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getById($gid, $fields = '*')
    {
        try {
            $groupRules = SysGroup::field($fields)->find($gid);
        } catch (DbException $e) {
            throw new Exception('角色获取失败', 400);
        }
        return $groupRules?$groupRules->toArray():[];
    }

    /**
     * 批量获取指定id的分组信息-id为键名
     *
     * @param $ids
     * @param string $fields
     * @return array
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getByIds($ids, $fields = '*')
    {
        if (!$ids) {
            return [];
        }
        !is_array($ids) and $ids = explode(',', $ids);
        $data = SysGroup::where('id', 'in', $ids)->column($fields, 'id');
        return $data;
    }
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
            $data = SysGroup::field($fields)->select()->toArray();
            return $data;
        } catch (DbException $e) {
            throw new Exception('角色查询异常', 400);
        }
    }

    /**
     * 分页获取分组列表
     *
     * @param $where
     * @param int $page
     * @param int $size
     * @param string $fields
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getPage($where, $page = 1, $size = 10, $fields = '*')
    {
        try {
            $rows = SysGroup::field($fields)
                ->where($where)
                ->limit(($page - 1)*$size, $size)
                ->select()
                ->toArray();
            $count = SysGroup::where($where)->count('id');
            return $this->setPageData($rows, $page, $size, $count);
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
            $data = SysGroup::where($where)->find();
            return $data?$data->toArray():[];
        } catch (DbException $e) {
            throw new Exception('角色查询异常', 400);
        }
    }

    /**
     * 新增分组（单个）
     *
     * @param $data
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function insertOne($data)
    {
        try {
            $group = new SysGroup();
            $group->save($data);
            return $group->id;
        } catch (DbException $e) {
            throw new Exception('新增角色异常', 400);
        }
    }
    /**
     * 更新指定id分组
     *
     * @param $id
     * @param $save
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function updateById($id, $save)
    {
        try {
            $data = SysGroup::find($id);
            if (!$data) {
                throw new Exception('角色不存在或已删除', 400);
            }
            $data->save($save);
            return $id;
        } catch (DbException $e) {
            throw new Exception('角色更新异常', 400);
        }
    }

    /**
     * 删除分组
     *
     * @param $ids
     * @return bool
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function del($ids)
    {
        is_string($ids) and $ids = explode(',', $ids);
        $res = SysGroup::destroy($ids);
        return $res;
    }

    /**
     *
     * @return array
     */
    public function selOption()
    {
        $option = SysGroup::column('name `text`,id `value`');
        return $option;
    }
}