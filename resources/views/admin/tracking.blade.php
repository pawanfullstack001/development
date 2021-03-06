<!DOCTYPE html>
<html lang="en">
<head>
		<title>Calculate Distance Between Two Locations(points) Using google maps javascript</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&sensor=false&libraries=places" type="text/javascript"></script>
</head>
<body>
 
 
<div class="container">
  <h2>Calculate Distance between two points google maps javascript</h2>
 
  <form class="form-inline" action="">
    <div class="form-group col-md-6">
      <label for="email">Source:</label>
      <input type="text" class="form-control" id="source" value="Jaipur, India" >
    </div>
    <div class="form-group col-md-6">
      <label for="pwd">Destination:</label>
      <input type="text" class="form-control" id="destination" value="Mumbai, India" >
    </div>
	</br>&nbsp;</br>
	<div class="form-group col-md-6">
 
    <button type="button" value="Get Route" onclick="get_rout()" >Get Rout & Distance</button>
    </div>
    <div class="form-group col-md-6">
      <label for="pwd">Distance in km :</label>
      <input type="text" class="form-control distance" readonly >
    </div>
  </form>
 
  <div class="row" >
  <br /><br />
   <div class='col-md-6' id='panallocation' style="width: 500px; height: 500px" ></div>
  <div class='col-md-6' id='maplocation' style="height: 450px;" ></div>
 
  </div>
</div>
 
<script type="text/javascript">
	var source, destination;
	var darection = new google.maps.DirectionsRenderer;
	var directionsService = new google.maps.DirectionsService;
	google.maps.event.addDomListener(window, 'load', function () {
		new google.maps.places.SearchBox(document.getElementById('source'));
		new google.maps.places.SearchBox(document.getElementById('destination'));
 
	});
 
	function get_rout() {
		var mapOptions = {
			mapTypeControl: false,
			center: {lat: -100.8688, lng: 151.2195},
			zoom: 20
		};
 
		map = new google.maps.Map(document.getElementById('maplocation'), mapOptions);
		darection.setMap(map);
		darection.setPanel(document.getElementById('panallocation'));
 
 
		source = document.getElementById("source").value;
		destination = document.getElementById("destination").value;
 
		var request = {
			origin: source,
			destination: destination,
			travelMode: google.maps.TravelMode.DRIVING
		};
		directionsService.route(request, function (response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				darection.setDirections(response);
			}
		});
 
		var service = new google.maps.DistanceMatrixService();
		service.getDistanceMatrix({
			origins: [source],
			destinations: [destination],
			travelMode: google.maps.TravelMode.DRIVING,
			unitSystem: google.maps.UnitSystem.METRIC,
			avoidHighways: false,
			avoidTolls: false
		}, function (response, status) {
			if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
				var distance = response.rows[0].elements[0].distance.text;
				var duration = response.rows[0].elements[0].duration.text;
 
				distancefinel = distance.split(" ");
				$('.distance').val(distancefinel[0]+"."+duration);
 
 
 
			} else {
				alert("Unable to find the distance between selected locations");
			}
		});
	}
</script>
</body>
</html>