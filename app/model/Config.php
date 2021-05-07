<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-5-18
 * Time: 14:15
 */

namespace app\model;

use think\Model;

class Config extends Model
{
    protected $pk = 'id';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $schema = [
        'id' => 'int',
        'explain' => 'string',    //说明
        'ident' => 'string',    //标识
        'type' => 'string',    //类型
        'sort' => 'int',    //排序
        'create_user' => 'int',
        'create_time' => 'int',
        'update_user' => 'int',
        'update_time' => 'int',
    ];

}