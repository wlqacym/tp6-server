<?php


namespace app\api\v1\config;


use app\api\Base;

class Index extends Base
{
    protected $middleware = [];

    /**
     * 新增配置项
     *
     * @return \think\response\Json
     * @throws \think\Exception
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function indexPost()
    {
        $this->logic->config->add();
        return $this->success('新增成功');
    }

    /**
     * 编辑配置项
     *
     * @return \think\response\Json
     * @throws \think\Exception
     *
     * @author wlq
     * @since 1.0 20210511
     */
    public function indexPut()
    {
        $id = $this->request->get('id');
        $this->logic->config->edit($id);
        return $this->success('新增成功');
    }
}