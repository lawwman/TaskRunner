<?php
	require('session.php');
	if(isset($_POST['skill']) && isLoggedIn()) {
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		$taskeremail = $_SESSION['userEmail'];
		$skillname = json_decode($_POST['skill']);
		$insertQuery = "DELETE FROM hasSkills WHERE temail = '$taskeremail' AND sname = '$skillname'";   
      	$result = pg_query($db, $insertQuery);
      	if ($result) {
			$stringVal = "skill has been removed";
	  		echo json_encode(array("abc" => $stringVal));
      	} else {
      		$stringVal = "failed";
	  		echo json_encode(array("abc" => $stringVal));
      	}
	}
?>