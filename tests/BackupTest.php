<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use PHPUnit\Framework\TestCase;

final class BackupTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('mysqli')) {
            self::markTestSkipped(
                'The MySQLi extension is not available.'
            );
        }
    }
}
