<?php
	//This file is used to obtain all data that would need to be displayed on the parking page. This page shows an individual parking spot will all its details and reviews.

	//start the php session to have access to session variables
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user back to the login page if the user has not yet logged in
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declare the database connection variables
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

	    //initializing variables to hold data that will be obtained through SQL queries
	    $parkingDetails=array();
	    $parkingReviews=array();
	    $parkingRating=0;
	    $parkingid=0;

	    //Grab URL parameters and save them in the php file for later use
	    $address=$_GET['address'];
	    $currentDistance=$_GET['distance'];

	   	//split the address details
	    $parkingSplit=explode(", ", $address);
	    

	    //building a query to obtain parking id of the spot that was selected
	    $sqlGetParkingID = "SELECT parkingid FROM parkingspot WHERE address='$parkingSplit[0]' and city='$parkingSplit[1]' and province='$parkingSplit[2]'";
		$stmtRecID = $conn->query($sqlGetParkingID);

		//cycle through query result to grab the parking id
		while($rowID=$stmtRecID->fetch(PDO::FETCH_ASSOC)){
			$parkingid=$rowID['parkingid'];
		}


	    //building a query to obtain all parking spot info that will need to be displayed on the parking page
	    $sqlGetParking = "SELECT address, city, province, fname, lname, price, dateavailable, availableovernight, description, imagefile, videofile, longitude, latitude   FROM parkingspot park INNER JOIN user on park.ownerid=userid WHERE park.parkingid='$parkingid'";
		$stmtRec = $conn->query($sqlGetParking);

		//cycle through query result to save the row of parking details
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$parkingDetails=$row;
			//append the distance to the array of details
			$parkingDetails['distance']=$currentDistance;
		}

		//save all parking details as a session variable
		$_SESSION['parkingDetails']=$parkingDetails;

		//building a query to obtain all ratings that have been placed on this parking spot
	    $sqlGetMyRatings = "SELECT fname, lname, score, comment FROM rating INNER JOIN user on renterid=userid WHERE parkingid='$parkingid'";
		$stmtRecRatings = $conn->query($sqlGetMyRatings);

		//cycle through query result to save all rows that are returned full of ratings
		while($rowRatings=$stmtRecRatings->fetch(PDO::FETCH_ASSOC)){
			$parkingReviews[]=$rowRatings;
		}

		//save the rating details as a session variable
		$_SESSION['parkingReviews']=$parkingReviews;

		//determine the average score of all ratings for this given parking spot
		$avgScore=0;
		for($j=0;$j<count($parkingReviews);$j++){
			$avgScore=$avgScore+$parkingReviews[$j]['score'];
		}
		$avgScore=$avgScore/count($parkingReviews);

		//save the average score as a session variable
		$_SESSION['parkingRating']=$avgScore;

	    //close connection
	    $conn = null;

	    //exit script and navigate to parking page
	    echo "<script>
			window.location.href='../parking.php';
			</script>";
        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

    //this is a function that takes in the latitude and longitude of two different locations and determines what is the distance between them.
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
	    return 0;
	  }
	  else {
	    $theta = $lon1 - $lon2;
	    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	    $dist = acos($dist);
	    $dist = rad2deg($dist);
	    $miles = $dist * 60 * 1.1515;
	    $unit = strtoupper($unit);

	    if ($unit == "K") {
	      return ($miles * 1.609344);
	    } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	      return $miles;
	    }
	  }
	}

?>