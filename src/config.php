<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('BASE_URL', $_ENV['BASE_URL']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_PORT', $_ENV['DB_PORT']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('RABBITMQ_HOST', $_ENV['RABBITMQ_HOST']);
define('RABBITMQ_PORT', $_ENV['RABBITMQ_PORT']);
define('RABBITMQ_USERNAME', $_ENV['RABBITMQ_USERNAME']);
define('RABBITMQ_PASSWORD', $_ENV['RABBITMQ_PASSWORD']);
define('RABBITMQ_EMAIL_QUEUE', $_ENV['RABBITMQ_EMAIL_QUEUE']);
define('MAIL_HOST', $_ENV['MAIL_HOST']);
define('MAIL_PORT', $_ENV['MAIL_PORT']);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD']);
define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);
define('OAUTH2_PRIVATE_KEY_PATH', $_ENV['OAUTH2_PRIVATE_KEY_PATH']);
define('OAUTH2_PUBLIC_KEY_PATH', $_ENV['OAUTH2_PUBLIC_KEY_PATH']);
define('OAUTH2_ENCRYPTION_KEY', $_ENV['OAUTH2_ENCRYPTION_KEY']);
