<?php

namespace dutchie027\EasyMySQLBackup;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3
{
    /**
     * @var S3Client
     */
    private $s3;

    /**
     * @var array<string>
     */
    private $bucketArray;

    /**
     * @var object
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $this->config->getS3Region(),
            'endpoint' => $this->config->getS3Endpoint(),
            'credentials' => [
                'key' => $this->config->getS3AccessKey(),
                'secret' => $this->config->getS3SecretKey(),
            ],
        ]);

        $this->loadS3Buckets();
    }

    /**
     * Upload File
     *
     * @param string $file
     * @param string $bucket
     * @param string $name
     */
    public function uploadFile($file, $bucket, $name = ''): void
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
                'ACL' => $this->config->getS3ACL(),
            ]);
        } catch (S3Exception $e) {
            Log::error($e->getMessage() . ' ' . __FILE__ . ' ' . __LINE__);
        }
    }

    /**
     * createS3Bucket
     *
     * @param string $bucketName
     */
    private function createS3Bucket($bucketName): void
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

    /**
     * loadS3Buckets
     */
    private function loadS3Buckets(): void
    {
        $this->bucketArray = [];
        Log::debug('Loading Bucket Names');
        /** @var array<string, array<array<string>>> $buckets */
        $buckets = $this->s3->listBuckets();

        foreach ($buckets['Buckets'] as $bucket) {
            Log::debug('Adding bucket ' . $bucket['Name'] . ' to the array');
            $this->bucketArray[] = $bucket['Name'];
        }
    }
}
