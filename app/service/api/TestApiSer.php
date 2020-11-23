<?php


namespace app\service\api;


use app\BaseApiService;

class TestApiSer extends BaseApiService
{
    /**
     * apiSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function test()
    {
        return 'testApiSer';
    }
}