<?php


namespace app\api\v1\auth;


use app\api\Base;
use think\Exception;

class Rule extends Base
{
    protected $middleware = [];
    public function indexPost()
    {
        $ident = $this->request->post('ident');
        $this->logic->rule->add($ident);
        return $this->success('新增成功');
    }

    public function indexPut()
    {
        $id = $this->request->get('id');
        if (!$id) {
            throw new Exception('缺少权限id');
        }
        $this->logic->rule->edit($id);
        return $this->success('新增成功');
    }
}