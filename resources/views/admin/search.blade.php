@extends('admin.layout.main1')

@section('map_scripts')


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&libraries=places"></script>
@endsection

@section('content')

<style>
  #driver_details {
    display: none;
  }
  .control-label{
    margin-bottom: 0;
  }

  .select2-container{
    width: auto !important;
  }


  /* .gm-style-iw.gm-style-iw-c{
    max-width: 300px !important;
  } */
  .select2-container--default .select2-selection--single .select2-selection__arrow {    
    top: auto !important;
    height: 23px !important;
  }

  #vehicleimage {
    width: 170px;
    height: 100px;
  }

  .input-group-text {
    padding: 0 !important;
  }

  .bold-text {
    font-weight: 600 !important;
  }

  .remove-class {
    position: absolute;
    right: 14px;
    top: 11px;
    font-size: 23px;
    z-index: 999;
  }

  .fixed-top {
    position: fixed;
    z-index: 999;
    width: 100%;
    padding: 20px 15px;
    background-color: #fff;
  }

  .pac-container {
    /* background-color: #fff;
    position: fixed !important;
    top: 80px !important;
    z-index: 99999999 !important; */
  }

  .form-group {
    margin: 0;
  }

  .gutter-sm {
    margin: 0px -8px !important
  }

  .gutter-sm [class*="col-"] {
    padding: 0px 8px !important;
  }

  @media(max-width: 574px) {
    /* .pac-container.pac-logo.hdpi {
      top: 145px !important;
    }

    .pac-container.pac-logo.hdpi+.pac-container.pac-logo.hdpi {
      top: 80px !important;
    } */
  }
</style>
<div class="profilePage"></div>
<div class="container">
  <div class="row" id="dis_map_div" style="display: none;">

    <div class="col-md-12">
      <div id="dvMap" style="height:100vh;"></div>
    </div>
    <!-- <div class="card" style="border-radius: 30px;border: 3px solid #6d5cae;">
      <div class="card-body">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div id="dvDistance">
          </div>
        </div>
      </div>
    </div> -->
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
      <div class="row mt-2" id="driver_details">
        <div class="col-12 col-sm-4 col-md-3 col-lg-4 mt-2">
          <p><span class="bold-text"> Chofer : </span><span id="driver_name"></span> &nbsp;<span class="bold-text"> Vehicle No. : </span><span id="driver_vehicle_type"></span></p>
          <p><span class="bold-text"> PAX : </span><span id="driver_passenger"></span> &nbsp;<span class="bold-text"> </span><img src="" id="driver_credit_card" style="width: 50px;height: auto;"></p>

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
  <div class="row" id="searchfrmdiv">
    <input type="hidden" name="pricebycountry" id="pricebycountry" value="0" />
    <input type="hidden" name="selectcountry" id="selectcountry" />
    <div class="card">
      <div class="card-body">
        <form autocomplete="off" id="searchfrm" class="mb-3">
          <div class="col-12 col-sm-3 col-md-4 col-lg-4 mt-3">
            <img class="img-fluid" alt="Radar.Taxi" src="https://admin.taxi/public/assets/images/{{$image}}" alt="">
            <div class="form-group mt-3 mb-3">
              <a href="https://www.asteriscotaxi.com/" class="btn btn-primary">Ayuda</a>
            </div>
            <p>
            <h3>Regístrate !</h3>
            </p>
            <p>Regístrate son tu nombre y número de teléfono para que el chofer pueda coordinar contigo el viaje como también avisarte si olvidas algo en el vehículo</p>
          </div>

          <div class="col-12 col-sm-3 col-md-4 col-lg-4">
            <div class="form-group">
              <label class="control-label bold-text">Nombre</label>
              <input type="text" id="nombre" placeholder="Nombre" maxlength="30" class="form-control alpha-only" autocomplete="off" />
            </div>
          </div>
          <div class="col-12 col-sm-3 col-md-4 col-lg-4">
            <div class="form-group">
              <label class="control-label bold-text">Telefono</label>
              <div class="input-group">
              <div class="input-group-prepend">
              <span class="input-group-text" >
                <select name="" class="form-control select2" id="country_phone_code" required>
                  @foreach($countries as $country)
                    <option {{ $country->dial_code=='+56' ? 'selected' : ''}} value="{{$country->dial_code}}">{{$country->dial_code}}</option>  
                  @endforeach     
                </select>
              </span>
              </div>
              
              <input type="tel" maxlength="9" id="telefono" placeholder="Telefono" class="form-control numeric-sc" autocomplete="off" aria-label="Enter Telefono" aria-describedby="country_phone_code" />
                            
               <p class="text-danger hide" id="invalid_num_err">Número inválido. Debe empezar con un 9 y tener 9 dígitos</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-3 col-md-4 col-lg-4">
            <div class="form-group">
              <label class="control-label bold-text">Origen</label>
              <input type="text" id="txtSource" placeholder="Origen" class="form-control" autocomplete="off" />
            </div>
          </div>
          <div class="col-12 col-sm-3 col-md-4 col-lg-4 ">
            <div class="form-group mb-3">
              <label class="control-label bold-text">Destino</label>
              <input type="text" id="txtDestination" placeholder="Destino" class="form-control" autocomplete="off" />
            </div>
          </div>
          <div class="col-12 col-sm-3 col-md-4 col-lg-4">
            <div class="form-group mb-3">
              <button onclick="GetRoute()" type="button" class="btn btn-info">Calcula la ruta</button>
            </div>
          </div>
        </form>

      </div>


    </div>
  </div>
