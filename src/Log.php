<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class Log {

	protected static $instance;

	/**
	 * Method to return the Monolog instance
	 *
	 * @return \Monolog\Logger
	 */
	static public function getLogger()
	{
		if (! self::$instance) {
			self::configureInstance();
		}

		return self::$instance;
	}

	/**
	 * Configure Monolog to use rotating files
	 *
	 */
	protected static function configureInstance() : void
	{
		$dir = LOG_DIR . DIRECTORY_SEPARATOR . 'log';

		if (!file_exists($dir)){
			mkdir($dir, 0700, true);
		}

		$logger = new Logger('easyMySQLBackup');
		$logger->pushHandler(new RotatingFileHandler($dir . DIRECTORY_SEPARATOR . 'easyMySQLBackup.log', Logger::DEBUG));
		self::$instance = $logger;
	}

    # Log::info("something really interesting happened");
	public static function debug($message, array $context = []) : void {
		self::getLogger()->debug($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function info($message, array $context = []): void {
		self::getLogger()->info($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function notice($message, array $context = []): void {
		self::getLogger()->notice($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function warning($message, array $context = []): void {
		self::getLogger()->warning($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function error($message, array $context = []): void {
		self::getLogger()->error($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function critical($message, array $context = []): void {
		self::getLogger()->critical($message, $context);
	}

    # Log::info("something really interesting happened");
	public static function alert($message, array $context = []): void {
		self::getLogger()->alert($message, $context);
	}

    # Log::info("something really interesting happened");
    public static function emergency($message, array $context = []): void {
		self::getLogger()->addEmergency($message, $context);
	}

}
