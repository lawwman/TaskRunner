<?php
	require('debugging.php');
	session_start();
	if (isset($_SESSION['taskid'])) {
		consoleLog($_SESSION['taskid']);
		unset($_SESSION['taskid']);
	}

?>