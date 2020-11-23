<?php


namespace app\middle;

/**
 * Class MiddleLogic
 * @package app\logic
 * @codeCoverageIgnore
 * @property \app\logic\TestLogic $test
 */
class Logic
{
    public function setLogic($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'Logic';
            $classPath = '\\app\\logic\\'.$className;
            $this->$class = new $classPath();
        }
        return $this;
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->setLogic($name);
        }
        return $this->$name;
    }
}