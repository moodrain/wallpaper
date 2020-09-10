<?php

namespace App\Services;

use OSS\OssClient;

class OssService
{
    private static $oss;
    private static $buckets = [];

    public function __construct()
    {
        ! self::$oss && self::$oss = new OssClient(config('aliyun.accessKeyId'), config('aliyun.accessKeySecret'), config('aliyun.oss.endpoint'));
    }

    public function list($bucket, $path = null)
    {
        $options = ['max-keys' => 1000];
        $path && $options['prefix'] = endWith('/', $path);
        return self::$oss->listObjects($bucket, $options);
    }

    public function files($bucket, $path = null)
    {
        $files = $this->list($bucket, $path)->getObjectList();
        $rs = [];
        foreach($files as $file) {
            if ($file->getKey() == endWith('/', $path)) {
                continue;
            }
            $rs[] = str_replace(endWith('/', $path) , '', $file->getKey());
        }
        return $rs;
    }

    public function directories($bucket, $path = null)
    {

        $paths = $this->list($bucket, $path)->getPrefixList();
        $rs = [];
        foreach($paths as $p) {
            $rs[] = str_replace(endWith('/', $path), '', mb_substr($p->getPrefix(), 0, -1));
        }
        return $rs;
    }

    public function put($bucket, $file, $content)
    {
        self::$oss->putObject($bucket, $file, $content);
    }

    public function putFromFile($bucket, $path, $file)
    {
        self::$oss->uploadFile($bucket, $path, $file);
    }

    public function get($bucket, $file)
    {
        return self::$oss->getObject($bucket, $file);
    }

    public function delete($bucket, $file)
    {
        is_array($file) ? self::$oss->deleteObjects($bucket, $file) : self::$oss->deleteObject($bucket, $file);
    }

    public function createBucket($name, $acl)
    {
        self::$oss->createBucket($name, $acl);
    }

    public function createPublicReadWriteBucket($name)
    {
        $this->createBucket($name, OssClient::OSS_ACL_TYPE_PUBLIC_READ_WRITE);
    }

    public function createPublicReadBucket($name)
    {
        $this->createBucket($name, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
    }

    public function createPrivateBucket($name)
    {
        $this->createBucket($name, OssClient::OSS_ACL_TYPE_PRIVATE);
    }

    public function setBucketAcl($name, $acl)
    {
        self::$oss->putBucketAcl($name, $acl);
    }

    public function setBucketPublicReadWrite($name)
    {
        $this->setBucketAcl($name, OssClient::OSS_ACL_TYPE_PUBLIC_READ_WRITE);
    }

    public function setBucketPublicRead($name)
    {
        $this->setBucketAcl($name, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
    }

    public function setBucketPrivate($name)
    {
        $this->setBucketAcl($name, OssClient::OSS_ACL_TYPE_PRIVATE);
    }

    public function dropBucket($name)
    {
        return self::$oss->deleteBucket($name);
    }

    public function buckets()
    {
        if (! self::$buckets) {
            $buckets = self::$oss->listBuckets()->getBucketList();
            foreach($buckets as $bucket) {
                self::$buckets[] = $bucket->getName();
            }
        }
        return self::$buckets;
    }

}