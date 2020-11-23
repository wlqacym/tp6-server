<?php


namespace app\logic;


use app\BaseLogic;

class TestLogic extends BaseLogic
{
    /**
     * logic用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function test()
    {
        return 'testLogic';
    }
    /**
     * logic调用dbSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testDb()
    {
        return $this->dbSer->test->test();
    }
    /**
     * logic调用apiSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testApi()
    {
        return $this->apiSer->test->test();
    }
    /**
     * logic调用helperSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelper()
    {
        return $this->helperSer->test->test();
    }
    /**
     * logic调用helperSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelperApi()
    {
        return $this->helperSer->test->testDb();
    }
    /**
     * logic调用helperSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function testHelperDb()
    {
        return $this->helperSer->test->testApi();
    }
}