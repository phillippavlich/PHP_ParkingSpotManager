<?php
	//This file is used to update the status of a booking by either a renter or a parking spot owner
	//this can only be done from the booking.php page

	//start the php session so that session variables can be accessed
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login page if they are not currently logged in.
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declaring the database connection variables
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
	  	
	  	//Grabbing the parameters from the URL which were passed from the previous page
	  	$book=$_GET['book'];
	  	$cancel=$_GET['cancel'];
	  	$address=$_GET['address'];
	  	$fromdate=$_GET['fromdate'];

	  	//split the address details
	  	$parkingSplit=explode(", ", $address);

	  	//Determining the new status of the booking based on the parameters given from the bookings.php page
	  	$message='Rejected';

	  	if($book=="true"){
	  		$message='Cancelled';
	  	}
	  	else if($cancel=="false"){
	  		$message='Approved';
	  	}

	  	//building a query to obtain the booking id that is being referred to
	    $sqlparkingID = "SELECT bookingid FROM parkingspot spot INNER JOIN booking book on book.parkingid=spot.parkingid WHERE address='$parkingSplit[0]' and city='$parkingSplit[1]' and province='$parkingSplit[2]' and book.fromdate='$fromdate'";
		$stmtparking = $conn->query($sqlparkingID);
		
		$bookingid=0;
		
		//cycle through query result to pull the booking id value
		while($rowParking=$stmtparking->fetch(PDO::FETCH_ASSOC)){
			$bookingid=$rowParking['bookingid'];
		}

	    //Build sql update query to update the status of a current booking based on the user's request.
	    $sql = "UPDATE booking set status='$message' where bookingid='$bookingid'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();


	    //close connection
	    $conn = null;



	    //exit script and navigate to bookings page
	    echo "<script>
			alert('The booking status has been updated');
			window.location.href='./retrieveBookingData.php';
			</script>";
        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

?>