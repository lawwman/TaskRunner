<?php 
	session_start();
	$options;
	$details;
	$locs;
	if (isset($_SESSION['options'])) {
		$options = json_decode($_SESSION['options'], true);
		unset($_SESSION['options']); //remove the session variable from $SESSION once it is obtained
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
	if(isset($_POST["date"]) && isset($_POST["endDate"])) {
		//connect to data base
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		$createdDateTime = date('Y-m-d H:i:s');
		$task_id = uniqid();
		$stringVal = $task_id . $options . $details . $locs . $date . $endDate;
	 	echo json_encode(array("abc" => $stringVal));

		// $insertQuery = "INSERT INTO Tasks Values('$task_id', '$options', '$taskee_email', '$tasker_email', NULL, '$createdDateTime', '$date', '$endDate', '$loc')";      
  //     	$result = pg_query($db, $insertQuery);
  //     	if ($result) {
  //     		$stringVal = $options . $details . $locs . $date . $endDate;
	 //  		echo json_encode(array("abc" => $stringVal));
  //     	}
	 } 

?>