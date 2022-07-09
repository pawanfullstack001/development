@extends('subadmin.layout.main')

@section('content')
<div class="profilePage"></div>
<div class="layout-content right-container" id="right-container">
  <div class="layout-content-body">
    <div class="row">
      <div class="col-md-6">
        <div class="main-header">
          <h4>Member Management</h4>
        </div>
      </div>
      <div class="col-md-6">
        <ul class="breadcrumb">
          <li><i class="fa fa-home"></i><a href="dashboard.php"> Home</a></li>
          <li class="active">Member Management</li>
        </ul>
      </div>
    </div>
    <div class="card edit-profile-page m-0"> 
      <div class="card-body">
        <form class="account_form m-b-15" action="">
          <div class="row">
           
            <div class="col-md-12">
                 <h2>Dashboard</h2>
            </div>
          </div>
        </form>
   
    <div >
        <div class="col-md-12">
        <div id="map" style=" height:700px;"></div>

      <div id="over_map" style=" position: absolute;top: 10px;left: 89%;z-index: 99;background-color: #ccffcc;padding: 10px;">
          <div>
              <span>Online Cars: </span><span id="cars">0</span>
          </div>
      </div>
        </div>
          </div>
        <!-- <div class="row">
          <div class="col-md-3">
             <div class="card card-box">
                 <div class="card-block">
                     <h4 class="card-title text-danger">34</h4>
                     <p class="card-text">Total Numbers of Drives</p>
                     <i class="fa fa-user-o"></i>
                 </div>
              </div>
          </div>
          <div class="col-md-3">
             <div class="card card-box">
                 <div class="card-block">
                     <h4 class="card-title text-danger">34</h4>
                     <p class="card-text">Total Numbers of Drives</p>
                     <i class="fa fa-user-o"></i>
                 </div>
              </div>
          </div>
          <div class="col-md-3">
             <div class="card card-box">
                 <div class="card-block">
                     <h4 class="card-title text-danger">34</h4>
                     <p class="card-text">Total Numbers of Drives</p>
                     <i class="fa fa-user-o"></i>
                 </div>
              </div>
          </div>
          <div class="col-md-3">
             <div class="card card-box">
                 <div class="card-block">
                     <h4 class="card-title text-danger">34</h4>
                     <p class="card-text">Total Numbers of Drives</p>
                     <i class="fa fa-user-o"></i>
                 </div>
              </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>


<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>

<script>
            // Replace your Configuration here..
            var config = {
                apiKey: "AIzaSyAbbkZxVrjJLs7rsDJBbrAQ83q7dD0Fw_w",
                authDomain: "demoproject-24f5c.firebaseapp.com",
                databaseURL: "https://demoproject-24f5c.firebaseio.com",
                projectId: "demoproject-24f5c",
                storageBucket: "demoproject-24f5c.appspot.com",
                messagingSenderId: "1037962903158",
                appId: "1:1037962903158:web:7edae789e1029cd11d2957",
                measurementId: "G-B5W93EV9VJ"
            };
            firebase.initializeApp(config);
        </script>

        <script>

            // counter for online cars...
            var cars_count = 0;

            // markers array to store all the markers, so that we could remove marker when any car goes offline and its data will be remove from realtime database...
            var markers = [];
            var map;
            function initMap() { // Google Map Initialization... 
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: new google.maps.LatLng(-35.4326725,-106.3104763),
                    mapTypeId: 'terrain'
                });
            }

            // This Function will create a car icon with angle and add/display that marker on the map
            function AddCar(data) {

                console.log("here");

                var icon = { // car icon
                    path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
                    scale: 0.4,
                    fillColor: "#427af4", //<-- Car Color, you can change it 
                    fillOpacity: 1,
                    strokeWeight: 1,
                    anchor: new google.maps.Point(0, 5),
                    rotation: data.val().angle //<-- Car angle
                };

                var uluru = { lat: data.val().lat, lng: data.val().lng };

                if(data.val().type == "ambulance"){
                  var iconimage = "{{  asset('assets/img/icons8-ambulance-16-.ico') }}";
                }else if(data.val().type == "individual"){
                  var iconimage = "{{  asset('assets/img/icons8-car-16.ico') }}";
                }else if(data.val().type == "taxi"){
                  var iconimage = "{{  asset('assets/img/icons8-taxi-16.ico') }}";
                }

                var marker = new google.maps.Marker({
                    position: uluru,
                    icon: iconimage,
                    map: map
                });

                markers[data.key] = marker; // add marker in the markers array...
                document.getElementById("cars").innerHTML = cars_count;

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow = new google.maps.InfoWindow({
                    content: '<h3 onclick="driver_details('+data.val().id+')">Driver id : '+data.val().id+'</h3><p>latitude :'+data.val().lat+' </p><p>longitude :'+data.val().lng+' </p>'
                });
                infowindow.open(map, marker);
            });
               

            }

            // get firebase database reference...
            var cars_Ref = firebase.database().ref('/');

            // this event will be triggered when a new object will be added in the database...
            cars_Ref.on('child_added', function (data) {
              console.log(data.val());
                cars_count++;
                AddCar(data);
            });

            // this event will be triggered on location change of any car...
            cars_Ref.on('child_changed', function (data) {
                markers[data.key].setMap(null);
                AddCar(data);
            });

            // If any car goes offline then this event will get triggered and we'll remove the marker of that car...  
            cars_Ref.on('child_removed', function (data) {
                markers[data.key].setMap(null);
                cars_count--;
                document.getElementById("cars").innerHTML = cars_count;
            });


            function driver_details(driverid){
                alert(driverid);
            }

        </script>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.11&sensor=false&key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&callback=initMap" type="text/javascript">
        </script>

@endsection