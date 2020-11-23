# tp6-phpunit-server
> - 以thinkphp6框架作为服务代码模板，增加api模块
> - 通过Api控制器实现api模块路由
> - api模块接口支持版本划分，支持restful
> - 通过反射类实现api模块支持tp中间件，使用方式同controller
> - 增加logic、service模块
> - 通过反射类实现logic、service模块的调用
> - logic与service模块支持phpunit单测框架的mock替换

## 依赖
- PHP 7.2+
- composer

## 使用
- 下载完成后需运行 `composer install`
- 复制`.example.env`为`.env`文件配置
- `app\helper\Di`类通过注释设置`@property`可增加ide提示
- api模块下支持二级路面，控制器层级间使用`.`连接，如：`api/v1/top/test/index`->`api/v1/top/test@index`
- api模块下参数为顺序绑定
  ```php
  /** api/v1/Test.php **/
  public function param($param1, $param2)
  {
      return $this->json([
          'param1' => $param1,
          'param2' => $param2
      ]);
  }
  
  // 访问 http://xxx/api/v1/test/param/aaa/bbb
  // 输出：{"param1":"aaa","param2":"bbb"}
  ```
- `api`模块与`logic`、`service`模块联动使用参考用例 `app/vi/test/test.php`
- `logic`模块与`service`模块类在`middle`模块中对应模块的映射类通过注释设置`@property`可增加`IDE`提示


## 验证
- 执行：`curl http://xxxx/api/v1/test/test`
- 执行：`curl -X POST http://xxxx/api/v1/test/test`

## 更新
2020-11-05 - v1.2.0
- 去除`index.php`全局变量
- 开启强制路由
- 应用异常处理类`app/controller/ApiController.php`异常处理方法`apiExceptionHandle()`返回方式，解决返回头`Content-Type`为`text/html`的问题

2020-10-28 - v1.1.1
- 使用`tp-session`替换中间件session实现
2020-11-02 - v1.2.0
- 增加`logic`模块与`service`模块
- 通过`middle`模块映射调用`logic`模块与`service`模块
- `app/helper/TraitReturn.php`增加日志记录，使用框架日志记录

2020-06-20 - v1.1.0
- `config/app.php`中增加`api_exception_handle`配置，处理api模块异常
- api模块异常默认返回json格式

2020-05-29 - v1.0.2
- 开启路由功能
- `BaseController`引入`TraitReturn`，统一返回结构

2020-05-28 - v1.0.1
- 修复Api路由，Post请求无法访问`indexPost`方法