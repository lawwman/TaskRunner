<?php
	require('session.php');
	require('sanitize.php');
	if(isset($_POST['skill']) && isset($_POST["pitch"]) && isset($_POST['prof']) && isset($_POST['rate']) && isLoggedIn()) {
		$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		$taskeremail = $_SESSION['userEmail'];
		$skillname = json_decode($_POST['skill']);
		$pitch =json_decode($_POST['pitch']);
		$prof = json_decode($_POST['prof']);
		$prof = getValidString($prof);
		$rate = json_decode($_POST['rate']);
		$insertQuery = "INSERT INTO hasSkills 
		Values('$taskeremail', '$skillname', '$rate', '$prof', '$pitch')";   
      	$result = pg_query($db, $insertQuery);
      	if ($result) {
			$stringVal = "skill has been created";
	  		echo json_encode(array("abc" => $stringVal));
      	} else {
      		$stringVal = "failed";
	  		echo json_encode(array("abc" => $stringVal));
      	}
	}
?>