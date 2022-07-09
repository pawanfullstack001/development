<form action="{{ url('admin/edit_subadmin') }}" enctype="multipart/form-data"  method="post">
          {{ csrf_field() }}

          <input type="hidden" id="edit_subadmin_id" name="admin_id" value="{{@$subadmin->id}}">


          {{--<div class="form-group">
            <label>Unique id <span class="text-danger">*<span></label>
            <input type="text" required id="edit_unique_id"   class="form-control restrict_length" name="unique_id" value="{{@$subadmin->username}}">
          </div>--}}

          
          <div class="form-group">
            <label>Username <span class="text-danger">*<span></label>
            <input type="text" required id="edit_username" class="form-control restrict_length" name="username" value="{{@$subadmin->username}}">
          </div>



          <div class="form-group">
                <label>Email <span class="text-danger">*<span></label>
                <input type="text" required id="edit_email" class="form-control restrict_length" name="email" value="{{@$subadmin->email}}">
          </div>

          <div class="form-group">
            <label>Password <span class="text-danger">*<span></label>
            <input type="text" required id="edit_password" class="form-control restrict_length" name="password" value="{{@$subadmin->plain_password}}">
         </div>

         <div class="form-group">
            <h4>Sub-Admin Permissions</h4>
            @foreach($permissions as $permission)
          
            <label>{{ucwords($permission->permission_title)}} &nbsp; <input type="checkbox" class="checkbox" value="{{$permission->id}}" name="permission_id[]" {{in_array($permission->id, $userPermissions) ? 'checked' : ''}} ></label>
            
            @endforeach
          </div>


         
          
          <div class="m-t-20 text-center">
            <button class="btn btn-success" type="submit">Submit</button>
          </div>
        </form>