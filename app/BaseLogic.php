<?php


namespace app;


use app\helper\CommonAttr;
use app\middle\service\Api;
use app\middle\service\Db;
use app\middle\service\Helper;

/**
 * Class BaseLogic
 * @package app
 */
class BaseLogic
{
    use CommonAttr;

    public function initHelper()
    {
        $this->helper->setAttrDb($this->db);
        $this->helper->setAttrApi($this->api);
        $this->helper->setAttrApp($this->app);
        $this->helper->setAttrRequest($this->request);
        $this->helper->setAttrNowTime($this->nowTime);
    }

    /**
     * 实例化数据服务类
     *
     * @param $class
     * @param null $object
     * @return $this
     *
     * @author wlq
     * @since 1.0
     */
    public function setDbSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->db->$class = $object;
        } else {
            $this->db->$class;
        }
        return $this;
    }

    /**
     * 实例化第三方服务服务类
     *
     * @param $class
     * @param null $object
     * @return $this
     *
     * @author wlq
     * @since 1.0
     */
    public function setApiSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->api->$class = $object;
        } else {
            $this->api->$class;
        }
        return $this;
    }

    /**
     * 实例化公共资源服务类
     *
     * @param $class
     * @param null $object
     * @return $this
     *
     * @author wlq
     * @since 1.0
     */
    public function setHelperSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->helper->$class = $object;
        } else {
            $this->helper->$class;
        }
        return $this;
    }
}