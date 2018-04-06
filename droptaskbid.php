<?php
	
	require('session.php');

    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());

	$taskid = $_POST['taskid'];
	$taskerEmail = $_SESSION['userEmail'];

	if(isset($_POST['dropbid']) && isset($_POST['taskid'])) {
		$query = "SELECT * FROM bids WHERE task_id = $taskid";
		$resultCount = pg_num_rows($db,$query);
		if($resultCount == 1){
	// 		//transaction
			BEGIN;
			$submitBidResult = pg_query($db, "DELETE FROM bids WHERE taskeremail = '$taskerEmail' AND task_id = '$taskid' ");
			$submitTasksResult = pg_query($db,"UPDATE tasks SET status = 'not bidded' WHERE task_id = '$taskid' ");
			COMMIT;
			if($submitTasksResult){
				echo json_encode(array("abc" => $_POST['taskid']));
			}else {
				$errorMsg = pg_last_error($db);
				echo json_encode(array("abc" => $errorMsg));
			}
		} else {
			$updateQueryBid = "DELETE FROM bids WHERE taskeremail = '$taskerEmail' AND task_id ='$taskid'";
			$submitBidResult = pg_query($db,$updateQueryBid);
			if($submitBidResult){
				echo json_encode(array("abc" => $_POST['taskid']));
			}else {
				$errorMsg = pg_last_error($db);
				echo json_encode(array("abc" => $errorMsg));
			}
		}
		
	}

	else if(isset($_POST['droptask']) && isset($_POST['taskid'])) {
		BEGIN;
		$updateQueryBid = "DELETE FROM bids WHERE taskeremail = '$taskerEmail' AND task_id = '$taskid' ";
		$submitBidResult = pg_query($db,$updateQueryBid);
		$updateQueryTasks = "UPDATE tasks SET status = 'terminated' WHERE task_id = '$taskid' ";
		$submitTasksResult = pg_query($db,$updateQueryTasks);
		COMMIT;
		if($submitTasksResult){
				echo json_encode(array("abc" => $_POST['taskid']));
			}else {
				$errorMsg = pg_last_error($db);
				echo json_encode(array("abc" => $errorMsg));
			}
	}

?>