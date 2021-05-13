<?php

namespace app\middle\service;


/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\db\TestDbSer $test
 * @property \app\service\db\AdminDbSer $admin
 * @property \app\service\db\ConfigDbSer $config
 * @property \app\service\db\GroupDbSer $group
 * @property \app\service\db\RuleDbSer $rule
 * @property \app\service\db\UserDbSer $user
 */
class Db
{
    public function setSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'DbSer';
            $classPath = '\\app\\service\\db\\'.$className;
            $this->$class = new $classPath();
        }
        return $this;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        if (!isset($this->$name)) {
            $this->setSer($name);
        }
        return $this->$name;
    }
}