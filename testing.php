<?php
	session_start();
	if (isset($_POST['options'])) {
		$_SESSION['options'] = $_POST['options'];
		$_SESSION['details'] = $_POST['details'];
		$_SESSION['dur'] = $_POST['dur'];
		echo json_encode($_POST['options']);
	}
?>