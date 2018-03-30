<?php 
	session_start();
	$options;
	if (isset($_SESSION['options'])) {
		$options = $_SESSION['options'];
		unset($_SESSION['options']); //remove the session variable from $SESSION once it is obtained
	}

	$date=$_POST["date"];
	$time=$_POST["time"];
	if(isset($_POST["date"])){
		//connect to data base
		// $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
		



		// $insertQuery = "INSERT INTO Tasks Values('$email', '$firstName', '$lastName', '$password', '$contact', $creditNum, $creditSecurity, '$creditExpiry', $zipcode)";      
  //     	$result = pg_query($db, $insertQuery);
		$stringVal = $options . $date . $time;
	  	echo json_encode(array("abc" => $stringVal));
	 } 

?>