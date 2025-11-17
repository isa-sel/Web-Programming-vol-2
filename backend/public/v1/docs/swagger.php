<?php

// CRITICAL: Suppress ALL output
@ini_set('display_errors', '0');
error_reporting(0);
ob_start();

require __DIR__ . '/../../../vendor/autoload.php';

if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('BASE_URL', 'http://localhost/Web%20programming%20vol%202/Web%20Milestone%201/backend');
} else {
    define('BASE_URL', 'https://add-production-server-after-deployment/backend/');
}

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php',
    __DIR__ . '/../../../rest/routes'
]);

// Clear any warnings
ob_end_clean();

// Output only JSON
header('Content-Type: application/json');
echo $openapi->toJson();
?>