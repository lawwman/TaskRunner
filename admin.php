
<!DOCTYPE html>  

<?php 
  require('debugging.php');
  require('session.php');

  redirectIfNot("admin");

  if ($_GET["argument"]=='signOut'){
    logout();
  }

  function showUser() {
    if (isLoggedIn()) {
      echo '
      <div class="ui dropdown inverted button">Hello, '. $_SESSION['userName'] . '</div>
      <div class="ui dropdown inverted button" id="signOut" formaction="/demo/signup.php">Sign Out</div>
      ';
    } else {
      echo "<a class='ui inverted button' href='/demo/taskerlogin.php'>Log in</a>
      <a class='ui inverted button' href='/demo/taskersignup.php'>Sign Up</a>";
    }
  }
?>

<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Admin Page</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/card.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/sidebar.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/transition.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/modal.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dimmer.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/table.css">  
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css"> 
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/input.css"> 

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/modal.js"></script>
  <script src="semantic/dist/components/dimmer.js"></script>
  <script src="semantic/dist/components/package.js"></script>

  <script>

    var result = [];

    function redirectPage(data) {
      var redirect = data.split('-');
      if (redirect[1] == "tasks") {
        console.log(redirect[1]);
        window.location.replace("/demo/edittasksadmin.php");
      } else if (redirect[1] == "taskees") {
        console.log(redirect[1]);
        window.location.replace("/demo/edittaskeeprofile.php");
      } else if (redirect[1] == "taskers") {
        console.log(redirect[1]);
        window.location.replace("/demo/edittaskerprofile.php");
      }
    }

    function retrieveData(tableName, direction, offset) {
      return $.ajax({ 
        type: 'GET', 
        url: '/demo/adminRetrieval.php', 
        data: { func: "retrieve-data", table: tableName, dir: direction, off: offset}, 
        dataType: 'json',
        success: function (data) {
          populateTable(data, tableName);
        }
      });
    }

    function populateTable(data, tableName) {
      let table = document.getElementById(tableName);
      $.each(data, function(k,v) {
        let row = table.insertRow(1);
        let generatedRow = `
          <td title=> 
          <div class= 'ui checkbox'> 
            <input type = 'checkbox' name = 'checked[]' value='` + k + `'>
          </div>
          </td>`;
        $.each(v, function(attr,val) {
          generatedRow = generatedRow + `<td title=` + val + `>` + val + '</td>';
        });

        row.innerHTML = generatedRow;
      });
      return data;
    }

    function clearTable(tableName) {
      let table = document.getElementById(tableName);
      while (table.rows.length > 1) {
        table.deleteRow(1);
      }
    }

    function updateButtons(tableName) {
      let nextBtn = document.getElementById(tableName + "-next");
      let prevBtn = document.getElementById(tableName + "-prev");
      let pageNo = document.getElementById(tableName + "-page-no");
      if (pageNo.value <= 1) {
        prevBtn.style.visibility = "hidden";
      } else {
        prevBtn.style.visibility = "visible";
      }
      let maxPages = document.getElementById(tableName + "-max-pages");
      if (pageNo.value >= maxPages.innerHTML) {
        nextBtn.style.visibility = "hidden";
      } else {
        nextBtn.style.visibility = "visible";
      }
    }

    function deleteData(tableName) {
      let records = [];
      let table = document.getElementById(tableName);
      for (let i = 1; i < table.rows.length; i++) {
        let checkbox = table.rows[i].cells[0].getElementsByTagName("input")[0];
        if (checkbox.checked) {
          let vals = table.rows[i].cells;
          let list = [];
          for (let j = 1; j < vals.length; j++) {
            list.push(vals[j].innerHTML);
          }
          records.push(list);
        }
      }

      if (records.length > 0) {
        $.ajax({ 
          type: 'GET', 
          url: '/demo/adminRetrieval.php', 
          data: { func: "delete-records", table: tableName, arguments: records}, 
          dataType: 'json',
          success: function (data) {
            refreshTable(tableName);
          }
        });
      }
    }

    //edits the first task that was checked in the check box
    function editData(tableName) {
      let records = [];
      let table = document.getElementById(tableName);
      for (let i = 1; i < table.rows.length; i++) {
        let checkbox = table.rows[i].cells[0].getElementsByTagName("input")[0];
        if (checkbox.checked) {
          let vals = table.rows[i].cells;
          let list = [];
          for (let j = 1; j < vals.length; j++) {
            list.push(vals[j].innerHTML);
          }
          records.push(list);
          break; //Only want the first tuple that was checked
        }
      }

      if (records.length == 1) {
        $.ajax({ 
          type: 'GET', 
          url: '/demo/adminedit.php', 
          data: { func: "edit-records", table: tableName, arguments: records}, 
          dataType: 'json',
          success: function (data) {
            console.log(data);
            redirectPage(data);
          }
        });
      }
    }




    function updateTable(tableName, direction) {
      clearTable(tableName);
      let pageNo = document.getElementById(tableName + "-page-no");
      let newData = retrieveData(tableName, direction, pageNo.value);
      if (direction == "forward") {
        pageNo.value = parseInt(pageNo.value) + 1;
      } else {
        pageNo.value = parseInt(pageNo.value) - 1;
      }
    }
    
    function updateTableMaxPages(tableName) {
      let maxPages = document.getElementById(tableName + "-max-pages");
      $.ajax({ 
        type: 'GET', 
        url: '/demo/adminRetrieval.php', 
        data: { func: "get-max-pages", table: tableName}, 
        dataType: 'json',
        success: function (data) {
          maxPages.innerHTML = data;
          updateButtons(tableName);
        }
      });
    }

    function refreshTable(tableName) {
      let pageNo = document.getElementById(tableName + "-page-no");
      let maxPages = document.getElementById(tableName + "-max-pages");
      $.ajax({ 
        type: 'GET', 
        url: '/demo/adminRetrieval.php', 
        data: { func: "get-max-pages", table: tableName}, 
        dataType: 'json',
        success: function (data) {
          maxPages.innerHTML = data;
          if (data < pageNo.value) {
            pageNo.value = data;
          }
          updateButtons(tableName);
          clearTable(tableName);
          retrieveData(tableName, "forward", pageNo.value - 1);

          if (tableName == "tasks") {
            refreshTable("bids");
          } else if (tableName == "taskers" || tableName == "taskees" || tableName == "skills") {
            refreshTable("tasks");
            refreshTable("bids");
          }
        }
      });
    }

    // performs sign out functionality.
    $(document).ready(function() {
      $('#signOut').click(function() {
        $.ajax({
          url: '/demo/viewtasks.php?argument=signOut',
          success: function(html){
            window.location.replace("/demo/index.php");
          }
        });
      });

      $('.next').click(function() {
        let curr = document.getElementById("tasks-page-no");
        let idArr = this.id.split("-");
        updateTable(idArr[0], "forward");
        updateButtons(idArr[0]);
      });

      $('.prev').click(function() {
        let curr = document.getElementById("tasks-page-no");
        let idArr = this.id.split("-");
        updateTable(idArr[0], "backward");
        updateButtons(idArr[0]);
      });

      $('.delete-btn').click(function() {
        let idArr = this.id.split("-");
        deleteData(idArr[0]);
      });


      $('.edit-btn').click(function() {
        let idArr = this.id.split("-");
        editData(idArr[0]);
        if (editData) {
          refreshTable(idArr[0]);
        } else {
          alert("edit failed");
        }
      });

      let taskData = retrieveData("tasks", "forward", 0);
      let bidData = retrieveData("bids", "forward", 0);
      let taskeeData = retrieveData("taskees", "forward", 0);
      let taskerData = retrieveData("taskers", "forward", 0);
      let skillData = retrieveData("skills", "forward", 0);
      updateTableMaxPages("tasks");
      updateTableMaxPages("bids");
      updateTableMaxPages("taskees");
      updateTableMaxPages("taskers");
      updateTableMaxPages("skills");
    })
  </script>

  <style type="text/css">

    .my_container {
      margin: 50px;
    }
    
    .masthead.segment {
      min-height: 100px;
      padding: 1em 0em;
    }
    .masthead .logo.item img {
      margin-right: 1em;
    }
    .masthead .ui.menu .ui.button {
      margin-left: 0.5em;
    }
    .masthead h1.ui.header {
      margin-top: 3em;
      margin-bottom: 0em;
      font-size: 4em;
      font-weight: normal;
    }
    .masthead h2 {
      font-size: 1.7em;
      font-weight: normal;
    }

    .ui.vertical.stripe {
      padding: 8em 0em;
    }
    .ui.vertical.stripe h3 {
      font-size: 2em;
    }
    .ui.vertical.stripe .button + h3,
    .ui.vertical.stripe p + h3 {
      margin-top: 3em;
    }
    .ui.vertical.stripe .floated.image {
      clear: both;
    }
    .ui.vertical.stripe p {
      font-size: 1.33em;
    }
    .ui.vertical.stripe .horizontal.divider {
      margin: 3em 0em;
    }

    .quote.stripe.segment {
      padding: 0em;
    }
    .quote.stripe.segment .grid .column {
      padding-top: 5em;
      padding-bottom: 5em;
    }

    .footer.segment {
      padding: 5em 0em;
    }

    .secondary.pointing.menu .toc.item {
      display: none;
    }

    @media only screen and (max-width: 700px) {
      .ui.fixed.menu {
        display: none !important;
      }
      .secondary.pointing.menu .item,
      .secondary.pointing.menu .menu {
        display: none;
      }
      .secondary.pointing.menu .toc.item {
        display: block;
      }
      .masthead.segment {
        min-height: 350px;
      }
      .masthead h1.ui.header {
        font-size: 2em;
        margin-top: 1.5em;
      }
      .masthead h2 {
        margin-top: 0.5em;
        font-size: 1.5em;
      }
    }
    
    .page-no {
      width: 4em;
      text-align: center;
    }

    .pagenum {
       line-height: 3em;       
    }

    .number {
      width: 4em;      
    }
    
    input::placeholder{
      text-align: center; 
    }



  </style>
