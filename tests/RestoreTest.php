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

    /**
     * @var string
     */
    private $tmp_ini;

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

    public function testRestore(): void
    {
        $buf = $this->backup->createLocalBackup('phpunit');
        $this->backup->restore()->restoreLocalBackup($buf, 'phprest', true);
        $cmd = "mysql -uroot -proot -e 'show databases'";
        exec($cmd, $output, $code);
        self::assertContains('phprest', $output);
    }
}
