@extends('admin.layout.main')

@section('content')

<div class="profilePage"></div>
<div class="layout-content right-container" id="right-container">
  <div class="layout-content-body">
    <div class="row">
      <div class="col-md-6">
        <div class="main-header">
          <h4>Price Management</h4>
        </div>
      </div>
      <div class="col-md-6">
        <ul class="breadcrumb">
          <li><i class="fa fa-home"></i><a href="dashboard.php"> Home</a></li>
          <li class="active">Price Management</li>
        </ul>
      </div>
    </div>
    <div class="card edit-profile-page m-0"> 
      <div class="card-body">
        <form class="account_form m-b-15" action="">
          <div class="row">
           
            <div class="col-md-12">
                 <div class="pull-left">
                     <h2>Price Management</h2>
                 </div>
                 <div class="add_member pull-right">
                <button class="btn btn-danger btn-sm" type="button"  onclick="add_new(0)">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Price
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
                <tr>
                  <th>Id</th>
                  <th>Country</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>

                  @foreach($data as $i=>$type)
                  <tr>
                    <th>{{ ++$i }}</th>
                    <td>{{ $type['country'] }}</td> 
                    <td>{{ $type['first_fixed_price'] }}</td>     
                    <td>                   
                      <button class="btn btn-primary btn-sm" onclick="add_new({{$type['id']}})"><i class="fa fa-pencil"></i></button>
                      <button class="btn btn-danger btn-sm"  onclick="setdelete_id({{ $type['id'] }})"  data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i></button>
                       
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
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-body">
        <h4 id="edit-text">Add New Plan</h4>
        <div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{Session::get('country-error')['cartSuccess']}}
        </div>
        <form action="{{ url('admin/add_price') }}" enctype="multipart/form-data"  method="post">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              {{ csrf_field() }}
              <input type="hidden" name="id" id="p_id">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
            <div class="form-group">
            <label>Service Type</label>
            <select class="form-control" name="service_type">
           
                <option value="">Yellow</option>
                <option value="">Executive</option>
                <option value="">Tourism</option>
                <option value="">Other</option>
       
            
            </select>
          </div>
              <label>Plan Name </label>
              <select class="form-control" id="plan_type" name="country" required="">
                
          
            </div>
            <!-- {{--<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Price/KM</label>
              <input type="number"  class="form-control" min="1" id="price" name="price" required>
            </div>--}} -->
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>First Fixed Distance (Meter)</label>
              <input type="number"  class="form-control" min="1" id="first_distance_meter" name="first_distance_meter" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>First Fixed Price</label>
              <input type="number"  class="form-control" min="1" id="first_fixed_price" name="first_fixed_price" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>After Fixed Price</label>
              <input type="number"  class="form-control" min="1" id="next_price" name="next_price" required>
            </div>
            <div class="m-t-20 text-center col-12">
              <button class="btn btn-success" type="submit">Submit</button>
            </div>
          </div>  
        </form>
      </div>
    </div>
  </div>
</div>




<!-- Modal -->
<div id="delete" class="modal fade">
  <div class="modal-dialog modal-sm" data-dismiss="modal">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h2><i class="text-danger fa fa-trash-o"></i></h2>
        <h5>Are you sure you want to delete this plan?</h5>
        <div class="m-t-20">
          <button class="btn btn-success" onclick="confirm_delete()" data-dismiss="modal" type="button">Yes</button>
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
        <h5>{{Session::get('success-message')}}</h5>
      </div>      
    </div>
  </div>
</div>
@if(Session::has('country-error'))
<script type="text/javascript">
   $('decument').ready(function(){    
    $('#add-admin').modal('show'); 
    });
</script>
@endif
@if(Session::has('success-message'))
<script type="text/javascript">
   $('decument').ready(function(){
    $('#exampleModalLong').modal('show');
    });
</script>
@endif
<script>
  $(function() {
    $('#toggle-one').bootstrapToggle();
  })
 
  $('decument').ready(function(){

    var request = $.ajax({
        url : "{{url('api/user/all_country_list')}}",
        type : "GET",
        dataType: "json"
          })
        request.done(function(data){
          //console.log(data);  
          var str = "<option value=''>Select country</option>"
          $.each(data.country , function( index, value ) {
             str+=`<option value="`+value.name+`">`+value.name+`</option>`; 
          });  
          $("#plan_type").html(str);
        });

        request.fail(function(data){
          console.log(' Data => ', data);
        });

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

  function add_new(id){
   // alert(id);
    if(id>0){
      var globalarray = <?php echo json_encode($data); ?>;
      var theIndex = -1;
      for (var i = 0; i < globalarray.length; i++) {
        if (globalarray[i].id == id) {
          theIndex = i;
          break;
        }
      }
      $("#edit-text").text("Edit Price");
      //$("#price").val(globalarray[theIndex].price_per_km);
      $("#plan_type").val(globalarray[theIndex].country);
      $("#first_distance_meter").val(globalarray[theIndex].first_distance_meter);
      $("#first_fixed_price").val(globalarray[theIndex].first_fixed_price);
      $("#next_price").val(globalarray[theIndex].next_price);
      $("#p_id").val(globalarray[theIndex].id);
    }else{
      $("#edit-text").text("Add Price");
     // $("#price").val("");
      $("#plan_type").val("");
      $("#first_distance_meter").val("");
      $("#first_fixed_price").val("");
      $("#next_price").val("");
      $("#p_id").val("");
    }
    $('#add-admin').modal('show');
  }
   delete_id = 0;
  function setdelete_id(id){
    delete_id = id;
  }
  
  function confirm_delete(){
    console.log(delete_id);
    window.location.href="{{ url('admin/delete_price') }}/"+delete_id;
  }
 

  var max_chars = 100;

  $('.restrict_length').keyup( function(e){
    if ($(this).val().length >= max_chars) { 
        $(this).val($(this).val().substr(0, max_chars));
    }
});

</script>
@endsection
