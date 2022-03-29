<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use Mockery\MockInterface;
use dutchie027\EasyMySQLBackup\Example;

class ExampleTest extends TestCase
{
    public function testGreet(): void
    {
        /** @var Example & MockInterface $example */
        $example = $this->mockery(Example::class);
        $example->shouldReceive('greet')->passthru();

        $this->assertSame('Hello, Friends!', $example->greet('Friends'));
    }
}
