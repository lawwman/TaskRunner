<?php
	if (isset($_POST['taskid']) && isset($_POST['user'])) { 

		$taskeremail = $_POST['user'];
		$taskid = $_POST['taskid'];


		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 

		BEGIN;
		$res1 = pg_query($db, "UPDATE Tasks SET (taskeremail, status) = ('$taskeremail', 'pending') WHERE task_id = '$taskid' ");
		$res2 = pg_query($db, "UPDATE Bids SET status = 'rejected' WHERE task_id = '$taskid' ");
		$res3 = pg_query($db, "UPDATE Bids SET status = 'accepted' WHERE task_id = '$taskid' AND taskeremail = '$taskeremail' ");
		COMMIT;

		$stringVal = "works";
		echo $stringVal;
	}
?>