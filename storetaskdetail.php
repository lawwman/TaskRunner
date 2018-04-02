<?php
	//storetaskdetail.php is to store data into the session
	//For future implementation, aim to integrate it with session.php.
	session_start();
	if (isset($_POST['skill'])) {
		$_SESSION['locs'] = $_POST['locs'];
		$_SESSION['details'] = $_POST['details'];
		$_SESSION['skill'] = $_POST['skill'];
		echo json_encode($_POST['skill']);
	}
?>