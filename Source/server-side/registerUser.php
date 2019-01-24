<?php
	//This file is used to register a user so that they have a valid account that they can login to

	//declare the database connection variables for proper connection to mysql
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

	    $message='Update profile was unsuccessful. ';
	    $Error=False;
	    $checkEmail=0;


	    //Validation on registering user 

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


	    //generate unique random salt for each user, this is randomly built, function at bottom of page
	    $salt = randString(20);
	  
	  	//build the password using user password with the salt and then hashed
	    $hashed=hash("SHA256",$salt . $_REQUEST['password']);

	    //building a query to obtain the highest userid currently used
	    $sqlGetMaxID = "SELECT MAX(userid) as 'userid' FROM user";
		$stmtRec = $conn->query($sqlGetMaxID);
		
		$maxid=0;
		$newid=0;

		//cycle through query result to determine next userid value
		while($row=$stmtRec->fetch(PDO::FETCH_ASSOC)){
			$maxid=$row['userid'];
			$newid=$maxid+1;
		}
		
	    //Build sql insert query to add users to the database based on their form values, salt is also saved
	    $sql = "INSERT INTO user(userid,fname, lname, email, phone, password, salt) VALUES('$newid','$firstname','$lastname','$email','$phonenumber','$hashed', '$salt')";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

	    //close connection
	    $conn = null;

	    //exit script and navigate to login html page
	    echo "<script>
			alert('You have successfully registered an account');
			window.location.href='../index.html';
			</script>";
        exit;
    }
    //exception in case connection to database fails
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

    //this is a function that randomly generates a string using the alphabet and numeric values
    function randString($length) {
	    $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $char = str_shuffle($char);
	    for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
	        $rand .= $char{mt_rand(0, $l)};
	    }
	    return $rand;
	}

?>