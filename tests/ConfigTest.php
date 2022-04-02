<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $tmp_ini;

    protected function setUp(): void
    {
        $this->tmp_ini = tempnam(sys_get_temp_dir(), 'phpunit') ?: sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php-unit';
        $handle = fopen($this->tmp_ini, 'w');

        if ($handle) {
            fwrite($handle, '[s3]' . PHP_EOL);
            fwrite($handle, "S3_REGION = 'us-east-3'" . PHP_EOL . PHP_EOL);
            fwrite($handle, '[database]' . PHP_EOL);
            fwrite($handle, "DB_USER = 'bob'" . PHP_EOL);
            fclose($handle);
        }

        $this->config = new Config($this->tmp_ini);
    }

    public function testgetDBUser(): void
    {
        self::assertEquals('bob', $this->config->getDBUser());
    }

    public function testgetDBPassword(): void
    {
        self::assertEquals('', $this->config->getDBPassword());
    }

    public function testgetLogDir(): void
    {
        self::assertEquals(sys_get_temp_dir(), Config::getLogDir());
    }

    public function testgetLogLevel(): void
    {
        self::assertEquals(100, Config::getLogLevel());
    }

    public function testgetLogPrefix(): void
    {
        self::assertEquals('easyMySQLBackup', Config::getLogPrefix());
    }

    public function testgetS3Region(): void
    {
        self::assertEquals('us-east-3', $this->config->getS3Region());
    }

    public function testgetS3Endpoint(): void
    {
        self::assertEquals('https://s3.us-east-1.amazonaws.com', $this->config->getS3Endpoint());
    }

    public function testgetS3ACL(): void
    {
        self::assertEquals('private', $this->config->getS3ACL());
    }

    public function testgetS3AccessKey(): void
    {
        self::assertEquals('', $this->config->getS3AccessKey());
    }

    public function testgetS3SecretKey(): void
    {
        self::assertEquals('', $this->config->getS3SecretKey());
    }
}
