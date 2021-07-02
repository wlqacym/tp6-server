<?php


namespace app\helper;


use think\facade\Db;

class Mongo
{
    private $connect = 'mongo';
    /**
     * @var Db
     * 
     */
    private static $db;
    
    public static function name($name)
    {
        if (!self::$db) {
            self::$db = app()->db->connect('mongo');
        }
        return self::$db->name($name);
    }
}