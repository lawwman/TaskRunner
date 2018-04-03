<?php
	if (isset($_POST['taskid'])) {
		echo json_encode(array("abc" => $_POST['taskid']));
	}

?>
