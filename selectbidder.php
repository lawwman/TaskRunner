<?php
	$stringVal;

	if (isset($_POST['taskid']) && isset($_POST['user']) { 

		$user = $_POST['user'];
		$taskid = $_POST['taskid'];


		// $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		// pg_query("BEGIN") or die("Could not start transaction\n");

		// $res1 = pg_query("UPDATE Tasks SET taskeremail WHERE id IN (1, 9)");
		// $res2 = pg_query("INSERT INTO cars VALUES (1, 'BMW', 36000), (9, 'Audi', 52642)");


		// if ($res1 and $res2) {
	 //    	$strinVal = "Commiting transaction\n";
	 //    	pg_query("COMMIT") or die("Transaction commit failed\n");
		// } else {
	 //    	$stringVal = "Rolling back transaction\n";
	 //    	pg_query("ROLLBACK") or die("Transaction rollback failed\n");;
		// }
		$stringVal = "works";
	} else {
		$stringVal = "shit";
	}
	echo json_encode($stringVal);
?>