# Installation
## Install via Github
```bash
# installation
clone https://github.com/timoshka-lab/laravel-health-check.git
cd laravel-health-check
composer install

# use case
./laravel-health-check -d <path-to-laravel-directory>
```

## Install via Composer
```bash
# installation
composer require timoshka-lab/laravel-health-check

# use case
./vendor/bin/laravel-health-check -d <path-to-laravel-directory>
```

# Usage
```
Description:
  The command line interface to verify laravel configuration in production mode.

Usage:
  laravel-health-check [options] -d <laravel-directory-path>

Options:
  -h|--help            Prints this usage information
  --without-database   Avoid to run database configuration tests
  --without-mail       Avoid to run email configuration tests
  --without-queue      Avoid to run job queue configuration tests
```

# Notices
**IMPORTANT:** This project is not yet ready to use on production or commercial platforms.
There is no warranty in any cases.