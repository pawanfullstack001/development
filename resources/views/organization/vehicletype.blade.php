@extends('organization.layout.main')

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
                     <h2>Vehicle Type Management</h2>
                 </div>
                 <div class="add_member pull-right">
                <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#add-admin">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Vehicle Type
                </button>
              </div> 
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="mytable" class="table table-striped table-bordered">
                <thead>
                  <th>S. No.</th>
                  <th>Service Type</th>
                  <th>Type</th>
                  <th>Action</th>
                </thead>
                <tbody>

                  @foreach($vehicletype as $i=>$type)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $type['servicetype']['name'] }}</td>
                    <td>{{ $type['name'] }}</td>
                    <td>
                      <button class="btn btn-primary btn-sm" onclick="edit('{{ $type['id'] }}','{{ $type['servicetype']['id'] }}','{{ $type['name'] }}')" ><i class="fa fa-pencil"></i></button>
                      <button class="btn btn-danger btn-sm" onclick="setdelete_id({{ $type['id'] }})"  data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i></button>
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
        <h4>Add New vehicle Type</h4>
        <form action="{{ url('organization/add_vehicle_type') }}" method="post">
        {{ csrf_field() }}
          <div class="form-group">
            <label>Add New vehicle Type</label>
            <input type="text" id="add_vehicle_name" class="form-control" name="name">
          </div>

          <div class="form-group">
            <label>service Type</label>
            <select id="add_service_type" class="form-control" name="service_type">
              @foreach($servicetype as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
              @endforeach
            </select>
          </div>
          
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
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Edit vehicle type</h4>
        <form action="{{ url('organization/edit_vehicle_type') }}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Edit vehicle type</label>
            <input type="hidden" id="edit_vehicle_id" class="form-control" name="id">
            <input type="text" id="edit_vehicle_name" class="form-control" name="name">
          </div>

          <div class="form-group">
            <label>service Type</label>
            <select id="edit_service_type"  class="form-control" name="service_type">
              @foreach($servicetype as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
              @endforeach
            </select>
          </div>
         
          
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
        <h5>Are you sure you want to delete this vehicle type?</h5>
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

  function edit(id,service_id,name){
    $("#edit-admin").modal('show');
    $("#edit_vehicle_id").val(id);
    $("#edit_service_type").val(service_id);
    $("#edit_vehicle_name").val(name);
  }   

  function setdelete_id(id){
    delete_id = id;
  }

  function confirm_delete(){
    window.location.href="{{ url('organization/delete_vehicle_type') }}/"+delete_id;
  }
</script>
@endsection
