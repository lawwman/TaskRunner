<?php 
	require('session.php');
	$skill;
	$details;
	$locs;
	$stringVal = "fail";

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

		//if values are set, proceed with sql statement
	if(isset($_POST["date"]) && isset($_POST["endDate"]) && isLoggedIn()) {
		//connect to data base
		$stringVal = "connect to db";
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		date_default_timezone_set('Asia/Singapore');

		//get date and enddate values
		$date=json_decode($_POST["date"]);
		$endDate=json_decode($_POST["endDate"]);
		$createdDateTime = date('Y-m-d H:i:s');
		$taskee_email = $_SESSION['userEmail'];
		$status = "not bidded";

		$availableTasker = pg_query($db, "SELECT getAvailableTasker('$skill', '$date', '$endDate')");

		if (pg_num_rows($availableTasker) > 0) {
			//get Unique ID and tasker email
			$status = 'pending';
			$taskerEmail = pg_fetch_result($availableTasker, 0, 0);
			$res = pg_query($db, "SELECT getUniqueTaskId()");
			$uniqueId = pg_fetch_result($res, 0, 0);

			pg_query($db, "BEGIN");
			$insertQuery = "INSERT INTO Tasks Values('$uniqueId', '$skill', '$details', '$taskee_email', '$taskerEmail', '$status', '$createdDateTime', '$date', '$endDate', '$locs')";
			$result = pg_query($db, $insertQuery);
			$result2 = pg_query($db, "INSERT INTO Bids Values('$uniqueId', '$taskee_email', '$taskerEmail', 'accepted', DEFAULT)");
			if ($result && $result2) {
				$stringVal = $taskerEmail . " has been successfully chosen for your task!";
				pg_query($db, "COMMIT");
			} else {
				pg_query($db, "ROLLBACK");
			}
		} else {
			$insertQuery = "INSERT INTO Tasks Values(DEFAULT, '$skill', '$details', '$taskee_email', NULL, '$status', '$createdDateTime', '$date', '$endDate', '$locs')";      
	      	$result = pg_query($db, $insertQuery);
	      	if ($result) {
				$stringVal = "Task has been successfully set.";
	      	}
		}
	 }
	echo json_encode(array("abc" => $stringVal));
?>
