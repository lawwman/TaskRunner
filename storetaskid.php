<?php
	session_start();
	if (isset($_POST['taskid']) && isset($_POST['tasktype'])) {
		$_SESSION['taskid'] = $_POST['taskid'];
		$_SESSION['tasktype'] = $_POST['tasktype'];
		echo json_encode(array("abc" => $_POST['taskid']));
	}
	if(isset($_POST['taskeremail'])){
		$_SESSION['taskeremail'] = $_POST['taskeremail'];
		echo json_encode(array("abc" => $_POST['taskeremail']));
	}
?>