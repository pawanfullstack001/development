@extends('admin.layout.main')

@section('content')

<style>
  .pac-container {
      z-index: 10000 !important;
  }
</style>



<script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M&sensor=false&libraries=places'></script>

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
                 <div class="pull-left">
                     <h2>Organization Type Management</h2>
                 </div>
                 <div class="add_member pull-right">
                <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" onclick="loadMap()" data-target="#add-admin">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Organization Type
                </button>
              </div> 
            </div>

            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong></strong> {{  session()->get('success') }}
            </div>
            @endif
            
          </div>
        </form>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="mytable" class="table table-striped table-bordered">
                <thead>
                  <th>S. No.</th>
                  <th>Organization Type</th>
                  <th>Radius in meters</th>
                  <th>Action</th>
                </thead>
                <tbody>
                @foreach($organization as $i => $type)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $type['name'] }}</td>
                    <td>{{ $type['radius'] }}</td>
                    <td>
                      <span class="d-flex">
                        <button class="btn btn-primary btn-sm" onclick="edit('{{ $type['id'] }}','{{ $type['name'] }}','{{ $type['radius'] }}','{{ $type['latitude'] }}','{{ $type['longitude'] }}','{{ $type['location'] }}')" data-toggle="modal" data-target="#edit-admin" ><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="setdelete_id({{ $type['id'] }})" data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i></button>
                      </span>
                    </td>
                  </tr>
                 @endforeach
                </tbody>
              </table>   
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="add-admin" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Add New Organization Type</h4>
        <form method="post" action="{{ url('admin/add_organization_type') }}">
         {{ csrf_field() }}
         <input type="hidden" id="longitude" name="longitude">
         <input type="hidden" id="latitude" name="latitude">
          <div class="form-group">
            <label>Name</label>
            <input type="text" required="true" class="form-control restrict_length" name="name">
          </div>

          <div class="form-group">
            <label>Radius in meters <span id="rangevalue"></span></label>
            <input type="range"  required="true" class="form-control circle"   id="radius" name="radius" min="2000" max="50000" />
          </div>

          <div class="form-group">
          <label>Location </label>
            <input id="pac-input" class="form-control" type="text" name="location" placeholder="Search Box">
          </div>

          <div id="googleMap" style="width:100%;height:400px;"></div>

          <div class="m-t-20 text-center">
            <button class="btn btn-success" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="edit-admin" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Edit Organization Type</h4>
       <form  id="organizationtypeform" action="{{ url('admin/edit_organization_type') }}" method="post">
        {{ csrf_field() }}
          <div class="form-group">
            <label>Name</label>
            <input type="hidden" id="organization_id" name="id">
            <input type="text" required="true"  id="organization_name" class="form-control restrict_length" name="name">
            
          </div>

          <div class="form-group">
            <label>Radius in meters  <span id="editrangevalue"></span></label>
            <input type="hidden" id="edit_latitude" name="latitude">
            <input type="hidden" id="edit_longitude" name="longitude"> 
            
            <input type="range"  required="true" class="form-control"  id="editradius" name="radius" min="2000" max="50000" />
          </div>


          <div class="form-group">
          <label>Location </label>
            <input id="edit-pac-input" class="form-control" type="text" name="location"  placeholder="Search Box">
          </div>

          <div id="editgoogleMap" style="width:100%;height:400px;"></div>
          
          <div class="m-t-20 text-center">
            <button class="btn btn-success" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="delete" class="modal fade">
  <div class="modal-dialog modal-sm" data-dismiss="modal">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h2><i class="text-danger fa fa-trash-o"></i></h2>
        <h5>Are you sure you want to delete this organization type?</h5>
        <div class="m-t-20">
          <button class="btn btn-success" onclick="confirm_delete()" type="button">Yes</button>
          <button class="btn btn-danger" data-dismiss="modal" type="button">No</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalLong">
  <div class="modal-dialog modal-sm" data-dismiss="modal">
    <div class="modal-content">      
      <div class="modal-body">
        <h2><i class="fa fa-check-square-o text-success"></i></h2>
        <h5>Member added Successfully</h5>
      </div>      
    </div>
  </div>
