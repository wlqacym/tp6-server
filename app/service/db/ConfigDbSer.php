<?php


namespace app\service\db;


use app\model\Config;
use app\model\ConfigEnum;
use app\BaseDbService;
use think\db\exception\DbException;
use think\Exception;

class ConfigDbSer extends BaseDbService
{
    /**
     * 添加配置项-单个
     *
     * @param $data
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function insertOne($data)
    {
        try {
            $model = new Config();
            $model->save($data);
            return $model->id;
        } catch (DbException $e) {
            throw new Exception('配置项添加异常', 400);
        }
    }

    /**
     * 添加配置项-批量
     *
     * @param $data
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function insertAll($data)
    {
        try {
            $res = Config::insertAll($data);
            return true;
        } catch (DbException $e) {
            throw new Exception('配置项添加异常', 400);
        }
    }

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
            $data = Config::where($where)->find();
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
            $data = Config::where('type', $type)
                ->order('sort asc')
                ->column($fields, $key);
            return $data;
        } catch (DbException $e) {
            throw new Exception('配置项查询异常', 400);
        }
    }
    /**
     * id更新配置项
     *
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function updateById($id, $data)
    {
        try {
            $config = Config::find($id);
            if (!$config) {
                throw new Exception('配置项不存在', 400);
            }
            $config->save($data);
            return $config->id;
        } catch (DbException $e) {
            throw new Exception('配置项更新异常', 400);
        }
    }

    /**
     * 主键删除配置项
     *
     * @param $ids
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function delByIds($ids)
    {
        !is_array($ids) and $ids = explode(',', $ids);
        try {
            $res = Config::destroy($ids);
            return $res;
        } catch (DbException $e) {
            throw new Exception('配置项删除异常', 400);
        }
    }


    /**
     * 添加配置值-单个
     *
     * @param $data
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function insertEnumOne($data)
    {
        try {
            $model = new ConfigEnum();
            $model->save($data);
            return $model->id;
        } catch (DbException $e) {
            throw new Exception('配置值添加异常', 400);
        }
    }

    /**
     * 添加配置值-批量
     *
     * @param $data
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function insertEnumAll($data)
    {
        try {
            $res = ConfigEnum::insertAll($data);
            return true;
        } catch (DbException $e) {
            throw new Exception('配置值添加异常', 400);
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
            $data = ConfigEnum::where($where)->find();
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
            $data = ConfigEnum::field($fields)
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
     * id更新配置值
     *
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function updateEnumById($id, $data)
    {
        try {
            $config = ConfigEnum::find($id);
            if (!$config) {
                throw new Exception('配置值不存在', 400);
            }
            $config->save($data);
            return $config->id;
        } catch (DbException $e) {
            throw new Exception('配置项更新异常', 400);
        }
    }

    /**
     * 主键删除配置值
     *
     * @param $ids
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function delEnumByIds($ids)
    {
        !is_array($ids) and $ids = explode(',', $ids);
        try {
            $res = ConfigEnum::destroy($ids);
            return $res;
        } catch (DbException $e) {
            throw new Exception('配置值删除异常', 400);
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
            $res = ConfigEnum::where('cid', 'in', $cIds)->delete();
            return $res;
        } catch (DbException $e) {
            throw new Exception('配置项删除异常', 400);
        }
    }

}