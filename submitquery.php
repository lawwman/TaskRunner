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
		$stringVal = $options . $date . $time;
	  	echo json_encode(array("abc" => $stringVal));
	 } 

?>