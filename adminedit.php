<?php
	 session_start();
	if ($_GET['func'] == 'edit-records') {
		$records = $_GET['arguments'];
		$table = $_GET['table'];

		$success;

		if ($table == "tasks") {
			$_SESSION['taskid'] = $records[0][0];
			$success .= " redirect-tasks-edit";
		} else if ($table == "taskees") {
			$success .= " redirect-taskees-edit";
			
		}



		echo json_encode($success);
	}
?>