</div>
<!-- Modal END-->

<script>
  var delete_id = 0;
  $('decument').ready(function(){

    $('#mytable').dataTable({
      info:false,
      columnDefs: [
      { targets: 'no-sort', orderable: false }
      ]
    });
    $("#mytable #checkall").click(function () {
     if($(this).is(':checked')) {
       $("#mytable td input[type=checkbox]").prop("checked", true);
     }else {
       $("#mytable td input[type=checkbox]").prop("checked", false);
     }
   });

    $("[data-toggle=tooltip]").tooltip();
  });  

  // $("#organizationtypeform").submit(function(){
  //   var organization = $("#organizationtypeform").serialize();
  //   console.log(organization);
    
  //   return false;
  // }); 

  function edit(id,name,radius,latitude,longitude,location){
    $("#organization_id").val(id);
    $("#organization_name").val(name);
    $("#edit_latitude").val(latitude);
    $("#edit_longitude").val(longitude);
    $("#editradius").val(radius);
    $("#editrangevalue").html(radius);
    $("#edit-pac-input").val(location);
    editloadMap();
    $("#edit-admin").modal('show');
  }

  function setdelete_id(id){
    delete_id = id;
  }

  function confirm_delete(){
    window.location.href="{{ url('admin/delete_organization_type') }}/"+delete_id;
  }


  function initializeAutocomplete(){
              var input = document.getElementById('pac-input');

              var options = {}

              var autocomplete = new google.maps.places.Autocomplete(input, options);

              google.maps.event.addListener(autocomplete, 'place_changed', function() {

                var place = autocomplete.getPlace();

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                var placeId = place.place_id;
                // to set city name, using the locality param
                var componentForm = {
                  locality: 'short_name',
                };
                for (var i = 0; i < place.address_components.length; i++) {
                  var addressType = place.address_components[i].types[0];
                  if (addressType == 'country') {
                    var country = place.address_components[i]['long_name'];
                    // document.getElementById("city").value = val;
                  }else if(addressType == 'sublocality_level_1'){
                    var searched_input = place.address_components[i]['long_name'];
                  }else if(addressType == 'locality'){
                    var city = place.address_components[i]['long_name'];
                  }
                }


                $("#latitude").val(lat);
                $("#longitude").val(lng);


                  google.maps.event.addListener(searchBox, 'places_changed', function() {
     searchBox.set('map', null);


     var places = searchBox.getPlaces();

     var bounds = new google.maps.LatLngBounds();
     var i, place;
     for (i = 0; place = places[i]; i++) {
       (function(place) {
         var marker = new google.maps.Marker({

           position: place.geometry.location
         });
         marker.bindTo('map', searchBox, 'map');
         google.maps.event.addListener(marker, 'map_changed', function() {
           if (!this.getMap()) {
             this.unbindAll();
           }
         });
         bounds.extend(place.geometry.location);


       }(place));

     }
     map.fitBounds(bounds);
     searchBox.set('map', map);
     map.setZoom(Math.min(map.getZoom(),12));

   });

                loadMap();
                // $("#location_id").val(placeId);
                // $("#country").val(country);
                // $("#city").val(city);
                // $("#locality").val(searched_input);
              });
            }

  function editinitializeAutocomplete(){
              var input = document.getElementById('editlocality');

              var options = {}

              var autocomplete = new google.maps.places.Autocomplete(input, options);

              google.maps.event.addListener(autocomplete, 'place_changed', function() {

                var place = autocomplete.getPlace();

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                var placeId = place.place_id;
                // to set city name, using the locality param
                var componentForm = {
                  locality: 'short_name',
                };
                for (var i = 0; i < place.address_components.length; i++) {
                  var addressType = place.address_components[i].types[0];
                  if (addressType == 'country') {
                    var country = place.address_components[i]['long_name'];
                    // document.getElementById("city").value = val;
                  }else if(addressType == 'sublocality_level_1'){
                    var searched_input = place.address_components[i]['long_name'];
                  }else if(addressType == 'locality'){
                    var city = place.address_components[i]['long_name'];
                  }
                }


                $("#edit_latitude").val(lat);
                $("#edit_longitude").val(lng);

                
              });
            }

        var max_chars = 40;

        $('.restrict_length').keyup( function(e){
          if ($(this).val().length >= max_chars) { 
              $(this).val($(this).val().substr(0, max_chars));
          }
        });



        

