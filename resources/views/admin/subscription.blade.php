@extends('admin.layout.main')

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
                 <div class="pull-left">
                     <h2>Subscription Plan Management</h2>
                 </div>
                 <div class="add_member pull-right">
                 <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#add-free-plan">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> View/Update Free Plan
                </button> &nbsp;
                <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#add-admin" onclick="add_new(0)">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Plan
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
                  <th>Id</th>
                  <th>Plan Name</th>
                  <th>Description</th>
                  <th>Plan Price</th>
                  <th>Type</th>
                  <th>No of Days</th>
                  <th>Action</th>
                </thead>
                <tbody>

                  @foreach($data as $i=>$type)
                  <tr>
                    <th>{{ ++$i }}</th>
                    <td>{{ $type['plan_name'] }}</td>           
                    <td>
                    <!-- <textarea class="form-control" readonly="" style="background: #ffffff;color: rgba(98, 98, 98, 2.23);">{{ $type['plan_description'] }}</textarea> -->
                    <?php
                    $str = $type['plan_description'];
                    echo wordwrap($str,15,"<br>\n");
                    ?>
                    </td>
                    <td>{{ $type['plan_price']}}</td>
                    <td>
                      @if($type['plan_type']==1)
                      By Calendar
                      @elseif($type['plan_type']==2)
                      By Days
                      @else
                      None
                      @endif
                    </td>
                   <!--  @if($type['image'])
                    <td><img src="{{ asset('public/files') }}/{{ $type['image'] }}"  height="58"></td>
                    @else 
                    <td><img src="{{ asset('assets/img/logo_2x.png') }}" height="58"></td>
                    @endif -->
                    <td>{{ $type['days']}}</td>
                    <td>                   
                      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-admin" onclick="add_new({{$type['id']}})"><i class="fa fa-pencil"></i></button>
                      <button class="btn btn-danger btn-sm"  onclick="setdelete_id({{ $type['id'] }})"  data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i></button>
                       <label class="switch">
                        <input type="checkbox" value="1" @if($type['status']==1) checked @endif onchange="changeStatus({{ $type['id'] }})" id="c{{ $type['id'] }}">
                        <span class="slider round"></span>
                      </label>
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
        <form action="{{ url('admin/add_plan') }}" enctype="multipart/form-data"  method="post">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              {{ csrf_field() }}
              <input type="hidden" name="id" id="p_id">
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Country</label>
              <input type="text" id="country" required class="form-control" name="country" required="">
            </div> 
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Country Code</label>
              <input type="number" id="country_code" required class="form-control" name="country_code" required="">
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Currency</label>
              <input type="text" id="currency" required class="form-control" name="currency" required="">
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>No of Days</label>
              <input type="number"  class="form-control" min="1" id="no_of_day" name="no_of_day" required>
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Price</label>
              <input type="number"  class="form-control" min="1" id="price" name="price" required>
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Per Day</label>
              <input style="color: #000;" type="text" class="form-control" id="per_day" name="per_day" readonly>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Type </label>
              <select class="form-control" id="plan_type" name="plan_type" required="">
                <option value="">Select type</option>
                <option value="1">By Calendar</option>
                <option value="2">By Days</option>
              </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Name </label>
              <input type="text" id="name" required class="form-control restrict_length" name="name">
            </div>            
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Description</label>
              <textarea id="description" required class="form-control restrict_length" name="description"></textarea>
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Payment</label>
              <input type="text" class="form-control" id="payment" name="payment">
            </div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Daily Text</label>
              <input type="text" class="form-control" id="daily_text" name="daily_text">
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



<div id="add-free-plan" class="modal fade">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-body">
        <h4>View/Update free Plan</h4>
        <form action="{{ url('admin/update_free_plan') }}" enctype="multipart/form-data"  method="post">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              {{ csrf_field() }}
              <input type="hidden" name="id" id="p_id" value="@if($freeplan){{$freeplan->id}}@endif">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Name </label>
              <input type="text" required class="form-control restrict_length" name="name" value="@if($freeplan){{$freeplan->plan_name}}@endif">
            </div>             
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Plan Description</label>
              <textarea required class="form-control restrict_length" name="description">@if($freeplan){{$freeplan->plan_description}}@endif</textarea>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>Price</label>
              <input type="number" class="form-control" min="1" name="price" value="0" readonly="">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
              <label>No of Days</label>
              <input type="number"  class="form-control" name="no_of_day" value="@if($freeplan){{$freeplan->days}}@endif" min="1" required>
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
		$("#price").on('keyup',function(){
			var cart = $(this).val();
			var days = $("#no_of_day").val();
			var multi = parseFloat(cart/days);
			$("#per_day").val(multi.toFixed(2));
		})
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
    if(id>0){
      $("#title-text").html('Edit Delivery Charge');
      var globalarray = <?php echo json_encode($data); ?>;
      var theIndex = -1;
      for (var i = 0; i < globalarray.length; i++) {
        if (globalarray[i].id == id) {
          theIndex = i;
          break;
        }
      }
      $("#edit-text").text("Edit Subscription Plan");
      $("#name").val(globalarray[theIndex].plan_name);
      $("#description").val(globalarray[theIndex].plan_description);
      $("#price").val(globalarray[theIndex].plan_price);
      $("#p_id").val(globalarray[theIndex].id);
      $("#no_of_day").val(globalarray[theIndex].days);
      $("#plan_type").val(globalarray[theIndex].plan_type);
	  $("#country").val(globalarray[theIndex].country);
	  $("#country_code").val(globalarray[theIndex].country_code);
	  $("#currency").val(globalarray[theIndex].currency);
	  $("#per_day").val(globalarray[theIndex].per_day);
	  $("#payment").val(globalarray[theIndex].payment);
	  $("#daily_text").val(globalarray[theIndex].daily_text);
       $("#image").prop('required',false);
      // $("#date_from").val(new Date(globalarray[theIndex].date_from * 1000).toISOString().slice(0, 10).replace('T', ' '));
      // $("#date_to").val(new Date(globalarray[theIndex].date_to * 1000).toISOString().slice(0, 10).replace('T', ' '));

    }else{
      $("#edit-text").text("Add New Subscription Plan");
      $("#name").val("");
      $("#description").val("");
      $("#price").val("");
      $("#p_id").val("");
      $("#no_of_day").val("");
      $("#plan_type").val("");
      $("#image").prop('required',true);
      // $("#date_from").val("");
      // $("#date_to").val("");
    }
  }
   delete_id = 0;
  function setdelete_id(id){
    delete_id = id;
  }
  
  function confirm_delete(){
    console.log(delete_id);
    window.location.href="{{ url('admin/delete_plan') }}/"+delete_id;
  }
  function changeStatus(id){
    var check = 0;
    var checkvalue = $("#c"+id+":checked").val();
    if(checkvalue){
      check =1;
    }
    window.location.href="{{ url('admin/plan_status') }}/"+id+"/"+check;    
  }

  var max_chars = 100;

  $('.restrict_length').keyup( function(e){
    if ($(this).val().length >= max_chars) { 
        $(this).val($(this).val().substr(0, max_chars));
    }
});

</script>
@endsection
