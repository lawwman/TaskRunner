<?php
	session_start();
	if (isset($_POST['options'])) {
		$_SESSION['options'] = $_POST['options'];
		echo json_encode($_POST['options']);
	}
?>