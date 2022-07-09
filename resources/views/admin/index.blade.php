@extends('admin.layout.main1')

@section('content')

<style>
  #vehicleimage {
    width: 170px;
    height: 100px;
  }

  .bold-text {
    font-weight: 600;
  }

  .remove-class {
    position: relative;
    float: right;
    font-size: 23px;
    bottom: 40px;
  }

  p {
    margin-bottom: 2px;
  }

  @media only screen and (max-width: 452px) {
    .remove-class {
      position: relative;
      float: right;
      font-size: 23px;
      bottom: 75px;
    }
  }

  @media only screen and (min-width: 452px) {
    .remove-class {
      position: relative;
      float: right;
      font-size: 23px;
      bottom: 75px;
    }
  }

  .container-fluid.container-fixed-lg.footer,
  .container-fluid.container-fixed-lg.footer .copyright.sm-text-center {
    display: none !important;
  }

  @media(min-width:768px) {
    .ml-md-10 {
      margin-left: 10% !important;
    }
  }
</style>
<div class="profilePage"></div>
<div class="layout-content right-container" id="right-container">
  <div class="layout-content-body">
    <div class="card edit-profile-page m-0">
      <div class="card-body" style="padding: 0px 20px">
        <div class="row">
          <div class="col-md-12">
            <div id="map" style="height:100vh;"></div>

            <div id="over_map" style=" position: absolute;top: 10px;left: 89%;z-index: 99;background-color: #ccffcc;padding: 10px;display: none;">
              <div>
                <span>Online Cars: </span><span id="cars">0</span>
              </div>
            </div>
          </div>
          <div class="col-md-12" id="select_taxi_div">
            <div class="card" style="border-radius: 15px;padding: 0%;background: #005cbf;">
              <div class="card-body" style="color: #fff;padding: 10px;z-index: 99;">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p style="font-size: 18px;line-height: 1.5;text-align: center;">Pulsa en un taxi para ver sus detalles</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12" id="taxi_reject_div" style="display: none;">
            <div class="card" style="border-radius: 15px;padding: 0%;background: #ff0000;">
              <div class="card-body" style="color: #fff;padding: 10px;z-index: 99;">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p style="font-size: 24px;line-height: 1.5;text-align: left;">El taxi está esperando en la puerta. Si no apareces dentro de 5 minutos se anulará el viaje</p>
                  <!-- <p style="font-size: 24px;line-height: 1.5;text-align: left;">
                    Busca otro taxi
                  </p> -->

                  <button class="btn btn-primary" onclick="backScreen()">Busca otro taxi</button>

                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12" id="driver_details_div" style="display: none;">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xl-6 col-6"><img id="driver_profile_img" style="width: auto;height: 50px;max-width:100%;" class="rounded-circle" src="" alt=""></div>
                  <div class="col-md-6 col-sm-6 col-xl-6 col-6"><img id="driver_taxi_img" style="width: auto;height: 50px;max-width:100%;" class="rounded" src="" alt=""></div>

                  <div class="col-md-12">
                    <p id="driver_taxi_details" style="font-size:18px" class="font-weight-bold">XXX-XX-XX XXXXX XXXXXX XXXX
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div id="driver_details">
                    <input type="hidden" name="driver_id" id="driver_id">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <div class="col-md-12">
                       <span id="driver_name"></span>
                    </div>
                    <div class="col-md-12">
                      <p>Acepta Visa y Master</p>
                    </div>

                    <div class="row" id="driver_select_div">
                      <div class="col-md-12">
                      <p style="font-size:20px" class="text-center text-danger font-weight-bold">Llega en <span cla id="duration"></span></p>
                      </div>
                    
                      <div class="col-md-6 mb-2">
                      <button class="btn btn-primary btn-block" id="select_driver_btn" onclick="selectDriver()">Elije este</button>
                      </div>
                      <div class="col-md-6 mb-2">
                      <button class="btn btn-danger btn-block" onclick="backScreen()">Busca otro</button>
                      </div>
                      
                     
                    </div>
                  </div>

                  <div class="col-md-12 text-center" id="driver_selected_div" style="display: none;">
                    <p style="font-size:20px" class="text-center text-danger font-weight-bold">Viene en Camino</p>
                    <a id="driver_mobile_num_a" style="font-size:20px" class="text-center text-complete font-weight-bold">Puedes llamarlo a <span id="driver_mobile_num">XXXXXXXXXX</span></a>
                    <button class="btn btn-info" onclick="showNoteDiv()">Puedes mandarle una nota</button>

                  </div>

                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12" id="note_to_driver" style="display: none;">
            <div class="card">
              <div class="card-body">
                <div class="row">


                  @php
                  $driverNotes = [
                  "Call me when you arrive",
                  "In a huge rush, need to get there asap",
                  "Need help with bags",
                  "I will wait out front",
                  ];

                  @endphp


                  <form>
                    <div class="form-group">
                      <select name="driver_note" id="driver_note" class="form-control">
                        <option value="" selected disabled>Select note</option>
                        @foreach($driverNotes as $note)
                        <option value="{{$note}}">{{$note}}</option>
                        @endforeach
                      </select>
                    </div>

                    <button type="button" id="send_note_btn" onclick="sendNoteToDriver()" class="btn btn-primary">Send note to driver</button>
                  </form>



                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  function showNoteDiv() {    
    $("#note_to_driver").css({
      'display': ''
    })
    $("#driver_details_div").css({
      'display': 'none'
    })
  }

  function sendNoteToDriver() {
    let note = $("#driver_note").val();
    if (!note) {
      return alert("Please select note.");
    }
    try {
      let bookingId = $("#booking_id").val();
      let apiUrl = "{{route('update_booking',':id')}}";
      apiUrl = apiUrl.replace(':id', bookingId);
      $("#send_note_btn").text('Enviando').prop("disabled", true);
      $.ajax({
        url: apiUrl,
        type: 'put',
        data: {
          'note': note
        },
        success: function(resp) {
          $("#send_note_btn").text('Sent, Please wait').prop("disabled", false);
          alert(resp.message);
        },
        error: function(error) {
          alert("Something went wrong, please try again later.")
          $("#send_note_btn").text('Elije este').prop("disabled", false);
        }
      });
    } catch (error) {
      alert("Something went wrong, please try again later.")
      $("#send_note_btn").text('Elije este').prop("disabled", false);
    }
  }

  function selectDriver() {
    try {
      $("#select_driver_btn").text('Espere..').prop("disabled", true);
      let driver_id = $("#driver_id").val();
      let request = {
        "driver_id": driver_id,
        "latitude": "{{request('latitude')}}",
        "longitude": "{{request('longitude')}}",
        "source": "{{request('source')}}",
        "destination": "{{request('destination')}}",
        "destination_lng": "{{request('destLngd')}}",
        "destination_lat": "{{request('destLatd')}}",
        "duration": "{{request('duration')}}",
        "distance": "{{request('distance')}}",
        "customer_name": "{{request('customer_name')}}",
        "customer_mob": "{{request('customer_phone')}}",
        "country": "{{request('country')}}",
      }
      $.ajax({
        url: "{{ route('booking_taxi') }}",
        type: 'post',
        data: request,
        success: function(resp) {
          alert(resp.message);
          if (resp.status) {
            let delayTime = 1000 * 70 * 1 // 1 min
            setTimeout(() => {
              checkBookingStatus(resp.data.id);
              $("#booking_id").val(resp.data.id);
              $("#select_driver_btn").text('Elije este').prop("disabled", false);
            }, delayTime);
          } else {
            $("#select_driver_btn").text('Elije este').prop("disabled", false);
          }
        },
        error: function(error) {
          alert("Something went wrong, please try again later.")
          $("#select_driver_btn").text('Elije este').prop("disabled", false);
        }
      });
    } catch (error) {
      alert("Something went wrong, please try again later.")
      $("#select_driver_btn").text('Elije este').prop("disabled", false);
    }
  }


  function checkBookingStatus(bookingId) {
    try {
      let apiUrl = "{{route('booking_details',':id')}}";
      apiUrl = apiUrl.replace(':id', bookingId);
      $.ajax({
        url: apiUrl,
        type: 'get',
        success: function(resp) {
          if (resp.status) {
            let bookingData = resp.data;
            if (bookingData.status == "1") {
              $("#driver_select_div").hide();
              $("#driver_selected_div").show();
            } else {
              $("#taxi_reject_div").css({
                'display': ''
              });
              $("#driver_details_div").css({
                'display': 'none'
              });
            }
          } else {
            alert("Something went wrong")
          }
        }
      });
    } catch (error) {

    }
  }


  function backScreen() {
    $("#driver_details_div").css({
      'display': 'none'
    });
    $("#taxi_reject_div").css({
      'display': 'none'
    });
    $("#select_taxi_div").css({
      'display': ''
    });
  }
