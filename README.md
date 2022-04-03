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

You will also need to ensure you have a copy of `mysqldump` on the box this is hosted on. Also, if you want to use compression for backup and restore, you'll need `gzip` and `gunzip`.

## Usage

The program will assume a lot of defaults if you don't have a config file, however it is highly suggested you create a config (see sample .ini below)

### Backup Locally

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

use dutchie027\EasyMySQLBackup\Backup;

# OPTION A: Create a new backup with "default" configuration set
$backup = new Backup();

# OPTION B: Create a configuration set using an .ini file
$backup = new Backup('/path/to/my.ini');

# Backup the database named "test". The location on the file system will be returned
$backup_file = $backup->createLocalBackup("test");
```

### Backup and Upload to S3

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

use dutchie027\EasyMySQLBackup\Backup;

# Because we're using S3, we have to instatiate it with a configuration set using an .ini file
$backup = new Backup('/path/to/my.ini');

# Backup the database named "test". The location on the file system will be returned
$backup_file = $backup->createLocalBackup("test");

# Upload the $backup_name file to the bucket "my-sql-backups"
# NOTE: If the bucket "my-sql-backups" doesn't exist, it will create it
$backup->s3->uploadFile($backup_file, "my-sql-backups");

# Using the initial connection, remove the local file
$backup->purgeBackup();
```

### Restore A Local File

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

use dutchie027\EasyMySQLBackup\Backup;

# OPTION A: Create a new backup with "default" configuration set
$backup = new Backup();

# Restore the file '/backups/mydb.20220330162457.sql.gz' to the database named 'restoredb'
# Also, force the database to be dropped & recreated
$backup->restore()->restoreLocalBackup('/backups/mydb.20220330162457.sql.gz', 'restoredb', 1);
```

### Restore From S3

``` php
#!/usr/bin/php
<?php

include_once 'vendor/autoload.php';

use dutchie027\EasyMySQLBackup\Backup;

# Because we're using S3, we have to give it a config file with our KVPs for S3 Access in them
$backup = new Backup('/path/to/my.ini');

# First download the file coredb.20220330162457.sql.gz from the bob-test bucket and put it in /tmp
# Assuming all runs well, store the local file name in the variable $buf
$buf = $backup->s3()->downloadFile('bob-test/coredb.20220330162457.sql.gz', '/tmp');

# Restore the newly downloaded $buf file to a new database 'core-restore' and force it to be dropped
# and created fresh
$backup->restore()->restoreLocalBackup($buf, 'core-restore', 1);
```

## Sample my.ini (showing all values)

``` ini
[s3]
S3_REGION     = 'us-east-1'
S3_ENDPOINT   = "https://s3.us-east-1.amazonaws.com"
S3_ACCESS_KEY = "ABCD1234EFGH5678ZZZZ"
S3_SECRET_KEY = "JuStiN8675309NeEDedA30918KeYtoTest567890"
S3_ACL        = "private"

[database]
DB_USER       = 'root'
DB_PASSWORD   = ''

[log]
LOG_LEVEL     = 300 ;Matches Constants found https://github.com/Seldaek/monolog/blob/main/src/Monolog/Logger.php
LOG_PREFIX    = 'easyMySQLBackup'
LOG_DIRECTORY = '/var/log/'
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
