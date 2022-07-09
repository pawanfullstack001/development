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
                     <h2>Subadmin Management</h2>
                 </div>
                 <div class="add_member pull-right">
                <button class="btn btn-danger btn-sm" type="button" onclick="loadMap()" data-toggle="modal" data-target="#add-admin">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Subadmin
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
                  <th>S.No.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Login password</th>
                  <th>Action</th>
                </thead>
                <tbody>

                  @foreach($subadmins as $i=>$subadmin)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $subadmin['username'] }}</td>
                    <td>{{ $subadmin['email'] }}</td>
                    <td>{{ $subadmin['plain_password'] }}</td>
                    <td>
                        <span class="d-flex">
                        
                            <button class="btn btn-primary btn-sm" onclick="editsubadmin({{$subadmin['id']}})"  ><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="setdelete_id({{ $subadmin['id'] }})"  data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i></button>
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
        <h4>Add New Subadmin</h4>
        <form action="{{ url('admin/add_subadmin') }}" enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        {{--<div class="form-group">
            <label>Unique id <span class="text-danger">*<span></label>
            <input type="text" required  class="form-control restrict_length" name="unique_id">
      </div>--}}


        <div class="form-group">
            <label>Username <span class="text-danger">*<span></label>
            <input type="text" required  class="form-control restrict_length" name="username">
      </div>



          <div class="form-group">
                <label>Email <span class="text-danger">*<span></label>
                <input type="text" required class="form-control restrict_length" name="email">
          </div>

          <div class="form-group">
            <label>Password <span class="text-danger">*<span></label>
            <input type="text" required class="form-control restrict_length" name="password">
         </div>

  
         <div class="form-group">
            <h4>Sub-Admin Permissions</h4>
            @foreach($permissions as $permission)
            @if($permission->permission_title!='subadmin management')
            <label>{{ucwords($permission->permission_title)}} &nbsp; <input type="checkbox" class="checkbox" value="{{$permission->id}}" name="permission_id[]"></label>
            @endif
            @endforeach
          </div>
  
         {{--<div class="form-group">
          <h4>Subadmin Role</h4>
          <label>Driver Management &nbsp; <input type="checkbox" class="checkbox" value="1" name="driver_management"></label>
          <label>Organization Management &nbsp; <input type="checkbox" class="checkbox" value="1" name="organization_management"></label>
          <label>Service Type &nbsp; <input type="checkbox" class="checkbox" value="1" name="service_type"></label>
          <label>Service Request &nbsp; <input type="checkbox" class="checkbox" value="1" name="service_request"></label>
          <label>Subscription Plan &nbsp; <input type="checkbox" class="checkbox" value="1" name="subscription_plan"></label>
          <label>Booking &nbsp; <input type="checkbox" value="1" class="checkbox" name="booking"></label>
          <label>SMS Management &nbsp; <input type="checkbox" class="checkbox" value="1" name="sms_management"></label>
          <label>Price and ETA &nbsp; <input type="checkbox" class="checkbox" value="1" name="price_and_eta"></label>
          <label>Notification &nbsp; <input type="checkbox" class="checkbox" value="1" name="notification"></label>
          <label>Report Generation &nbsp; <input type="checkbox" class="checkbox" value="1" name="report_generation"></label>
          <label>Rating & Review &nbsp; <input type="checkbox" class="checkbox" value="1" name="rating_and_review"></label>
          <label>Setting &nbsp; <input type="checkbox" value="1" class="checkbox" name="setting"></label>
         </div>--}}

         
          <div class="m-t-20 text-center">
            <button class="btn btn-success"  type="submit">Submit</button>
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
      <div class="modal-body" id="editsubadminPermission">
        <h4>Edit Subadmin</h4>
       
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
        <h5>Are you sure you want to delete this subadmin?</h5>
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
        <h5>Member added Successfully</h5>
      </div>      
    </div>
  </div>
</div>

<script>
  delete_id = 0;
  $('document').ready(function(){

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

  function edit(id,username,email,password,plain_password){
    // var option  = "<option value='"+type_id+"'>"+type_name+"</option>";
    // var optionhtml = $("#edit_organization_type").html();
    // optionhtml = $("#edit_organization_type").html(option+optionhtml);
    $("#edit-admin").modal('show');
    $("#edit_subadmin_id").val(id);
    $("#edit_username").val(username);
    $("#edit_email").val(email);
    $("#edit_password").val(password);
   

    $(".checkbox").prop('checked',false);

    if(driver_management_role){ $("#edit_driver_management").prop('checked',true); }
    if(organization_management_role){ $("#edit_organization_management").prop('checked',true); }
    if(service_type_role){ $("#edit_service_type").prop('checked',true); }
    if(service_request_role){ $("#edit_service_request").prop('checked',true); }
    if(subscription_plan_role){ $("#edit_subscription_plan").prop('checked',true); }
    if(sms_management_role){ $("#edit_sms_management").prop('checked',true); }
    if(price_and_eta_role){ $("#edit_price_and_eta").prop('checked',true); }
    if(notification_role){ $("#edit_notification").prop('checked',true); }
    if(report_generation_role){ $("#edit_report_generation").prop('checked',true); }
    if(rating_and_review_role){ $("#edit_rating_and_review").prop('checked',true); }
    if(setting_role){ $("#edit_setting").prop('checked',true); }
    if(booking_role){ $("#edit_booking").prop('checked',true); }
  }   

  function setdelete_id(id){
    delete_id = id;
  }

  function confirm_delete(){
    window.location.href="{{ url('admin/delete_subadmin') }}/"+delete_id;
  }

function test(id){
  alert(id);
}
  function editsubadmin(id) {
 
        $.ajax({
            type: "POST",
            url: "{{ URL::to('admin/edit-subadmin-permission') }}",
            data: {'id': id, "_token": "{{ csrf_token() }}"},
            success: function (data) {
               $('#editsubadminPermission').html(data);
               $("#edit-admin").modal('show');
            }
        });
    }

  var max_chars = 40;

  $('.restrict_length').keyup( function(e){
    if ($(this).val().length >= max_chars) { 
        $(this).val($(this).val().substr(0, max_chars));
    }
});




</script>



@endsection
