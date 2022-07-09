@extends('admin.layout.main1')

@section('content')

<style>
#driver_details{
  display:none;
}

#vehicleimage{
  width: 170px;
  height: 100px;
}

.bold-text{
  font-weight: 600 !important;
}
.remove-class{
  position: absolute;
  right: 14px;
  top: 11px;
  font-size: 23px;
  z-index: 999;
}
.fixed-top{
  position: fixed;
  z-index: 999;
  width: 100%;
  padding: 20px 15px;
  background-color: #fff;
}
.pac-container {
    background-color: #fff;
    position: fixed!important;
    top: 80px !important;
    z-index: 99999999 !important;
}
.form-group {
  margin: 0;
}
.gutter-sm{
  margin: 0px -8px!important
}
.gutter-sm [class*="col-"]{
  padding: 0px 8px!important;
}
@media(max-width: 574px){
  .pac-container.pac-logo.hdpi{
    top: 145px!important;
  }
  .pac-container.pac-logo.hdpi + .pac-container.pac-logo.hdpi{
    top: 80px!important;
  }
}
</style>
<div class="profilePage"></div>
<div class="layout-content right-container" id="right-container">
  <div class="layout-content-body">
	<input type="hidden" name="pricebycountry" id="pricebycountry" value="0"/>
  <input type="hidden" name="selectcountry" id="selectcountry"/>
   
    <div class="card edit-profile-page m-0"> 
      <div class="card-body" style="padding: 20px 20px">
      <div class="fixed-top">
        <div class="row">
        
          <form autocomplete="on" id="searchfrm">
            <div class="col-12 col-sm-3 col-md-4 col-lg-4">
              <div class="form-group">
                <label class="control-label bold-text">Origen</label>
                <input type="text" id="txtSource"  class="form-control" autocomplete="off" />
              </div>
            </div>
            <div class="col-12 col-sm-3 col-md-4 col-lg-4">
              <div class="form-group">
                <label class="control-label bold-text">Nombre</label>
                <input type="text" id="nombre"  class="form-control" autocomplete="off" />
              </div>
            </div>
            <div class="col-12 col-sm-3 col-md-4 col-lg-4">
              <div class="form-group">
                <label class="control-label bold-text">Telefono</label>
                <input type="text" id="telefono"  class="form-control" autocomplete="off" />
              </div>
            </div>
            <div class="col-12 col-sm-3 col-md-4 col-lg-4">
              <div class="form-group">
                <label class="control-label bold-text">Destino</label>
                <input type="text" id="txtDestination"  class="form-control" autocomplete="off" />
              </div>
            </div>
            
            <div class="col-6 col-sm-3 col-md-2 col-lg-2">
              <div class="form-group">
                <label class="control-label">&nbsp;</label>
               <button onclick="GetRoute()" class="btn btn-info btn-block" >Get Route</button>
              </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2 col-lg-2">
              <div class="form-group">
                <label class="control-label">&nbsp;</label>
               <a href="https://www.asteriscotaxi.com/" class="btn btn-primary btn-block">Ayuda</a>
              </div>
            </div>
            
            
          </form>
        
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <!--  <div id="dvDistance">
              <div class="row">
                <div class="col-md-4 col-sm-4 col-12 col-lg-2" >
                  <div class="form-group mt-2 ml-2">
                    <label class="control-label"><strong>Distancia:</strong>  <span>838KM</span></label>               
                  </div>                              
                </div>
                <div class="col-md-4 col-sm-4 col-12 col-lg-2" >
                  <div class="form-group mt-2 ml-2">
                    <label class="control-label"><strong>Duración:</strong>  <span>838KM</span></label>               
                  </div>                              
                </div>
              
               </div>
              </div> -->
            </div>
           </div> 
          </div>
        <div class="row">
          <div class="col-md-12">
          <!-- <div id="dvMap" style="height:100vh;"></div>
          </div> -->

          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row mt-2" id="driver_details">
              <div class="col-12 col-sm-4 col-md-3 col-lg-4 mt-2">
                <p><span class="bold-text"> Chofer : </span><span id="driver_name"></span> &nbsp;<span class="bold-text"> Vehicle No. : </span><span id="driver_vehicle_type"></span></p>
                <p><span class="bold-text"> PAX : </span><span id="driver_passenger"></span> &nbsp;<span class="bold-text"> </span><img src=""id="driver_credit_card" style="width: 50px;
    height: auto;"></p>

              </div>
              <div class="col-6 col-sm-6 col-md-2 col-lg-2 mt-2">
                
                <img src="" alt="" id="profileimage" style="width: 130px;height: 80px;" title="">
              </div>

              <div class="col-6 col-sm-6 col-md-2 col-lg-2 mt-2">
                
                <img src="" alt="vehicle image" id="vehicleimage" style="width: 130px;height: 80px;" title="">
              </div>
              <div class="col-8 col-sm-8 col-md-3 col-lg-2 mt-2">
                <p><span class="bold-text"> Phone no : </span><a id="driver_phone_no" onclick="NotDetails()">xxxxxxxxxx</a>, <span class="bold-text"> Status : </span><span id="d-status"></span></p>
              </div>
              <div class="col-4 col-sm-4 col-md-2 col-lg-2 mt-2">
                <a id="telImg" onclick="NotDetails()"><img src="{{url('public/call3.png')}}" id="" style="width: 45px;" title="image"></a>
              </div>
              <a class="remove-class" onclick="removeDetails()"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></a>
            </div>
          </div>
          </div>
         </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&sensor=false&libraries=places"></script>
