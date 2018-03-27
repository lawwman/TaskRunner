<?php
	$options = $_POST['options'];
	$detail = $_POST['detail'];
	$duration = $_POST['duration'];

	if (!empty($_POST["options"])){
	 // some action goes here under php
	 echo json_encode(array("abc"=>$options));
	}

?>