<?php


namespace app;


use app\controller\Admin;
use app\tests\mock\MiddleApiMock;
use app\tests\mock\MiddleDbMock;
use PHPUnit\Framework\TestCase;
use think\App;

class BaseTest extends TestCase
{
    /** @var App */
    protected $app;

    /**
     * @var MiddleDbMock
     */
    protected $dbMock;

    /**
     * @var MiddleApiMock
     */
    protected $apiMock;

    /**
     * 初始化配置
     */
    protected function setUp():void
    {
        $this->app = new App();
        $this->app->initialize();
        $this->dbMock = new MiddleDbMock();
        $this->apiMock = new MiddleApiMock();
    }

    /**
     * 初始化登陆
     */
    public function setLogin()
    {
    }

    /**
     * 模拟POST入参
     *
     * @param $data
     *
     * @author wlq
     * @since 1.0 20201015
     */
    public function setRequestPost($data)
    {
        $this->app->request->withPost($data);
    }

    /**
     * 模拟GET入参
     *
     * @param $data
     *
     * @author wlq
     * @since 1.0 20201015
     */
    public function setRequestGet($data)
    {
        $this->app->request->withGet($data);
    }

    /**
     * 模拟PUT入参
     *
     * @param $data
     *
     * @author wlq
     * @since 1.0 20201015
     */
    public function setRequestPut($data)
    {
        $header = $this->app->request->header();
        $header['Content-Type'] = 'application/json';
        $this->app->request->setMethod('put')
            ->withHeader($header)
            ->withInput(json_encode($data));
    }

    /**
     * 模拟PATCH入参
     *
     * @param $data
     *
     * @author wlq
     * @since 1.0 20201015
     */
    public function setRequestPatch($data)
    {
        $header = $this->app->request->header();
        $header['Content-Type'] = 'application/json';
        $this->app->request->setMethod('patch')
            ->withHeader($header)
            ->withInput(json_encode($data));
    }
}