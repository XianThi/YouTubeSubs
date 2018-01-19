<?php

include 'config.php';

$ch_id=$_POST["channel_id"];
$objDBController = new DBController();
$existing_member = $objDBController->getUserByOAuthId($ch_id);
if(count($existing_member)>0){
	$credit=$existing_member["credit"];
	$users=$objDBController->getUserswithLimit($credit,$ch_id);
	$i=0;
		foreach($users as $user){
			$access_token=$user["access_token"];
			$client->setAccessToken($access_token);
			if ($client->getAccessToken()) {
				try {
					$result = $api->subscribe($access_token,$ch_id);
					$objDBController->updateCredit($ch_id);
					echo $user["member_name"].' is subscribe you <br>';
					$i=$i+1;
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
		echo 'Totaly '.$i.' users subscribe you <br>';
}else{
	echo "user not found";
	exit(0);
}

?>
