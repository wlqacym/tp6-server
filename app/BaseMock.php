<?php


namespace app;


use app\controller\Admin;
use PHPUnit\Framework\TestCase;
use think\App;

class BaseMock extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $mock;

    /**
     * @var array
     */
    protected $data = [];

    public function mock()
    {
        return $this->mock;
    }

    /**
     * 模拟tp查询方法column
     *
     * @param array $data   格式：<p>[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key1' => 'value1',
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key2' => 'value2'
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;]
     * <p>]
     * @param string $fields
     * @param string $key
     * @return array
     *
     * @author wlq
     * @since 1.0 20201029
     */
    protected function dbColumn(array $data, $fields = '*', $key = '')
    {
        $return = [];
        if ($data) {
            if ($fields == '*' && $key == '') {
                $return = [];
            } else {
                if ($fields == '*') {
                    $return = array_column($data, null, $key);
                } elseif (count(explode(',', $fields)) == 1) {
                    $return = array_column($data, $fields, $key?:null);
                } else {
                    $fields = explode(',', $fields);
                    foreach ($data as $val) {
                        $newVal = [];
                        foreach ($fields as $v) {
                            isset($val[$v]) and $newVal[$v] = $val[$v];
                        }
                        $return[] = $newVal;
                    }
                    $key and $return = array_column($return, null, $key);
                }
            }
        }
        return $return;
    }

    /**
     * 模拟tp查询方法select
     *
     * @param array $data 格式：<p>[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key1' => 'value1',
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key2' => 'value2'
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;]
     * <p>]
     * @param string $fields
     * @return array
     *
     * @author wlq
     * @since 1.0 20201029
     */
    protected function dbSelect(array $data, $fields = '*')
    {
        $return = [];
        $fields != '*' and $fields = explode(',', $fields);
        foreach ($data as $v) {
            if (is_array($fields)) {
                $val =[];
                foreach ($fields as $f) {
                    isset($v[$f]) and $val[$f] = $v[$f];
                }
                $return[] = $val;
                $v = $val;
            }
            $return[] = $v;
        }
        return $return;
    }

    /**
     * 模拟tp查询方法find
     *
     * @param array $data 格式：<p>[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;[
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key1' => 'value1',
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key2' => 'value2'
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;]
     * <p>]
     * @param $id
     * @param string $fields
     * @param string $pk
     * @return array|mixed
     *
     * @author wlq
     * @since 1.0 20201029
     */
    protected function dbFind(array $data, $id, $fields = '*', $pk = 'id')
    {
        $fields != '*' and $fields = explode(',', $fields);
        $data = array_column($data, null, $pk);
        $data = $data[$id]??[];
        $return = [];
        if ($data) {
            if (is_array($fields)) {
                $fields = explode(',', $fields);
                foreach ($fields as $f) {
                    isset($data[$f]) and $return[$f] = $data[$f];
                }
            } else {
                $return = $data;
            }
        }
        return $return;
    }
}