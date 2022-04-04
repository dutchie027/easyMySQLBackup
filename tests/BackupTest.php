<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Backup;
use PHPUnit\Framework\TestCase;

final class BackupTest extends TestCase
{
    /**
     * @var Backup
     */
    private $backup;

    /**
     * @var string
     */
    private $tmp_ini;

    /**
     * @var string
     */
    private static $filename;

    public static function setUpBeforeClass(): void
    {
        new Config();
        self::$filename = Config::getLogDir() . DIRECTORY_SEPARATOR . Config::getLogPrefix() . date('-Y-m-d') . '.log';
    }

    protected function setUp(): void
    {
        $this->tmp_ini = tempnam(sys_get_temp_dir(), 'phpunit') ?: sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php-unit';
        $handle = fopen($this->tmp_ini, 'w');

        if ($handle) {
            fwrite($handle, '[s3]' . PHP_EOL);
            fwrite($handle, "S3_REGION = 'us-east-3'" . PHP_EOL . PHP_EOL);
            fwrite($handle, '[database]' . PHP_EOL);
            fwrite($handle, "DB_USER = 'root'" . PHP_EOL);
            fwrite($handle, "DB_PASSWORD = 'root'" . PHP_EOL);
            fclose($handle);
        }

        $this->backup = new Backup($this->tmp_ini);
    }

    public function testcreateLocalBackup(): void
    {
        $buf = $this->backup->createLocalBackup('phpunit');
        self::assertStringContainsString('phpunit', $buf);
    }

    public function testcreateLocalBackupFailure(): void
    {
        $buf = $this->backup->createLocalBackup('empty-db');
        self::assertNotFalse(strpos($this->returnContents(), 'exited with a non-zero status'));
    }

    public function testPurgeBackup(): void
    {
        $this->backup->purgeBackup();
        self::assertFileDoesNotExist(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php-unit');
    }

    private function returnContents(): string
    {
        return file_get_contents(self::$filename) ?: '';
    }
}
