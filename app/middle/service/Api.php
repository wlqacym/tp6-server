<?php


namespace app\middle\service;


/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\api\TestApiSer $test
 * @property \app\service\api\UserApiSer $user
 */
class Api
{
    protected $apiConfigEnum;

    public function setSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'ApiSer';
            $classPath = '\\app\\service\\api\\'.$className;
            $this->$class = new $classPath();
            $this->$class->initConfig($this->apiConfigEnum);
        }
        return $this;
    }

    public function setApiConfigEnum($data)
    {
        $this->apiConfigEnum = $data;
    }

    public function getApiConfigEnum()
    {
        return $this->apiConfigEnum;
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