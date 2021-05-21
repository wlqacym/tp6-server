<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\db\exception\PDOException;
use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Log;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandleApi extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        $logType = 'error';
        $log = [];
        if ($exception instanceof HttpException) {
            $logType = 'api_error';
        } else {
            $log['url'] = $this->app->request->controller().'/'.$this->app->request->action();
            $log['method'] = $this->app->request->method();
            $log['param'] = $this->app->request->param();
        }
        // 使用内置的方式记录异常日志
        if (!$this->isIgnoreReport($exception)) {
            // 收集异常数据
            if ($this->app->isDebug()) {
                $data = [
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                    'message' => $this->getMessage($exception),
                    'code'    => $this->getCode($exception),
                ];
                $log['code'] = $data['code'];
                $log['message'] = $data['message'];
                $log['file'] = $data['file'];
                $log['line'] = $data['line'];

            } else {
                $data = [
                    'code'    => $this->getCode($exception),
                    'message' => $this->getMessage($exception),
                ];
                $log['code'] = $data['code'];
                $log['message'] = $data['message'];
            }

            if ($this->app->config->get('log.record_trace') && $logType != 'api_error') {
                $log['record_trace'] = PHP_EOL . $exception->getTraceAsString();
                $log['record_trace'] = explode('#', $log['record_trace']);
            }

            try {
                if ($logType == 'api_error') {
                    $msg = json_decode($log['message'], true);
                    $log['message'] = $msg?:$log['message'];
                }
                $this->app->log->record(json_encode($log), $logType);
            } catch (Exception $e) {}
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        $code = 500;
        $msg         = $this->getMessage($e);
        $description = $e->getTraceAsString();
        if ($e instanceof \ArgumentCountError) {
            $msg         = '缺少必要参数';
            $description = $this->getMessage($e);
        }
        if ($e instanceof HttpException) {
            $description = json_decode($e->getMessage(), true);
            $msg = $description['error']??'请求错误';
            $description = isset($description['error'])?[
                'title' => $description['title'],
                'error' => $description['error']
            ]:$e->getMessage();
        }
        if ($e instanceof PDOException) {
            $msg = '数据操作异常';
        }
        // 添加自定义异常处理机制
        $data = [
            'code'        => $code,
            'msg'         => $msg,
            'description' => is_array($description)?$description:explode('#', $description)
        ];
        return json($data, 500);
    }
}
