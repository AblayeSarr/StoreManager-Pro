
<?php

require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Controllers/POSController.php';

$database = new Database();

$posController = new POSController($database);

$posController->index();