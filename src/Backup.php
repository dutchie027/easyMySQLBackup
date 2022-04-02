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
     * @var string
     */
    protected $local_file = '';

    /**
     * @var object
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->local_store = $this->config->getLogDir();

        if (!file_exists($this->local_store)) {
            mkdir($this->local_store, 0700, true);
        }
        $this->user = $this->config->getDBUser();
        $this->pass = strlen($this->config->getDBPassword()) > 1 ? '-p' . $this->config->getDBPassword() : null;
    }

    /**
     * createLocalBackup
     *
     * @param string $database
     * @param bool   $compress
     *
     * @throws \Exception
     */
    public function createLocalBackup($database, $compress = true): string
    {
        $output = '';
        $exitCode = 0;

        $this->local_file = $this->local_store . DIRECTORY_SEPARATOR . $database . '.' . date('YmdHis') . '.sql';

        if ($compress) {
            $gzip = ' | gzip -9';
            $this->local_file = $this->local_file . '.gz';
        } else {
            $gzip = '';
        }

        $command = "mysqldump -u {$this->user} {$this->pass} {$database} {$gzip} > {$this->local_file}";

        print $command;

        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            throw new \Exception('mysqldump exited with a non-zero status.something must have been wrong');
        }
        Log::info('something really interesting happened');

        return $this->local_file;
    }

    /**
     * purgeBackup
     */
    public function purgeBackup(): void
    {
        @unlink($this->local_file);
    }
}
