<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-05-28
 * Time: 18:34
 */

namespace app\helper;

use app\middle\service\Api;
use app\middle\service\Db;
use app\middle\service\Helper;
use think\facade\Log;
use think\Response;

trait CommonAttr
{

    /**
     * 数据库服务实例
     * @var Db
     */
    protected $db;

    /**
     * 第三方服务实例
     * @var Api
     */
    protected $api;

    /**
     * 公共资源服务实例
     * @var Helper
     */
    protected $helper;

    /**
     * 应用实例
     * @var \think\App
     */
    public $app;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 当前时间
     *
     * @var int
     */
    protected $nowTime;
    /**
     * 登陆信息
     *
     * @var array
     */
    protected $loginInfo;

    /**
     * 数据库服务初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrDb()
    {
        $this->db = new Db();
    }

    /**
     * 数据库服务设置
     *
     * @param $ser
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrDb($ser)
    {
        $this->db = $ser;
    }

    /**
     * 第三方微服务初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrApi()
    {
        $this->api = new Api();
    }

    /**
     * 第三方微服务设置
     *
     * @param null $ser
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrApi($ser)
    {
        $this->api = $ser;
    }

    /**
     * 公共资源服务设置
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrHelper()
    {
        $this->helper = new Helper();
    }

    /**
     * 公共资源服务设置
     *
     * @param $ser
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrHelper($ser)
    {
        $this->helper = $ser;
    }

    /**
     * 应用实例初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrApp()
    {
        $this->app = app();
    }
    /**
     * 应用实例设置
     *
     * @param $app
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrApp($app)
    {
        $this->app = $app;
    }

    /**
     * Request实例初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrRequest()
    {
        if (empty($this->app)) {
            $this->initAttrApp();
        }
        $this->request = $this->app->request;
    }

    /**
     * Request实例设置
     *
     * @param $request
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrRequest($request)
    {
        $this->request = $request;
    }

    /**
     * 当前时间初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrNowTime()
    {
        if (empty($this->request)) {
            $this->initAttrRequest();
        }
        $this->nowTime = $this->request->time();
    }
    /**
     * 当前时间设置
     *
     * @param $nowTime
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrNowTime($nowTime)
    {
        $this->nowTime = $nowTime;
    }


    /**
     * 登陆账户信息初始化
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function initAttrLoginInfo()
    {
        $token = $this->request->header('access-token');
        !$token and $token = $this->request->get('token');
        if ($token) {
            $userInfo = cache('admin_login_'.$token);
            $this->loginInfo = $userInfo?:[];
        }
    }
    /**
     * 登陆账户信息设置
     *
     * @param $loginInfo
     *
     * @author wlq
     * @since 1.0 20210427
     */
    public function setAttrLoginInfo($loginInfo)
    {
        $this->loginInfo = $loginInfo;
    }
    /**
     * 初始化方法
     *
     * @author wlq
     * @since 1.0 20210427
     */

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
    }
}