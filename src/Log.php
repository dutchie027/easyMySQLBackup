<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class Log
{
    /**
     * @var Logger
     */
    protected static $instance;

    /**
     * @var bool
     */
    protected static $is_set = false;

    /**
     * Method to return the Monolog instance
     */
    public static function getLogger(): Logger
    {
        if (!self::$is_set) {
            self::configureInstance();
        }

        return self::$instance;
    }

    /**
     * Configure Monolog to use rotating files
     *
     *  @psalm-suppress UndefinedConstant
     */
    protected static function configureInstance(): void
    {
        $dir = LOG_DIR . DIRECTORY_SEPARATOR . 'log'; // @phpstan-ignore-line

        if (!file_exists($dir)) {
            mkdir($dir, 0700, true);
        }

        $logger = new Logger('easyMySQLBackup');
        $logger->pushHandler(new RotatingFileHandler($dir . DIRECTORY_SEPARATOR . 'easyMySQLBackup.log', Logger::DEBUG));
        self::$instance = $logger;
        self::$is_set = true;
    }

    /**
     * Add Debug Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::debug("something really interesting happened");
     */
    public static function debug($message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    /**
     * Add Info Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::info("something really interesting happened");
     */
    public static function info($message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    /**
     * Add Notice Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::notice("something really interesting happened");
     */
    public static function notice($message, array $context = []): void
    {
        self::getLogger()->notice($message, $context);
    }

    /**
     * Add Warning Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::warning("something really interesting happened");
     */
    public static function warning($message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    /**
     * Add Error Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::error("something really interesting happened");
     */
    public static function error($message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    /**
     * Add Critical Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::critical("something really interesting happened");
     */
    public static function critical($message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }

    /**
     * Add Alert Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::alert("something really interesting happened");
     */
    public static function alert($message, array $context = []): void
    {
        self::getLogger()->alert($message, $context);
    }

    /**
     * Add Emergency Message
     *
     * @param string       $message
     * @param array<mixed> $context
     *
     * @example Log::emergency("something really interesting happened");
     */
    public static function emergency($message, array $context = []): void
    {
        self::getLogger()->emergency($message, $context);
    }
}
