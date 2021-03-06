<?php
	/*
	* Script reads new category information from input parameters and puts data 
	* in database.(cid is generated by DB) Script also sends respond weather the task is completed succesfully
	*
	* input parameters:
	* 	
	* name [string], 
	* color[string], 
	* reminders[string], 
	*
	* output format:
	* {"response" : true} 
	*/
	
	include('../php/include/sessionAjax.php');
	
	function checkParameters() {
		return isset($_POST['name']) && isset($_POST['reminders']);
	}
	
	if($session->logged_in) {
		$posts = array();
		if(DB_CONNECT && checkParameters()) {
			$query = "SELECT * ".
					 "FROM ".TBL_USERS." ".
					 "WHERE username='$session->username'";
					 
			$result = $database->query($query);
			$dbarray = mysql_fetch_array($result);
			
			if(isset($_POST['color']))
				$color=$_POST['color'];
			else
				$color="#000000";
			
			$query = "INSERT INTO ".TBL_CATEGORY." (ID_user, name, color, default_reminder_email, default_reminder_sms) ".
					 "VALUES (".(int)$dbarray['ID_user'].", '".$_POST['name']."', '".$color."', ".(int)$_POST['reminders'].", ".(int)$_POST['reminders'].")";
			
			$result =  $database->query($query);
			
			$posts = array ("response" => $result);
			
			header('Content-type: application/json');
						
			echo json_encode($posts);
		} else {
			header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable', true, 503);
		}
	} else {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
?>