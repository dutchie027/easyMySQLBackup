<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Log;
use PHPUnit\Framework\TestCase;

define('LOG_DIR', '.build');

final class LogTest extends TestCase
{
    /**
     * @var string
     */
    private static $filename;

    /**
     * @var string
     */
    private static $logname;

    public static function setUpBeforeClass(): void
    {
        self::$logname = 'easyMySQLBackup' . date('-Y-m-d') . '.log';
        self::$filename = LOG_DIR . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . self::$logname;
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
