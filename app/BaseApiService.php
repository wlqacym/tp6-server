<?php


namespace app;


use HttpClient\HttpClient;
use think\Exception;
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
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'body'=>false,</p>
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'isJson'=>false,</p>
     * <p>&nbsp;&nbsp;&nbsp;&nbsp;'header'=>[['key1'=>'value1'],['key2'=>'value2']]</p>
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
        $serviceName = $this->serviceName;
        if (!$this->url) {
            throw new Exception("未配置{$serviceName}服务请求地址", 400);
        }
        $path = $this->path[$pathIndex]??'';
        if (!$path) {
            throw new Exception("未配置{$serviceName}服务请求地址", 400);
        }
        $log = [
            'title' => "{$serviceName}服务-".$logTip,
            'url' => $this->url,
            'method' => $method,
            'path' => $path,
            'param' => $params,
        ];
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
            $log['error'] = $e->getMessage();
            Log::write(json_encode($log), 'api_error');
            throw new Exception( "{$serviceName}服务请求异常", 400);
        }
        $resData = $response->getJsonBody(true);
        $log['response'] = $resData?:$response->getBody();
        if (!$response->isSuccess() || ($this->checkCode && isset($resData['code']) && floor($resData['code']/100) != 2)) {
            Log::write(json_encode($log), 'api_error');
            throw new Exception($resData[$errorTip] ?? '获取失败', 500);
        }
        Log::write(json_encode($log), 'api_success');
        return $resData;
    }
}