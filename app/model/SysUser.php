<?php


namespace app\model;


use app\BaseModel;

class SysUser extends BaseModel
{
    protected $schema = [
        'id' => 'int',
        'zty_user_id' => 'int',   //智通云账户id
        'user_name' => 'string',   //账户
        'mobile' => 'string',   //手机号
        'sex' => 'int',   //0未知 1男 2女
        'real_name' => 'string',   //姓名
        'status' => 'int',   //状态 1启用 2禁用
        'school_id' => 'int',   //学校id
        'school_name' => 'string',   //学校名称
        'imgs' => 'string',   //用户图片
        'relation_id' => 'int',   //关联用户id
        'del' => 'int',   //是否删除 0否 1是
        'create_time' => 'int',   //创建时间
        'create_user' => 'int',   //创建人
        'update_time' => 'int',   //修改人
        'update_user' => 'int',   //更新人
    ];
}