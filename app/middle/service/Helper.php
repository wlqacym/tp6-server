<?php


namespace app\middle\service;


use app\helper\CommonAttr;

/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\helper\TestHelperSer $test
 */
class Helper
{
    use CommonAttr;

    public function setSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'HelperSer';
            $classPath = '\\app\\service\\helper\\'.$className;
            $this->$class = new $classPath();
            $this->$class->setAttrDb($this->db);
            $this->$class->setAttrApi($this->api);
            $this->$class->setAttrApp($this->app);
            $this->$class->setAttrRequest($this->request);
            $this->$class->setAttrNowTime($this->nowTime);
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