<?php


namespace app;


use HttpClient\HttpClient;
use think\Exception;
use think\exception\HttpException;
use think\facade\Log;

/**
 * Class BaseApiService
 * @package app
 * @codeCoverageIgnore
 */
class BaseApiService
{

    protected $url = '';
    protected $path = [];
    protected $serviceName = '';
    protected $checkCode = false;
    public function __construct()
    {
        $this->init();
    }

    protected function init(){}

    protected function setPath(){}

    /**
     * 请求服务
     *
     * @param string $pathIndex
     * @param array $params
     * @param string $method
     * @param string $logTip
     * @param string $errorTip
     * @param array $private 自定义设置，格式
     * <p>
     * [
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'body'=>false,//参数是否在body中提交</p>
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'isJson'=>false,//参数是否json格式</p>
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'header'=>[['key1'=>'value1'],['key2'=>'value2']],//</p>
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'log'=>false,//是否强制记录日志（非get请求强制记录日志），默认false</p>
     * ]
     * </p>;
     * @return mixed
     *
     * @throws Exception
     * @author wlq
     * @since 1.0 20201028
     */
    protected function request(string $pathIndex, array $params, string $method, string $logTip, string $errorTip = 'msg', $private = [])
    {
        $this->setPath();
        $private['body'] = $private['body']??false;
        $private['isJson'] = $private['isJson']??false;
        $private['header'] = $private['header']??[];
        $private['log'] = $private['log']??false;
        $serviceName = $this->serviceName;
        $log = [
            'error' => '',
            'title' => "{$serviceName}服务-".$logTip,
            'url' => $this->url,
            'method' => $method,
            'path' => '',
            'param' => $params,
            'response' => '',
            'desc' => ''
        ];
        if (!$this->url) {
            $log['error'] = "未配置{$serviceName}服务请求地址";
            throw new HttpException(400, json_encode($log));
        }
        $path = $this->path[$pathIndex]??'';
        if (!$path) {
            $log['error'] = "未配置{$serviceName}服务请求地址";
            throw new HttpException(400, json_encode($log));
        }
        $log['path'] = $path;
        try {
            $http = new HttpClient($this->url);
            if ($private['body']) {
                $http->setBodyParams($params,true);
            }
            if ($private['isJson']) {
                $http->setHeader('Content-Type', 'application/json');
                $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            }
            if ($private['header']) {
                foreach ($private['header'] as $k => $v) {
                    $http->setHeader($k, $v);
                }
            }
            $response = $http->$method($path, $params);
        } catch (\Exception $e) {
            $log['error'] = "{$serviceName}服务请求异常";
            $log['desc'] = $e->getMessage();
            throw new HttpException(400, json_encode($log));
        }
        $resData = $response->getJsonBody(true);
        $log['response'] = $resData?:$response->getBody();
        if (!$response->isSuccess() || ($this->checkCode && isset($resData['code']) && floor($resData['code']/100) != 2)) {
            $log['error'] = $resData[$errorTip] ?? '获取失败';
            throw new HttpException($response->getCode(), json_encode($log));
        }
        //非get请求强制记录日志，get请求默认不记录日志
        if ($private['log'] || $method != 'get') {
            Log::write(json_encode($log), 'api_success');
        }
        return $resData;
    }
}