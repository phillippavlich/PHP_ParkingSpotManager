<?php
  //This file replaces results.html
  //It is responsible for the results page but it needs to be a php file to access the php session variables to dynamically populate the data that is displayed on this page

  //start the session to access session variables
  session_start();
  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <link href="css/pageFormat.css" rel="stylesheet"/>
    <link href="css/formView.css" rel="stylesheet"/>
 
    <title>Parking App</title>
    
  </head>
  <body class="defaultSpacingRemoval">
    <header>
        <img src="./images/parkingLogo.svg" id="parkingLogo"/>
        <div id="details">
          <h1>Parking Manager</h1>
          <h3>Produced by: Phillip Pavlich 001414960</h3>
        </div> 
        <div class="logoutButton" onclick="location.href='./server-side/logout.php'">Logout</div>    
    </header>
    
    <div id="menuBar">
      <nav>
        <div id="nav" >
          <ul class="defaultSpacingRemoval">
            <li class="menuOptions">
              <a href="index.html">Home</a>
            </li>

            <li class="menuOptions">
              <a href="search.html">Search</a>
            </li>

            <li class="menuOptions">
              <a href="submission.html">Add a New Parking Spot</a>
            </li>

            <li class="menuOptions">
              <a href="./server-side/retrieveBookingData.php">Bookings</a>
            </li>

            <li class="menuOptions">
              <a href="./server-side/retrieveProfileData.php">Profile</a>
            </li>

            <li class="menuOptions">
              <a href="register.html">Register</a>
            </li>
            
          </ul>
        </div>
        <div id="selector">

          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>

        </div>
      </nav> 
    </div>

    <!-- This can only be implemented through the use of on click events with JavaScript-->
    <div id="contentOptions" class="clearboth">
      <ul>
        <li class="dropoptions">
          <a href="index.html">Home</a>
        </li>
        <li class="dropoptions">
          <a href="search.html">Search</a>
        </li>
        <li class="dropoptions">
          <a href="submission.html">Add a New Parking Spot</a>
        </li>
        <li class="dropoptions">
          <a href="./server-side/retrieveBookingData.php">Bookings</a>
        </li>
        <li class="dropoptions"> 
          <a href="./server-side/retrieveProfileData.php">Profile</a>
        </li>
        <li class="dropoptions">
          <a href="register.html">Register</a>
        </li>
      <ul>
    </div>

    <div id="resultsBox">
      <h2>Results: </h2>
      <h4>Choose one of the results below to make a booking. </h4>
    </div>

    <div id="map"></div>

    <table id="tableResults">
      <tr>
        <th></th>
        <th>Parking Address</th>
        <th>Distance</th> 
        <th>Price</th>
        <th>Rating</th>
      </tr>
      <?php
        //use a while loop to dynamically populate all the results that matched the search criteria
        //in between the while loop is html table rows that get repeated for each record
        $i=0;
        while($i<count($_SESSION['resultsDetails'])){
      ?>

      <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
      <tr>
        <td>
          <Button onclick="location.href = './server-side/retrieveParkingData.php?address='+this.parentElement.parentElement.children[1].innerHTML+'&distance='+ this.parentElement.parentElement.children[2].innerHTML">View</Button>
        </td>
        <td><?php echo $_SESSION['resultsDetails'][$i]['address'] . ', ' . $_SESSION['resultsDetails'][$i]['city'] . ', ' . $_SESSION['resultsDetails'][$i]['province']; ?></td>
        <td><?php echo $_SESSION['resultsDetails'][$i]['distance']; ?> km</td> 
        <td>$<?php echo $_SESSION['resultsDetails'][$i]['price']; ?></td>
        <td><?php echo $_SESSION['resultsDetails'][$i]['score']; ?></td>
      </tr>
      

      <?php
          $i=$i+1;
        }
      ?>
    </table>

    <footer>
      <p>Copyright @ 2018 Phillip Pavlich CAN. All rights reserved. @Parking Logo Icon was made by 
        <a target="_blank" href="https://www.flaticon.com/authors/freepik">freepik</a>
        from 
        <a target="_blank" href="https://www.flaticon.com">www.flaticon.com</a>
        .
      </p>
    </footer>
    <script type="text/javascript">
      var latitudes = new Array();
      var longitudes = new Array();
      var addresses = new Array();
      var prices = new Array();
      var distances = new Array();

      <?php for($i=0; $i<count($_SESSION['resultsDetails']);$i++){ ?>
          //labels.push('hello');
          addresses.push("<?php echo $_SESSION['resultsDetails'][$i]['address'] . ', ' . $_SESSION['resultsDetails'][$i]['city'] . ', ' . $_SESSION['resultsDetails'][$i]['province']; ?>");
          latitudes.push("<?php echo $_SESSION['resultsDetails'][$i]['latitude']; ?>");
          longitudes.push("<?php echo $_SESSION['resultsDetails'][$i]['longitude']; ?>");
          prices.push("<?php echo $_SESSION['resultsDetails'][$i]['price']; ?>");
          distances.push("<?php echo $_SESSION['resultsDetails'][$i]['distance']; ?>");
      <?php } ?>
      
  
    </script>
    <script type="text/javascript" src="js/NavBar.js"></script>
    <script type="text/javascript" src="js/addMaps.js"></script>
  </body>
</html>
