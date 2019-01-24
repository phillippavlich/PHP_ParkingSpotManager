//create a script to access google APIs with my api key
const script = document.createElement('script');
script.src ='https://maps.googleapis.com/maps/api/js?key=AIzaSyDnD9SY3P7Q3O_wXzL7n_x8tvaT7jX92QE';
document.body.appendChild(script);

//Adds one single location to a map to show the user where the parking spot can be found
function addSingleMap(latitude, longitude) {
    //position
    var spot = {
      lat: latitude, 
      lng: longitude
    };

    // The map, centered at your searched parking spot
   
    var map = new google.maps.Map(
    document.getElementById('parkingGrid'), {zoom: 14, center: spot});
    var marker = new google.maps.Marker({position: spot, map: map, label: document.getElementById("currentAddress").innerHTML.split(", ")[0]});
  
    var contentString = '<div>'+
    '<h3>'+document.getElementById("currentAddress").innerHTML+'</h3>'+
    '<p>'+document.getElementById("currentOwner").innerHTML+', Price: '+document.getElementById("currentCost").innerHTML+'</p>'+
    '</div>';

    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });

    marker.addListener('click', function() {
      map.setZoom(14);
      map.setCenter(marker.getPosition());
      infowindow.open(map, marker);
    });
 
}

//once google script loads call function to add single map
script.onload=()=>{
  //pass location variables given for specific parking spot
  addSingleMap(Number(document.getElementById("hiddenLatitude").innerHTML), Number(document.getElementById("hiddenLongitude").innerHTML));
  //The above is hardcoded for 1 example detailed parking page

}

