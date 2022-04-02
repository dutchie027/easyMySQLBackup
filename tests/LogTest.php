<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Log;
use dutchie027\EasyMySQLBackup\Config;
use PHPUnit\Framework\TestCase;

final class LogTest extends TestCase
{
    /**
     * @var string
     */
    private static $filename;

    public static function setUpBeforeClass(): void
    {
        new Config;
        self::$filename = Config::getLogDir() . DIRECTORY_SEPARATOR . Config::getLogPrefix() . date('-Y-m-d') . '.log';
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$filename);
    }

    public function testconfigureInstance(): void
    {
        Log::error('initialize');
        self::assertFileExists(self::$filename);
    }

    public function testErrorMessage(): void
    {
        Log::error('error');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.ERROR: error'));
    }

    public function testDebugMessage(): void
    {
        Log::debug('debug');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.DEBUG: debug'));
    }

    public function testInfoMessage(): void
    {
        Log::info('info');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.INFO: info'));
    }

    public function testNoticeMessage(): void
    {
        Log::notice('notice');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.NOTICE: notice'));
    }

    public function testWarningMessage(): void
    {
        Log::warning('warning');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.WARNING: warning'));
    }

    public function testCriticalMessage(): void
    {
        Log::critical('critical');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.CRITICAL: critical'));
    }

    public function testAlertMessage(): void
    {
        Log::alert('alert');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.ALERT: alert'));
    }

    public function testEmergencyMessage(): void
    {
        Log::emergency('emergency');
        self::assertNotFalse(strpos($this->returnContents(), 'easyMySQLBackup.EMERGENCY: emergency'));
    }

    private function returnContents(): string
    {
        return file_get_contents(self::$filename) ?: '';
    }
}
