<?php


namespace app\middle\service;


/**
 * Class BaseDbService
 * @package app\service
 * @property \app\service\helper\TestHelperSer $test
 */
class Helper
{
    protected $db;
    protected $api;
    public function __construct(Db $db, Api $api)
    {
        $this->db = $db;
        $this->api = $api;
    }

    public function setSer($class, $object = null)
    {
        if ($object && is_object($object)) {
            $this->$class = $object;
        } else {
            $className = ucfirst($class).'HelperSer';
            $classPath = '\\app\\service\\helper\\'.$className;
            $this->$class = new $classPath($this->db, $this->api);
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