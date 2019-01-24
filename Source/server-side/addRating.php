<?php
	//This file is used to add ratings to a given parking spot. This lets the users know how previews renter's experiences went

	//session start so that session variables can be accessed
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login screen if the user has not yet logged in
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declare the database connection variables to connect to mysql
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
	    $owner=$_REQUEST['owner'];
	    $rating=$_REQUEST['rating'];
	    $comment=$_REQUEST['comment'];
		
		//splitting up address details
		$parkingSplit=explode(", ", $address);	    

	    //building a query to obtain the highest ratingid currently used
	    $sqlGetMaxID = "SELECT MAX(ratingid) as 'ratingid' FROM rating";
		$stmtRec = $conn->query($sqlGetMaxID);
		
		$maxid=0;
		$newid=0;

		//cycle through query result to determine next ratingid value
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$maxid=$row['ratingid'];
			$newid=$maxid+1;
		}

		//building a query to obtain the parkingid that the rating is referring to
	    $sqlparkingID = "SELECT parkingid FROM parkingspot WHERE address='$parkingSplit[0]' and city='$parkingSplit[1]' and province='$parkingSplit[2]'";
		$stmtRecparking = $conn->query($sqlparkingID);
		
		$parkingid=0;

		//cycle through query result to determine parkingid value for the rating
		while($rowParking=$stmtRecparking->fetch(PDO::FETCH_ASSOC)){
			$parkingid=$rowParking['parkingid'];
		}
		
	    //Build sql insert query to add a rating to the database based on their form values
	    $sql = "INSERT INTO rating(ratingid,parkingid, renterid, score, comment) VALUES('$newid','$parkingid','$userid','$rating','$comment')";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

	    //close connection
	    $conn = null;

	    //exit script and navigate to booking page
	    echo "<script>
			alert('You have successfully added a rating on this parking spot');
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