<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Backup;
use PHPUnit\Framework\TestCase;

final class RestoreTest extends TestCase
{
    /**
     * @var Backup
     */
    private $backup;

    protected function setUp(): void
    {
        $this->backup = new Backup();
    }

    public function testRestore(): void
    {
        $buf = $this->backup->createLocalBackup('phpunit');
        $this->backup->restore()->restoreLocalBackup($buf, 'phprest', 1);
        $cmd = "mysql -u root -e 'show databases'";
        exec($cmd, $output, $code);
        self::assertContains('phprest', $output);
    }
}
