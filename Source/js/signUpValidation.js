//global error message
var errorMessage;
//This function is called when the register.html form has an onSubmit event
//This function is used to validate the data that is being used to create an account 
function signUpValidation(){
	//extracting values from the form to validate the values given by the user
	var fname = document.forms["registerForm"]["firstname"].value;
	var lname = document.forms["registerForm"]["lastname"].value;
	var email = document.forms["registerForm"]["email"].value;
	var phonenumber = document.forms["registerForm"]["phonenumber"].value;
	var password = document.forms["registerForm"]["password"].value;
	
	var validSubmission=true;
	errorMessage="Invalid Submission! ";

	if(!checkLength("First Name", fname )){
		validSubmission=false;
		
	}

	if(!checkLength("Last Name", lname )){
		validSubmission=false;
	}

	if(!checkLength("Email", email )){
		validSubmission=false;
	}

	if(!checkLength("Phone Number", phonenumber )){
		validSubmission=false;
	}

	if(!checkLength("Password", password )){
		validSubmission=false;
	}

	if(!verifyEmail(email)){
		validSubmission=false;
	}

	if(!verifyPhone(phonenumber)){
		validSubmission=false;
	}

	if(!validSubmission){
		alert(errorMessage);
		document.forms["registerForm"].action ="index.html"
		return 0;
	}
	//redirect action for form if account was created successfully, return to main menu
	//document.forms["registerForm"].action ="index.html"
	return 1;
}

//verifies the length of earch field is greater than 0
function checkLength(field, valueGiven){

	if(valueGiven.length===0){
		errorMessage=errorMessage+"The field "+field+ " is blank. ";
		return false;
	}
	else{
		return true;
	}
}

//verifies the format of the email
function verifyEmail(valueGiven){
	if(valueGiven.indexOf(".", 0) < 0 || valueGiven.indexOf("@", 0) < 0){
		errorMessage=errorMessage+"The field Email must have an \"@\" and \".\" symbol. ";
		return false;
	}
	return true;
}

//verify the format of the phone number
function verifyPhone(valueGiven){
	var isphone = /^(1\s|1|)?((\(\d{3}\))|\d{3})(\-|\s)?(\d{3})(\-|\s)?(\d{4})$/.test(valueGiven);
	if(!isphone){
		errorMessage=errorMessage+"The field Phone Number must be in the form 555-555-5555,(555) 555-5555 or 1 555 555 5555. ";
	}
	return isphone;

}