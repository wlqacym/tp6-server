<?php


namespace app\service\helper;


use app\BaseHelperService;

class TestHelperSer extends BaseHelperService
{
    /**
     * helper独立用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function test()
    {
        return 'testHelperSer';
    }

    /**
     * helper调用dbSer用例
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
     * helper调用apiSer用例
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
}