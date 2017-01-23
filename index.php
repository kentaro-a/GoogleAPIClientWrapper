<?php
// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/GoogleClientWrapper.php';
require_once __DIR__ . '/GoogleAnalytics.php';

// Any ID of your projects
$usr_id = "sampleId-001";
$client = new GoogleClientWrapper($usr_id);
$auth = $client->getAuthInfo();

if (!empty($auth)) {
    // If authinfo that includes access token, refresh token, etc..., is exist.
	$client->setAccessToken($auth);

	// Check if token has been expired, and rewrite authfile.
	$client->refleshAuth();

	//  --- Someting to do. ---
	$analytics = new GoogleAnalytics($client);

	$params = [
		"view_id" => "xxxxxxxxxx",
		"start_date" => "2016-12-01",
		"end_date" => "2017-01-23",
		"metrics" => "ga:sessions,ga:pageviews",	// Metorix(,separated)
		"dimensions" => "ga:pageTitle,ga:pagePath",	// Dimension(,separated)
		"sort" => "-ga:pageviews",					// Sort(- is Descending)
	];
	$ret = $analytics->getCustomReport($params);
	foreach ($ret as $r) {
		echo implode(",", $r) ."<br>";
	}


} else {
	// If authinfo is not exist, redirect to callback.php you registered to GoogleAPI manager.
	$client->redirectCallback();
}
