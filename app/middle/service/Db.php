<?php

namespace app\middle\service;


/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\db\TestDbSer $test
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