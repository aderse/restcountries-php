<?php

// Load the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MainController;

$controller = new MainController();

$controller->index();