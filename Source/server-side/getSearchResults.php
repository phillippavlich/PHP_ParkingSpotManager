<?php
	//This page is built to use the search results in order to extract all the parking spots that match the search criteria

	//start the php session so that session variables can be accessed
	session_start();
	
	//declaring database connection variables for connecting to mysql
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
	    $searchname=$_REQUEST['searchName'];
	    $distance=$_REQUEST['distance'];
	    $currentLongitude=$_REQUEST['longitude'];
	    $currentLatitude=$_REQUEST['latitude'];
	    $priceMin=$_REQUEST['priceMin'];
	    $priceMax=$_REQUEST['priceMax'];
	    $minRating=$_REQUEST['rating'];

	    //initialize arrays to handle all rows that are returned
	    $resultsWithID=array();
	    $resultsDistance=array();

	    //building a query to obtain the parking id and location of the spot
	    //this is used later for distance calculations
	    $sqldistance = "SELECT parkingid, latitude, longitude FROM parkingspot spot";
		$stmtdistance = $conn->query($sqldistance);
		
		//cycle through query result to determine all longitudes and latitudes for each parking spot
		while($rowdistance=$stmtdistance->fetch(PDO::FETCH_ASSOC)){
			//this checks to see if the distance from the user's location and the parking spot is smaller than or equal to the distance submitted in the search form
			if(distance($currentLatitude, $currentLongitude, $rowdistance['latitude'], $rowdistance['longitude'], "K")<=$distance){
				//if the parking spot matches the distance search criteria, save the details
				array_push($resultsWithID,$rowdistance['parkingid']);

				//save array with parking ids with distances
				$resultsDistance[]=array('parkingid' => $rowdistance['parkingid'], 'distance' => distance($currentLatitude, $currentLongitude, $rowdistance['latitude'], $rowdistance['longitude'], "K"));
			}
		}

	    $resultsFound=array();

	    //must format ids in comma sparated string values for sql IN query
	    $formatInIds="'" . join("','",$resultsWithID) . "'" ;

		//building a query to obtain all parking details for the parking spots that match all the search criteria including the distance
	    $sqlresults = "SELECT spot.parkingid as 'parkingid', address, city, province, price, latitude, longitude, avg(score) as 'score'  FROM parkingspot spot LEFT OUTER JOIN rating rat on rat.parkingid=spot.parkingid WHERE address LIKE '%$searchname%' and price between '$priceMin' and '$priceMax' and spot.parkingid IN ($formatInIds) group by spot.parkingid, address, city, province, price, latitude, longitude HAVING ifnull(avg(score),10) >= '$minRating'  ";
	    
		$stmtresults = $conn->query($sqlresults);

		//cycle through query result to save all parking details
		while($rowresults=$stmtresults->fetch(PDO::FETCH_ASSOC)){
			$resultsFound[]=$rowresults;
		}

		//This appends the distance from the user's current location to each parking spot to the array returned from the sql query
		$spotid=0;
		for($j=0; $j<count($resultsFound); $j++){
			$spotid=$resultsFound[$j]['parkingid'];
			for($r=0; $r< count($resultsDistance); $r++){
				if($resultsDistance[$r]['parkingid']==$spotid){
					$resultsFound[$j]['distance']=round($resultsDistance[$r]['distance'],2) ;
				}
			}
			
		}

		//save the results as a session variable array to be accessed later on
		$_SESSION['resultsDetails']=$resultsFound;

	    //close connection
	    $conn = null;

	    //exit script and navigate to results page while adding current user's latitude and longitude values
	    echo "<script> window.location.href='../results.php?longitude=";
	    echo $currentLongitude;
	    
		echo "&latitude=";
		echo $currentLatitude;
		echo "';
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