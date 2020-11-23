<?php


namespace app\api\v1\test;


use app\api\Base;

class Test extends Base
{
    /**
     * 调用logic用例-test
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function test()
    {
        $data = $this->logic->test->test();
        return $this->success($data);
    }
    /**
     * 调用logic用例-testDb
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testDb()
    {
        $data = $this->logic->test->testDb();
        return $this->success($data);
    }
    /**
     * 调用logic用例-testApi
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testApi()
    {
        $data = $this->logic->test->testApi();
        return $this->success($data);

    }
    /**
     * 调用logic用例-testHelper
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelper()
    {
        $data = $this->logic->test->testHelper();
        return $this->success($data);

    }
    /**
     * 调用logic用例-testHelperDb
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelperDb()
    {
        $data = $this->logic->test->testHelperDb();
        return $this->success($data);

    }
    /**
     * 调用logic用例-testHelperApi
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelperApi()
    {
        $data = $this->logic->test->testHelperApi();
        return $this->success($data);

    }
}