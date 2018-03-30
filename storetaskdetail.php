<?php
	//storetaskdetail.php is to store data into the session
	//For future implementation, aim to integrate it with session.php.
	session_start();
	if (isset($_POST['options'])) {
		$_SESSION['options'] = $_POST['options'];
		echo json_encode($_POST['options']);
	}
?>