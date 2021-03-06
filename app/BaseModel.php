<?php
declare (strict_types = 1);

namespace app;

use app\service\Admin;
use think\Exception;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 模型基础类
 */
class BaseModel extends Model
{
    protected $pk = 'id';
    use SoftDelete;
    protected $deleteTime = 'del';
    protected $defaultSoftDelete = 0;
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    //TODO  记录创建|更新人


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
        $model->update_user = $loginUser['id'];
    }

    /**
     * 更新前记录更新人
     *
     * @param Model $model
     * @return mixed|void
     * @throws Exception
     *
     * @author  wlq
     * @since   v1.0    20200604
     */
    public static function onBeforeUpdate(Model $model)
    {
        unset($model->create_time);
        unset($model->update_time);
        try {
            $loginUser = Admin::loginInfo();
        } catch (Exception $e) {
            $loginUser = ['id' => 0];
        }
        $model->update_user = $loginUser['id'];
    }
}
