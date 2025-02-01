<?php
require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler('ErrorHandler::handleError');
set_exception_handler('ErrorHandler::handleException');

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-type: application/json; charset=UTF-8");

// Initialize the database connection
$database = new Database(
    $_ENV["DB_HOST"],
    $_ENV["DB_NAME"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"]
);
