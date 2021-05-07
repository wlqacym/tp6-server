<?php


namespace app\service\db;


use app\BaseDbService;

class TestDbSer extends BaseDbService
{
    protected $modelName = 'WxConfig';
    /**
     * dbSer用例
     *
     * @return string
     *
     * @author wlq
     * @since 1.0 20201102
     */
    public function test()
    {
        return 'testDbSer';
    }
}