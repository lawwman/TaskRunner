<!DOCTYPE html>

<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Homepage - Semantic</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/form.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/input.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/checkbox.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/message.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">  

  <!-- Following 4 links are needed for auto-complete to work. Auto-complete uses jQuery-UI-->
  <link rel="stylesheet" type="text/css" href="assets/jquery-ui/jquery-ui.css">
  <script src="assets/jquery-3.3.1.min"></script>
  <script src="assets/jquery-ui/jquery.js"></script>
  <script src="assets/jquery-ui/jquery-ui.min.js"></script>

  <!-- list.js is a js file that stores a list of suggestions for the autocomplete function. Needed only if suggestions are from a local source-->
  <script src="list.js"></script>

  <style>
    .ui-autocomplete {
      max-height: 200px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }
  </style>

  <!-- Following script block shows how to get implement autocomplete with suggestions from DB-->
  <script>
  $(document).ready(function(){
    var suggestions;
    //used ajax to get values back from php file.
    $.ajax({ type: 'GET', url: "suggestSkill.php", success: function(data) {
      suggestions = JSON.parse(data);
      $('#suggestionFromDB').autocomplete({
        source: suggestions
      });
    }});
  });
  </script>

  <!-- Following script block shows how to get implement autocomplete with suggestions from a local js file-->
  <script>
    var availableTags = getList(); //getList() function is a function from "list.js". Returns an array of string
    var selectedOptions = []; //To store the selected options
    var count = 0;
    var id = "removeTag";
   $(document).ready(function() {
      $('#suggestionFromLocalSource').autocomplete({
        source: availableTags,

        //when an option is selected
        select: function(select, ui) {
          var label = ui.item.label;
          selectedOptions.push(label); //store selected function
          console.log(selectedOptions);
          count++; //increment count to give unique id to each tag!
          $('#tags').append('<div class="ui image label">'+ label + '<i id="' + id + count + '" class="delete icon removeTag"></i></div>');

          //function when option tag is clicked
          $("#" + id+ count).click(function() {
            var toRemove = $(this).parent().text(); //get option
            selectedOptions = selectedOptions.filter(function(item) {
              return item != toRemove;
            });
            console.log(selectedOptions);

            //finally, remove tag.
            $(this).parent().remove(); 
          });
        }
      });
      // do not submit form. Manually insert link.
    $('#localForm').on('submit', function(){
      event.preventDefault();
      if (selectedOptions.length === 0) {
        $('#autocompleteValidation').append('<div class="ui pointing red basic label"><p>Please select an option</p></div>');
      }
      return false;
      });
    });
  </script>

</head>

<body>


  <div class="ui middle aligned center aligned grid inverted">
    <div class="six wide column">

      <!--Input HTML for suggestions from a database-->
      <div class="ui-widget">
        <form>
          <label >From Database: </label>
          <input id="suggestionFromDB">
        </form>
      </div>

      <br>

      <!-- Input HTML for suggestions from a local source-->
      <div class="ui-widget">
        <form id="localForm">
          <label >Local Source: </label>
          <input id="suggestionFromLocalSource">
        </form>

        <!--Tags for skills selected--> 
        <div id="tags"></div>

        <!--validation for autocorrect-->
        <div id="autocompleteValidation"></div>
      </div>

    </div>
  </div>


</body>

</html>
