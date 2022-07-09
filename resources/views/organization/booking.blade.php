@extends('organization.layout.main')

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
                     <h2>Booking Management</h2>
                 </div>
                 <div class="add_member pull-right">
                
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
                  <th>Driver Name</th>
                  <th>Rrice</th>
                  <th>Source</th>
                  <th>Destination</th>
                  <th>Duration</th>
                  <th>Destination</th>
                  <th>Status</th>
                </thead>
                <tbody>

                  @foreach($data as $i=>$value)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>@if($value->driver) {{$value->driver['name']}} @else none @endif</td>
                    <td>{{ $value->price }}</td>
                    <td>{{ $value->source }}</td>
                    <td>{{ $value->destination }}</td>
                    <td>{{ $value->duration }}</td>
                    <td>{{ $value->distance }}</td>
                    <td>
                      @if($value->status=="1")
                      <span class="text-warning">Outgoing</span>
                      @elseif($value->status=="3")
                      <span class="text-success">Complete</span>
                      @else($value->status=="4")
                      <span class="text-danger">Cancel</span>
                      @endif
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




</script>



@endsection
