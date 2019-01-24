//create a script to access google APIs with my api key
const script = document.createElement('script');
script.src ='https://maps.googleapis.com/maps/api/js?key=AIzaSyDnD9SY3P7Q3O_wXzL7n_x8tvaT7jX92QE';
document.body.appendChild(script);

//how to add maps to the screen
function addMaps(latitude, longitude, addresses, latitudes, longitudes, prices, distances) {
    //position
    var spot = {
      lat: latitude, 
      lng: longitude
      
    };

    var map = new google.maps.Map(
    document.getElementById('map'), {zoom: 12, center: spot});
    var marker = new google.maps.Marker({position: spot, map: map, label: "Here"});
  
    var contentString = '<div>'+
    '<h1>This is your Current Location!</h1>'+
    '</div>';

    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });

    marker.addListener('click', function() {
      map.setZoom(14);
      map.setCenter(marker.getPosition());
      infowindow.open(map, marker);
    });

    for(i=0;i<addresses.length; i++){
      map= addMarker(map, addresses[i], latitudes[i], longitudes[i], prices[i], distances[i], i+1 );

    }

}

//This function add all individual markers for each result
function addMarker(map, address, latitude, longitude, price, distance, which){
  var label = {
    lat: Number(latitude), 
    lng: Number(longitude)
  };

  // The marker, positioned at each spot found for each search result
  var marker1 = new google.maps.Marker({position: label, map: map, label: address.split(", ")[0]});
  var string='./server-side/retrieveParkingData.php?address='+ address+'&distance='+distance+ ' km';
  
  //adding content strings for location pop ups
  var contentStringA = '<div>'+
    '<h1>Result '+which+'</h1>'+
    '<p>Parking spot available at: <Button onclick="location.href = \''+string+'\'">'+address+'</Button><br>Price is: $'+price+
    '.</p>'+
    '</div>';

  //adding info windows so that search result details can pop up upon click
  var infowindowA = new google.maps.InfoWindow({
    content: contentStringA
  });

  //add event listeners for clicking a search result
  marker1.addListener('click', function() {
    map.setZoom(14);
    map.setCenter(marker1.getPosition());
    infowindowA.open(map, marker1);
  });

  return map;

   
}

//get all the parameters in the url
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

//once google script loads call function to add maps
script.onload=()=>{
  var param=getUrlVars();
  
  //pass location variables given from search page
  addMaps(Number(param["latitude"]), Number(param["longitude"]), addresses, latitudes, longitudes, prices, distances);
  //addMaps(43.262243, -79.914622);

}

