<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

final class Config
{
    /**
     *  @const array<int>
     * 'DEBUG'|'INFO'|'NOTICE'|'WARNING'|'ERROR'|'CRITICAL'|'ALERT'|'EMERGENCY'
     */
    private const ALLOWED_LEVELS = [100, 200, 250, 300, 400, 500, 550, 600];

    /**
     * @var string
     */
    private $db_user;

    /**
     * @var string
     */
    private $db_password;

    /**
     * @var string
     */
    private $s3_region;

    /**
     * @var string
     */
    private $s3_endpoint;

    /**
     * @var string
     */
    private $s3_access_key;

    /**
     * @var string
     */
    private $s3_secret_key;

    /**
     * @var string
     */
    private $s3_acl;

    /**
     * @var array<string,array<string>>
     */
    private $ini_data;

    private static string $s_log_dir;

    private static int $s_log_level;

    private static string $s_log_prefix;

    /**
     * Default Constructor - Initialize Values
     */
    public function __construct(string $loc = '')
    {
        $this->ini_data = $this->returnIniArray($loc);
        $this->db_user = $this->returnContents('database/DB_USER', 'root');
        $this->db_password = $this->returnContents('database/DB_PASSWORD', '');
        $this->s3_endpoint = $this->returnContents('s3/S3_ENDPOINT', 'https://s3.us-east-1.amazonaws.com');
        $this->s3_region = $this->returnContents('s3/S3_REGION', 'us-east-1');
        $this->s3_access_key = $this->returnContents('s3/S3_ACCESS_KEY', '');
        $this->s3_secret_key = $this->returnContents('s3/S3_SECRET_KEY', '');
        $this->s3_acl = $this->returnContents('s3/S3_ACL', 'private');
        self::$s_log_dir = $this->returnContents('log/LOG_DIRECTORY', sys_get_temp_dir());
        self::$s_log_prefix = $this->returnContents('log/LOG_PREFIX', 'easyMySQLBackup');
        self::$s_log_level = $this->returnLogLevel('log/LOG_LEVEL', 100);
    }

    /**
     * Returns Database user
     */
    public function getDBUser(): string
    {
        return $this->db_user;
    }

    /**
     * Returns Database Password
     */
    public function getDBPassword(): string
    {
        return $this->db_password;
    }

    /**
     * Returns Log Directory
     */
    public static function getLogDir(): string
    {
        return self::$s_log_dir;
    }

    /**
     * Returns Logging Level
     */
    public static function getLogLevel(): int
    {
        return self::$s_log_level;
    }

    /**
     * Returns Log Prefix
     */
    public static function getLogPrefix(): string
    {
        return self::$s_log_prefix;
    }

    /**
     * Returns S3 Region
     */
    public function getS3Region(): string
    {
        return $this->s3_region;
    }

    /**
     * Returns S3 Endpoint
     */
    public function getS3Endpoint(): string
    {
        return $this->s3_endpoint;
    }

    /**
     * Returns S3 ACL
     */
    public function getS3ACL(): string
    {
        return $this->s3_acl;
    }

    /**
     * Returns S3 Access Key
     */
    public function getS3AccessKey(): string
    {
        return $this->s3_access_key;
    }

    /**
     * Returns S3 Secret Key
     */
    public function getS3SecretKey(): string
    {
        return $this->s3_secret_key;
    }

    /**
     * Used to set values from .ini array or default value
     */
    private function returnContents(string $var, string $dv): string
    {
        [$root, $key] = explode('/', $var);
        $varlc = strtolower($key);

        return (isset($this->ini_data[$root][$key])) ? $this->ini_data[$root][$key] : $dv;
    }

    /**
     * Used to set values from .ini array or default value
     */
    private function returnLogLevel(string $var, int $dv): int
    {
        [$root, $key] = explode('/', $var);
        $varlc = strtolower($key);

        return ((isset($this->ini_data[$root][$key])) && (in_array($this->ini_data[$root][$key], self::ALLOWED_LEVELS, true))) ? (int) $this->ini_data[$root][$key] : $dv;
    }

    /**
     * Checks existence of ini file and then returns KVP Array
     *
     * @param string $loc
     *
     * @return array<string,array<string>>
     */
    private function returnIniArray($loc): array
    {
        $return = [];

        if (file_exists($loc)) {
            $return = parse_ini_file($loc, true) ?: [];
        }

        return $return;
    }
}
