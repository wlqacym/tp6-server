<?php


namespace app\service\db;


use app\BaseDbService;
use app\model\SysUser;
use app\model\SysUserGroup;
use think\Db;
use think\db\exception\DbException;
use think\Exception;
use think\facade\Log;

/**
 * Class UserDbSer
 * @package app\service\db
 * @codeCoverageIgnore
 */
class UserDbSer extends BaseDbService
{
    /**
     * 数据模型名称
     *
     * @var string
     */
    protected $modelName = 'SysUser';

    /**
     * 用户服务用户id获取用户信息
     *
     * @param $ztyId
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210510
     */
    public function getByZtyId($ztyId)
    {
        $data = ($this->getModel())::where('zty_user_id', $ztyId)->find();
        return $data?$data->toArray():[];
    }

}