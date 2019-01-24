<?php
  //This file replaces parking.html
  //It is responsible for the parkings page but it needs to be a php file to access the php session variables to dynamically populate the data that is displayed on this page

  //start the session to access session variables
  session_start();
  $userid=0;
  if(isset($_SESSION['userid'])){
    $userid=$_SESSION['userid'];
    //echo "Your session is running ". $_SESSION['userid'];
  }
  else{
    //redirect the user to the login page if they have not yet logged in
    echo "<script>
      alert('You have not yet logged into the system');
      window.location.href='../index.html';
      </script>";
  }
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

    <div class="hiddenInput" id="hiddenLongitude"></div>
    <div class="hiddenInput" id="hiddenLatitude"></div>


    <div id="parkingBox">
      <h2 id="currentAddress"><?php echo $_SESSION['parkingDetails']['address'] . ', ' . $_SESSION['parkingDetails']['city'] . ', ' . $_SESSION['parkingDetails']['province']; ?></h2>
      <h4 id="currentOwner">Parking Spot Owner: <?php echo $_SESSION['parkingDetails']['fname'] . ' ' . $_SESSION['parkingDetails']['lname']; ?></h4>
    </div>

    <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
    <table id="verticalTable">
      <tr>
        <th>Distance:</th>
        <td><?php echo $_SESSION['parkingDetails']['distance']; ?></td>
      </tr>
      <tr>
        <th>Price:</th>
        <td id="currentCost">$<?php echo $_SESSION['parkingDetails']['price']; ?></td>
      </tr>
      <tr>
        <th>Rating:</th>
        <td><?php echo round($_SESSION['parkingRating'],1); ?></td>
      </tr>
      <tr>
        <th>Available as of:</th>
        <td><?php echo $_SESSION['parkingDetails']['dateavailable']; ?></td>
      </tr>
      <tr>
        <th>Available Overnight:</th>
        <td>
          <?php
          if($_SESSION['parkingDetails']['availableovernight']){ 
            echo "Yes"; 
          }
          else{
            echo "No"; 
          }
          ?>
        </td>
      </tr>
    </table>

    <div id='parkingGrid'></div>
    
    <div id="furtherDetails">
      <h4 class="sameLine">Description:</h4>
      <p><?php echo $_SESSION['parkingDetails']['description']; ?></p>

      <Button class="returnButton" onclick="location.href='results.php'">Return to previous search</Button>

      <Button class="acceptButton" onclick="location.href = './makeBooking.html?address='+document.getElementById('currentAddress').innerHTML+'&cost='+ document.getElementById('currentCost').innerHTML.slice(1)">Book this parking spot!</Button>

    </div>


    <div id="sampleVid">
      <h4 >Video of Parking Spot: </h4>
      
      
      <video id="vidSource" controls>
        <source src="./videos/ParkingSpaceVideo.mp4" type="video/mp4">
        <source src="./videos/ParkingSpaceVideo.ogg" type="video/ogg">  
        Your browser does not support the video tag.
      </video>
    
    </div>

    <div id="reviews">
      <h4>Reviews:</h4>

      <table id="tableReviews">
      <tr>
        <th>Review Given By</th>
        <th>Rating (/10)</th>
        <th>Comment</th> 
        
      </tr>
      <?php
        //use a while loop to dynamically populate all the ratings that are on the parking spot
        //in between the while loop is html table rows that get repeated for each record
        $j=0;
        while($j<count($_SESSION['parkingReviews'])){
      ?>

      <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
      <tr>
        <td><?php echo $_SESSION['parkingReviews'][$j]['fname'] . ' ' . $_SESSION['parkingReviews'][$j]['lname']; ?></td>
        <td><?php echo $_SESSION['parkingReviews'][$j]['score']; ?></td>
        <td><?php echo $_SESSION['parkingReviews'][$j]['comment']; ?></td> 
      </tr>

      <?php
          $j=$j+1;
        }
      ?>
      
    </table>
    </div>
    
    
    <footer>
      <p>Copyright @ 2018 Phillip Pavlich CAN. All rights reserved. @Parking Logo Icon was made by 
        <a target="_blank" href="https://www.flaticon.com/authors/freepik">freepik</a>
        from 
        <a target="_blank" href="https://www.flaticon.com">www.flaticon.com</a>
        .
      </p>
    </footer>
    <script type="text/javascript">
      document.getElementById("hiddenLatitude").innerHTML=<?php echo $_SESSION['parkingDetails']['latitude']; ?>;
      document.getElementById("hiddenLongitude").innerHTML=<?php echo $_SESSION['parkingDetails']['longitude']; ?>;
    </script>
    <script type="text/javascript" src="js/NavBar.js"></script>
    <script type="text/javascript" src="js/addSingleMap.js"></script>
  </body>
</html>
