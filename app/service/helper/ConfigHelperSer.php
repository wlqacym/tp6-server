<?php


namespace app\service\helper;


use app\BaseHelperService;
use think\Exception;

class ConfigHelperSer extends BaseHelperService
{
    /**
     * 清除指定类型的缓存
     *
     * @param $type
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function clearCache($type)
    {
        $this->app->cache->set('bf_admin_config_'.$type, null);
    }
    /**
     * 获取指定分类配置
     *
     * @param $type
     * @param string $ident
     * @return array
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20201027
     */
    public function getByType($type, $ident = '')
    {
        $config = $this->app->cache->get('bf_admin_config_'.$type);
        if (!$config) {
            $config = $this->dbSer->config->getByType($type,'id,explain,ident,type,sort');
            $ids = array_column($config, 'id');
            $enum = $this->dbSer->config->getEnumByCIds($ids, 'id,cid,key,value,sort');
            $enumByCid = [];
            foreach ($enum as $ve) {
                $enumByCid[$ve['cid']] = $enumByCid[$ve['cid']]??[];
                $enumByCid[$ve['cid']][] = $ve;
            }
            foreach ($config as &$v) {
                $v['enum'] = $enumByCid[$v['id']]??[];
            }
            $this->app->cache->set('bf_admin_config_'.$type, $config);
        }
        if ($ident) {
            $config = array_column($config, null, 'ident');
            $config = $config[$ident]??[];
        }
        return $config;
    }
}