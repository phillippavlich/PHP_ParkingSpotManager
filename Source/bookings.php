<?php
  //This file replaces bookings.html
  //It is responsible for the bookings page but it needs to be a php file to access the php session variables to dynamically populate the data that is displayed on this page

  //start the session to access session variables
  session_start();
  $userid=0;
  if(isset($_SESSION['userid'])){
    $userid=$_SESSION['userid'];
    //echo "Your session is running ". $_SESSION['userid'];
  }
  else{
    //redirect the user to the login screen if they have not yet logged in
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

            <li class="menuOptions currentPage">
              Bookings
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
        <li class="dropoptions">Bookings</li>
        <li class="dropoptions"> 
          <a href="./server-side/retrieveProfileData.php">Profile</a>
        </li>
        <li class="dropoptions">
          <a href="register.html">Register</a>
        </li>
      <ul>
    </div>

    <h2>My Bookings</h2>

    <table id="tableBookings">
      <tr>
        <th></th>
        <th>Parking Address</th>
        <th>Owner</th> 
        <th>From Date</th>
        <th>To Date</th>
        <th>Price</th>
        <th>Status</th>
        <th>Review</th>
        
      </tr>
      <?php
        //use a while loop to dynamically populate all the bookings that the user has made 
        //in between the while loop is html table rows that get repeated for each record
        $i=0;
        while($i<count($_SESSION['myBookings'])){
      ?>
      
      <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
      <tr>
        <td>
          <?php if($_SESSION['myBookings'][$i]['status']!="Rejected"){ ?>
          <Button class="removeButton" onclick="location.href = './server-side/changeBookingStatus.php?book=true&cancel=true&address='+this.parentElement.parentElement.children[1].children[0].innerHTML+'&fromdate='+ this.parentElement.parentElement.children[3].innerHTML">X</Button>
          <?php };?>
        </td>
        <td>
          <Button onclick="location.href = './server-side/retrieveParkingData.php?address='+this.parentElement.children[0].innerHTML"><?php echo $_SESSION['myBookings'][$i]['address'] . ", " . $_SESSION['myBookings'][$i]['city'] . ", " . $_SESSION['myBookings'][$i]['province']; ?></Button>    
        </td>
        <td><?php echo $_SESSION['myBookings'][$i]['fname'] . " " . $_SESSION['myBookings'][$i]['lname']; ?></td> 
        <td><?php echo $_SESSION['myBookings'][$i]['fromdate']; ?></td>
        <td><?php echo $_SESSION['myBookings'][$i]['todate']; ?></td>
        <td>$<?php echo $_SESSION['myBookings'][$i]['cost']; ?></td>
        <td><?php echo $_SESSION['myBookings'][$i]['status']; ?></td>
        <td>
          <Button class="reviewButton" onclick="location.href = './submitReview.html?address='+this.parentElement.parentElement.children[1].children[0].innerHTML+'&owner='+this.parentElement.parentElement.children[2].innerHTML">Leave a Review</Button>
        </td>
      </tr>

      <?php
          $i=$i+1;
        }
      ?>
      
    </table>

    <h2>Requests on my Parking Spots</h2>
    <table id="tableRequests">
      <tr>
        <th></th>
        <th>Parking Address</th>
        <th>Driver</th> 
        <th>From Date</th>
        <th>To Date</th>
        <th>Price</th>
        <th>Status</th>
        
      </tr>

      <?php
        //use a while loop to dynamically populate all the bookings that the user has on their own parking spots 
        //in between the while loop is html table rows that get repeated for each record
        $j=0;
        while($j<count($_SESSION['mySpots'])){
      ?>

      <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
      <tr>
        <td>
          <?php if($_SESSION['mySpots'][$j]['status']=="Undecided"){ ?>
          <Button class="removeButton" onclick="location.href = './server-side/changeBookingStatus.php?book=false&cancel=true&address='+this.parentElement.parentElement.children[1].children[0].innerHTML+'&fromdate='+ this.parentElement.parentElement.children[3].innerHTML">X</Button>
          <Button class="addButton" onclick="location.href = './server-side/changeBookingStatus.php?book=false&cancel=false&address='+this.parentElement.parentElement.children[1].children[0].innerHTML+'&fromdate='+ this.parentElement.parentElement.children[3].innerHTML">&#10004;</Button>
          <?php };?>
          
        </td>
        <td>
          <Button onclick="location.href = './server-side/retrieveParkingData.php?address='+this.parentElement.children[0].innerHTML"><?php echo $_SESSION['mySpots'][$j]['address'] . ", " . $_SESSION['mySpots'][$j]['city'] . ", " . $_SESSION['mySpots'][$j]['province']; ?></Button>
        </td>
        <td><?php echo $_SESSION['mySpots'][$j]['fname'] . " " . $_SESSION['mySpots'][$j]['lname']; ?></td> 
        <td><?php echo $_SESSION['mySpots'][$j]['fromdate']; ?></td>
        <td><?php echo $_SESSION['mySpots'][$j]['todate']; ?></td>
        <td>$<?php echo $_SESSION['mySpots'][$j]['cost']; ?></td>
        <td><?php echo $_SESSION['mySpots'][$j]['status']; ?></td>
      </tr>

      <?php
          $j=$j+1;
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
    <script type="text/javascript" src="js/NavBar.js"></script>
    
  </body>
</html>
