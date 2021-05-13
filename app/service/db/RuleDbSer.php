<?php


namespace app\service\db;


use app\BaseDbService;
use app\model\SysGroup;
use app\model\SysRule;
use think\db\exception\DbException;
use think\Exception;

class RuleDbSer extends BaseDbService
{
    protected $modelName = 'SysRule';
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
        $data = ($this->getModel())::order('sort')
            ->column('*', 'id');
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
            $data = ($this->getModel())::where($where)->find();
        } catch (DbException $e) {
            throw new Exception('权限查询异常', 400);
        }
        return $data?$data->toArray():[];
    }

}