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
                     <h2>Service Type Management</h2>
                 </div>
                 <div class="add_member pull-right">
                <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#add-admin">
                  <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Service Type
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
                  <th>Image</th>
                  <th>Organization Type</th>
                  <th>Type</th>
                  <th>Pax</th>
                  <th>Accept credit card</th>
                  <th>Action</th>
                </thead>
                <tbody>

                  @foreach($servicetype as $i=>$type)
                  <tr>
                    <td>{{ $type["servicetype_id"] }}</td>
                    @if($type['image'])
                    <td><img src="{{ asset('public/files') }}/{{ $type['image'] }}"  height="58"></td>
                    @else 
                    <td><img src="{{ asset('assets/img/logo_2x.png') }}" height="58"></td>
                    @endif

                    <td>{{ $type['organizationtype']['name'] }}</td>
                    <td>{{ $type['name'] }}</td>
                    <td>{{ $type['pax'] }}</td>
                    <td>{{ ($type['accept_ccard'])?'Accepted':'Not accepted' }}</td>
                    <td>
                      <button class="btn btn-primary btn-sm" onclick="edit('{{ $type['id'] }}','{{ $type['organizationtype']['id'] }}','{{ $type['name'] }}','{{ $type['pax'] }}','{{ $type['accept_ccard'] }}','{{ $type['servicetype_id'] }}')" ><i class="fa fa-pencil"></i></button>
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
        <h4>Add New Service Type</h4>
        <form action="{{ url('organization/add_service_type') }}" enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Service type id </label>
          <input type="text" id="service_typeid" required class="form-control restrict_length" name="service_typeid">
        </div>

          <div class="form-group">
            <label>Name</label>
            <input type="text" id="add_service_name" required class="form-control restrict_length" name="name">
          </div>

          <div class="form-group">
              <label>Image</label>
              <input type="file" required="true" required class="form-control" accept="image/*" name="image">
            </div>

          <div class="form-group">
            <label>Organization Type</label>
            <select id="add_organization_type" class="form-control" name="organization_type">
              @foreach($organizationtype as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
              <label>Pax</label>
              <input type="text" required="true" required class="form-control" name="pax">
          </div>


          <div class="form-group">
              <label>Accept credit card</label>
              <select name="accept_ccard" class="form-control">
                <option value="1">Yes</option>
                <option value="0">No</option>
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
        <h4>Edit Service type</h4>
        <form action="{{ url('organization/edit_service_type') }}" enctype="multipart/form-data"  method="post">
          {{ csrf_field() }}

          <div class="form-group">
            <label>Service type id </label>
            <input type="text" id="edit_service_typeid" required class="form-control restrict_length" name="service_typeid">
          </div>

          <div class="form-group">
            <label>Edit Service type</label>
            <input type="hidden" id="edit_service_id" class="form-control" name="id">
            <input type="text"   id="edit_service_name" required class="form-control restrict_length" name="name">
          </div>

          <div class="form-group">
              <label>Image</label>
              <input type="file"  class="form-control"  accept="image/*" name="image">
            </div>


          <div class="form-group">
            <label>Organization Type</label>
            <select id="edit_organization_type"  class="form-control" name="organization_type">
              @foreach($organizationtype as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Pax</label>
            <input type="text" required="true" id="edit_pax" required class="form-control" name="pax">
          </div>


        <div class="form-group">
            <label>Accept credit card</label>
            <select name="accept_ccard" id="edit_accept_ccard"  class="form-control">
              <option value="1">Yes</option>
              <option value="0">No</option>
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
        <h5>Are you sure you want to delete this service type?</h5>
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

  function edit(id,organization_id,name,pax,accept_ccard,servicetypeid){
    $("#edit-admin").modal('show');
    $("#edit_service_typeid").val(servicetypeid);
    $("#edit_service_id").val(id);
    $("#edit_organization_type").val(organization_id);
    $("#edit_service_name").val(name);
    $("#edit_pax").val(pax);
    $("#edit_accept_ccard").val(accept_ccard);
  }   

  function setdelete_id(id){
    delete_id = id;
  }

  function confirm_delete(){
    window.location.href="{{ url('organization/delete_service_type') }}/"+delete_id;
  }

  var max_chars = 40;

  $('.restrict_length').keyup( function(e){
    if ($(this).val().length >= max_chars) { 
        $(this).val($(this).val().substr(0, max_chars));
    }
});

</script>
@endsection
