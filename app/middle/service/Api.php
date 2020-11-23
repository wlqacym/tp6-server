<?php


namespace app\middle\service;


/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\api\TestApiSer $test
 */
class Api
{
    public function setSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'ApiSer';
            $classPath = '\\app\\service\\api\\'.$className;
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