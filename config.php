<?php
require_once 'db.php';
require_once 'api.php';
session_start();

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
 $client_id = 'CLIENT_ID';
 $client_secret = 'CLIENT_SECRET';
 $redirect_uri = 'REDIRECT_URI';
 $API_KEY='API_KEY';
 $api = new YouTubeAPI();
 $api->client_id=$client_id;
 $api->client_secret=$client_secret;
 $api->redirect_uri=$redirect_uri;
 $api->API_KEY=$API_KEY;
 $client = $api->create();
 $youtube=$api->youtube($client);
