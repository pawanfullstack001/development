@extends('admin.layout.main')
@section('content')
      
<div class="content sm-gutter">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-xl-5 mx-auto" style="padding-top: 10%; min-height: calc(100vh - 180px)">
        <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column">
          <div class="p-l-25 p-r-25 p-t-20 p-b-20 text-center">
            <h1><strong>401</strong></h1>
            <h3>You don't have permission to access this page!</h3>
            <h5><a href="{{url('admin/home')}}">Back to Dashboard</a></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        

@endsection