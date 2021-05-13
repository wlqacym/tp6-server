<?php

namespace app\model;

use app\BaseModel;

class SysRule extends BaseModel
{

    // 设置字段信息
    protected $schema = [
        'id' => 'int',
        'pid' => 'int',     //父级id',
        'href' => 'string',     //控制器/方法',
        'method' => 'string',     //请求方式',
        'title' => 'string',     //标题',
        'ident' => 'string',     //标识',
        'auth_open' => 'int',     //是否验证权限，1=是，0=否',
        'is_same_pid' => 'int',     //是否跟随父级权限选中，1=是，0=否',
        'icon' => 'string',     //图标',
        'sort' => 'int',     //排序',
        'type' => 'int',     //类型，1=菜单目录，2=按钮，3=接口，4=特殊权限',
        'is_show' => 'int',     //是否展示，0=否，1=是',
        'del' => 'int',     //删除，0=否，非0=是',
        'create_time' => 'int',     //创建时间',
        'create_user' => 'int',     //创建人',
        'update_time' => 'int',     //更新时间',
        'update_user' => 'int',     //更新人',
    ];

}