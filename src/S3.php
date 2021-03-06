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
     * @var string
     */
    private $s3_acl;

    /**
     * Default Constructor
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
        $this->s3_acl = $this->config->getS3ACL();
        $this->loadS3Buckets();
    }

    /**
     * Upload File
     *
     * @param string $localFile
     * @param string $s3File
     */
    public function uploadFile($localFile, $s3File): void
    {
        $fileParts = explode('/', $s3File);
        $bucket = $fileParts[0];
        $fileBase = pathinfo($localFile, PATHINFO_BASENAME);

        // try {
        //     $pattern = '/(\.gz|\.sql|\/)$/';
        //     if (!preg_match($pattern, $s3File)) {
        //         throw new \Exception($s3File . ' must end in .sql, .gz or a /');
        //     }
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     print $e->getMessage() . PHP_EOL;

        //     exit;
        // }

        $s3Key = '';

        for ($x = 1;$x < count($fileParts) ; ++$x) {
            $s3Key .= $fileParts[$x] . DIRECTORY_SEPARATOR;
        }

        $s3Key = substr($s3Key, 0, -1);

        $pattern = '/(\.gz|\.sql)$/';

        if (!preg_match($pattern, $s3File)) {
            $s3Key = preg_match('/\/$/', $s3Key) ? $s3Key . $fileBase : $s3Key . DIRECTORY_SEPARATOR . $fileBase;
        }

        if (!in_array($bucket, $this->bucketArray, true)) {
            $this->createS3Bucket($bucket);
            $this->s3->waitUntil('BucketExists', ['Bucket' => $bucket]);
            Log::info($bucket . " didn't exist. I'm creating it");
        }

        print $s3Key . PHP_EOL;

        try {
            $result = $this->s3->putObject([
                'Bucket' => $bucket,
                'Key' => $s3Key,
                'SourceFile' => $localFile,
                'ACL' => $this->s3_acl,
            ]);
        } catch (S3Exception $e) {
            $error = 'Trying to upload ' . $localFile . ' to ' . $s3File . ' returned ' . $e->getStatusCode() . ' ' . $e->getAWSErrorCode();
            Log::error($error);
            print $error . PHP_EOL;

            exit;
        }
    }

    /**
     * Download File
     *
     * @param string $file
     * @param string $directory
     * @param string $newName
     *
     * @return string location and file name
     */
    public function downloadFile($file, $directory = '.', $newName = null): string
    {
        $fileParts = explode('/', $file);
        $bucket = $fileParts[0];
        $s3Key = '';

        for ($x = 1;$x < count($fileParts) ; ++$x) {
            $s3Key .= $fileParts[$x] . DIRECTORY_SEPARATOR;
        }

        $s3Key = substr($s3Key, 0, -1);
        $s3File = $newName ?? $fileParts[count($fileParts) - 1];

        try {
            if (!in_array($bucket, $this->bucketArray, true)) {
                throw new \Exception('Bucket ' . $bucket . ' is incorrect or does not exist');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $saveAs = $directory . DIRECTORY_SEPARATOR . $s3File;

        try {
            $result = $this->s3->getObject([
                'Bucket' => $bucket,
                'Key' => $s3Key,
                'SaveAs' => $saveAs,
            ]);
        } catch (S3Exception $e) {
            $error = $file . ' returned ' . $e->getStatusCode() . ' ' . $e->getAWSErrorCode();
            Log::error($error);
            print $error . PHP_EOL;

            exit;
        }

        return $saveAs;
    }

    /**
     * Create an S3 Bucket
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
            $error = $bucketName . ' returned ' . $e->getStatusCode() . ' ' . $e->getAWSErrorCode();
            Log::error($error);
            print $error . PHP_EOL;

            exit;
        }
    }

    /**
     * Load all of the S3 Buckets in to a local array
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
