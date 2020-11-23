<?php


namespace app;


use app\middle\service\Api;
use app\middle\service\Db;
use HttpClient\HttpClient;
use think\exception\HttpException;
use think\facade\Log;

/**
 * Class BaseHelperService
 * @package app
 */
class BaseHelperService
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
     * BaseHelperService constructor.
     * @param Db $db
     * @param Api $api
     */
    public function __construct(Db $db, Api $api)
    {
        $this->dbSer = $db;
        $this->apiSer = $api;
        $this->app = app();
        $this->request = $this->app->request;
        $this->init();
    }

    protected function init()
    {
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
}