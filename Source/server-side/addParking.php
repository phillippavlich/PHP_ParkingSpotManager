<?php
	//This file add a parking spot to the database based on the form values provided by the user

	//start the php session so that session variables can be accessed.
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirects the used to the login page if they have not yet logged in
		echo "<script>
			alert('You have not yet logged into the system');
			window.location.href='../index.html';
			</script>";
	}

	//declaring database connection variables
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
	    $city=$_REQUEST['city'];
	    $province=$_REQUEST['province'];
	    $postalCode=$_REQUEST['postalCode'];
	    $country=$_REQUEST['country'];

	    $latitude=$_REQUEST['latitude'];
	    $longitude=$_REQUEST['longitude'];


	    $dayAvailable=$_REQUEST['dayAvailable'];
	    $price=$_REQUEST['price'];
	    $overnight=$_REQUEST['overnight'];
	    $vid=$_REQUEST['vid'];
	    $pic=$_REQUEST['pic'];

	    $desc=$_REQUEST['desc'];
	    
	    //checkbox, must convert to database value
	    if($overnight=='on'){
	    	$overnight=1;
	    }
	    else{
	    	$overnight=0;
	    }

	    
	    $message='Adding parking was unsuccessful. ';
	    $Error=False;
	    $checkParking=0;


	    //Validation on adding a new parking spot 

	    //building a query to check if email address already exists
	    $sqlcheckParking = "SELECT parkingid FROM parkingspot WHERE address='$address' and city='$city' and province='$province'";
		$stmtcheckParking = $conn->query($sqlcheckParking);

		//cycle through query result to determine next userid value
		while($rowcheckParking=$stmtcheckParking->fetch(PDO::FETCH_ASSOC)){
			$checkParking=$rowcheckParking['parkingid'];
		}

		if($checkParking){
			$message=$message . 'This address already exists. Cannot duplicate an address. ' ;
			$Error=True;
		}

		if($address=="" or $city=="" or $province=="" or $postalCode=="" or $country=="" or $price==""){
			$message=$message . 'Address, city, province, postal, country and price must all be provided. ' ;
			$Error=True;
		}

		//double check that longitude and latitude were obtained.
		if($longitude==0 or $latitude==0 ){
			$message=$message . 'You must press the verify location button to obtain a valid longitude and latitude. If you did, then your address was not valid and does not exist in the system. ' ;
			$Error=True;
		}


		if($Error){
			//exit script and navigate back to submission page, error occured
		    echo "<script> alert('" . $message . "'); window.location.href='../submission.html';
				</script>" ;
			
	        exit;
		}

	    //building a query to obtain the highest parkingid currently used
	    $sqlGetMaxID = "SELECT MAX(parkingid) as 'parkingid' FROM parkingspot";
		$stmtRec = $conn->query($sqlGetMaxID);
		
		$maxid=0;
		$newid=0;

		//cycle through query result to determine next parkingid value
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$maxid=$row['parkingid'];
			$newid=$maxid+1;
		}
		

	    //Build sql insert query to add a parking spot to the database based on their form values
	    $sql = "INSERT INTO parkingspot(parkingid,address, city, province, postalcode, country, latitude, longitude, dateavailable, price, availableovernight, videofile, imagefile, Description, ownerid ) VALUES('$newid','$address','$city','$province','$postalCode','$country' ,'$latitude','$longitude','$dayAvailable','$price','$overnight','$vid','$pic','$desc','$userid')";
		$stmt = $conn->prepare($sql);
		$stmt->execute();


		//This was an attempt to add images and videos to the database but I kept getting errors.

		//$target_path = "./" . $_FILES['pic']['name'];
		
		//print_r($_FILES);
		//echo $target_path;

		//$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/test.jpg";
		
		//if(move_uploaded_file($_FILES['pic']['tmp_name'], $target)){
			
			//echo "image uploaded successfully";
		//}
		//else{
			//echo "There was a problem uploading the image";
		//}





	    //close connection
	    $conn = null;

	    //exit script and navigate to login booking page
	    echo "<script>
			alert('You have successfully added a parking spot to your locations');
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