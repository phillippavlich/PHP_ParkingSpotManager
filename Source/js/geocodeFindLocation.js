//create a script to access google APIs with my api key
const script = document.createElement('script');
script.src ='https://maps.googleapis.com/maps/api/js?key=AIzaSyDnD9SY3P7Q3O_wXzL7n_x8tvaT7jX92QE';
document.body.appendChild(script);

//grabbing text spot to post longitude and latitude values
var text = document.getElementById("locationData");
var latitudeInput=document.getElementById("latitudeHidden");
var longitudeInput=document.getElementById("longitudeHidden");

//use geocoder api to search for location
function searchForLocation() {
   var geocoder = new google.maps.Geocoder();
   //event listener on click
    document.getElementById('verifyLocation').addEventListener('click', function() {
      geocodeAddress(geocoder);
    });
}

//this function combines user inputs such as address, city, province and country to find the longitude and latitude
function geocodeAddress(geocoder) {
  var address = document.getElementById('addressBox').value;
  var city = document.getElementById('cityBox').value;
  var province = document.getElementById('provinceBox').value;
  var country = document.getElementById('countryBox').value;
  var totalAddress=address+", "+city+ ", "+province+", "+country;
  
  //google geocode api call
  geocoder.geocode({'address': totalAddress}, function(results, status) {
    if (status === 'OK') {
      text.innerHTML="<h4>Your Location is:</h4> "+"Latitude: " + results[0].geometry.location.lat() + 
    "<br>Longitude: " + results[0].geometry.location.lng();
      latitudeInput.value=results[0].geometry.location.lat();
      longitudeInput.value=results[0].geometry.location.lng();

    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

//once google script loads call function to use geocoder google api
script.onload=()=>{
  //search for latitude and longitude using address info
  searchForLocation();
  

}