</div>

<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>
<script type="text/javascript">
  $(".numeric-sc").on("input", function() {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
  });
  $(".alpha-only").on("input", function() {
    $(this).val($(this).val().replace(/[^a-zA-Z ]/g, ''));
  });
  var curLngd = 0;
  var curLatd = 0;

  var destLngd = 0;
  var destLatd = 0;

  function initMap(curLat, curLng) { // Google Map Initialization... 
    curLatd = curLat;
    curLngd = curLng;


    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('dvMap'), {
      zoom: 14,
      center: new google.maps.LatLng(curLat, curLng),
      mapTypeId: 'terrain'
    });
    var latlng = new google.maps.LatLng(curLat, curLng);
    geocoder.geocode({
      'latLng': latlng
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
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
      origin: new google.maps.Point(0, 0), // origin
      anchor: new google.maps.Point(0, 0)
    };
    var userMarker = new google.maps.Marker({
      position: latlng,
      map: map,
      icon: icon
    });
  }


  if ("geolocation" in navigator) { //check geolocation available 
    //try to get user current location using getCurrentPosition() method
    navigator.geolocation.getCurrentPosition(function(position) {
      curLng = position.coords.longitude;
      curLat = position.coords.latitude;
      initMap(curLat, curLng);
    });
  } else {
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

  function removeDetails() {
    $("#driver_details").css('display', 'none');
  }

  function NotDetails() {
    alert("Please enter drop location");
  }

  function driver_details(driverid) {

    var $target = $('html,body');
    $target.animate({
      scrollTop: $target.height()
    }, 1000);

    $("#driver_details").css('display', 'flex');
    setTimeout(function() {
      $.ajax({
        url: "{{ url('admin/userdetails') }}/" + driverid,
        type: 'get',
        dataType: 'json',
        success: function(json) {
          json = json.appuser;
          $("#driver_name").text(json.name);
          $("#driver_vehicle_type").text(json.vehicle_number);
          $("#driver_passenger").text(json.passengers);

          setTimeout(function() {
            $("#profileimage").prop('src', "https://radar.taxi/public/files/" + json.profile_pic);
            $("#vehicleimage").prop('src', "https://radar.taxi/public/files/" + json.vehicle_image);
            if (json.accept_credit_card) {
              $("#driver_credit_card").prop('src', "{{url('/public/files/card.png')}}");
            }
          }, 1000);
          if (json.subscribed == 1 && json.available_status == 1 && json.status == 0) {
            $("#driver_phone_no").text(json.country_code + json.mobile_no);
            $("#driver_phone_no").attr('href', "tel:" + json.country_code + json.mobile_no);
            $("#telImg").attr('href', "tel:" + json.country_code + json.mobile_no);
            // $("#telImg").html(`<img src="{{url('public/call3.png')}}" style="width: 45px;" title="image">`);
            $("#d-status").html("Available").removeClass("text-danger").addClass('text-success');
          } else {
            $("#driver_phone_no").text("xxxxxxxxxx");
            // $("#driver_phone_no").attr('href',"");
            // $("#telImg").attr('href',"");
            $("#d-status").html("Busy").removeClass("text-success").addClass('text-danger');
          }

          $("#profileimage").attr('src', "public/files/" + json.profile_pic);
          $("#vehicleimage").attr('src', "public/files/" + json.vehicle_image);

        }
      });
    }, 3000);
  }
  $(document).ready(function() {

    initialize1();
    initialize();
  });
  var price = 0;
  var first_distance_meter = 0;
  var first_fixed_price = 0;
  var next_price = 0;

  function getPrice(distance, country) {
    var d;
    var distance = distance.replace('km', '');
    return $.ajax({
      url: "{{url('admin/get_price')}}",
      data: {
        "country": country,
        "distance": distance,
        "_token": "{{ csrf_token() }}"
      },
      type: "POST",
    });

  }

  function calPrice_BKP(distance) {
    var distance = distance.replace('km', '');
    // var floor = Math.floor(distance);
    // var d =  floor * price + (((distance - floor) * 1000) * price)/1000;
    var amount = ((distance * 1000) - 200) / 200;
    var nextamount = Math.round(amount) * 150;
    var d = 300 + nextamount;
    // console.log(Math.round(amount));
    return d;
  }

  function calPrice(distance) {
    var distance = distance.replace('km', '');
    // var floor = Math.floor(distance);
    // var d =  floor * price + (((distance - floor) * 1000) * price)/1000;
    var amount = ((distance * 1000) - 200) / 200;
    var nextamount = Math.round(amount) * 150;
    var d = 300 + nextamount;
    // console.log(Math.round(amount));
    return d;
  }

  function initialize() {
    var input = document.getElementById('txtSource');    
    var options = {
      types: ['geocode'] //this should work !
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener("place_changed",function(){
      const place = autocomplete.getPlace();
      console.log(place);
    });
   
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
        console.log(destLatd + " " + destLngd);
    });


  }



  var cars_count = 0;
  var markers = [];
  var geocoder;
  var country;
  var map;

 


  var source, destination,customer_name,customer_phone,country_phone_code;
  var directionsDisplay;
  var directionsService = new google.maps.DirectionsService();
  google.maps.event.addDomListener(window, 'load', function() {
    directionsDisplay = new google.maps.DirectionsRenderer({
      'draggable': false
    });
  });
 

  $("#searchfrm").submit(function(event) {
    event.preventDefault();
  });


  $("#telefono").keyup(function(event) {
    event.preventDefault();
    if (this.value.length==9) {
      $("#invalid_num_err").addClass("hide");
    }else{
      $("#invalid_num_err").removeClass("hide");
    }

  });

  function GetRoute() {
   
    source = document.getElementById("txtSource").value;
    destination = document.getElementById("txtDestination").value;
    customer_name = document.getElementById("nombre").value;
    customer_phone = document.getElementById("telefono").value;
    country_phone_code = document.getElementById("country_phone_code").value;
    if (source == "") {
      alert("Enter Source location");
      return false;
    }
    if (destination == "") {
      alert("Enter drop location");
      return false;
    }
    if (customer_name == "") {
      alert("Enter nombre");
      return false;
    }
    if (customer_phone == "") {
      alert("Ingrese el teléfono con el código del país");
      return false;
    }
    if (customer_phone.length!=9) {
      alert("Invalid number");
      return false;
    }
    if (country_phone_code == "") {
      alert("Enter country code");
      return false;
    }

   
    $("#dis_map_div").css({
      "display": ''
    });

    //console.log(curLatd);
    var mumbai = new google.maps.LatLng(curLatd, curLngd);
    var mapOptions = {
      zoom: 7,
      center: mumbai
    };
    map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
    directionsDisplay.setMap(map);

    var uluru = {
      lat: curLatd,
      lng: curLngd
    };
    var pin=new google.maps.LatLng(50.00, 50.00);
    var marker = new google.maps.Marker({
      position: uluru,
     // icon: icon,
      map: map
    });

    //*********DIRECTIONS AND ROUTE**********************//

    geocoder.geocode({
      'address': source
    }, function(results, status) {

      if (status == 'OK') {
        curLatd = results[0].geometry.location.lat();
        curLngd = results[0].geometry.location.lng();
        //console.log((results[0].address_components).length);
        //console.log((results[0].address_components));

        if ((results[0].address_components).length == 4) {
          country = results[0].address_components[3]['long_name'];
        } else if ((results[0].address_components).length == 5) {
          country = results[0].address_components[4]['long_name'];
        } else if ((results[0].address_components).length == 3) {
          country = results[0].address_components[2]['long_name'];
        } else if ((results[0].address_components).length == 2) {
          country = results[0].address_components[1]['long_name'];
        } else if ((results[0].address_components).length == 1) {
          country = results[0].address_components[0]['long_name'];
        } else if ((results[0].address_components).length == 7) {
          country = results[0].address_components[5]['long_name'];
        } else if ((results[0].address_components).length == 8) {
          country = results[0].address_components[6]['long_name'];
        }

        for (var i = 0; i < results[0].address_components.length; i++) {
          var shortname = results[0].address_components[i].short_name;
          var longname = results[0].address_components[i].long_name;
          var type = results[0].address_components[i].types;
          if (type.indexOf("country") != -1) {
            country = longname;

          }
        }     
        document.getElementById("selectcountry").value = country;

      } else {
        console.log('Geocode was not successful for the following reason: ' + status);
      }
    });
    var request = {
      origin: source,
      destination: destination,
      travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
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
    }, function(response, status) {
      if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
        var distance = response.rows[0].elements[0].distance.text;
        var duration = response.rows[0].elements[0].duration.text;
       // var dvDistance = document.getElementById("dvDistance");
        var countryval = document.getElementById("selectcountry").value;
        var cprice = 0;
        getPrice(distance, countryval).then(function(res) {
          cprice = res;

          var urlRedirect = "{{url('search')}}" + "?latitude=" + curLatd + "&longitude=" + curLngd + "&source=" + source + "&destination=" + destination + "&duration=" + duration + "&distance=" + distance + "&destLngd=" + destLngd + "&destLatd=" + destLatd+"&customer_name="+customer_name+"&customer_phone="+country_phone_code+customer_phone+"&country="+countryval;
         // dvDistance.innerHTML = "";

          let priceHtml = "";
          if (cprice.length) {
            cprice.forEach(element => {
              priceHtml += `<p style="margin-bottom:0"><strong>${element.fareName}</strong>&nbsp;&nbsp;&nbsp;<span>${element.price} ${element.currency}</span></p>`;
            });
          } else {
            alert("No Plans found for the given source and destination")
          }




        

          let htmlCont = `            
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12 col-12">
                        <div class="form-group "><b>A</b> &nbsp;&nbsp;&nbsp;${source}
                        </div>
                        <div class="form-group ">
                            <b>B</b> &nbsp;&nbsp;&nbsp;${destination}
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12 col-12">
                        <div class="form-group ">
                            <label class="control-label"><strong>Distancia:</strong> <span>${distance}</span></label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12 col-12">
                        <div class="form-group ">
                            <label class="control-label"><strong>Duración:</strong> <span>${duration}</span></label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12 col-12">
                        <div class="form-group ">
                            ${priceHtml}
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12 col-12">
                        <div class="form-group ">
                            <a href="${urlRedirect}" class="btn btn-primary">Busca Tu taxi</a>
                        </div>
                    </div>
                </div> `;

                      //  dvDistance.innerHTML = htmlCont;

     infowindow = new google.maps.InfoWindow({
          content: `${htmlCont}`,
          //maxWidth: 300 
      });
      infowindow.open(map,marker);
                  
          $("#searchfrmdiv").css({
            "display": 'none'
          });
        });
      } else {
        alert("Unable to find the distance via road.");
      }
    });
  }
</script>
<script>
  $(".select2").select2()
</script>
@endsection

