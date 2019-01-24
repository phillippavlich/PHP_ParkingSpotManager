<?php
	//This php file is used to obtain all data from the backend that will be displayed on the bookings page

	//start the php session so that session variables can be used
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login page if they have not already logged in to the system.
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declare connection variables for database access
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

	    //Building empty arrays to save the data returned from sql queries
	    $myBookings=array();
	    $mySpots=array();
	    

	    //building a query to obtain the the booking data of the parking spot along with the owner details
	    //this is the used to determine all bookings made by the user as a renter
	    $sqlGetMyBookings = "SELECT book.*, address, city, province, fname, lname  FROM booking book INNER JOIN parkingspot park on book.parkingid=park.parkingid INNER JOIN user on book.ownerid=userid WHERE renterid=$userid";
		$stmtRec = $conn->query($sqlGetMyBookings);

		//cycle through query result to save the user's rented parking spots
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$myBookings[]=$row;
		}

		//building a query to obtain the the booking data of the parking spot along with the driver details
	    //this is the used to determine all bookings made by the user as an owner (other people booking his spots)
	    $sqlGetMySpots = "SELECT book.*, address, city, province, fname, lname  FROM booking book INNER JOIN parkingspot park on book.parkingid=park.parkingid INNER JOIN user on book.renterid=userid WHERE book.ownerid=$userid";
		$stmtRecSpots = $conn->query($sqlGetMySpots);

		//cycle through query result to save the user's parking spot bookings
		while($rowSpots=$stmtRecSpots->fetch(PDO::FETCH_ASSOC)){
			$mySpots[]=$rowSpots;
		}

		//save the booking details as session variables
		$_SESSION['myBookings']=$myBookings;
		$_SESSION['mySpots']=$mySpots;


	    //close connection
	    $conn = null;

	    //exit script and navigate to booking page
	    echo "<script>
			window.location.href='../bookings.php';
			</script>";
        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

?>