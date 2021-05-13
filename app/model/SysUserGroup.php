<?php


namespace app\model;


use app\service\Admin;
use think\Exception;
use think\Model;

class SysUserGroup extends Model
{
    protected $pk = 'user_id';
    protected $schema = [
        'user_id' => 'int',  //用户id',
        'group_id' => 'int',  //分组id',
        'create_time' => 'int',  //创建时间',
        'create_user' => 'int',  //创建人',
    ];
    protected $createTime = 'create_time';
    /**
     * 创建前记录创建更新人
     *
     * @param Model $model
     * @return mixed|void
     * @throws Exception
     *
     * @author  wlq
     * @since   v1.0    20200604
     */
    public static function onBeforeInsert(Model $model)
    {
        try {
            $loginUser = Admin::loginInfo();
        } catch (Exception $e) {
            $loginUser = ['id' => 0];
        }
        $model->create_user = $loginUser['id'];
    }
}