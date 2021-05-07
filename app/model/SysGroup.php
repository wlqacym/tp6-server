<?php

namespace app\model;

use app\BaseModel;

class SysGroup extends BaseModel
{

    // 设置字段信息
    protected $schema = [
        'id' => 'int',
        'name' => 'string',
        'ident' => 'string',
        'blurb' => 'string',
        'rules' => 'string',
        'rules_show' => 'string',
        'del' => 'int',
        'create_time' => 'int',
        'create_user' => 'int',
        'update_time' => 'int',
        'update_user' => 'int'
    ];

    public function setRulesAttr($value)
    {
        $value = $value?:'';
        return $value;
    }

    public function getRulesAttr($value)
    {
        $value = $value?:'';
        return $value;
    }

    public function setRulesShowAttr($value)
    {
        $value = $value?:'';
        return $value;
    }

    public function getRulesShowAttr($value)
    {
        $value = $value?:'';
        return $value;
    }
}