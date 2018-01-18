<?php
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';
session_start();

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
 $client_id = 'CLIENT_ID';
 $client_secret = 'CLIENT_SECRET';
 $redirect_uri = 'REDIRECT_URI';
 $API_KEY='API_KEY';
 
//Create Client Request to access Google API
$client = new Google_Client();
$client->setApplicationName("YoutubeSubs");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$client->setRedirectUri($redirect_uri);

// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);

