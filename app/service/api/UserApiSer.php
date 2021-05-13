<?php


namespace app\service\api;


use app\BaseApiService;
use think\exception\HttpException;

/**
 * Class UserApiSer
 * @package app\service\api
 * @codeCoverageIgnore
 */
class UserApiSer extends BaseApiService
{
    protected $apiConfigIdent = 'user';
    protected $d;
    protected $s;
    protected $s1;
    protected $d1;
    /**
     * 初始化
     */
    protected function init()
    {
        parent::init();
        $this->serviceName = '用户';
    }

    /**
     * 接口地址设置
     */
    protected function setPath()
    {
        parent::setPath();
        $path = $this->pathConfig;
        $path['users_edit'] = sprintf($path['users_edit'], $this->d1);
        $this->path = $path;
    }

    /**
     * 查看用户是否注册
     *
     * @param $params
     * @return array
     *
     * @author wlq
     * @since 1.0 20200913
     */
    public function confirmFetch($params)
    {
        $data = $this->request('confirm_fetch', $params, 'postJson', '获取用户信息（用户名获取需要验证合法性）');
        return $data;
    }

}