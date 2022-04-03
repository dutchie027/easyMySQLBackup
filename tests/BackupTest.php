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

    protected function setUp(): void
    {
        $this->backup = new Backup();
    }

    public function testPurgeBackup(): void
    {
        $this->backup->purgeBackup();
        self::assertFileDoesNotExist(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php-unit');
    }
}
