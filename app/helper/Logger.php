<?php


namespace app\helper;


use think\facade\Log;

class Logger
{
    const LEVEL_LOW = 1;
    const LEVEL_MID = 2;
    const LEVEL_HIGH = 3;
    const LEVEL_DANGER = 4;

    private $handler;

    private $startTime;
    private $startMem;
    private $enable = true;


    private $module = '';
    private $controller = '';
    private $action = '';
    private $level = self::LEVEL_LOW;
    private $operatorId = 0;
    private $operatorName = '';

    private $process = [];
    private $parameters = [];

    private $explain = '';
    private $responseCode = 200;
    private $responseBody = '';

    private $recordTrace = '';

    /**
     * 全链路-traceId
     * @var string
     */
    private $traceId = '';
    /**
     * 全链路-客户端请求spanId
     * @var string
     */
    private $spanId = '';
    private $headerVersion = '';
    private $headerGrayService = '';

    public function __construct()
    {
        $this->startTime = defined('START_TIME') ? START_TIME : microtime(true);
        $this->startMem = defined('START_MEM') ? START_MEM : memory_get_usage();
    }

    public function setStartTime(float $startTime)
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function setStartMem(int $startMem)
    {
        $this->startMem = $startMem;
        return $this;
    }

    public function setModule(string $module)
    {
        $this->module = $module;
        return $this;
    }

    public function setController(string $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function setAction(string $action)
    {
        $this->action = $action;
        return $this;
    }

    public function setLevel(int $level)
    {
        if ($level >= self::LEVEL_LOW && $level <= self::LEVEL_DANGER) {
            $this->level = $level;
        }
        return $this;
    }

    public function setTraceId(string $traceId)
    {
        $this->traceId = $traceId;
        return $this;
    }
    public function setSpanId(string $spanId)
    {
        $this->spanId = $spanId;
        return $this;
    }
    public function setHeaderVersion(string $headerVersion)
    {
        $this->headerVersion = $headerVersion;
        return $this;
    }
    public function setHeaderGrayService(string $headerGrayService)
    {
        $this->headerGrayService = $headerGrayService;
        return $this;
    }
    public function setOperator(int $id, string $name = '')
    {
        $this->operatorId = $id;
        $this->operatorName = $name;
        return $this;
    }

    public function getOperator()
    {
        return [$this->operatorId, $this->operatorName];
    }

    public function enable()
    {
        $this->enable = true;
        return $this;
    }

    public function disable()
    {
        $this->enable = false;
        return $this;
    }

    public function setExplain(string $explain, bool $cover = true)
    {
        if ($cover || !$this->explain) {
            $this->explain = $explain;
        }
        return $this;
    }

    public function setRecordTrace($recordTrace)
    {
        $this->recordTrace = $recordTrace;
        return $this;
    }

    public function setResponse($body, $code = 200)
    {
        $this->responseCode = $code;
        $this->responseBody = is_string($body) ? $body : json_encode($body, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * 添加过程数据（建议带上key，方便查询）
     *
     * @param string $key
     * @param mixed $process
     * @return $this
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function addProcess(string $key, $process)
    {
        $this->process[$key] = $process;
        return $this;
    }

    /**
     * 添加额外参数（同级）
     *
     * @param $name
     * @param $value
     * @return $this
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    public function isEnable()
    {
        return $this->enable;
    }

    public function save()
    {
        if (!$this->isEnable()) {
            return '';
        }
        $url = isset($_SERVER['REQUEST_URI'])?parse_url($_SERVER['REQUEST_URI']):'';
        $path = $url['path'] ?? '';
        $query = [];
        if (isset($url['query'])) {
            parse_str($url['query'], $query);
        }
        $data = [
            'router' => [
                'module' => $this->module,
                'controller' => $this->controller,
                'action' => $this->action
            ],
            'level' => $this->level,
            'explain' => $this->explain,
            'request' => [
                'uri' => $_SERVER['REQUEST_URI']??'',
                'method' => $_SERVER['REQUEST_METHOD']??'',
                'path' => $path,
                'query' => $query,
                'body' => urldecode(file_get_contents('php://input')),
                'trace_id' => $this->traceId,
                'span_id' => $this->spanId,
                'version' => $this->headerVersion,
                'gray_service' => $this->headerGrayService
            ],
            'response' => [
                'code' => intval($this->responseCode),
                'body' => $this->responseBody,
            ],
            'process' => $this->process,
            'create_time' => intval($this->startTime),
            'create_date' => date('Y-m-d H:i:s', intval($this->startTime)),
            'operator' => [
                'id' => intval($this->operatorId),
                'name' => $this->operatorName,
                'ip' => $this->getIp()
            ],
            'debug' => [
                'start_time' => microtime(true) . 's',
                'time' => number_format(round(microtime(true) - $this->startTime, 10), 6) . 's',
                'memory' => number_format((memory_get_usage() - $this->startMem) / 1024, 2) . 'kb',
                'file' => count(get_included_files()),
            ],
            'record_trace' => $this->recordTrace
        ];
        if ($this->parameters) {
            $data += $this->parameters;
        }
        $this->disable();
        try {
            Mongo::name('ykb_logger_' . $this->module)->save($data);
        } catch (\Exception $e) {
            $data['mongo_error'] = $e->getMessage();
            Log::write(json_encode($data), floor($this->responseCode/100) == 2?'success':'error');
        }
        return true;
    }

    private function getIp()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_X_REAL_IP"])) {
                $ip = $_SERVER["HTTP_X_REAL_IP"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"]??'';
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_REAL_IP')) {
                $ip = getenv('HTTP_X_REAL_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        if (trim($ip) == "::1") {
            $ip = "127.0.0.1";
        }
        if (is_string($ip)) {
            $ips = explode(',', $ip);
            $ip = end($ips);
        } elseif (is_array($ip)) {
            $ip = end($ip);
        }
        return $ip;
    }
}