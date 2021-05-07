<?php

namespace app\model;

use app\BaseModel;

class SysRule extends BaseModel
{

    // 设置字段信息
    protected $schema = [
        'id' => 'int',
        'pid' => 'int',
        'href' => 'string',
        'method' => 'string',
        'title' => 'string',
        'ident' => 'string',
        'auth_open' => 'int',
        'same_pid' => 'int',
        'icon' => 'string',
        'sort' => 'int',
        'menu_status' => 'int',
        'path_id' => 'int',
        'is_btn' => 'int',
        'del' => 'int',
        'create_time' => 'int',
        'create_user' => 'int',
        'update_time' => 'int',
        'update_user' => 'int'
    ];

}