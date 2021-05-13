<?php

namespace app\model;

use app\BaseModel;

class WxConfig extends BaseModel{

    // 设置字段信息
    protected $schema = [
        'id' => 'int',                      //
        'ident' => 'string',                //标识
        'name' => 'string',                 //名称
        'app_id' => 'string',               //开发者id
        'secret' => 'string',               //开发者密码secret
        'token' => 'string',                //令牌
        'encoding_aes_key' => 'string',     //消息加密秘钥
        'type' => 'int',                    //类型，1=公众号，2=小程序
        'del' => 'int',                     //
        'create_time' => 'int',             //
        'create_user' => 'int',             //
        'update_time' => 'int',             //
        'update_user' => 'int',             //
    ];

}