<?php
if (!defined('HC_LARAVEL_DIR')) exit;

define('LARAVEL_START', microtime(true));

$composer = require_once HC_LARAVEL_DIR . '/vendor/autoload.php';
$app = require_once HC_LARAVEL_DIR . '/bootstrap/app.php';

$classMap = $composer->getClassMap();

if (isset($classMap['PHPUnit\Framework\TestCase'])) {
    echo "Error: require-dev packages was detected in production mode.\n";
    exit(1);
}

unset($classMap);

require_once __DIR__ . '/vendor/autoload.php';