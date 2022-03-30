<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\Example;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testGreet(): void
    {
        $hello = new Example();
        self::assertEquals('Hello, Friends!', $hello->greet('Friends'));
    }
}
