<?php
	//This is the php code that gets executed when the user attempts to login to the website.

	//database connection variables are created
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

	    //Grabbing input email from the html form
	    $email=$_REQUEST['email'];

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

	    //use salted and hashed password in combination with the email address to determine if the user successfully logged in
	    $sqlGetUserID = "SELECT userid FROM user where email='$email' and password='$password'";
		$stmtRec = $conn->query($sqlGetUserID);
		
		$userid=0;

		//if this pulls a record, then the user successfully logged in. If they didn't the userid variable will still be 0
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$userid=$row['userid'];
		}

		//check to see if login was successful with message 
		if($userid==0){
			echo "The username and/or password are incorrect";
			echo "<script>
				alert('The username and/or password are incorrect');
				window.location.href='../index.html';
				</script>";
		}
		else{
			session_start();
			$_SESSION['userid']=$userid;
			echo "<script>
				alert('You have successfully logged in');
				window.location.href='./retrieveBookingData.php';
				</script>";
		}

	    //close connection
	    $conn = null;

        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

?>