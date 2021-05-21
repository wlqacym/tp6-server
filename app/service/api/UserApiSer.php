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
        $path['get_user_by_id'] = sprintf($path['get_user_by_id'], $this->d);
        $path['classes_teaching'] = sprintf($path['classes_teaching'], $this->d);
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

    /**
     * 学校名称查询学校
     * @param $name
     * @param $page
     * @param $size
     * @return mixed
     * @throws \think\Exception
     * @author Eric
     * @since 1.0 2021/5/12
     */
    public  function searchSchoolByName($name, $page, $size)
    {
        $params = ['page' => $page, 'size' => $size, 'keywords' => $name];
        $data = $this->request('school_search',$params,'get','学校名称查询学校');
        return $data;
    }

    /**
     * 获取政班的详细信息
     *
     * @param string $ztyClassIds
     * @param false $needMembers
     * @return mixed
     * @throws \think\Exception
     *
     * @author wlq
     * @since 1.0 20210517
     */
    public  function getClassById(string $ztyClassIds, $needMembers = false)
    {
        $params = ['classIds' => $ztyClassIds, 'needMembers' => $needMembers];
        $data = $this->request('get_class_by_id',$params,'get','获取政班的详细信息');
        return $data;
    }

    /**
     * 搜索教师
     * @param $param
     * @return mixed
     * @throws \think\Exception
     * @author zqk
     * @since 1.0 20210514
     */
    public function searchUser($param)
    {
        $data = $this->request('users_search',$param,'get','查询教师');
        return $data;
    }

    public function getUserById($ztyId)
    {
        $this->d = $ztyId;
        $data = $this->request('get_user_by_id', [], 'get', '获取用户信息');
        return $data;
    }

    /**
     * 批量获取用户信息
     *
     * @param string $ztyIds
     * @return mixed
     * @throws \think\Exception
     *
     * @author wlq
     * @since 1.0 20210519
     */
    public function getUserByIds(string $ztyIds)
    {
        $param = [
            'userIds' => $ztyIds
        ];
        $data = $this->request('get_user_by_ids', $param, 'postJson', '批量获取用户信息');
        return $data;
    }

    /**
     * 获取老师任教的所有行政班
     *
     * @param $ztyId
     * @throws \think\Exception
     *
     * @author wlq
     * @since 1.0 20210520
     */
    public function classesTeaching($ztyId)
    {
        $this->d = $ztyId;
        return $this->request('classes_teaching', ['status' => 1], 'get', '获取老师任教的所有行政班');
    }
}