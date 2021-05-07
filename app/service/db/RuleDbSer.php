<?php


namespace app\service\db;


use app\BaseDbService;
use app\model\SysGroup;
use app\model\SysRule;
use think\db\exception\DbException;
use think\Exception;

class RuleDbSer extends BaseDbService
{
    /**
     * 获取所有权限
     *
     * @return array
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getAll()
    {
        $data = SysRule::order('sort')->column('id,pid,href,method,title,ident,auth_open,same_pid,icon,sort,menu_status,path_id,is_btn');
        return $data;
    }

    /**
     * 权限标识获取非指定id的权限信息
     *
     * @param $ident
     * @param int $id
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201009
     */
    public function getByIdent($ident, $id = 0)
    {
        try {
            $where = [];
            $where[] = ['ident', '=', $ident];
            if ($id) {
                $where[] = ['id', '<>', $id];
            }
            $data = SysRule::where($where)->find();
        } catch (DbException $e) {
            throw new Exception('权限查询异常', 400);
        }
        return $data?$data->toArray():[];
    }

    /**
     * 新增权限（单个）
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
            $rule = new SysRule();
            $rule->save($data);
            return $rule->id;
        } catch (DbException $e) {
            throw new Exception('新增权限异常', 400);
        }
    }

    /**
     * 更新指定id权限
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
            $data = SysRule::find($id);
            if (!$data) {
                throw new Exception('权限不存在或已删除', 400);
            }
            $data->save($save);
            return $id;
        } catch (DbException $e) {
            throw new Exception('权限更新异常', 400);
        }
    }

    /**
     * 删除权限
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
        $res = SysRule::destroy($ids);
        return $res;
    }
}