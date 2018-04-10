<?php
	require("debugging.php");
	$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234"); 
	$result = pg_query($db, "SELECT * FROM Skills");
	$array = '[';
	while ($row = pg_fetch_assoc($result)) {
		$array .= '{"title":"';
		$array .=  $row['sname'] . '"},';
	}
	$array = rtrim($array,',');
	$array .= ']';
	// jQuery wants JSON data
	echo json_encode($array);
	pg_close($db);
?>