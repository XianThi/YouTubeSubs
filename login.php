<?php

include 'config.php';

//Logout
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  unset($_SESSION['code']);
  unset($_SESSION['state']);
  unset($_SESSION['registered']);
  $client->revokeToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . $redirect_uri);
}
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}


//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
	if (isset($_SESSION['registered']) && $_SESSION["registered"]==TRUE){
	$objDBController = new DBController();
	$existing_member = $objDBController->getUserByAccessToken($_SESSION["access_token"]["access_token"]);
	$userData["credit"]=$existing_member["credit"];
	$userData["channel_id"]=$existing_member["channel_id"];
	$userData["snippet"]["title"]=$existing_member["member_name"];
	$userData["channel_id"]=$existing_member["channel_id"];
	$userData["snippet"]["thumbnails"]["default"]["url"]=$existing_member["photo"];
	}else{
		$ch_id=$api->get_channel_id($_SESSION['access_token']['access_token']);
		$ch_info=$api->get_channel_info($ch_id);
		if(isset($ch_info)){
			$userData = $ch_info;
		}else{
			$ch_info=$api->channelsListById($youtube,'snippet,contentDetails,statistics',array('id' => $ch_id));
			$userData=$ch_info["items"][0];
		}
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
					$_SESSION['registered']=TRUE;
					header('Location: ' . $redirect_uri);
				}else{
					$objDBController->updateAccessToken($existing_member["channel_id"],$_SESSION['access_token']['access_token']);
					$userData["credit"]=$existing_member["credit"];
					$userData["channel_id"]=$existing_member["channel_id"];
				}
			}
			$_SESSION['access_token'] = $client->getAccessToken();
	}
}else{
  $authUrl = $client->createAuthUrl();
}
require_once("main.php");