</script>

<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&sensor=false&libraries=places"></script>

<script>
  $(document).delegate("#service_type_filter", 'change', function() {
    var servicetypevalue = $(this).val();
    window.location.href = "{{ url('/') }}?type=" + servicetypevalue;
  });
  $(document).ready(function() {
    setTimeout(() => {
      initMap();
    }, 1000);

  });

  var curLat = 0;
  var curLng = 0;
  var driver_id = 0;

  // Replace your Configuration here..
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

  // counter for online cars...
  var source = "{{$source}}";
  var destination = "{{$destination}}";
  var cars_count = 0;
  var source, destination;
  var directionsDisplay;
  var directionsService;
  // markers array to store all the markers, so that we could remove marker when any car goes offline and its data will be remove from realtime database...
  var markers = [];
  var map;

  function initMap() { // Google Map Initialization...
    let lat = parseFloat({{$latitude}});
    let lng = parseFloat({{$longitude}});
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 14,
      center: new google.maps.LatLng(lat, lng),
      mapTypeId: 'terrain'
    });
    directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer({
      map: map
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


    // get firebase database reference...
    var cars_Ref = firebase.database().ref('/');
    let filterDrivers = @json($filterDrivers );

    // this event will be triggered when a new object will be added in the database...
    cars_Ref.on('child_added', function(data) {
console.log('cars_Ref - '+JSON.stringify(data));
      servicetype = {{@($_GET['type']) ? $_GET['type'] : 0 }};
      let service_type = data.val().service_type;
      let service_type_img = data.val().servicetypeimage;
      let document_verified = data.val().documentverified;

      let is_service_type = ((!servicetype) || (servicetype == -1) || (servicetype && (service_type == servicetype)))

      if (is_service_type && document_verified == 1 && service_type_img && filterDrivers.length && filterDrivers.includes(data.val().id)) {
        cars_count++;
        console.log(cars_count + " car added");
        AddCar(data);
      }
    });

    // this event will be triggered on location change of any car...
    cars_Ref.on('child_changed', function(data) {
      markers[data.key].setMap(null);
      AddCar(data);
    });

    // If any car goes offline then this event will get triggered and we'll remove the marker of that car...  
    cars_Ref.on('child_removed', function(data) {
      servicetype = {{@($_GET['type']) ? $_GET['type'] : 0 }};
      if ((!servicetype) || (servicetype == -1) || (servicetype && (data.val().servicetype == servicetype)) && data.val().documentverified && data.val().servicetypeimage) {
        console.log(data.val());
        markers[data.key].setMap(null);
        cars_count--;
        document.getElementById("cars").innerHTML = cars_count;
      }
    });

  }

  // This Function will create a car icon with angle and add/display that marker on the map
  function AddCar(data) {
    var base_url = "https://admin.taxi/public/files/";
    if (data.val().servicetypeimage) {
      var icon = {
        url: base_url + data.val().servicetypeimage, // url
        scaledSize: new google.maps.Size(25, 25), // scaled size
        origin: new google.maps.Point(0, 0), // origin
        anchor: new google.maps.Point(0, 0) // anchor
      };
    } else {
      var icon = {
        url: base_url + "marker.jpg", // url
        scaledSize: new google.maps.Size(25, 25), // scaled size
        origin: new google.maps.Point(0, 0), // origin
        anchor: new google.maps.Point(0, 0)
      };
    }
    var uluru = {
      lat: data.val().lat,
      lng: data.val().lng
    };
    new google.maps.Size(42, 68)
    var marker = new google.maps.Marker({
      position: uluru,
      icon: icon,
      map: map
    });
    markers[data.key] = marker; // add marker in the markers array...
    document.getElementById("cars").innerHTML = cars_count;
    google.maps.event.addListener(marker, 'click', function() {
      driver_details(data.val().id);
      let driverHtml = $("#driver_details_div").html();
   //   console.log(driverHtml);

      infowindow = new google.maps.InfoWindow({
          content: `${driverHtml}`
      });
      infowindow.open(map, marker);
      
    });
  }
</script>

<script>
  function removeDetails() {
    $("#driver_details_div").css('display', 'none');
  }
  var service = new google.maps.DistanceMatrixService();

  function driver_details(driver_id) {
    $("#select_driver_btn").text('Elije este').prop("disabled", false);
    var $target = $('html,body');
    $target.animate({
      scrollTop: $target.height()
    }, 1000);

    $.ajaxSetup({
      cache: false
    });
    $.ajax({
      url: '{{ url('user') }}/' + driver_id,
      type: 'get',
      dataType: 'json',
      success: function(json) {
        json = json.appuser;
        if (json) {
          var source = {
            "lat": parseFloat("{{request('latitude')}}"),
            "lng": parseFloat("{{request('longitude')}}")
          };
          var destination = {
            "lat": parseFloat(json.latitude),
            "lng": parseFloat(json.longitude)
          };
          const request = {
            origins: [source],
            destinations: [destination],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
          };
          service.getDistanceMatrix(request, function(response, status) {
            if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
              var duration = response.rows[0].elements[0].duration.text;
              $("#duration").text(duration)
            }
          });
          $("#driver_name").text(json.name);
          $("#driver_id").val(driver_id);
          $("#driver_taxi_details").text(`${json.vehicle_number} ${json.brand} ${json.year}`);
          setTimeout(function() {
            $("#driver_profile_img").prop('src', "https://radar.taxi/public/files/" + json.profile_pic);
            $("#driver_taxi_img").prop('src', "https://radar.taxi/public/files/" + json.vehicle_image);
          }, 1000);
          if (json.subscribed == 1 && json.available_status == 1 && json.status == 0) {
            $("#driver_mobile_num").text(json.country_code + json.mobile_no);
            $("#driver_mobile_num_a").attr('href', "tel:" + json.country_code + json.mobile_no);
            $("#d-status").html("Available").removeClass("text-danger").addClass('text-success');
          } else {
            $("#driver_mobile_num").text("xxxxxxxxxx");
            $("#driver_mobile_num_a").attr('href', "");
            $("#confirmsubmit").css('display', 'none');
            $("#driver_mobile_num_a").attr('href', "tel:");
          }
          // $("#driver_details_div").css({
          //   'display': ''
          // });
          $("#taxi_reject_div").css({
            'display': 'none'
          });
          $("#select_taxi_div").css({
            'display': 'none'
          });
        }
      }
    });
  }
</script>

@endsection