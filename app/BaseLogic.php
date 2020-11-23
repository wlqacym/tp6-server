<?php


namespace app;


use app\middle\service\Api;
use app\middle\service\Db;
use app\middle\service\Helper;

/**
 * Class BaseLogic
 * @package app
 */
class BaseLogic
{

    /**
     * 数据库服务实例
     * @var Db
     */
    protected $dbSer;

    /**
     * 第三方服务实例
     * @var Api
     */
    protected $apiSer;

    /**
     * 公共资源服务实例
     * @var Helper
     */
    protected $helperSer;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 初始化
     * BaseLogic constructor.
     */
    public function __construct()
    {
        $this->dbSer = new Db();
        $this->apiSer = new Api();
        $this->helperSer = new Helper($this->dbSer, $this->apiSer);
        $this->app = app();
        $this->request = $this->app->request;
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
            $this->dbSer->$class = $object;
        } else {
            $this->dbSer->$class;
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
            $this->apiSer->$class = $object;
        } else {
            $this->apiSer->$class;
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
            $this->helperSer->$class = $object;
        } else {
            $this->helperSer->$class;
        }
        return $this;
    }
}