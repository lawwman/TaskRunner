<?php 
  require('session.php');

$startJson=$_POST["start"];
$start = str_replace('"','',$startJson);

$endJson=$_POST["end"];
$end = str_replace('"','',$endJson);

$ttypeUpdateJson=$_POST["ttype"];
$ttypeUpdate = str_replace('"','',$ttypeUpdateJson);

$locUpdateJson = $_POST["loc"];
$locUpdate = str_replace('"','',$locUpdateJson);

$detailsUpdateJson = $_POST["details"];
$detailsUpdate = str_replace('"','',$detailsUpdateJson);

$taskid = $_SESSION['taskid'];

// if(!isset($_POST["startdate"],$_POST["starttime"],$_POST["enddate"],$_POST["endtime"],$_POST["ttype"],$_POST["loc"],$_POST["details"])){
    //Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 
    $queryTuple = "SELECT * FROM tasks WHERE task_id = $taskid";
    $resultRetrieve = pg_query($db,$queryTuple);
    $row = pg_fetch_assoc($resultRetrieve);

   $result= pg_query($db,"UPDATE tasks 
    		  SET ttype = '$ttypeUpdate', 
    		  	  task_details ='$detailsUpdate', 
    		  	  startdatetime = '$start', 
    		  	  enddatetime = '$end', 
    		  	  loc = '$locUpdate' 
    		  WHERE task_id = '$taskid' ");

if($result){
$stringVal = $taskid . $ttypeUpdate . $start . $end . $locUpdate . $detailsUpdate;
  echo json_encode(array("abc" => $stringVal));
  } else{
 $errorMsg = pg_last_error($db);
  	echo json_encode(array("abc" => $errorMsg));
  }

?>