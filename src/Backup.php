<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

class Backup
{
    /**
     * @var string
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $pass;

    /**
     * @var string
     */
    protected $local_store;

    /**
     * Constructor
     *
     * @param array<string> $settings
     */
    public function __construct(array $settings)
    {
        $this->local_store = sys_get_temp_dir();
        $this->user = $settings['user'];
        $this->pass = isset($settings['pass']) ? '-p' . $settings['pass'] : null;
    }

    /**
     * createLocalBackup
     *
     * @param string $database
     * @param bool   $compress
     *
     * @throws \Exception
     *
     * @return string
     */
    public function createLocalBackup($database, $compress = true): string
    {
        $output = '';
        $exitCode = 0;

        $local_file = $this->local_store . DIRECTORY_SEPARATOR . $database . '.' . date('YmdHis') . '.sql';

        if ($compress) {
            $gzip = ' | gzip -9';
            $local_file = $local_file . '.gz';
        } else {
            $gzip = '';
        }

        $command = "mysqldump -u {$this->user} {$this->pass} {$database} {$gzip} > {$this->local_file}";

        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            throw new \Exception('mysqldump exited with a non-zero status.something must have been wrong');
        }
        Log::info('something really interesting happened');

        return $local_file;
    }

    /**
     * purgeBackup
     */
    public function purgeBackup() : void
    {
        unlink($this->local_file);
    }
}
