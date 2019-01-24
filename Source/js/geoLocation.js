var text = document.getElementById("locationData");
var latValue = document.getElementById("latVal");
var lonValue = document.getElementById("lonVal");

//attempt to use google api to receive current position
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
        
    } else {
        text.innerHTML = "Geolocation is not supported by this browser.";
    }
}

//display the longitude and the latitude of the user if given permission
function showPosition(position) {
	var spot= {lat: position.coords.latitude, lng: position.coords.longitude}
    text.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude; 

    //save user location if given so that I do not need to prompt the user for their location in results page
    latValue.value=position.coords.latitude;
    lonValue.value=position.coords.longitude;
      
}

//Error messages for the google maps api
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            text.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            text.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            text.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            text.innerHTML = "An unknown error occurred."
            break;
    }
}

getLocation();