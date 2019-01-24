<?php
	//This file is used to update the user's profile details if they decide to make changes

	//starts the session so that session variables can be utilized
	session_start();
	$userid=0;
	if(isset($_SESSION['userid'])){
		$userid=$_SESSION['userid'];
		//echo "Your session is running ". $_SESSION['userid'];
	}
	else{
		//redirect the user to the login screen if they have not yet logged in
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
	  
	    //Grabbing input values from the html form
	    $firstname=$_REQUEST['firstname'];
	    $lastname=$_REQUEST['lastname'];
	    $email=$_REQUEST['email'];
	    $phonenumber=$_REQUEST['phonenumber'];


	    $message='Registration was unsuccessful. ';
	    $Error=False;
	    $checkEmail=0; 
	    
	    //Validation on updating user details

	    //building a query to check if email address already exists
	    $sqlcheckEmail = "SELECT userid FROM user WHERE email='$email'";
		$stmtcheckEmail = $conn->query($sqlcheckEmail);

		//cycle through query result to determine next userid value
		while($rowcheckEmail=$stmtcheckEmail->fetch(PDO::FETCH_ASSOC)){
			$checkEmail=$rowcheckEmail['userid'];
		}

		if($checkEmail){
			$message=$message . 'This email address already has an account. ' ;
			$Error=True;
		}

		if($firstname=="" or $lastname=="" or $email=="" or $phonenumber=="" or $_REQUEST['password']==""){
			$message=$message . 'Data fields are not valid. ' ;
			$Error=True;
		}


		if($Error){
			//exit script and navigate back to registration page, error occured
		    echo "<script> alert('" . $message . "'); window.location.href='../register.html';
				</script>" ;
			
	        exit;
		}



	    //retrieve unique salt from the database for the email address given
	    $sqlGetSalt = "SELECT salt FROM user where email='$email'";
		$stmtRecSalt = $conn->query($sqlGetSalt);
		
		$salt=0;

		//cycle through query result to retrieve the salt this email
		while($rowSalt=$stmtRecSalt->fetch(PDO::FETCH_ASSOC)){
			$salt=$rowSalt['salt'];
		}

	    //take the user password provided, add the salt, hash the code using SHA 256 and use this to verify login 
	    $password=hash("SHA256",$salt . $_REQUEST['pass']);

	    //Build sql update query to update the user's profile data based on the form
	    $sql = "UPDATE user set fname='$firstname', lname='$lastname', email='$email', phone='$phonenumber', password='$password' where userid='$userid'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();


	    //close connection
	    $conn = null;

	    //exit script and navigate to booking page
	    echo "<script>
			alert('Your profile settings have been successfully changed');
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