<?php


namespace app\middle;

use app\helper\CommonAttr;
use app\middle\service\Api;
use app\middle\service\Db;
use app\middle\service\Helper;

/**
 * Class MiddleLogic
 * @package app\logic
 * @codeCoverageIgnore
 * @property \app\logic\TestLogic $test
 */
class Logic
{
    use CommonAttr;

    public function init()
    {
        $this->initAttrDb();
        $this->initAttrApi();
        $this->initAttrHelper();
        $this->initAttrNowTime();
    }

    public function setLogic($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'Logic';
            $classPath = '\\app\\logic\\'.$className;
            $this->$class = new $classPath();
            $this->$class->setAttrDb($this->db);
            $this->$class->setAttrApi($this->api);
            $this->$class->setAttrHelper($this->helper);
            $this->$class->setAttrApp($this->app);
            $this->$class->setAttrRequest($this->request);
            $this->$class->setAttrNowTime($this->nowTime);
            $this->$class->initHelper();
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