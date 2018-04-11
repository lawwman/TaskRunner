<?php
	//require('sanitize.php');
	require('session.php');

if(isset($_POST['newskill']) && isset($_POST['newskilldesc'])) {
	$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
		$newskill = json_decode($_POST['newskill']);
		//$newskill = getValidString($newskill);
		$newskilldesc = json_decode($_POST['newskilldesc']);
		//$newskilldesc = getValidString($newskilldesc);
		$insertQueryAdmin = "INSERT INTO skills VALUES ('$newskill', '$newskilldesc') ";
		$result = pg_query($db, $insertQueryAdmin);
      	if ($result) {
			$stringVal = "skill has been created";
	  		echo json_encode(array("abc" => $stringVal));
      	} else {
      		$stringVal = "failed";
	  		echo json_encode(array("abc" => $stringVal));
      	}
	} 
?>