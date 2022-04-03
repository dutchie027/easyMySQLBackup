<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

class Restore
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string|null
     */
    private $pass;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Functions
     */
    private $func;

    /**
     * Default Constructor
     *
     * @throws \Exception When mysqldump executable not found
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->func = new Functions();

        try {
            $this->func->checkForFiles(['gunzip', 'mysql']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $this->user = $this->config->getDBUser();
        $this->pass = strlen($this->config->getDBPassword()) > 1 ? '-p' . $this->config->getDBPassword() : null;
    }

    /**
     * createLocalBackup
     *
     * Creates a local backup of the database passed in the function call
     *
     * @param string $file     The name of the databse to be backed up
     * @param string $database
     * @param bool   $force
     *
     * @throws \Exception When mysqldump executable not found
     */
    public function restoreLocalBackup($file, $database, $force = false): void
    {
        $restoreFile = $file;

        try {
            $this->func->checkFileExists($file);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $pathParts = pathinfo($file);

        if (array_key_exists('extension', $pathParts) && strtolower($pathParts['extension']) === 'gz') {
            $unzipCommand = "gunzip -q -f $file";
            $restoreFile = $pathParts['dirname'] . DIRECTORY_SEPARATOR . $pathParts['filename'];

            try {
                $this->func->performCommand($unzipCommand);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                print $e->getMessage() . PHP_EOL;

                exit;
            }
        }

        if ($force) {
            $dropDatabase = "mysql -u {$this->user} {$this->pass} -e \"drop database if exists {$database}\"";

            try {
                $this->func->performCommand($dropDatabase);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                print $e->getMessage() . PHP_EOL;

                exit;
            }
        }
        $createDatabase = "mysql -u {$this->user} {$this->pass} -e \"create database {$database}\"";

        try {
            $this->func->performCommand($createDatabase);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $restoreCommand = "mysql -u {$this->user} {$this->pass} {$database} < {$restoreFile}";

        try {
            $this->func->performCommand($restoreCommand);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            print $e->getMessage() . PHP_EOL;

            exit;
        }

        $message = $database . ' was restored successfully';
        Log::info($message);
    }
}
