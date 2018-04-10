<?php
	 session_start();
	 $success;
	if ($_GET['func'] == 'edit-records') {
		$records = $_GET['arguments'];
		$table = $_GET['table'];



		if ($table == "tasks") {
			$_SESSION['taskid'] = $records[0][0];
			$success .= " redirect-tasks-edit";
		} else if ($table == "taskees") {
			$success .= " redirect-taskees-edit";
			$_SESSION['taskeeemail_admin_use'] = $records[0][0];
		}
	}
	else if ($_GET['func'] == 'isAdmin') {
		if ($_SESSION['isAdmin'] == "t") {
			$success .= "Yes";
		} else {
			$success .= "No";
		}
	}
	echo json_encode($success);
?>