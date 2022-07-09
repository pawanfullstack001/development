@extends('admin.layout.main')
@section('content')
      
<div class="content sm-gutter">
  <div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
      <div class="col-lg-6 col-xl-5 m-b-10 hidden-xlg">
        <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column">
          <div class="p-l-25 p-r-25 p-t-20 p-b-20">
            <form  method="post" class="form mt-2 mb-2">

               {{ csrf_field() }}
               @if(session()->has('success'))
               <p class="text-success">{{ session()->get('success')  }}</p>
             @endif

             @if(session()->has('error'))
             <p class="text-danger">{{ session()->get('error')  }}</p>
           @endif
              <div class="w-100 m-b-15 pull-left">
                <h5 class="text-success pull-left mt-0">Change Password</h5>

               

               

                <label class="pull-right">
                  <img class="result" src="{{ (!$admin['image'])?asset('assets/img/profiles/bc2x.jpg'):asset('public/files/').'/'.$admin['image'] }}" width="90">
                </label>
              </div>
              <div class="form-group form-group-default">
                <label>Old Password</label>
                <div class="controls">
                  <input type="password" name="old_password" placeholder="88888888" class="form-control" required>
                 
                </div>
              </div>
              @if($errors)
                <p class="text-danger">{{ $errors->first('old_password') }}</p>
              @endif
              <div class="form-group form-group-default">
                <label>New Password</label>
                <div class="controls">
                  <input type="password" name="new_password" placeholder="88888888" class="form-control" required>
                </div>
              </div>
              @if($errors)
              <p class="text-danger">{{ $errors->first('new_password') }}</p>
              @endif
              <div class="form-group form-group-default">
                <label>Confirm Password</label>
                <div class="controls">
                  <input type="password" name="confirm_password" placeholder="88888888" class="form-control" required>
                </div>
              </div>
              @if($errors)
                <p class="text-danger">{{ $errors->first('confirm_password') }}</p>
              @endif
              <button class="btn btn-primary btn-cons m-t-10" type="submit">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        

<!-- Modal -->
<div class="modal fade" id="success-modal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p>Password changed successfully</p>      
        <button type="button" class="btn btn-default fs-20 mt-2 text-success" data-dismiss="modal"><i class="fa fa-check-square-o"></i></button>
      </div>
    </div>    
  </div>
</div>
@endsection