</script>




<script>

  var circle;
  function loadMap() {
      var marker;
      
      var  geocoder = new google.maps.Geocoder();
      let mapEle = document.getElementById('googleMap');
      var map = new google.maps.Map(mapEle, {
        center: new google.maps.LatLng(28.6266, 77.3848),
        zoom: 8
      });




      var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
      google.maps.event.addListener(searchBox, 'places_changed', function() {
    //  searchBox.set('map', null);


     var places = searchBox.getPlaces();

     var bounds = new google.maps.LatLngBounds();
     var i, place;
     for (i = 0; place = places[i]; i++) {
       (function(place) {
        //  var marker = new google.maps.Marker({

        //    position: place.geometry.location
        //  });

        placeMarker(place.geometry.location);
        //  marker.bindTo('map', searchBox, 'map');
        //  google.maps.event.addListener(marker, 'map_changed', function() {
        //    if (!this.getMap()) {
        //      this.unbindAll();
        //    }
        //  });
         bounds.extend(place.geometry.location);


       }(place));

     }


    

     map.fitBounds(bounds);
     searchBox.set('map', map);
     map.setZoom(Math.min(map.getZoom(),8));

   });
   
  
      
      function placeMarker(location) {
         var rad = document.getElementById('radius').value;
        //  if(rad==""){
        //   rad="50000";
        //  }
         var radiu = parseInt(rad);
        if (marker && circle) {
          marker.setPosition(location);
          circle.setCenter(location)
          circle.setRadius(radiu)
        } else {
          
           circle = new google.maps.Circle({
            center: location,
            map: map,
            radius: radiu,          // IN METERS.
            fillColor: '#FF6600',
            fillOpacity: 0.3,
            strokeColor: "#FFF",
            strokeWeight: 0         // DON'T SHOW CIRCLE BORDER.
            
        });
            //create a marker
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });
        }
  
  
    }
                
  
      
  
    
   var self = this;
        google.maps.event.addListener(map, 'click', (event) => {
          placeMarker(event.latLng);
          geocoder.geocode({
            'latLng': event.latLng
        },(results, status) => {
                var lat =event.latLng.lat();
                var long = event.latLng.lng();
                var lATlONG =lat+','+long;
                var geocoder = new google.maps.Geocoder;
                var infowindow = new google.maps.InfoWindow;

                $("#longitude").val(long);
                $("#latitude").val(lat);
                geocodeLatLng(geocoder, map, infowindow,lATlONG).call();
        })
         
  
      });
  
  
  
  
  function geocodeLatLng(geocoder, map, infowindow,lATlONG) {
          var input = lATlONG;
  
          var latlngStr = input.split(',', 2);
          var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
          geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
              if (results[1]) {
                document.getElementById("default_address").value=results[1].formatted_address;
              } else {
                window.alert('No results found');
              }
            } else {
              window.alert('Geocoder failed due to: ' + status);
            }
          });
        }
  }



    $("#radius").change(function(){
      if(circle){
          circle.setRadius(Number($(this).val()));
        }          
    })

</script>




