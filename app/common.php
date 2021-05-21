<?php
// 应用公共文件
/**
 * 转换以key为键的数组，重复的key不覆盖，值添加key的数组末位
 *
 * @param array $data
 * @param string $key
 * @return array
 *
 * @author wlq
 * @since 1.0 20210324
 */
function bf_array_column(array $data, string $key)
{
    $dataNew = [];
    foreach ($data as $v) {
        $dataNew[$v[$key]] = $dataNew[$v[$key]]??[];
        $dataNew[$v[$key]][] = $v;
    }
    return $dataNew;
}

/**
 * 枚举 + 日期转换
 *
 * @param array $data
 * @param array $enum
 * @param array $date
 *
 * @return bool
 * @since 1.0 20210520
 * @author wlq
 */
function runEnum(array &$data, array $enum = [], array $date = [])
{
    if (!$enum && !$date) {
        return true;
    }
    foreach ($data as $k => &$v) {
        foreach ($enum as $fe => $ve) {
            isset($v[$fe]) and $v[$fe.'_show'] = $ve[$v[$fe]]??'';
        }
        foreach ($date as $fd => $vd) {
            isset($v[$fd]) and $v[$fd.'_show'] = $v[$fd]?date($vd, $v[$fd]):'';
        }
    }
}

/**
 * 云课表班级转换
 * @param int $grade
 * @return array
 * @author zqk
 * @since 1.0 20210517
 */
function cloud_to_resource(int $grade)
{
    $data = [];
    if (!$grade) {
        return $data;
    }
    if ($grade < 20) {
        $resGrade = ($grade-10)*10;
    } else {
        //初高中
        $resGrade = ((floor($grade/10)*3)+$grade%10)*10;
    }
    $data= [
        'min' => $resGrade,
        'max' => $resGrade + 10
    ];
    return $data;
}


/**
 * 根据传入参数，组成路径返回
 *
 *
 * @return string
 *
 * create by zqk 20200624
 */
function build_path()
{
    $args = func_get_args();
    $path = [];
    for ($i = 0; $i < func_num_args(); $i++) {
        if (!$args[$i]) {
            continue;
        }
        $path[] = rtrim($args[$i], '/');
    }
    return implode('/', $path);
}