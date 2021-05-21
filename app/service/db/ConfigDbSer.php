<?php


namespace app\service\db;


use app\model\Config;
use app\model\ConfigEnum;
use app\BaseDbService;
use think\db\exception\DbException;
use think\Exception;

class ConfigDbSer extends BaseDbService
{
    protected $modelName = 'Config';

    /**
     * 标识查询配置项-排除指定id
     *
     * @param $ident
     * @param int $id
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function getByIdent($ident, $type, $id = 0)
    {
        $where = [];
        $where[] = ['ident', '=', $ident];
        $where[] = ['type', '=', $type];
        try {
            $data = ($this->getModel())::where($where)->find();
            $data = $data?($data->id == $id?[]:$data->toArray()):[];
            return $data;
        } catch (DbException $e) {
            throw new Exception('查询配置项异常', 400);
        }
    }

    /**
     * 获取指定类型配置项
     *
     * @param $type
     * @param string $key
     * @param string $fields
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function getByType($type, $fields = '*', $key = '')
    {
        try {
            $data = ($this->getModel())::where('type', $type)
                ->order('sort asc')
                ->column($fields, $key);
            return $data;
        } catch (DbException $e) {
            throw new Exception('配置项查询异常', 400);
        }
    }


    /**
     * key=''的配置值更新
     *
     * @param $type
     * @throws Exception
     */
    public function updateEnumByCIds($cIds)
    {
        try {
            is_array($cIds) and $cIds = implode(',', $cIds);
            $sql = "UPDATE `config_enum` SET `key` = `id` WHERE `cid` IN ({$cIds}) AND `key` = ''";
            $res = app()->db->execute($sql);
            return true;
        } catch (DbException $e) {
            throw new Exception('配置值更新异常', 400);
        }
    }
    /**
     * 标识查询配置值-排除指定id
     *
     * @param $key
     * @param $cId
     * @param int $id
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function getEnumByKey($key, $cId, $id = 0)
    {
        $where = [];
        $where[] = ['key', '=', $key];
        $where[] = ['cid', '=', $cId];
        try {
            $data = ($this->getModel('Enum'))::where($where)->find();
            $data = $data?($data->id == $id?[]:$data->toArray()):[];
            return $data;
        } catch (DbException $e) {
            throw new Exception('查询配置值异常', 400);
        }
    }

    /**
     * 标识查询配置值
     *
     * @param $cIds
     * @param string $fields
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function getEnumByCIds($cIds, $fields = '*')
    {
        !is_array($cIds) and $cIds = explode(',', $cIds);
        try {
            $data = ($this->getModel('Enum'))::field($fields)
                ->where('cid', 'in', $cIds)
                ->order('sort asc')
                ->select()
                ->toArray();
            return $data;
        } catch (DbException $e) {
            throw new Exception('查询配置值异常', 400);
        }
    }

    /**
     * 删除指定配置项的配置值
     *
     * @param $cIds
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function delEnumByCIds($cIds)
    {
        !is_array($cIds) and $cIds = explode(',', $cIds);
        try {
            $res = ($this->getModel('Enum'))::where('cid', 'in', $cIds)->delete();
            return $res;
        } catch (DbException $e) {
            throw new Exception('配置项删除异常', 400);
        }
    }

}