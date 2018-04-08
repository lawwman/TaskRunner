<?php

  $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());

  $taskid = $_POST['taskid'];
  
  $query= "UPDATE tasks SET status = 'deleted' WHERE task_id=$taskid";
  $result = pg_query($db, $query);

  if($result){
  	echo json_encode(array("abc" => $taskid));
  } else {
  	$errorMsg = pg_last_error($db);
  	echo json_encode(array("abc" => $errorMsg));
  }


?>