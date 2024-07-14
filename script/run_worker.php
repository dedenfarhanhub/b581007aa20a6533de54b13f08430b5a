<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

use App\Queue\EmailWorker;
use App\Services\EmailService;

$emailService = new EmailService(new App\Services\DBLogger());
$emailWorker = new EmailWorker($emailService);
$emailWorker->processQueue();