<script>
  var editcircle;
  function editloadMap() {
      var marker;
      
      var  geocoder = new google.maps.Geocoder();
      let mapEle = document.getElementById('editgoogleMap');
      var map = new google.maps.Map(mapEle, {
        center: new google.maps.LatLng(28.6266, 77.3848),
        zoom: 8
      });


    latv = $('#edit_latitude').val();
    lonv = $('#edit_longitude').val();
    myLatlng = new google.maps.LatLng(latv,lonv);

    placeMarker(myLatlng);
      



      // alert(placeMarker(('28.457705605505698', '78.21152119140629')));





        var searchBox = new google.maps.places.SearchBox(document.getElementById('edit-pac-input'));
        google.maps.event.addListener(searchBox, 'places_changed', function() {
      //  searchBox.set('map', null);


      var places = searchBox.getPlaces();

      var bounds = new google.maps.LatLngBounds();
      var i, place;
      for (i = 0; place = places[i]; i++) {
        (function(place) {
          //  var marker = new google.maps.Marker({

          //    position: place.geometry.location
          //  });

          placeMarker(place.geometry.location);
          //  marker.bindTo('map', searchBox, 'map');
          //  google.maps.event.addListener(marker, 'map_changed', function() {
          //    if (!this.getMap()) {
          //      this.unbindAll();
          //    }
          //  });
          bounds.extend(place.geometry.location);


        }(place));

      }


      

      map.fitBounds(bounds);
      searchBox.set('map', map);
      map.setZoom(Math.min(map.getZoom(),8));

    });
    
    





  
      
      function placeMarker(location) {
        // console.log("there1");
         var rad = document.getElementById('editradius').value;
        //  if(rad==""){
        //   rad="50000";
        //  }
         var radiu = parseInt(rad);
        if (marker && editcircle) {

          marker.setPosition(location);
          editcircle.setCenter(location)
          editcircle.setRadius(radiu)
        } else {
          
          editcircle = new google.maps.Circle({
            center: location,
            map: map,
            radius: radiu,          // IN METERS.
            fillColor: '#FF6600',
            fillOpacity: 0.3,
            strokeColor: "#FFF",
            strokeWeight: 0         // DON'T SHOW CIRCLE BORDER.
            
        });
            //create a marker
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });
        }
  
  
    }
                
  
      
  
    // alert(google.maps.event.trigger('click'));

    // var marker = new google.maps.Marker({});
    
    

  //  var self = this;

        google.maps.event.addListener(map, 'click', (event) => {
          
          placeMarker(event.latLng);
          geocoder.geocode({
            'latLng': event.latLng
        },(results, status) => {
                var lat =event.latLng.lat();
                var long = event.latLng.lng();
                var lATlONG =lat+','+long;
                var geocoder = new google.maps.Geocoder;
                var infowindow = new google.maps.InfoWindow;

                $("#edit_longitude").val(long);
                $("#edit_latitude").val(lat);

                // console.log( lATlONG);
                geocodeLatLng(geocoder, map, infowindow,lATlONG).call();
        })
         
  
      });
  
  
  
  
  function geocodeLatLng(geocoder, map, infowindow,lATlONG) {
    // alert("here1");
          var input = lATlONG;
  
          var latlngStr = input.split(',', 2);
          var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
          geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
              if (results[1]) {
                document.getElementById("default_address").value=results[1].formatted_address;
              } else {
                window.alert('No results found');
              }
            } else {
              window.alert('Geocoder failed due to: ' + status);
            }
          });
        }



        // circle = new google.maps.Circle({
        //     center: location,
        //     map: map,
        //     radius: 20000,          // IN METERS.
        //     fillColor: '#FF6600',
        //     fillOpacity: 0.3,
        //     strokeColor: "#FFF",
        //     strokeWeight: 0         // DON'T SHOW CIRCLE BORDER.
            
        // })



        // marker = new google.maps.Marker({
        //         position: new google.maps.LatLng(28.886647357533793,77.73910908203129),
        //         map: map,
        // });


        
 

  }


          $("#editradius").change(function(){
            if(editcircle){
              editcircle.setRadius(Number($(this).val()));
             }   
          });


              var slider = document.getElementById("radius");
              var output = document.getElementById("rangevalue");
              output.innerHTML = slider.value;

              slider.oninput = function() {
                $("#rangevalue").text(this.value);
              }


              var slider = document.getElementById("editradius");
              var output = document.getElementById("editrangevalue");
              output.innerHTML = slider.value;

              slider.oninput = function() {
                output.innerHTML = this.value;
              }



              

                </script>


@endsection