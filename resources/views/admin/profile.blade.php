@extends('admin.layout.main')

@section('content')

<div class="content sm-gutter">
  <div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
      <div class="col-lg-6 col-xl-5 m-b-10 hidden-xlg">
        <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column">
          <div class="p-l-25 p-r-25 p-t-20 p-b-20">
            <div class="mt-2 mb-2">
              <div class="w-100 pull-left">
                <h4 class="text-success pull-left">Admin</h4>
                <label class="profile-img pull-right">
                  <img class="result" src="{{ (!$admin['image'])?asset('assets/img/profiles/bc2x.jpg'):asset('public/files/').'/'.$admin['image'] }}">
                </label>
              </div>
              <div class="form-group">
                <label>
                  <strong>Email</strong><br>
                  {{ $admin['email'] }}
                </label>
              </div>
              <div class="form-group">
                <label>
                  <strong>Phone</strong><br>
                  {{ $admin['phone'] }}
                </label>
              </div>
              <div class="form-group">
                <label>
                  <strong>Location</strong><br>
                  {{ $admin['location'] }}
                </label>
              </div>
              <div class="form-group">
                <label>
                  <strong>Address</strong><br>
                  {{ $admin['address'] }}
                </label>
              </div>
              <div class="form-group">
                <label>
                  <strong>About Me</strong><br>
                  <p class="text-capitalize"> {{ $admin['about'] }}</p>
                </label>
              </div>
              <a href="{{ url('admin/editprofile') }}" class="btn btn-primary btn-cons m-t-10" type="submit">Edit</a>
              <a href="{{ url('admin/changepassword') }}" class="btn btn-info btn-cons m-t-10" type="submit">Change Password</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection