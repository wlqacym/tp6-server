<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-5-18
 * Time: 14:15
 */

namespace app\model;

use think\Model;

class ConfigEnum extends Model
{
    protected $pk = 'id';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $schema = [
        'id' => 'int',
        'cid' => 'int',      //配置项id
        'key' => 'string',      //配置值key
        'value' => 'string',      //配置值value
        'sort' => 'int',      //排序',
        'create_user' => 'int',
        'create_time' => 'int',
        'update_user' => 'int',
        'update_time' => 'int',
    ];

}