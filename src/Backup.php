<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

class Backup
{
    protected $user;
    
    protected $pass;

    protected $local_store;
    
    protected $local_file;

    /**
     * SimpleDumper constructor.
     * @param $user
     * @param $pass
     */
    public function __construct($settings)
    {
        $this->local_store = sys_get_temp_dir();
        $this->user = $settings['user'];
        $this->pass = isset($settings['pass']) ? "-p" . $settings['pass'] : null;
    }

    /**
     * Returns dump from the database
     * @param string $database
     * @throws \Exception
     * @return string
     */
    public function createLocalBackup($database, $compress = true) : mixed
    {
        $output = "";
        $exitCode = 0;

        $this->local_file = $this->local_store . DIRECTORY_SEPARATOR . $database . "." . date("YmdHis") . ".sql";

        if ($compress) {
            $gzip = " | gzip -9";
            $this->local_file = $this->local_file . ".gz";
        } else {
            $gzip = "";
        }

        $command = "mysqldump -u {$this->user} {$this->pass} {$database} {$gzip} > {$this->local_file}";

        exec($command,$output,$exitCode);

        if($exitCode > 0)
        {
            throw new \Exception("mysqldump exited with a non-zero status.something must have been wrong");
        }
        Log::info("something really interesting happened");
        return $this->local_file;
    }

    /**
     * Returns dump from the database
     * @param string $database
     * @throws \Exception
     * @return string
     */
    public function purgeBackup()
    {
        unlink($this->local_file);
    }
}
