<?php
	 session_start();
	if ($_GET['func'] == 'edit-records') {
		$records = $_GET['arguments'];
		$table = $_GET['table'];

		$success;

		if ($table == "tasks") {
			$_SESSION['taskid'] = $records[0][0];
			$success = "set taskid,";
		}

		$success .= " redirect-tasks-edit";

		echo json_encode($success);
	}
?>