</head>

<body>

  <!-- Top menu -->
 <div class="pusher">
  <div class="ui inverted vertical masthead center aligned segment">

    <div class="ui container">
      <div class="ui large secondary inverted pointing menu">
        <a class="toc item">
          <i class="sidebar icon"></i>
        </a>
        <a class="item" href="/demo/index.php">Home</a>
          <a class ="active item" href="/demo/admin.php"> View/Manage</a>          
        <div class="right item">
          <?php showUser(); ?> 
        </div>
      </div>
    </div>
  </div>
  
  <div class="my_container">
    <h2>View/Manage Tasks
      <button class ="ui right floated tiny teal button edit-btn" id="tasks-edit"> Edit Task </button>
      <button class ="ui right floated tiny teal button delete-btn" id="tasks-delete"> – </button>          
      <button class ="ui right floated tiny teal button add-btn" id="tasks-add"> + </button>   
    </h2>               

    <table class="ui fixed single line celled table" id="tasks">
      <thead>
        <th> Select </th>        
        <th> Task Id </th>
        <th> Task Type </th>
        <th> Task Details </th>
        <th> Task Creator </th>          
        <th> Tasker Assigned </th>
        <th> Status </th>
        <th> Created Date and Time </th>
        <th> Start Date and Time </th>
        <th> End Date and Time </th>          
        <th> Location </th>
      </thead>
      <tbody>
      </tbody>
    </table>
      
    <div class="ui container">
      <div align="center">
        <div class="ui input focus">            
            
          <button class= "ui tiny compact button prev" id="tasks-prev">
            <i class= "caret left icon"></i>
          </button> &ensp;
        
          <input type="text" value="1" type="number" class="page-no" id="tasks-page-no">
          &ensp; <div class="pagenum">of <span id="tasks-max-pages">50</span></div> &ensp;
        
          <button class ="ui tiny compact button next" id="tasks-next">
            <i class= "caret right icon"></i>
          </button>        
        </div>
      </div> 
    </div>
    <br>

    <h2>View/Manage Taskers
    <button class ="ui right floated tiny teal button edit-btn" id="taskers-edit"> Edit Tasker </button>
      <button class ="ui right floated tiny teal button delete-btn" id="taskers-delete"> – </button>          
      <button class ="ui right floated tiny teal button add-btn" id="taskers-add"> + </button>   
    </h2>
    <table class="ui fixed single line celled table" id="taskers">
      <thead>
        <th> Select </th>
        <th> Tasker Email </th>
        <th> First Name </th> <!-- thinking can concatenate first and last name-->
        <th> Last Name </th> <!-- thinking can concatenate first and last name-->
        <th> Password </th>          
        <th> Birthdate </th>          
        <th> Phone </th>
        <th> Credit Card </th>          
        <th> Card Sec # </th>
        <th> Card Expiry </th>
        <th> Street </th>
        <th> Unit </th>
        <th> Zipcode </th>
        <th> isAdmin </th>
        <th> isStaff </th>
      </thead>
      <tbody>
      </tbody>
    </table>

    <div class="ui container">
      <div align="center">
        <div class="ui input focus">            
          
          <button class ="ui tiny compact button prev" id="taskers-prev">
            <i class= "caret left icon"></i>
          </button> &ensp;
        
          <input type="text" value="1" type="number" class="page-no" id="taskers-page-no">
          &ensp; <div class="pagenum">of <span id="taskers-max-pages">50</span></div> &ensp;
        
          <button class ="ui tiny compact button next" id="taskers-next">
            <i class= "caret right icon"></i>
          </button>        
        </div>
      </div> 
    </div>
    <br>

    <h2>View/Manage Taskees
      <button class ="ui right floated tiny teal button edit-btn" id="taskees-edit"> Edit Taskee </button>
      <button class ="ui right floated tiny teal button delete-btn" id="taskees-delete"> – </button>          
      <button class ="ui right floated tiny teal button add-btn" id="taskees-add"> + </button>   
    </h2> 

    <table class="ui fixed single line celled table" id="taskees">
      <thead>
        <th> Select </th>
        <th> Taskee Email </th>
        <th> First Name </th>
        <th> Last Name </th>
        <th> Password </th>
        <th> Contact </th>
        <th> Credit Card </th>          
        <th> Card Sec # </th>
        <th> Card Expiry </th>
        <th> Zipcode </th>
        <th> isAdmin </th>
        <th> isStaff </th>          
      </thead>
      <tbody>
      </tbody>
    </table>        
    
    <div class="ui container">
      <div align="center">
        <div class="ui input focus">            
          
          <button class= "ui tiny compact button prev" id="taskees-prev">
            <i class= "caret left icon"></i>
          </button> &ensp;
        
          <input type="text" value="1" type="number" class="page-no" id="taskees-page-no">
          &ensp; <div class="pagenum">of <span id="taskees-max-pages">50</span></div> &ensp;
        
          <button class ="ui tiny compact button next" id="taskees-next">
            <i class= "caret right icon"></i>
          </button>        
        </div>
      </div> 
    </div>

    <br>

    <h2>View/Manage Skills
      <button class ="ui right floated tiny teal button edit-btn" id="skills-edit"> Edit Skill </button>
      <button class ="ui right floated tiny teal button delete-btn" id="skills-delete"> – </button>          
      <button class ="ui right floated tiny teal button add-btn" id="skills-add"> + </button>   
    </h2>
    <table class="ui fixed single line celled table" id="skills">
      <thead>
        <th> Select </th>
        <th> Skill Name </th>
        <th> Skill Details </th>          
      </thead>
      <tbody>
      </tbody>
    </table>        
    
    <div class="ui container">
      <div align="center">
        <div class="ui input focus">            
          
          <button class= "ui tiny compact button prev" id="skills-prev">
            <i class= "caret left icon"></i>
          </button> &ensp;
        
          <input type="text" value="1" type="number" class="page-no" id="skills-page-no">
          &ensp; <div class="pagenum">of <span id="skills-max-pages">50</span></div> &ensp;
        
          <button class ="ui tiny compact button next" id="skills-next">
            <i class= "caret right icon"></i>
          </button>        
        </div>
      </div> 
    </div>
    <br>

    <h2>View/Manage Bids
      <button class ="ui right floated tiny teal button delete-btn" id="bids-delete"> – </button>          
      <button class ="ui right floated tiny teal button add-btn" id="bids-add"> + </button>   
    </h2>      
    <table class="ui fixed single line celled table" id="bids">
      <thead>
        <th> Select </th>
        <th> Task Id </th>
        <th> Taskee Email </th>
        <th> Tasker Email </th>
        <th> Bid Status </th>
        <th> Bid Date and Time </th>          
      </thead>
      <tbody>
      </tbody>
    </table>        
    
    <div class="ui container">
      <div align="center">
        <div class="ui input focus">            
            
          <button class= "ui tiny compact button prev" id="bids-prev">
            <i class= "caret left icon"></i>
          </button> &ensp;
        
          <input type="text" value="1" type="number" class="page-no" id="bids-page-no">
          &ensp; <div class="pagenum">of <span id="bids-max-pages">50</span></div> &ensp;

          <button class ="ui tiny compact button next" id="bids-next">
            <i class= "caret right icon"></i>
          </button>        
        </div>
      </div> 
    </div>
    <br>
    </table>
  </div>
</div>

  <!-- Footer -->
  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">About</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Sitemap</a>
            <a href="#" class="item">Contact Us</a>
          </div>
        </div>
        <div class="three wide column">
          <h4 class="ui inverted header">Services</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">DNA FAQ</a>
            <a href="#" class="item">How To Access</a>
          </div>
        </div>
        <div class="seven wide column">
          <h4 class="ui inverted header">Footer Header</h4>
          <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
