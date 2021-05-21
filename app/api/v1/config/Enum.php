<?php


namespace app\api\v1\config;


use app\api\Base;
use think\Exception;

class Enum extends Base
{

    /**
     * 新增配置值
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
            'key' => 'require',
            'value' => 'require',
            'cid' => 'require',
        ];
        $msg = [
            'key.require' => '缺少键名',
            'value.require' => '缺少键值',
            'cid.require' => '缺少配置项id',
        ];
        $this->validate($this->request->post(), $rules, $msg);
        $this->logic->config->addEnum();
        return $this->success('新增成功');
    }

    /**
     * 编辑配置值
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
            throw new Exception('缺少配置值id');
        }
        $rules = [
            'key' => 'require',
            'value' => 'require',
            'cid' => 'require',
        ];
        $msg = [
            'key.require' => '缺少键名',
            'value.require' => '缺少键值',
            'cid.require' => '缺少配置项id',
        ];
        $this->validate($this->request->put(), $rules, $msg);
        $this->logic->config->editEnum($id);
        return $this->success('编辑成功');
    }
}