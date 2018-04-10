<?php 
  require('session.php');
  require('sanitize.php');
  $stringVal = "";

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
$detailsUpdate = getValidString($detailsUpdate);

$taskid = $_SESSION['taskid'];

if (!empty($_POST['admin'])) {
    //Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 

    $tasker = json_decode($_POST['tasker']);


    $updateQuery; //query
    
    //could be deleting or not adding new tasker to tasks
    if ($tasker == "not set") {
      $res1 = pg_query($db, "SELECT * FROM Tasks WHERE task_id = '$taskid'");
      $taskeremail = pg_fetch_result($res1, 0, 4);
      //no existing tasker - normal update query
      if ($taskeremail == "") {
        $updateQuery = "UPDATE tasks SET ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
        $res4 = pg_query($db, $updateQuery);
        if ($res4) {
          $stringVal .= "Tasker - not set, No existing tasker, success ";
        } else {
          $stringVal .= "Tasker - not set, No existing tasker, fail ";
        }
      } else {
        //Existing tasker found - remove from tasks
        //remove from tasks and bids - set to rejected
        pg_query($db, "BEGIN");
        $updateQuery = "UPDATE tasks SET taskeremail = NULL, status = 'not bidded', ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
        $updateBidQuery = "UPDATE Bids SET status = 'rejected' WHERE task_id = '$taskid'";
        $res2 = pg_query($db, $updateQuery);
        $res3 = pg_query($db, $updateBidQuery);
        if ($res2 && $res3) {
          pg_query($db, "COMMIT");
          $stringVal .= "Tasker - not set, existing tasker, success ";
        } else {
          pg_query($db, "ROLLBACK");
          $stringVal .= "Tasker - not set, existing tasker, fail ";
        }
      }
    } 
     else {
       //tasker set. Check if same as original tasker
       $res5 = pg_query($db, "SELECT * FROM Tasks WHERE task_id = '$taskid'");

       $taskeremail = pg_fetch_result($res5, 0, 4);
       //no diff, same as original tasker
       if ($tasker == $taskeremail) {
        $updateQuery = "UPDATE Tasks SET ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
        $res6 = pg_query($db, $updateQuery);
        if ($res6) {
          $stringVal .= "Tasker - set, existing tasker, same as original, success ";
        } else {
          $stringVal .= "Tasker - set, existing tasker, same as original, fail ";
        }
      }
      else {
        //diff. Set new tasker. Determine if tasker exists. Determine if tasker exists
        $res10 = pg_query($db, "SELECT * FROM Tasks WHERE task_id = '$taskid'");
        $oldtasker = pg_fetch_result($res10, 0, 4); //get old tasker
        $res17 = pg_query($db, "SELECT * FROM Tasks WHERE task_id = '$taskid'");
        $taskeeemail = pg_fetch_result($res17, 0, 3);

        $res7 = pg_query($db, "SELECT * FROM Taskers t WHERE t.email = '$tasker'");
        if (pg_num_rows($res7) > 0) {
          $stringVal .= "Tasker - set, existing tasker, diff from original ";
          //check availability
          $availabilityQuery = "SELECT * from Taskers t where t.email = '$tasker' and exists(select * from getAvailableTaskers('$ttypeUpdate', '$start', '$end') WHERE getAvailableTaskers = t.email)";
          $res8 = pg_query($db, $availabilityQuery);
          if (pg_num_rows($res8) > 0) {
            $stringVal .= "Tasker available, ";
            //check if tasker has already bidded
            $res9 = pg_query($db, "SELECT * FROM Bids b where b.task_id = '$taskid' and b.taskeremail = '$tasker'");
            if (pg_num_rows($res9) > 0) {
              $stringVal .= "Tasker has bidded, ";
              pg_query($db, "BEGIN");
              $updateQuery = "UPDATE tasks SET taskeremail = '$tasker', status = 'pending', ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
              $updateBidQuery1 = "UPDATE Bids SET status = 'accepted' where task_id = '$taskid' and taskeremail = '$tasker'";
              $updateBidQuery2 = "UPDATE Bids SET status = 'rejected' where task_id = '$taskid' and taskeremail = '$oldtasker'";
              $res11 = pg_query($db, $updateQuery);
              $res12 = pg_query($db, $updateBidQuery1);
              $res13 = pg_query($db, $updateBidQuery2);
              if ($res11 && $res12 && $res13) {
                pg_query($db, "COMMIT");
                $stringVal .= "success ";
              } else {
                pg_query($db, "ROLLBACK");
                $stringVal .= "fail ";
              }
            } else {
              $stringVal .= "Tasker has not bidded, ";
              //add bid
              pg_query($db, "BEGIN");
              $updateQuery = "UPDATE tasks SET taskeremail = '$tasker', status = 'pending', ttype = '$ttypeUpdate', task_details ='$detailsUpdate', startdatetime = '$start', enddatetime = '$end', loc = '$locUpdate' WHERE task_id = '$taskid' ";
              $createBidQuery = "INSERT into Bids Values('$taskid', '$taskeeemail', '$tasker', 'accepted', DEFAULT)";
              $updateBidQuery = "UPDATE Bids SET status = 'rejected' where task_id = '$taskid' and taskeremail = '$oldtasker'";
              $res14 = pg_query($db, $updateQuery);
              $res15 = pg_query($db, $updateBidQuery);
              $res16 = pg_query($db, $createBidQuery);
              if ($res14 && $res15 && $res16) {
                pg_query($db, "COMMIT");
                $stringVal .= "success ";
              } else {
                pg_query($db, "ROLLBACK");
                $stringVal .= "fail ";
              }
            }
          } else {
            $stringVal .= "Tasker not available ";
          }
        } else {
          $stringVal .= "Tasker - set, invalid tasker, fail ";
        }
      }
    }

  $stringVal .= "edit as admin ";
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