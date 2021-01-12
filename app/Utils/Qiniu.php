<?php

namespace App\Utils;

use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\BucketManager;
use Illuminate\Support\Str;
use RuntimeException;
use InvalidArgumentException;

class Qiniu
{
    protected $bucket;
    protected $auth;
    protected $config;
    protected $bucketManager;

    public function __construct($bucket = '') {
        $bucket = $this->bucketDefaultName($bucket);
        $bucketInfo = config('qiniu.buckets')[$bucket];
        $bucketInfo['name'] = $bucket;
        $this->bucket = $bucketInfo;
    }

    /**
     * 获取上传 Token.
     *
     * @param $fileName (文件名) require
     * @param $space (空间)
     * @param $folder (文件)
     *
     * @return string
     */
    public function uploadToken($fileName, $space, $folder) {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $saveInfo = $this->saveInfoDefault($space, $folder, $extension);
        $key = $saveInfo['full'];
        $optional['returnBody'] = json_encode([
            'bucket' => '$(bucket)',
            'key' => '$(key)',
            'etag' => '$(etag)',
            'fname' => '$(fname)',
            'fsize' => '$(fsize)',
            'mimeType' => '$(mimeType)',
            'endUser' => '$(endUser)',
            'persistentId' => '$(persistentId)',
            'ext' => '$(ext)',
            'uuid' => '$(uuid)',
        ]);
        $token = $this->auth()->uploadToken($this->bucket['name'], $key, 86400, $optional, false);
        $url = [
            'preview' => $this->url($key, 3600),
            'download' => $this->url($key.'?'.http_build_query(['attname' => $fileName]), 3600),
        ];

        return compact('key', 'token', 'url');
    }
    /**
     * 上传远程文件.
     *
     * @param $remote (远程文件地址)require
     * @param $fileName (文件名) require
     * @param $space (空间)
     * @param $folder (文件)
     *
     * @return String
     *
     * @throws RuntimeException()
     */
    public function fetchFile($remote, $fileName, $space, $folder)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $saveInfo = $this->saveInfoDefault($space, $folder, $extension);
        $key = $saveInfo['full'];
        $bucketManager = $this->bucketManager();
        $bucket = $this->bucket['name'];
        list($result, $error) = $bucketManager->fetch($remote, $bucket, $key);

        if (! empty($error)) {
            throw new RuntimeException('抓取失败:'.$error->message());
        }

        return $this->url($key, 3600);
    }
    /**
     * 远程文件
     *
     * @param $space (空间)
     * @param $folder (文件)
     * @param $extension (文件后缀)
     *
     * @return String
     *
     * @throws RuntimeException()
     */
    protected function saveInfoDefault($space, $folder, $extension)
    {
        if (empty($space)) {
            $space = 'default';
        }
        if (! preg_match('/\d$/', $space)) {
            // 不是以数字结尾的加上日期
            $space = $space.'/'.date('Y/md');
        }
        if (! empty($folder)) {
            $folder = '/'.$folder;
        }

        $path = $space.$folder;
        $name = Str::random(40);
        if (! empty($extension)) {
            $name .= '.'.$extension;
        }
        $full = $path.'/'.$name;

        return compact('path', 'name', 'full');
    }

    /**
     * 文件地址
     *
     * @param $path       (路径)
     * @param $expiration (有效期)
     *
     * @return string
     */
    public function url($path, $expiration = null)
    {
        $bucket = $this->bucket;
        $url = $bucket['domain'].'/'.ltrim($path, '/');
        if ($bucket['visibility'] === 'public') {
            return $url;
        }
        $expiration = $expiration ?: 3600;
        return $this->auth()->privateDownloadUrl($url, $expiration);
    }
    /**
     * 七牛 默认 bucket.
     *
     * @param $bucket (bucket)
     * @return String
     */
    public function bucketDefaultName ($bucket) {
        $bucket = $bucket ? $bucket : array_keys(config('qiniu.buckets'))[0];
        if (!array_key_exists($bucket, config('qiniu.buckets'))) {
            throw new InvalidArgumentException("Qiniu bucket [{$bucket}] not configured.");
        }
        return $bucket;
    }
    /**
     * 七牛 Auth.
     *
     * @return \Qiniu\Auth
     */
    protected function auth()
    {
        if (is_null($this->auth)) {
            $this->auth = new Auth(config('qiniu.access_key'), config('qiniu.secret_key'));
        }
        return $this->auth;
    }
    /**
     * 七牛 Config.
     *
     * @return \Qiniu\Config
     */
    protected function config()
    {
        if (is_null($this->config)) {
            $this->config = new Config();
        }

        return $this->config;
    }
    /**
     * 七牛 BucketManager.
     *
     * @return Qiniu\Storage\BucketManager
     */
    protected function bucketManager()
    {
        if (is_null($this->bucketManager)) {
            $this->bucketManager = new BucketManager($this->auth(), $this->config());
        }

        return $this->bucketManager;
    }
}
