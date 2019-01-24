<?php
	//This file updates the user's parking spot details based on the data submitted in the form
	//please be aware: address, city, postal code, province, country, longitude and latitude values cannot be switched.
	//The data is not pre populated in the form but whatever values are put in the form for the selected parking spot will overwrite the details in the database for that parking spot.

	//start the session so that session variables can be used
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login page when they have not yet logged in
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declare database connection variables 
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
	    $dayAvailable=$_REQUEST['dayAvailable'];
	    $price=$_REQUEST['price'];
	    $overnight=$_REQUEST['overnight'];
	    $vid=$_REQUEST['vid'];
	    $pic=$_REQUEST['pic'];
	    $desc=$_REQUEST['desc'];

	    //translate the form value for overnight into a 1 or 0 for database
	    if($overnight=='on'){
	    	$overnight=1;
	    }
	    else{
	    	$overnight=0;
	    }

	    //split address details
	    $parkingSplit=explode(", ", $address);	
	    
	    //building a query to obtain the parking id of the selected address to be modified
	    $sqlparkingID = "SELECT parkingid FROM parkingspot WHERE address='$parkingSplit[0]' and city='$parkingSplit[1]' and province='$parkingSplit[2]'";
		$stmtRecparking = $conn->query($sqlparkingID);
		
		$parkingid=0;

		//cycle through query result to save the parkingid value
		while($rowParking=$stmtRecparking->fetch(PDO::FETCH_ASSOC)){
			$parkingid=$rowParking['parkingid'];
		}

	    //Build sql update query to overwrite the parking spot data in the database with the form values 
	    $sql = "UPDATE parkingspot set dateavailable='$dayAvailable', price='$price', availableovernight='$overnight', videofile='$vid', imagefile='$pic', description='$desc' where parkingid='$parkingid'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();


	    //close connection
	    $conn = null;

	    //exit script and navigate to the booking page
	    echo "<script>
			alert('Your have successfully changed your parking spot details');
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