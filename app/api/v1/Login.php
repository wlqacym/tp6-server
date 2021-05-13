<?php


namespace app\api\v1;


use app\api\Base;
use think\Exception;
use think\exception\ValidateException;

class Login extends Base
{

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [
    ];

    public function indexPost()
    {
        $params = $this->request->post();
        try {
            $rule = [
                'username' => 'require',
                'password' => 'require'
            ];
            $msg = [
                'username.require' => '请输入账号',
                'password.require' => '请输入密码'
            ];
            $this->validate($params, $rule, $msg);
        }catch (ValidateException $e) {
            return $this->error(400, $e->getError());
        }
        try {
            $data = $this->logic->login->login($params['username'], $params['password']);
            return $this->json(['token_list' => $data]);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}