<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script> 
<script type="text/javascript">
var curLngd = 0;
var curLatd = 0;

var destLngd = 0;
var destLatd = 0;
if ("geolocation" in navigator){ //check geolocation available 
    //try to get user current location using getCurrentPosition() method
    navigator.geolocation.getCurrentPosition(function(position){ 
        curLng = position.coords.longitude;
        curLat = position.coords.latitude;
        initMap(curLat,curLng);
      });
}else{
    console.log("Browser doesn't support geolocation!");
  }
            var config = {
              apiKey: "AIzaSyAzeG2PmaXS8ak4yV6m_7OIkExaq3IV6lM",
              authDomain: "emergency-taxi.firebaseapp.com",
              databaseURL: "https://emergency-taxi.firebaseio.com",
              projectId: "emergency-taxi",
              storageBucket: "emergency-taxi.appspot.com",
              messagingSenderId: "868787528086",
              appId: "1:868787528086:web:ec952293dafe31344c3db7",
              measurementId: "G-64ML7CXFPL"
            };
            firebase.initializeApp(config);

    function removeDetails(){
      $("#driver_details").css('display','none');
    } 
    function NotDetails(){
      alert("Please enter drop location");
    }
    function driver_details(driverid){
     
      var $target = $('html,body'); 
            $target.animate({scrollTop: $target.height()}, 1000);

            $("#driver_details").css('display','flex');
            setTimeout(function(){
            $.ajax({
              url : "{{ url('admin/userdetails') }}/"+driverid,
              type:'get',
              dataType:'json',
              success:function(json){
                json = json.appuser;
                $("#driver_name").text(json.name);
                $("#driver_vehicle_type").text(json.vehicle_number);
                $("#driver_passenger").text(json.passengers);
               
                setTimeout(function(){
                  $("#profileimage").prop('src',"https://radar.taxi/public/files/"+json.profile_pic);
                  $("#vehicleimage").prop('src',"https://radar.taxi/public/files/"+json.vehicle_image);
                  if (json.accept_credit_card) {
                    $("#driver_credit_card").prop('src',"{{url('/public/files/card.png')}}");
                  }
                  },1000);  
                  if(json.subscribed==1 && json.available_status==1 && json.status==0){
                  $("#driver_phone_no").text(json.country_code+json.mobile_no);
                  $("#driver_phone_no").attr('href',"tel:"+json.country_code+json.mobile_no);
                  $("#telImg").attr('href',"tel:"+json.country_code+json.mobile_no);
                  // $("#telImg").html(`<img src="{{url('public/call3.png')}}" style="width: 45px;" title="image">`);
                  $("#d-status").html("Available").removeClass("text-danger").addClass('text-success');
                  }
                           
                else
                {
                  $("#driver_phone_no").text("xxxxxxxxxx");
                  // $("#driver_phone_no").attr('href',"");
                  // $("#telImg").attr('href',"");
                  $("#d-status").html("Busy").removeClass("text-success").addClass('text-danger');
                }
          
                $("#profileimage").attr('src',"public/files/"+json.profile_pic);
                $("#vehicleimage").attr('src',"public/files/"+json.vehicle_image);

              }
            });
          },3000);  
    }
    $(document).ready(function(){
      
      initialize1();
      initialize();
    });
     var price = 0;
     var first_distance_meter = 0;
     var first_fixed_price = 0;
     var next_price = 0;
    
    function getPrice(distance,country){
      var d;
      var distance = distance.replace('km','');
       $.ajax({
        url : "{{url('admin/get_price')}}",
        data:{"country":country,"distance":distance, "_token": "{{ csrf_token() }}"},
        type : "POST",
        success:function(data){
          console.log(data);
          
          $("#pricebycountry").val(data);
          //d=data;
         }
      });
      
    }

   function calPrice_BKP(distance){
    var distance = distance.replace('km','');
    // var floor = Math.floor(distance);
    // var d =  floor * price + (((distance - floor) * 1000) * price)/1000;
    var amount = ((distance*1000)-200)/200;
    var nextamount = Math.round(amount)*150;
    var d = 300 + nextamount;
   // console.log(Math.round(amount));
    return d;
  }

  function calPrice(distance){
    var distance = distance.replace('km','');
    // var floor = Math.floor(distance);
    // var d =  floor * price + (((distance - floor) * 1000) * price)/1000;
    var amount = ((distance*1000)-200)/200;
    var nextamount = Math.round(amount)*150;
    var d = 300 + nextamount;
   // console.log(Math.round(amount));
    return d;
  }

    function initialize() {
        var input = document.getElementById('txtSource');
        //debugger;
        var options = {
          types: ['geocode'] //this should work !
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
     } 
     function initialize1() {
        var input = document.getElementById('txtDestination');
        var options = {
          types: ['geocode'] //this should work !
        };
        var autocomplete1 = new google.maps.places.Autocomplete(input, {});

        google.maps.event.addListener(autocomplete1, 'place_changed', function() {
            var place = autocomplete1.getPlace();

            destLatd = place.geometry.location.lat(),
            destLngd = place.geometry.location.lng(),
            console.log(destLatd+" "+destLngd);
        });

     
     } 

    //  google.maps.event.addListener(autocomplete1, 'place_changed', function() {
    //             var place = autocomplete1.getPlace();
    //             console.log(place.address_components);
    //         });

     
    var cars_count = 0;
    var markers = [];
    var geocoder;
    var country;
            var map;
            function initMap(curLat,curLng) { // Google Map Initialization... 
              curLatd = curLat;
              curLngd = curLng;


              geocoder = new google.maps.Geocoder();
                map = new google.maps.Map(document.getElementById('dvMap'), {
                    zoom: 14,
                    center: new google.maps.LatLng(curLat,curLng),
                    mapTypeId: 'terrain'
                });
                var latlng = new google.maps.LatLng(curLat, curLng);
                geocoder.geocode({'latLng': latlng}, function(results, status) {
                  if(status == google.maps.GeocoderStatus.OK) {
                     // console.log(results)
                      if(results[1]) {
                          //formatted address
                          var address = results[0].formatted_address;
                          $("#txtSource").val(address);
                      } else {
                          alert("No results found");
                      }
                  } else {
                      alert("Geocoder failed due to: " + status);
                  }
                });
                 var icon = {
                          url: "{{url('public/files/marker.jpg')}}", // url
                          scaledSize: new google.maps.Size(25, 25), // scaled size
                          origin: new google.maps.Point(0,0), // origin
                          anchor: new google.maps.Point(0, 0)
                        };
                var userMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: icon
                });
            }
            // This Function will create a car icon with angle and add/display that marker on the map
            function AddCar(data) {
                
                      var base_url = "https://admin.taxi/public/files/";
                      if(data.val().servicetypeimage){
                        var icon = {
                      
                           url: base_url+data.val().servicetypeimage, // url
                          scaledSize: new google.maps.Size(25, 25), // scaled size
                          origin: new google.maps.Point(0,0), // origin
                          anchor: new google.maps.Point(0, 0) // anchor
                        };
                      }
                      else{
                        console.log(base_url+"marker.jpg");
                        var icon = {
                      
                          url: base_url+"marker.jpg", // url
                          scaledSize: new google.maps.Size(25, 25), // scaled size
                          origin: new google.maps.Point(0,0), // origin
                          anchor: new google.maps.Point(0, 0)
                        };
                      }
                      

                      var uluru = { lat: data.val().lat, lng: data.val().lng };

                      new google.maps.Size(42,68)

                      var marker = new google.maps.Marker({
                          position: uluru,
                          icon: icon,
                          map: map
                      });

                      markers[data.key] = marker; // add marker in the markers array...
                    

                      google.maps.event.addListener(marker, 'click', function() {
                      driver_details(data.val().id);
                      });
               
            }
            var cars_Ref = firebase.database().ref('/');

            // this event will be triggered when a new object will be added in the database...
            cars_Ref.on('child_added', function (data) {
              servicetype = {{ @($_GET['type'])?$_GET['type']:0 }};
                if(((!servicetype)||(servicetype==-1)||(servicetype&&(data.val().servicetype==servicetype))) && data.val().documentverified ==1 && data.val().servicetypeimage){
                 //console.log(data.val());
                cars_count++;
                AddCar(data);
                }
            });
            // this event will be triggered on location change of any car...
            cars_Ref.on('child_changed', function (data) {
                markers[data.key].setMap(null);
                AddCar(data);
            });

            // If any car goes offline then this event will get triggered and we'll remove the marker of that car...  
            cars_Ref.on('child_removed', function (data) {
              servicetype = {{ @($_GET['type'])?$_GET['type']:0 }};
                if((!servicetype)||(servicetype==-1)||(servicetype&&(data.val().servicetype==servicetype)) && data.val().documentverified && data.val().servicetypeimage){
                  //console.log(data.val());
                markers[data.key].setMap(null);
                cars_count--;
                }
            });   

        var source, destination;
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        google.maps.event.addDomListener(window, 'load', function () {
            // new google.maps.places.SearchBox(document.getElementById('txtSource'));
            // new google.maps.places.SearchBox(document.getElementById('txtDestination'));
            directionsDisplay = new google.maps.DirectionsRenderer({ 'draggable': false });
        });

        function GetRoute() {
            source = document.getElementById("txtSource").value;
            destination = document.getElementById("txtDestination").value;
            if(destination==""){
              alert("Enter drop location");
              return false;
            }

            $("#searchfrm").submit(function(event){
             
                event.preventDefault();  
              
            });
			
			
          //console.log(curLatd);
            var mumbai = new google.maps.LatLng(curLatd, curLngd);
            var mapOptions = {
                zoom: 7,
                center: mumbai
            };
            map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
            directionsDisplay.setMap(map);

            //*********DIRECTIONS AND ROUTE**********************//

            geocoder.geocode( { 'address': source}, function(results, status) {

              if (status == 'OK') {
                 curLatd = results[0].geometry.location.lat();
                 curLngd = results[0].geometry.location.lng();
                 //console.log((results[0].address_components).length);
                 //console.log((results[0].address_components));

                 if((results[0].address_components).length == 4){
                  country = results[0].address_components[3]['long_name'];
                 }else if((results[0].address_components).length == 5){
                  country = results[0].address_components[4]['long_name'];
                 }else if((results[0].address_components).length == 3){
                  country = results[0].address_components[2]['long_name'];
                 }else if((results[0].address_components).length == 2){
                  country = results[0].address_components[1]['long_name'];
                 }else if((results[0].address_components).length == 1){
                  country = results[0].address_components[0]['long_name'];
                 }else if((results[0].address_components).length == 7){
                  country = results[0].address_components[5]['long_name'];
                 }else if((results[0].address_components).length == 8){
                  country = results[0].address_components[6]['long_name'];
                 } 

                 for (var i = 0; i < results[0].address_components.length; i++)
                  {
                      var shortname = results[0].address_components[i].short_name;
                      var longname = results[0].address_components[i].long_name;
                      var type = results[0].address_components[i].types;
                      if (type.indexOf("country") != -1)
                      {    
                              country = longname;
                        
                      }
                  } 
                //getPrice(country);
                document.getElementById("selectcountry").value= country;
              
              } else {
                console.log('Geocode was not successful for the following reason: ' + status);
              }
            });
            var request = {
                origin: source,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                }
            });

            //*********DISTANCE AND DURATION**********************//
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
                    var dvDistance = document.getElementById("dvDistance");
                     //cprice = getPrice(distance,country);
                    //cprice = calPrice(distance);
                 //console.log(cprice+'-----------');
                  //cprice = 60;
                 var countryval = document.getElementById("selectcountry").value;
                  var cprice = getPrice(distance,countryval);
                  setTimeout(function(){
                  var cprice = document.getElementById("pricebycountry").value;
                    var urlRedirect = "{{url('search')}}"+"?latitude="+curLatd+"&longitude="+curLngd+"&source="+source+"&destination="+destination+"&duration="+duration+"&distance="+distance+"&price="+cprice+"&destLngd="+destLngd+"&destLatd="+destLatd;
					
                    dvDistance.innerHTML = "";
                    dvDistance.innerHTML += "<span class='mr-1'><b>Distance:</b> " + distance +"&nbsp;</span>";
                    dvDistance.innerHTML += "<span class='mr-1'><b>Duration:</b> " + duration +"&nbsp;</span>";
                    dvDistance.innerHTML += "<span class='mr-3'><b>Estimate price:</b> " + cprice + "&nbsp;</span>";
            //        dvDistance.innerHTML += "<a href='http://asteriscotaxi.com' target='__blank' class='btn btn-primary btn-xs'>Help</a>";
                    //dvDistance.innerHTML += `<a class="btn btn-success btn-xs" href='${urlRedirect}'>Find Taxi</a>`;
					          dvDistance.innerHTML += "<a class='btn btn-success btn-xs' href='"+urlRedirect+"'>Busca Tu taxi</a>";
                  }, 2000);
                } else {
                    alert("Unable to find the distance via road.");
                }
            });
        }
    </script>
	
	
