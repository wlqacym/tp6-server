<?php


namespace app;


use app\helper\Db;
use HttpClient\HttpClient;
use think\exception\HttpException;
use think\facade\Log;

/**
 * Class BaseDbService
 * @package app
 */
class BaseDbService
{
    use Db;
}