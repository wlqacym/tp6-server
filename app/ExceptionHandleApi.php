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
        // 使用内置的方式记录异常日志
        if (!$this->isIgnoreReport($exception)) {

            $log = [
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'message' => $this->getMessage($exception),
                'code'    => $this->getCode($exception),
            ];
            if ($this->app->isDebug()) {
                $log['record_trace'] = PHP_EOL . $exception->getTraceAsString();
                $log['record_trace'] = explode('#', $log['record_trace']);
            }
            $this->app->logger->setRecordTrace($log);
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
        $this->app->logger->enable();
        $this->app->logger->setLevel(3);
        $code = 500;
        $msg         = $this->getMessage($e);
        $description = $e->getTraceAsString();
        if ($e instanceof \ArgumentCountError) {
            $msg         = '缺少必要参数';
            $description = $this->getMessage($e);
        }
        if ($e instanceof PDOException) {
            $this->app->logger->setLevel(4);
            $msg = '数据操作异常';
        }
        // 添加自定义异常处理机制
        $data = [
            'code'        => $code,
            'msg'         => $msg,
            'description' => is_array($description)?$description:explode('#', $description)
        ];
        if (!$this->app->isDebug()) {
            $data['description'] = $data['msg'];
        }
        app()->logger->setResponse($data, 500);
        return json($data, 500);
    }
}
