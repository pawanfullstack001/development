@extends('organization.layout.main')
@section('content')
      
<div class="content sm-gutter">
  <div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
      <div class="col-lg-6 col-xl-5 m-b-10 hidden-xlg">
        <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column">
          <div class="p-l-25 p-r-25 p-t-20 p-b-20">
            <form method="post" enctype="multipart/form-data" class="form mt-2 mb-2">
             {{ csrf_field() }}
              <div class="w-100">
                <h5 class="text-success pull-left">Edit Profile</h5>
                <label class="profile-img pull-right">
                  <img class="result" src="{{ (!$admin['image'])?asset('assets/img/profiles/bc2x.jpg'):asset('public/files/').'/'.$admin['image'] }}">
                  <input type="file" name="profile_pic" class="fileInput">
                  <span class="fa fa-pencil"></span>
                </label>
              </div>
              <div class="form-group form-group-default">
                <label>Email Id</label>
                <div class="controls">
                  <input type="email" value="{{ $admin['email'] }}" name="email" placeholder="" class="form-control" >
                </div>
               
              </div>
              @if($errors)
              <p style="text-danger">{{ $errors->first('email') }}</p>
                @endif
              <div class="form-group form-group-default">
                <label>Phone</label>
                <div class="controls">
                  <input type="text" value="{{ $admin['phone_no'] }}"  name="phone" placeholder="" class="form-control" >
                </div>
              </div>
              @if($errors)
              <p style="text-danger">{{ $errors->first('phone') }}</p>
            @endif
              <div class="form-group form-group-default">
                <label>Location</label>
                <div class="controls">
                  <input type="text"  value="{{ $admin['location'] }}"  name="location" placeholder="" class="form-control" >
                </div>
               
              </div>
              @if($errors)
              <p style="text-danger">{{ $errors->first('location') }}</p>
          @endif
              <div class="form-group form-group-default">
                <label>About Me</label>
                <div class="controls">
                  <textarea type="text"  class="form-control" name="about" placeholder="" rows="3">{{ $admin['about'] }}</textarea>
                </div>
                
              </div>
              @if($errors)
                    <p style="text-danger">{{ $errors->first('about') }}</p>
                @endif
              <div class="form-group form-group-default">
                <label>Address</label>
                <div class="controls">
                  <input type="text" value="{{ $admin['address']   }}" name="address" placeholder="" class="form-control">
                </div>
                
              </div>
              @if($errors)
                    <p style="text-danger">{{ $errors->first('address') }}</p>
                @endif
              <button class="btn btn-primary btn-cons m-t-10" type="submit" >Submit</button>
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
        <p>Profile Updated</p>      
        <button type="button" class="btn btn-default fs-20 mt-2 text-success" data-dismiss="modal"><i class="fa fa-check-square-o"></i></button>
      </div>
    </div>    
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
  $(document).on("change",".fileInput",function(e2) {
    var img1 = e2.target.files[0];
    if(!iEdit.open(img1, true, function(res1){
      $(".result").attr("src", res1);  
    })){
      alert("Whoops! That is not an image!");
    }
  });
</script>

@endsection