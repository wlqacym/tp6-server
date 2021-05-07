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
        dump($this->db->test->getPage());
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
        return $this->db->test->test();
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
        return $this->api->test->test();
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
        return $this->helper->test->test();
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
        return $this->helper->test->testApi();
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
        $this->db->test->test();
        $this->helper->test->testDb();
        return $this->helper->test->testDb();
    }
}