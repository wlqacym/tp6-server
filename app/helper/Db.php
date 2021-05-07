<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-05-28
 * Time: 18:34
 */

namespace app\helper;

use think\Exception;
use think\facade\Log;
use think\Model;
use think\Response;

trait Db
{
    /**
     * 数据模型名称
     *
     * @var string
     */
    protected $modelName;

    /**
     * 获取数据模型
     *
     * @param string|null $modelName
     * @return string|Model
     *
     * @author wlq
     * @since 1.0 20210429
     */
    private function getModel(string $modelName = null)
    {
        $modelName = $modelName??$this->modelName;
        return '\\app\model\\'.$modelName;
    }

    /**
     * 主键获取数据
     *
     * @param $id
     * @param string|null $modelName
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getById($id, string $modelName = null):array
    {
        $modelPath = $this->getModel($modelName);
        $data = $modelPath::find($id);
        return $data?$data->toArray():[];
    }

    /**
     * 查询条件查询数据
     * <br>(($fields = '*' & $key = ‘’) || $key = null)，则返回以主键为键名的数组
     * <br>($fields != '*' & $key != null')，则返回以$key为键名的数组【$key=''时，返回有序数组】
     *
     * @param null $where
     * @param string $fields
     * @param string|null $key
     * @param string|null $modelName
     * @return array
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getWhereByKey($where = null, string $fields = '*', string $key = null, string $modelName = null):array
    {
        $modelPath = $this->getModel($modelName);
        $model = $modelPath::where($where);
        $key = $key===null?$model->getPk():$key;
        return $model->column($fields, $key);
    }

    /**
     * 查询条件单表分页查询
     *
     * @param null $where
     * @param int $page
     * @param int $size
     * @param string $fields
     * @param string $order
     * @param string|null $modelName
     * @return array
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getPage(
        $where = null,
        $page = 0,
        $size = 1,
        $fields = '*',
        $order = '',
        string $modelName = null
    ):array
    {
        $modelPath = $this->getModel($modelName);
        $model = $modelPath::where($where);
        $data = $model->field($fields)->order($order)->page($page, $size)->select()->toArray();
        $count = $model->count($model->getPk());
        return $this->setPageData($data, $page, $size, $count);
    }

    /**
     * 分页数据整理格式
     *
     * @param $rows
     * @param $page
     * @param $size
     * @param $count
     * @return array
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function setPageData($rows, $page, $size, $count):array
    {
        return ['rows' => $rows, 'total' => $count, 'next' => $page*$size>=$count?0:1, 'page' => $page, 'size' => $size];
    }
    /**
     * 批量主键获取数据
     *
     * @param array $ids
     * @param string $fields
     * @param string|null $modelName
     * @return mixed
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function getByIds(array $ids, string $fields = '*', string $modelName = null)
    {
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $pk = $model->getPk();
        return $model->where($pk, 'in', $ids)->column($fields, $pk);
    }

    /**
     * 单条新增数据
     * <br>新增数据默认使用post请求参数
     *
     * @param string|null $modelName
     * @return mixed
     *
     * @author wlq
     * @since 1.0 20210429
     */
    public function insertOne(string $modelName = null)
    {
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $model->save(app()->request->post());
        $pk = $model->getPk();
        return $model->$pk;
    }

    /**
     * 批量添加数据
     * <br>新增数据默认使用post请求参数insertAll数据集合
     *
     * @param array|null $data
     * @param string|null $modelName
     * @return bool
     *
     * @author wlq
     * @since 1.0 20210428
     */
    public function insertAll(array $data = null, string $modelName = null):bool
    {
        $modelPath = $this->getModel($modelName);
        $model = new $modelPath();
        $filedType = $model->getOptions()['field_type'];
        //参数校验
        $data = $this->makeAllData($filedType, $data);
        $model->insertAll($data);
        return true;
    }

    /**
     * 生成批量新增数据
     * <br>新增数据默认使用post请求参数insertAll数据集合
     * <br>自动过滤非数据表数据
     * <br>自动填充为提交字段默认值
     *
     * @param $filedType
     * @param array|null $data
     * @param array $autoField
     * @return array
     *
     * @author wlq
     * @since 1.0 20210428
     */
    public function makeAllData($filedType, array $data = null, array $autoField = []):array
    {
        $data = $data?:app()->request->post('insertAll');
        $insertAll = [];
        //字段类型默认值规则
        $typeDefaultVal = ['int' => 0, 'float' => 0, 'string' => ''];
        //自动填充字段
        $time = time();
        isset($filedType['create_time']) and $autoField['create_time'] = $time;
        isset($filedType['update_time']) and $autoField['update_time'] = $time;
        foreach ($data as $value) {
            $save = [];
            foreach ($filedType as $field => $type) {
                $save[$field] = $value[$field]??($autoField[$field]??$typeDefaultVal[$type]);
            }
            $insertAll[] = $save;
        }
        return $insertAll;
    }
}