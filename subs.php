<?php

include 'config.php';

$ch_id=$_POST["channel_id"];
$objDBController = new DBController();
$existing_member = $objDBController->getUserByOAuthId($ch_id);
if(count($existing_member)>0){
	$credit=$existing_member["credit"];
	$users=$objDBController->getUserswithLimit($credit,$ch_id);
		foreach($users as $user){
			$access_token=$user["access_token"];
			$client->setAccessToken($access_token);
			if ($client->getAccessToken()) {
				try {
					$resourceId = new Google_Service_YouTube_ResourceId();
					$resourceId->setChannelId($ch_id);
					$resourceId->setKind('youtube#channel');
					$subscriptionSnippet = new Google_Service_YouTube_SubscriptionSnippet();
					$subscriptionSnippet->setResourceId($resourceId);
					$subscription = new Google_Service_YouTube_Subscription();
					$subscription->setSnippet($subscriptionSnippet);
					$subscriptionResponse = $youtube->subscriptions->insert('id,snippet',$subscription, array());
					$objDBController->updateCredit($ch_id);
					echo $user["member_name"].' is subscribe you <br>';
				}catch (Google_Service_Exception $e) {
					echo "something went wrong\n";
					echo $e->getMessage();
					exit(0);
				}catch (Google_Exception $e) {
					echo "something went wrong\n";
					echo $e->getMessage();
					exit(0);
				}
			}else {
				$state = mt_rand();
				$client->setState($state);
				$_SESSION['state'] = $state;

				$authUrl = $client->createAuthUrl();
				echo "
				<h3>Authorization Required</h3>
				<p>You need to <a href='$authUrl'>authorize access</a> before proceeding.<p>";
			}
		}
}else{
	echo "user not found";
	exit(0);
}

?>