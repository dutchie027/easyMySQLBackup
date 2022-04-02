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

The program will assume a lot of defaults if you don't have a config file, however it is highly suggested you create a config (see sample .ini below)

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

use dutchie027\EasyMySQLBackup\Backup;
use dutchie027\EasyMySQLBackup\Config;
use dutchie027\EasyMySQLBackup\S3;

# OPTION A: Create a new default "configuration" set
$config = new Config();

# OPTION B: Create a configuration set using a .ini file
$config = new Config('/path/to/my.ini');

# Create a new backup instance with the config from above
$backup = new Backup($config);
# Backup the database named "test". The location on the file system will be returned
$backup_file = $backup->createLocalBackup("test");

# Upload the $backup_name file to the bucket "my-sql-backups"
# NOTE: If the bucket "my-sql-backups" doesn't exist, it will create it
(new S3($config))->uploadFile($backup, "my-sql-backups");

# Using the initial connection, remove the local file
$backup->purgeBackup();
```

## Sample my.ini

``` ini
[s3]
S3_REGION     = 'us-east-1'
S3_ENDPOINT   = "https://s3.us-east-1.amazonaws.com"
S3_ACCESS_KEY = "ABCD1234EFGH5678ZZZZ"
S3_SECRET_KEY = "JuStiN8675309NeEDedA30918KeYtoTest567890"

[database]
DB_USER = 'root'
DB_PASSWORD = ''

[log]
LOG_LEVEL = 105
LOG_PREFIX = easyMySQLBackup
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
