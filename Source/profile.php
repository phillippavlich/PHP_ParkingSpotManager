<?php
  //This file replaces profile.html
  //It is responsible for the profile page but it needs to be a php file to access the php session variables to dynamically populate the data that is displayed on this page

  //start the session to access session variables
  session_start();
  $userid=0;
  if(isset($_SESSION['userid'])){
    $userid=$_SESSION['userid'];
    $_SESSION['whichSelected']=0;
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

            <li class="menuOptions currentPage">
              Profile
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
        <li class="dropoptions">Profile</li>
        <li class="dropoptions">
          <a href="register.html">Register</a>
        </li>
      <ul>
    </div>

    <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
    <h2>My Profile</h2>
    <div class="newActBox">
      <h2 class="centerText">Edit Account Info</h2>
      
      <form  id="formNewAct" onsubmit="return signUpValidation()" action="./server-side/updateProfile.php">

        <input class="nameInput floatLeft" type="text" name="firstname" placeholder="First Name" value=
        <?php echo $_SESSION['myProfile']['fname']; ?>
        >
        </input>

        <input class="nameInput floatRight" type="text" name="lastname" placeholder="Last Name" value=
        <?php echo $_SESSION['myProfile']['lname']; ?>
        >
        </input> <br>

        <input class="fullBox" type="text" name="email" placeholder="Email" value=<?php echo $_SESSION['myProfile']['email']; ?>
        >
        </input> <br>

        <input class="fullBox" type="text" name="phonenumber" placeholder="Mobile Number" value=
        <?php echo $_SESSION['myProfile']['phone']; ?>
        >
        </input> <br>

        <input class="fullBox" type="password" name="password" placeholder="New Password"></input> <br>

        <input class="fullBox" type="submit" value="Save">

      </form>
    </div>

    <!-- Use PHP to insert data through the use of php tags which access php session variables previously populated in a php retrieving from database file -->
    <h2>My Parking Spots</h2>

    <div class="newActBox">
      <h4 class="centerText">Please select an address to update: </h4>

      <form  id="formNewAct1" action="./server-side/updateSpots.php"> 
        <?php 
        echo '<input class="hiddenInput" type="text" name="address" id="currentAddress" value="' . $_SESSION['myModifiedSpots']['0']['address'] .', ' . $_SESSION['myModifiedSpots']['0']['city'] . ', ' . $_SESSION['myModifiedSpots']['0']['province'] . '">';
        ?>
        </input>

        <h4>Address:</h4>
        <select name="allAdresses" id="selectedAddress" 
        onchange="var currentAdd=document.getElementById('currentAddress');
          var optionValue=document.getElementById('selectedAddress').value;
          currentAdd.value=optionValue;"
        value="Please select an address">
          <?php
            $i=0;
            while($i<count($_SESSION['myModifiedSpots'])){
          ?>
          <option value="
          <?php echo $_SESSION['myModifiedSpots'][$i]['address'] . ', ' . $_SESSION['myModifiedSpots'][$i]['city'] . ', ' . $_SESSION['myModifiedSpots'][$i]['province']; ?>"
          >
          <?php echo $_SESSION['myModifiedSpots'][$i]['address'] . ", " . $_SESSION['myModifiedSpots'][$i]['city'] . ", " . $_SESSION['myModifiedSpots'][$i]['province']; ?>
          </option>

          <?php
            $i=$i+1;
          }
      ?>
          
        </select>        

        <h4>When is it available?</h4>
        <input class="fullBox" type="date" name="dayAvailable" ></input> <br>

        <input class="nameInput floatLeft" type="currency" name="price" placeholder="Price"
        >
        </input>


        <input class="checkbox" type="checkbox" name="overnight">Available Overnight</input>
        
        <h4 id="uploadFile">Upload a video file: </h4>
        <input type="file" name="vid" accept="video/*">

        <h4 id="uploadFile2">Upload an image: </h4>
        <input type="file" name="pic" accept="image/*">

        <input class="fullBox" type="text" name="desc" placeholder="Description"></input> 

        <input class="fullBox" type="submit" value="Update Parking Spot">

      </form>
       
      
    </div>
    
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