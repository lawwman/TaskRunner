<?php 
  require('session.php');
  $stringVal;

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
if (!empty($_POST['admin'])) {
    //Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 

    $tasker = json_decode($_POST['tasker']);
    $updateQuery = "UPDATE tasks SET taskeremail = NULL, ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
    
    if ($tasker == "not set") {
      $res1 = pg_query($db, "SELECT * FROM Tasks WHERE task_id = '$taskid'");
      $taskeremail = pg_fetch_result($res1, 0, 4);
    }
    //check if tasker has been set
    // if ($tasker != "not set") {
    //   $re1 = pg_query($db, "SELECT * FROM Taskers WHERE email = '$tasker'");
    //   //check if tasker exists
    //   if (pg_num_rows($rel)) {
    //     $updateQuery = "UPDATE tasks SET taskeremail = '$tasker', status = 'pending', ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
    //   }
    // }


    // $result= pg_query($db,"UPDATE tasks 
    //         SET ttype = '$ttypeUpdate', 
    //             task_details ='$detailsUpdate', 
    //             startdatetime = '$start', 
    //             enddatetime = '$end', 
    //             loc = '$locUpdate' 
    //         WHERE task_id = '$taskid' ");

    // if($result){
    //   $stringVal = $taskid . $ttypeUpdate . $start . $end . $locUpdate . $detailsUpdate;
    //   echo json_encode(array("abc" => $stringVal));
    // }
  $stringVal = "edit as admin " . $taskeremail;
} else {
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
      $stringVal = "edit as user";
    }
  }
  echo json_encode(array("abc" => $stringVal));
?>