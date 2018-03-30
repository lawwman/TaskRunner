<?php 

$date=$_POST["date"];
$time=$_POST["time"];
if(isset($_POST["date"])){
    // Connect to the database. Please  function submitTask($date,$time){ change the password in the following line accordingly
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 
     $taskee_email = $_SESSION['user'];
    date_default_timezone_set('Asia/Singapore'); 
    $createddatetime = date('Y-m-d H:i:s');
   
    $query = "INSERT INTO taskss VALUES('$createddatetime','$taskee_email',null,null,$date, $time, null, null, null)";
    //insertQuery($db,$query);
  echo json_encode($_POST["date"]);
  } 

?>