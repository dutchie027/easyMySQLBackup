<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Restore;
use PHPUnit\Framework\TestCase;

final class RestoreTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('mysqli')) {
            $this->markTestSkipped(
              'The MySQLi extension is not available.'
            );
        }
    }
}
