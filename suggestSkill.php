<?php 
	$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234"); 
	$result = pg_query($db, "SELECT * FROM tasks");
	$array = array();
	while ($row = pg_fetch_assoc($result)) {
		$array[] = $row['task_name'];
	}
	// jQuery wants JSON data
	echo json_encode($array);
	flush();
?>
