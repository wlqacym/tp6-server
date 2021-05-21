<?php


namespace app\logic;


use app\BaseLogic;
use think\Exception;

class ConfigLogic extends BaseLogic
{
    /**
     * 删除配置项
     *
     * @codeCoverageIgnore
     * @param $ids
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function del($ids)
    {
        if (!$ids) {
            throw new Exception('缺少配置项id');
        }
        $this->db->config->delByIds($ids);
        $this->db->config->delEnumByCIds($ids);
        return true;
    }

    /**
     * 清除指定类型缓存
     *
     * @param $type
     * @return bool
     *
     * @since 1.0 20201029
     * @author wlq
     */
    public function clearCache($type)
    {
        $this->helper->config->clearCache($type);
        return true;
    }

    /**
     * 重置指定类型的配置项及配置值
     * * 配置项空ident与配置值的空key会导致各个环境值不一致
     * * 各个环境ident与key不一致的配置不得在代码中使用
     *
     * @param $type
     * @param $params
     * @return bool
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function replaceByType($type, $data)
    {
        $config = [];
        $enum = [];
        $checkIdent = [];
        //整理配置项添加数据，空标识配置项自动生成标识
        $time = time();
        $login = $this->loginInfo;
        foreach ($data as $k => $v) {
            if (!isset($v['ident']) || empty($v['explain']) || empty($v['sort'])) {
                throw new Exception('缺少配置项参数', 400);
            }
            if ($v['ident']) {
                if (isset($checkIdent[$v['ident']])) {
                    throw new Exception('配置项标识重复', 400);
                }
                $checkIdent[$v['ident']] = 1;
            }
            $v['ident'] = $v['ident']?:($type.'_'.strtolower(substr(md5(uniqid()), 0, 6)));
            $config[] = [
                'explain' => $v['explain'],
                'ident' => $v['ident'],
                'type' => $type,
                'sort' => $v['sort'],
                'create_user' => $login['id'],
                'create_time' => $time,
                'update_user' => $login['id'],
                'update_time' => $time,
            ];
            $data[$k]['ident'] = $v['ident'];
        }
        $this->app->db->startTrans();
        try {
            //删除指定类型的配置项及配置值
            $cIds = $this->db->config->getByType($type, 'id');
            if ($cIds) {
                //删除配置项
                $this->db->config->delByIds($cIds);
                //删除配置项下的配置值
                $this->db->config->delEnumByCIds($cIds);
            }
            //添加配置项
            $this->db->config->insertAll($config);
            //获取新增配置项的id
            $configByIdent = $this->db->config->getByType($type, 'id', 'ident');
            $cIds = [];
            foreach ($data as $v) {
                if (!isset($configByIdent[$v['ident']])) {
                    throw new Exception('配置项更新失败', 400);
                }
                $cIds[] = $configByIdent[$v['ident']];
                $checkKey = [];
                foreach ($v['enum'] as $ke => $ve) {
                    if (!isset($ve['key']) || empty($ve['value']) || empty($ve['sort'])) {
                        throw new Exception('缺少配置值参数', 400);
                    }
                    if ($ve['key']) {
                        if (isset($checkKey[$ve['key']])) {
                            throw new Exception('配置值key重复', 400);
                        }
                        $checkKey[$ve['key']] = 1;
                    }
                    $enum[] = [
                        'cid' => $configByIdent[$v['ident']],
                        'key' => $ve['key'],
                        'value' => $ve['value'],
                        'sort' => $ve['sort'],
                        'create_user' => $login['id'],
                        'create_time' => $time,
                        'update_user' => $login['id'],
                        'update_time' => $time,
                    ];
                }
            }
            //添加配置值
            $this->db->config->insertAll($enum, 'Enum');
            //key为空的配置值将key更新为id值
            $this->db->config->updateEnumByCIds($cIds);
            $this->app->db->commit();
            $this->helper->config->clearCache($type);
            return true;
        } catch (Exception $e) {
            $this->app->db->rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 获取指定分类配置
     *
     * @param $type
     * @param string $ident
     * @return array
     * @throws Exception
     * @author wlq
     * @since 1.0 20201027
     */
    public function getType($type, $ident = '')
    {
        $data = $this->helper->config->getByType($type, $ident);
        return $data;
    }


    /**
     * 新增配置项
     *
     * @return mixed
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function add()
    {
        $ident = $this->request->put('ident');
        $type = $this->request->put('type');
        $configCheck = $this->db->config->getByType($type, 'id', 'ident');
        if (isset($configCheck[$ident])) {
            throw new Exception('配置项标识重复');
        }
        $id = $this->db->config->insertOne();
        $this->helper->config->clearCache($type);
        return $id;
    }

    /**
     * 编辑配置项
     *
     * @param $id
     * @return mixed
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function edit($id)
    {
        $config = $this->db->config->getById($id);
        if (!$config) {
            throw new Exception('配置项不存在');
        }
        $ident = $this->request->put('ident');
        $type = $this->request->put('type');
        $configCheck = $this->db->config->getByType($type, 'id', 'ident');
        if (isset($configCheck[$ident]) && $id != $configCheck[$ident]) {
            throw new Exception('配置项标识重复');
        }
        $this->db->config->updateById($id);
        $this->helper->config->clearCache($type);
        return $id;
    }

    /**
     * 新增配置值
     *
     * @return mixed
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function addEnum()
    {
        $cid = $this->request->post('cid');
        $key = $this->request->post('key');
        $config = $this->db->config->getById($cid);
        if (!$config) {
            throw new Exception('配置项不存在');
        }
        $enum = $this->db->config->getEnumByCIds($cid);
        $enumByKey = array_column($enum, 'value', 'key');
        if (isset($enumByKey[$key])) {
            throw new Exception('配置值键名已存在');
        }
        $id = $this->db->config->insertOne(null, 'Enum');
        $this->helper->config->clearCache($config['type']);
        return $id;
    }

    /**
     * 编辑配置值
     *
     * @param $id
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function editEnum($id)
    {
        $cid = $this->request->post('cid');
        $key = $this->request->post('key');
        $config = $this->db->config->getById($cid);
        if (!$config) {
            throw new Exception('配置项不存在');
        }
        $enum = $this->db->config->getEnumByCIds($cid);
        $enumByKey = array_column($enum, 'id,value', 'key');
        if (isset($enumByKey[$key]) && $enumByKey[$key]['id'] != $id) {
            throw new Exception('配置值键名已存在');
        }
        $this->db->config->updateById($id, null, 'Enum');
        $this->helper->config->clearCache($config['type']);
        return $id;
    }
}