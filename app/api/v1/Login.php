<?php


namespace app\api\v1;


use app\api\Base;
use app\middleware\RuleCheck;
use think\Exception;

class Login extends Base
{

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [
        'app\middleware\RuleCheck' => [
            'except' => [
                'index',
                'out',
                'token',
                'silence'
            ],
        ]
    ];

    /**
     * 账户密码登陆
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function indexPost()
    {
        $params = $this->request->post();
        $rule = [
            'username' => 'require',
            'password' => 'require'
        ];
        $msg = [
            'username.require' => '请输入账号',
            'password.require' => '请输入密码'
        ];
        $this->validate($params, $rule, $msg);
        $data = $this->logic->login->login($params['username'], $params['password']);
        return $this->json(['token_list' => $data]);
    }

    /**
     * 获取登陆信息
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function infoGet()
    {
        $data = $this->logic->login->loginInfo();
        return $this->json($data);
    }

    /**
     * 登出
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function outGet()
    {
        $this->logic->login->out();
        return $this->success('注销登陆成功');
    }

    /**
     * 获取静默登陆token
     *
     * @return \think\response\Json
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function tokenGet()
    {
        $token = $this->logic->login->makeSilenceToken();
        return $this->json(['silence_token' => $token]);
    }

    /**
     * 静默登陆
     *
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public function silencePost()
    {
        $token = $this->request->post('silence_token');
        if (!$token) {
            throw new Exception('缺少token');
        }
        $userId = $this->request->post('zty_user_id');
        if (!$userId) {
            throw new Exception('缺少用户id');
        }
        $data = $this->logic->login->silence($token, $userId);
        return $this->json($data);
    }
}