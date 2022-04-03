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
     * @var Config
     */
    private $config;

    /**
     * Default Constructor
     *
     * @param string $configLoc Location of an .ini style config file
     *
     * @throws \Exception When mysqldump executable not found
     */
    public function __construct(string $configLoc = null)
    {
        $this->config = null === $configLoc ? new Config() : new Config($configLoc);
        $this->local_store = $this->config->getLogDir();

        try {
            $this->checkForFiles();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        if (!file_exists($this->local_store)) {
            mkdir($this->local_store, 0700, true);
        }
        $this->user = $this->config->getDBUser();
        $this->pass = strlen($this->config->getDBPassword()) > 1 ? '-p' . $this->config->getDBPassword() : null;
    }

    /**
     * createLocalBackup
     *
     * Creates a local backup of the database passed in the function call
     *
     * @param string $database The name of the databse to be backed up
     * @param bool   $compress Boolean signifiying if you want to compress the backup or not using gzip. If the parameter is omitted, it assumes true (and will compress at a level of 9)
     * @param int    $level    When $compress is true, this pararmeter can also be included. It will denote the compression level. When excluded, it defaults to 9
     *
     * @throws \Exception When mysqldump executable not found
     *
     * @return string Returns the full name of the file that was created in the backup
     */
    public function createLocalBackup($database, $compress = true, $level = 9): string
    {
        $output = '';
        $exitCode = 0;

        $this->local_file = $this->local_store . DIRECTORY_SEPARATOR . $database . '.' . date('YmdHis') . '.sql';

        if ($compress) {
            $compressLevel = ($level > 0 && $level < 10) ? $level : 9;
            $gzip = " | gzip -{$compressLevel}";
            $this->local_file = $this->local_file . '.gz';
        } else {
            $gzip = '';
        }

        $backupCommand = "mysqldump -u {$this->user} {$this->pass} {$database} {$gzip} > {$this->local_file}";

        try {
            $this->performBackup($backupCommand);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $message = $database . ' was backed up successfully as ' . $this->local_file;
        Log::info($message);

        return $this->local_file;
    }

    /**
     * purgeBackup
     */
    public function purgeBackup(): void
    {
        @unlink($this->local_file);
    }

    /**
     * s3
     * Pointer to the \S3 class
     */
    public function s3(): S3
    {
        return new S3($this->config);
    }

    /**
     * Checks to ensure the existence of the mysqldump command
     */
    private function checkForFiles(): void
    {
        $required = ['gzip', 'mysqldump'];

        if (PHP_OS != 'WINNT') {
            foreach ($required as $program) {
                Log::debug('Checking existence of ' . $program);
                exec("command -v $program", $output, $exitCode);
                if ($exitCode > 0) {
                    throw new \Exception('You don\'t seem to have ' . $program . ' on the system');
                }
            }
        }

        return;
    }

    /**
     * Function to handle the execution of mysqldump
     *
     * @param string $command
     */
    private function performBackup($command): void
    {
        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            throw new \Exception('mysqldump exited with a non-zero status.something must have been wrong');
        }

        return;
    }
}
