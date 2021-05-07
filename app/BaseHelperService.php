<?php


namespace app;


use app\helper\CommonAttr;
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
    use CommonAttr;
//    /**
//     * 数据库服务实例
//     * @var Db
//     */
//    protected $dbSer;
//
//    /**
//     * 第三方服务实例
//     * @var Api
//     */
//    protected $apiSer;
//
//    /**
//     * 应用实例
//     * @var \think\App
//     */
//    protected $app;
//
//    /**
//     * Request实例
//     * @var \think\Request
//     */
//    protected $request;
//
//    /**
//     * 当前时间
//     *
//     * @var int
//     */
//    protected $nowTime;
//    /**
//     * 登陆信息
//     *
//     * @var array
//     */
//    protected $loginInfo;

    /**
     * 初始化
     * BaseHelperService constructor.
     */
    public function __construct()
    {
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

//
//    /**
//     * 设置当前时间
//     *
//     * @param $nowTime
//     *
//     * @author wlq
//     * @since 1.0 20210409
//     */
//    public function setNowTime($nowTime)
//    {
//        $this->nowTime = $nowTime;
//    }
//
//    /**
//     * 设置当前登陆账户
//     *
//     * @param $nowTime
//     *
//     * @author wlq
//     * @since 1.0 20210409
//     */
//    public function setLoginInfo($loginInfo)
//    {
//        $this->loginInfo = $loginInfo;
//    }
}