<div class="modal fade" id="user-signin-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="margin-top: 100px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
         <div class="form-group-default">
			<div class="controls">
			<label for="user-email">Your email</label>
          <input type="email" id="user-email" name="user-email" class="form-control validate">
          
        </div>
        </div>

       <div class="form-group-default">
			<div class="controls">
			<label for="user-password">Your password</label>
          <input type="password" id="user-password" name="user-password" class="form-control validate">
          
        </div>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" onclick="doLogin();">Login</button>
      </div>
	  <div class="modal-footer d-flex justify-content-center">
	  <a href="javascript:void(0);" class="text-center" onclick="showRegistration();">Create Account </a>
	  </div>
    </div>
  </div>
</div>

<div class="modal fade" id="user-signup-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="margin-top: 100px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign Up</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
	  
	  <div class="form-group-default">
		<div class="controls">
          <label for="register-email">Your Name</label>
          <input type="text" id="register-name" name="register-name" class="form-control validate">
          
        </div>
		</div>
        <div class="form-group-default">
		<div class="controls">
          <label for="register-email">Your email</label>
          <input type="email" id="register-email" name="register-email" class="form-control validate">
          
        </div>
        </div>

        <div class="form-group-default">
			<div class="controls">
			 <label for="register-password">Your password</label>
			  <input type="password" id="register-password" name="register-password" class="form-control validate">
			  
			</div>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" onclick="doRegistration();">Register</button>
      </div>
	  <div class="modal-footer d-flex justify-content-center">
	  <a href="javascript:void(0);" class="text-center" onclick="showLogin();">I have an account</a>
	  </div>
    </div>
  </div>
</div>

@endsection