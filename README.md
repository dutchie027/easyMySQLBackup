# Easy MySQL Backup

[![Packagist Downloads](https://img.shields.io/packagist/dm/dutchie027/easymysqlbackup)](https://packagist.org/packages/dutchie027/easymysqlbackup)
[![Code Coverage](https://codecov.io/gh/dutchie027/easymysqlbackup/branch/main/graph/badge.svg)](https://codecov.io/gh/dutchie027/easymysqlbackup)
[![CodeFactor](https://www.codefactor.io/repository/github/dutchie027/easymysqlbackup/badge)](https://www.codefactor.io/repository/github/dutchie027/easymysqlbackup)
[![Code Coverage](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/code-coverage.yml/badge.svg)](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/code-coverage.yml)
[![Coding Standards](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/code-standards.yml/badge.svg)](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/code-standards.yml)
[![Static analysis](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/static-analysis.yml)
[![Tests](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/tests.yml/badge.svg)](https://github.com/dutchie027/easyMySQLBackup/actions/workflows/tests.yml)

## About

This package allows you to backup a mysql database using `mysqldump` and then store it to a local file store (by default) or upload it to S3.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require dutchie027/easymysqlbackup
```

You will also need to ensure you have a copy of `mysqldump` on the box this is hosted on.

## Usage

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

# This is the directory where the logs will be stored
define ('LOG_DIR', '/tmp');

# These are the settings for mysqldump
# The keys are user (required), password (optional), and dir (optional)
$settings = [
    'user' => 'root',
];

# Establish the connection with the settings above
$connection = new dutchie027\EasyMySQLBackup\Backup($settings);
# Backup the database named "test". The location on the file system will be returned
$backup_name = $connection->createLocalBackup("test");

# Grab S3 Config in to an array of Key Value Pairs
# NOTE: Store this outside of the directory/project files
$s3config = parse_ini_file('/opt/configs/s3.ini');

# Create a new S3 connector using the config values pulled from the .ini
$s3 = new dutchie027\EasyMySQLBackup\S3($s3config);

# Upload the $backup_name file to the bucket "my-sql-backups"
# NOTE: If the bucket "my-sql-backups" doesn't exist, it will create it
$s3->uploadFile($backup_name, "my-sql-backups");

# Using the initial connection, remove the local file
$connection->purgeBackup();
```

## Sample s3.ini

``` ini
[s3]
region = 'us-east-1'
endpoint = "https://s3.us-east-1.amazonaws.com"
access_key = "ABCD1234EFGH5678ZZZZ"
secret_key = "JuStiN8675309NeEDedA30918KeYtoTest567890"
```

## To-Do

* Add routines to allow for removal of local backups older than "x" days
* Possibly add additional APIs for cloud storage
* Clean up the documentation
* Other things

## Code of Conduct

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## License

Easy MySQL Backup is released under the MIT License. See [`LICENSE`](LICENSE) for details.

## Versioning

This code uses [Semver](https://semver.org/). This means that versions are tagged
with MAJOR.MINOR.PATCH. Only a new major version will be allowed to break backward
compatibility (BC).

Classes marked as `@experimental` or `@internal` are not included in our backward compatibility promise.
You are also not guaranteed that the value returned from a method is always the
same. You are guaranteed that the data type will not change.

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).
