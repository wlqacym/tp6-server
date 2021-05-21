<?php


namespace app\api\v1\auth;


use app\api\Base;
use think\Exception;

class Rule extends Base
{
    protected $middleware = [];

    /**
     * 获取所有权限
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210518
     */
    public function indexGet()
    {
        $rules = $this->logic->rule->get();
        return $this->json($rules);
    }

    /**
     * 新增权限
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210518
     */
    public function indexPost()
    {
        $params = $this->request->post();
        $rule = [
            'pid' => 'require',
            'href' => 'require',
            'method' => 'require',
            'title' => 'require',
            'ident' => 'require',
            'auth_open' => 'in:0,1',
            'is_same_pid' => 'in:0,1',
            'type' => 'in:1,2,3,4',
            'is_show' => 'in:0,1',
        ];
        $msg = [
            'href.require' => '缺少控制器/方法',
            'method.require' => '缺少请求方式',
            'title.require' => '缺少标题',
            'ident.require' => '缺少标识',
            'auth_open.in' => '是否验证权限值错误',
            'is_same_pid.in' => '是否跟随父级值错误',
            'type.in' => '类型值错误',
            'is_show.in' => '是否展示值错误',
        ];

        $this->validate($params, $rule, $msg);
        $params['method'] = strtoupper($params['method']);
        if (!in_array($params['method'], ['POST', 'GET', 'DELETE', 'PUT', 'PATCH'])) {
            throw new Exception('请求方式值错误', 400);
        }
        $ident = $this->request->post('ident');
        $this->logic->rule->add($ident);
        return $this->success('新增成功');
    }

    /**
     * 编辑权限
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210518
     */
    public function indexPut()
    {
        $id = $this->request->get('id');
        if (!$id) {
            throw new Exception('缺少权限id');
        }
        $params = $this->request->put();
        $rule = [
            'pid' => 'require',
            'href' => 'require',
            'method' => 'require',
            'title' => 'require',
            'ident' => 'require',
            'auth_open' => 'in:0,1',
            'is_same_pid' => 'in:0,1',
            'type' => 'in:1,2,3,4',
            'is_show' => 'in:0,1',
        ];
        $msg = [
            'href.require' => '缺少控制器/方法',
            'method.require' => '缺少请求方式',
            'title.require' => '缺少标题',
            'ident.require' => '缺少标识',
            'auth_open.in' => '是否验证权限值错误',
            'is_same_pid.in' => '是否跟随父级值错误',
            'type.in' => '类型值错误',
            'is_show.in' => '是否展示值错误',
        ];

        $this->validate($params, $rule, $msg);
        $params['method'] = strtoupper($params['method']);
        if (!in_array($params['method'], ['POST', 'GET', 'DELETE', 'PUT', 'PATCH'])) {
            throw new Exception('请求方式值错误', 400);
        }
        $this->logic->rule->edit($id);
        return $this->success('编辑成功');
    }

    /**
     * 删除权限
     *
     * @return \think\response\Json
     * @throws Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function indexDelete()
    {
        $ids = $this->request->delete('ids');
        if (!$ids) {
            throw new Exception('未选则权限');
        }
        $this->logic->rule->del($ids);
        return $this->success('删除成功');
    }
}