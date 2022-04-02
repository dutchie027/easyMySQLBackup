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

    protected function setUp(): void
    {
        $this->config = new Config();
    }

    public function testgetDBUser(): void
    {
        self::assertEquals('root', $this->config->getDBUser());
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
        self::assertEquals('us-east-1', $this->config->getS3Region());
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
