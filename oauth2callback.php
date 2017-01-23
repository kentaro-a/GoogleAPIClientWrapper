<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/GoogleClientWrapper.php';

// Any ID of your projects
$usr_id = "sampleId-001";
$client = new GoogleClientWrapper($usr_id);

// Handle authorization flow from the server.
if (!isset($_GET['code'])) {
	$client->authRequest();
} else {
	$client->authByCode($_GET['code']);
}
