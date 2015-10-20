<?php
require_once __DIR__ . '/../vendor/autoload.php';
define('APPLICATION_RUNTIME', 'test');
define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
ini_set('display_errors', '1');
ini_set('memory_limit', '2048M');
error_reporting(E_ALL | E_STRICT);
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
