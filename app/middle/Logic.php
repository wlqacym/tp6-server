<?php


namespace app\middle;

use app\helper\CommonAttr;
use app\middle\service\Api;
use app\middle\service\Db;
use app\middle\service\Helper;
use think\Exception;

/**
 * Class MiddleLogic
 * @package app\logic
 * @codeCoverageIgnore
 * @property \app\logic\TestLogic $test
 * @property \app\logic\RuleLogic $rule
 * @property \app\logic\ConfigLogic $config
 * @property \app\logic\GroupLogic $group
 * @property \app\logic\LoginLogic $login
 */
class Logic
{
    use CommonAttr;
    protected $a;
    public function init()
    {
        $this->initAttrDb();
        $this->initAttrApi();
        $this->initAttrHelper();
        $this->initAttrNowTime();
        $this->initAttrLoginInfo();
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
            $this->$class->setAttrLoginInfo($this->loginInfo);
            $this->$class->initHelper();
            if (!$this->api->getApiConfigEnum()) {
                try {
                    $config = $this->helper->config->getByType('api_service');
                    $config = array_column($config, null, 'ident');
                } catch (Exception $e) {
                    $config = [];
                }
                $this->api->setApiConfigEnum($config);
            }
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