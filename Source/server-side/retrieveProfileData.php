<?php
	//This php file is used to retrieve profile data and the user's current parking spots to display them on the profile page allowing the user to make modifications if they wish

	//start the session so that session variables can be used
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login screen if they have not already been logged in
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//save the database connection variables
	$servername = "localhost";
	$db="4HC3Parking";
	$username = "root";
	$password = "test123";

    try {
    	//Connect to the database located in ec2 amazon server
	    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
	    
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    //echo "Connected successfully"; 

	    //Initialize empty arrays to hold data returned by sql queries
	    $myProfile=array();
	    $mySpots=array();
	    

	    //building a query to obtain all profile information about the user
	    $sqlGetMyProfile = "SELECT * FROM user WHERE userid=$userid";
		$stmtRec = $conn->query($sqlGetMyProfile);

		//cycle through query result to save all profile details
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$myProfile=$row;
		}

		//building a query to obtain all parking spots that are owned by the user
	    $sqlGetMySpots = "SELECT * FROM parkingspot WHERE ownerid=$userid";
		$stmtRecSpots = $conn->query($sqlGetMySpots);

		//cycle through query result to save user's parking spots
		while($rowSpots=$stmtRecSpots->fetch(PDO::FETCH_ASSOC)){
			$mySpots[]=$rowSpots;
		}

		//save both profile and parking spot details as session variables
		$_SESSION['myProfile']=$myProfile;
		$_SESSION['myModifiedSpots']=$mySpots;

	    //close connection
	    $conn = null;

	    //exit script and navigate to profile page
	    echo "<script>
			window.location.href='../profile.php';
			</script>";
        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

?>