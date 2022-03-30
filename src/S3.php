<?php

namespace dutchie027\EasyMySQLBackup;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3
{
    /**
     * @var object
     */
    private $s3;

    /**
     * @var array
     */
    private $bucketArray;

    public function __construct(array $settings)
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $settings['region'],
            'endpoint' => $settings['endpoint'],
            'credentials' => [
                'key' => $settings['access_key'],
                'secret' => $settings['secret_key'],
            ],
        ]);

        $this->loadS3Buckets();
    }

    public function uploadFile($file, $bucket, $name = null)
    {
        $key = (strlen($name) < 1) ? basename($file) : $name;

        if (!in_array($bucket, $this->bucketArray, true)) {
            $this->createS3Bucket($bucket);
            $this->s3->waitUntil('BucketExists', ['Bucket' => $bucket]);
            Log::info($bucket . " didn't exist. I'm creating it");
        }

        try {
            $result = $this->s3->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $file,
                'ACL' => 'private',
            ]);
        } catch (S3Exception $e) {
            Log::error($e->getMessage() . ' ' . __FILE__ . ' ' . __LINE__);
        }
    }

    private function createS3Bucket($bucketName)
    {
        Log::info("Gonna make bucket $bucketName");

        try {
            $result = $this->s3->createBucket([
                'Bucket' => $bucketName,
            ]);
        } catch (S3Exception $e) {
            Log::error($e->getMessage() . ' ' . __FILE__ . ' ' . __LINE__);
        }
    }

    private function loadS3Buckets()
    {
        $this->bucketArray = [];
        Log::debug('Loading Bucket Names');
        $buckets = $this->s3->listBuckets();

        foreach ($buckets['Buckets'] as $bucket) {
            Log::debug('Adding bucket ' . $bucket['Name'] . ' to the array');
            $this->bucketArray[] = $bucket['Name'];
        }
    }
}
