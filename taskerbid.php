<?php
  require('session.php');

$taskid = $_POST['taskid'];
$trimmedTaskid = str_replace(array('[',']','"'), '',$taskid);
$allTasks = explode(",", $trimmedTaskid);

$db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());

foreach($allTasks as $taskingid){
$result = pg_query($db, "SELECT * FROM tasks WHERE task_id = '$taskingid' ");
$row = pg_fetch_assoc($result);
  $creator = $row['taskeeemail'];
  $runner = $_SESSION['userEmail'];
  $statusBid = 'pending';
  $statusTask = 'bidded';
  date_default_timezone_set('Asia/Singapore');
  $createddatetime = date('Y-m-d H:i:s');
  BEGIN;
  pg_query($db,"INSERT INTO bids VALUES ('$taskingid', '$creator', '$runner', '$statusBid', '$createddatetime') ");
 $submitSuccess = pg_query($db, "UPDATE tasks SET status = '$statusTask' WHERE task_id = '$taskingid' ");
  COMMIT;
}
if($submitSuccess){
	echo json_encode(array("abc" => $taskid));
} else {
	$errorMsg = pg_last_error($db);
  	echo json_encode(array("abc" => $errorMsg));
}
?>
