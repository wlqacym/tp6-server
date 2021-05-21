<?php


namespace app\api\v1\config;


use app\api\Base;
use think\Exception;

class Index extends Base
{
    protected $middleware = [];

    public function indexGet()
    {
        $ident = $this->request->get('ident');
        $type = $this->request->get('type');
        $data = $this->logic->config->getType($type, $ident);
        return $this->json($data);
    }
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
        $rules = [
            'explain' => 'require',
            'ident' => 'require',
            'type' => 'require',
        ];
        $msg = [
            'explain.require' => '缺少说明',
            'ident.require' => '缺少标识',
            'type.require' => '缺少类型',
        ];
        $this->validate($this->request->post(), $rules, $msg);
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
        if (!$id) {
            throw new Exception('缺少配置项id');
        }
        $rules = [
            'explain' => 'require',
            'ident' => 'require',
            'type' => 'require',
        ];
        $msg = [
            'explain.require' => '缺少说明',
            'ident.require' => '缺少标识',
            'type.require' => '缺少类型',
        ];
        $this->validate($this->request->put(), $rules, $msg);
        $this->logic->config->edit($id);
        return $this->success('编辑成功');
    }
}