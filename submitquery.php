<?php 
	require('session.php');
	$options;
	$details;
	$locs;

	//check if prior values from addtasks.php has been set properly
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

	//get date and enddate values
	$date=json_decode($_POST["date"]);
	$endDate=json_decode($_POST["endDate"]);

	//if values are set, proceed with sql statement
	if(isset($_POST["date"]) && isset($_POST["endDate"]) && isLoggedIn()) {
		//connect to data base
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		date_default_timezone_set('Asia/Singapore');

		$createdDateTime = date('Y-m-d H:i:s');
		$taskee_email = $_SESSION['userEmail'];
		$status = "not bidded";

		$insertQuery = "INSERT INTO Tasks Values(DEFAULT, '$skill', '$details', '$taskee_email', NULL, '$status', '$createdDateTime', '$date', '$endDate', '$locs')";      
      	$result = pg_query($db, $insertQuery);
      	if ($result) {
			$stringVal = "Task has been successfully set, Task info: " . $task_id . $skill . $taskee_email . $status . $details . $locs . $createdDateTime . $date . $endDate;
	  		echo json_encode(array("abc" => $stringVal));
      	}
	 } 

?>