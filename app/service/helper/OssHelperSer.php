<?php
namespace app\service\helper;

// 应用请求对象类
use app\BaseHelperService;
use OSS\OssClient;

class OssHelperSer extends BaseHelperService
{

    /**
     * ali-oss 文件上传
     * @param $param
     * @return mixed
     * @throws \OSS\Core\OssException
     * @author Eric
     * @since 1.0 2021/5/17
     */
    public  function ossUpload($param, $ossConfig)
    {
        $keyId = $ossConfig['access_key_id'];
        $keySecret = $ossConfig['access_key_secret'];
        $endpoint = $ossConfig['endpoint'];
        $bucket = $ossConfig['bucket'];
        $path = $param['path'];
        $file = $param['file'];
        $ossClient = new OssClient($keyId, $keySecret, $endpoint);
        $result    = $ossClient->uploadFile($bucket, ltrim($path, '/'), $file);
        $url       = $result['oss-request-url'];
        return $url;
    }

    /**
     * ali-oss  文件上传
     * @param $ossConfig
     * @return array
     * @throws \OSS\Core\OssException
     *
     * @author wlq
     * @since 1.0 20210520
     */
    public function ossUploadNew($ossConfig)
    {
        $file = $this->request->file();
        //TODO 验证文件格式

        //生成文件路径
        $module = 'back';
        $subModule = 'timetable';
        $key = 'SA201004';
        $extension =  $file['image']->getOriginalExtension();
        $tempPath =  $file['image']->getRealPath();
        $key = $key ?? $ossConfig['key'];
        $md5Str = md5_file($tempPath);
        $ossFileName = basename($md5Str . '.' . $extension);
        $path = build_path($key, $module, $subModule, date('Ymd'), $ossFileName);

        //上传oss
        $ossClient = new OssClient($ossConfig['access_key_id'], $ossConfig['access_key_secret'], $ossConfig['endpoint']);
        $result = $ossClient->uploadFile($ossConfig['bucket'], ltrim($path, '/'), $tempPath);
        return [
            'url' => $result['oss-request-url'],
            'file_name' => $file->getFilename()
        ];
    }
}
