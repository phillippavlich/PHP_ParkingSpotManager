<?php
	//This sheet allows a user to add a booking on a given parking spot


	//starts php session so that session variabes can be shared
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//If the user is not logged in, send them back to the login page
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declaring connection variables for mysql
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

	    //Grabbing input values from the html form
	    $address=$_REQUEST['address'];
	    $cost=$_REQUEST['cost'];
	    $fromDate=$_REQUEST['fromDate'];
	    $toDate=$_REQUEST['toDate'];

	    //splitting address details
	    $parkingSplit=explode(", ", $address);
	    

	    //building a query to obtain the highest bookingid currently used
	    $sqlGetMaxID = "SELECT MAX(bookingid) as 'bookingid' FROM booking";
		$stmtRec = $conn->query($sqlGetMaxID);
		
		$maxid=0;
		$newid=0;

		//cycle through query result to determine next bookingid value
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$maxid=$row['bookingid'];
			$newid=$maxid+1;
		}


		//building a query to obtain the owner and the parking spot so that a booking can be made
	    $sqlparkingID = "SELECT parkingid, ownerid FROM parkingspot WHERE address='$parkingSplit[0]' and city='$parkingSplit[1]' and province='$parkingSplit[2]'";
		$stmtparking = $conn->query($sqlparkingID);
		
		$parkingid=0;
		$ownerid=0;

		//cycle through query result to save parkingid and the spot id
		while($rowParking=$stmtparking->fetch(PDO::FETCH_ASSOC)){
			$parkingid=$rowParking['parkingid'];
			$ownerid=$rowParking['ownerid'];
		}


		$message='Make booking was unsuccessful. ';
	    $Error=False;
	    $checkBook=0;


	    //Validation on adding a booking 

	    //Verify that the user is not making a booking on their own parking spot
	    if($ownerid==$userid ){
			$message=$message . 'You cannot make a booking on your own parking spot. ' ;
			$Error=True;
		}

		//verification that the to date is greater than the from date
		if($cost<=0 ){
			$message=$message . 'The to date must be greater than the from date. ' ;
			$Error=True;
		}


		if($Error){
			//exit script and navigate back to registration page, error occured
		    echo "<script> alert('" . $message . "'); window.location.href='./retrieveBookingData.php';
				</script>" ;
			
	        exit;
		}


		
	    //Build sql insert query to add the new booking to the database based on their form values
	    $sql = "INSERT INTO booking(bookingid, ownerid, renterid, parkingid, fromdate, todate, status, cost) VALUES('$newid','$ownerid', '$userid','$parkingid','$fromDate','$toDate','Undecided', '$cost')";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

	    //close connection
	    $conn = null;

	    //exit script and navigate to booking page
	    echo "<script>
			alert('You have successfully made a booking');
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