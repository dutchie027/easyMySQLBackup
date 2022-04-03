<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

class Functions
{
    /**
     * Checks to ensure the existence of the mysqldump command
     *
     * @param array<string> $commands
     */
    public function checkForFiles($commands): void
    {
        $required = ['gzip', 'mysqldump'];

        if (PHP_OS != 'WINNT') {
            foreach ($required as $program) {
                Log::debug('Checking existence of ' . $program);
                $command = "command -v $program";
                $this->performCommand($command);
            }
        }
    }

    /**
     * Function to handle the execution of mysqldump
     *
     * @param string $command
     */
    public function performCommand($command): void
    {
        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            $pattern = '/(\-p.+?)\s/i';
            preg_replace($pattern, '', $command);

            throw new \Exception($command . ' exited with a non-zero status.');
        }
    }

    /**
     * Function to handle the execution of mysqldump
     *
     * @param string $file
     */
    public function checkFileExists($file): void
    {
        if (!file_exists($file)) {
            throw new \Exception($file . ' doesn\'t exist.');
        }
    }
}
