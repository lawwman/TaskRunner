<?php 
	require('session.php');
	$options;
	$details;
	$locs;
	if (isset($_SESSION['skill'])) {
		$skill = json_decode($_SESSION['skill']);
		unset($_SESSION['skill']); //remove the session variable from $SESSION once it is obtained
	}
	if (isset($_SESSION['locs'])) {
	$locs = json_decode($_SESSION['locs']);
	unset($_SESSION['locs']); //remove the session variable from $SESSION once it is obtained
	}
	if (isset($_SESSION['details'])) {
	$details = json_decode($_SESSION['details']);
	unset($_SESSION['details']); //remove the session variable from $SESSION once it is obtained
	}
	$date=json_decode($_POST["date"]);
	$endDate=json_decode($_POST["endDate"]);
	if(isset($_POST["date"]) && isset($_POST["endDate"]) && isLoggedIn()) {
		//connect to data base
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		date_default_timezone_set('Asia/Singapore');
		$createdDateTime = date('Y-m-d H:i:s');
		$task_id = uniqid();
		$taskee_email = $_SESSION['userEmail'];
		$status = "not bidded";

		$insertQuery = "INSERT INTO Tasks Values('$task_id', '$skill', '$details', '$taskee_email', NULL, '$status', '$createdDateTime', '$date', '$endDate', '$locs')";      
      	$result = pg_query($db, $insertQuery);
      	if ($result) {
			$stringVal = $task_id . $skill . $taskee_email . $status . $details . $locs . $createdDateTime . $date . $endDate;
	  		echo json_encode(array("abc" => $stringVal));
      	}
	 } 

?>