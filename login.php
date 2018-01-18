<?php
function json_curl($url){
	//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
return json_decode($result, true);
}

include 'config.php';

//Logout
if (isset($_REQUEST['logout'])) {
	$tokenSessionKey = 'token-' . $client->prepareScopes();
  unset($_SESSION['access_token']);
  unset($_SESSION['code']);
  unset($_SESSION['state']);
  unset($_SESSION[$tokenSessionKey]);
  $client->revokeToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

// Check if an auth token exists for the required scopes
$tokenSessionKey = 'token-' . $client->prepareScopes();
if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }

  $client->authenticate($_GET['code']);
  $_SESSION[$tokenSessionKey] = $client->getAccessToken();
  header('Location: ' . $redirect_uri);
}

if (isset($_SESSION[$tokenSessionKey])) {
  $client->setAccessToken($_SESSION[$tokenSessionKey]);
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
	$ch_url='https://www.googleapis.com/youtube/v3/channels?part=id&mine=true&access_token='.$_SESSION['access_token']['access_token'];
	$channel = json_curl($ch_url);
	$ch_data_url='https://www.googleapis.com/youtube/v3/channels/?id='.$channel["items"][0]["id"].'&part=snippet%2CcontentDetails%2Cstatistics&key='.$API_KEY;
	$data = json_curl($ch_data_url);
	if(isset($data["items"][0])){
  $userData = $data["items"][0];
  if(!empty($userData)) {
	$objDBController = new DBController();
	$existing_member = $objDBController->getUserByOAuthId($userData["id"]);
	if(empty($existing_member)) {
		$user = new stdClass();
		$user->id=$userData["id"];
		$user->name=$userData["snippet"]["title"];
		$user->picture=$userData["snippet"]["thumbnails"]["default"]["url"];
		$user->access_token=$_SESSION['access_token']['access_token'];
		$objDBController->insertOAuthUser($user);
		header('Location: ' . $redirect_uri);
	}else{
		$objDBController->updateAccessToken($existing_member["channel_id"],$_SESSION['access_token']['access_token']);
		$userData["credit"]=$existing_member["credit"];
		$userData["channel_id"]=$existing_member["channel_id"];
	}
  }
  $_SESSION['access_token'] = $client->getAccessToken();
	}else{
		echo 'You are not have any YouTube channel';
	}
  } else {
  $authUrl = $client->createAuthUrl();
}
require_once("main.php");