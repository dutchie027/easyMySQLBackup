<?php

declare(strict_types=1);

namespace dutchie027\Test\EasyMySQLBackup;

use dutchie027\EasyMySQLBackup\S3;
use PHPUnit\Framework\TestCase;

final class S3Test extends TestCase
{
    public function testCreateS3Connection(): void
    {
        $settings = [
            'user' => 'bob',
        ];

        $stub = $this->getMockBuilder(S3::class)->disableOriginalConstructor()->getMock();
        self::doesNotPerformAssertions();

        // $stub->method('createS3Bucket')
        // ->willReturn(true);
        // $s3->uploadFile($stockFile, "bob-test");
        // $stub("test");
        // $this->assertSame(true, $stub->createS3Bucket('bob'));
    }

    public function testUploadFile(): void
    {
        $stub = $this->getMockBuilder(S3::class)->disableOriginalConstructor()->onlyMethods(['uploadFile'])->getMock();
        $stub->uploadFile('file', 'bucket');
        self::doesNotPerformAssertions();
    }
}
