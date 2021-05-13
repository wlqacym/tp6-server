<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
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
        HttpException::class,
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
//        parent::report($exception);
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
        $code = $e->getCode()?:500;
        $msg         = $this->getMessage($e);
        $description = $e->getTraceAsString();
        if ($e instanceof \ArgumentCountError) {
            $msg         = '缺少必要参数';
            $description = $this->getMessage($e);
        }
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $description = json_decode($e->getMessage(), true);
            $description = $description['error']??$e->getMessage();
            $msg = $description['error']??'请求错误';
            Log::write($e->getMessage(), 'api_error');
        }
        // 添加自定义异常处理机制
        $data = [
            'code'        => $code,
            'msg'         => $msg,
            'description' => $description
        ];
        $log = $data;
        $log['url'] = $this->app->request->controller().'/'.$this->app->request->action();
        $log['method'] = $this->app->request->method();
        $log['param'] = $this->app->request->param();
        Log::write(json_encode($log), 'error');
        return json($data, 500);
    }
}
