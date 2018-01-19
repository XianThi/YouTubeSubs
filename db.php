<?php
class DBController {
	private $host = "localhost";
	private $user = "username";
	private $password = "password";
	private $database = "database";
	
	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->selectDB($conn);
		}
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conn);
	}
	
	function __destruct() {
		mysql_close();
	}
	
	function connectDB() {
		$conn = mysql_connect($this->host,$this->user,$this->password);
		return $conn;
	}
	
	function selectDB($conn) {
		mysql_select_db($this->database,$conn);
	}
	
	function getUserByOAuthId($channel_id) {
		$query = "SELECT * FROM members WHERE channel_id = '" . $channel_id . "'";
		$result = mysql_query($query);
		if(!empty($result)) {
			$existing_member = mysql_fetch_assoc($result);
			return $existing_member;
		}
	}
	
	function getUserByAccessToken($access_token){
		$query = "SELECT * FROM members WHERE access_token = '" . $access_token . "'";
		$result = mysql_query($query);
		if(!empty($result)) {
			$existing_member = mysql_fetch_assoc($result);
			return $existing_member;
		}
		
		
	}
	function updateCredit($channel_id){
		$query = "update members set credit=credit-1 WHERE channel_id = '" . $channel_id . "'";
		$result = mysql_query($query);
	}
	
	function updateAccessToken($channel_id,$access_token){
		$query = "update members set access_token='".$access_token."' WHERE channel_id = '" . $channel_id . "'";
		$result = mysql_query($query);
	}
	
	function getUserswithLimit($limit,$channel_id){
		$rows = array();
		$query = "select *from members where channel_id<>'$channel_id' limit $limit ";
		$result = mysql_query($query);
		if(!empty($result)) {
			while($row = mysql_fetch_assoc($result)){
				$rows[] = $row;
			}
		}
			return $rows;
	}
	
	function insertOAuthUser($userData) {
		$query = "INSERT INTO members (member_name, channel_id, photo, credit,access_token) VALUES ('" . $userData->name . "','" . $userData->id . "','" . $userData->picture . "',50,'".$userData->access_token."')";
		$result = mysql_query($query) or die(mysql_error());
	}
}
?>
