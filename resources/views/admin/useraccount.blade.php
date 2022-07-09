@extends('admin.layout.main')

@section('content')

<style>
         #body{
            padding: 30px
            }

            .select2-results__options[aria-multiselectable="true"] li {
               padding-left: 30px;
               position: relative
            }

            .select2-results__options[aria-multiselectable="true"] li:before {
               position: absolute;
               left: 8px;
               opacity: .6;
               top: 6px;
               font-family: "FontAwesome";
               content: "\f0c8";
            }
            #mytable tr th{
               white-space: nowrap;
            }
            #mytable tr td{
               white-space: nowrap;
            }

            .select2-results__options[aria-multiselectable="true"] li[aria-selected="true"]:before {
               content: "\f14a";
            }


            .zoom {
                  padding: 50px;
                  transition: transform .2s; /* Animation */
                  width: 200px;
                  height: 200px;
                  margin: 0 auto;
               }

               .zoom:hover {
                  transform: scale(2.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
               }

</style>

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
               @if(session()->has('success'))
               <div class="col-sm-3 alert alert-success alert-dismissible m-t-15 m-b-15">
                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                 <strong></strong> {{  session()->get('success') }}
               </div>
               @endif
         <div class="row  m-b-15"></div>
<div class="row">
	<div class="col-lg-12">
		<section class="wrapper1">
			  <ul class="tabs">
				<li id="first" onclick="changeUrl(1)"><a href="#tab1" >Verified</a></li>
				<li id="second" onclick="changeUrl(2)"><a href="#tab2" >Unverified</a></li>
			  </ul>
			  <div class="clr"></div>
			  <section class="block">
				<article id="tab1">
				  <select name="fabric_color_en[]" id="fabric_color_en1[]" multiple="multiple" class="form-control select2">
					
						<option selected value="1">Name</option>
						<option selected value="2">Contact</option>
						<option selected value="3">Service type</option>
						<option selected value="4">Date of joining</option>
						<option selected value="5">Address</option>
						<option selected value="6">Status</option>
						<option selected value="8">Details</option>
						<option selected value="9">Block</option>
						<option selected value="10">Delete</option>
				  </select>
				<div class="table-responsive">
					<table id="mytable" class="table table-striped table-bordered">
					  <thead>
						<!-- <th class="no-sort">
						  <label class="checkbox">
							<input type="checkbox" id="checkall" /> 
							<span class="text-label"></span>     
						  </label>
						</th> -->
						<tr>
					
						<th>Personal ID</th>
						<th>Name</th>
						<th>Mobile no.</th>
						<th>Service type</th>
						<th>Date of joining</th>
						<th class="mwpx-170">Address</th>
						<th>Subscription</th>
					
						<th>Details</th>
						<th>GPS</th>
						<th>Pax</th>
						<th>Vehicle type</th>
						<th>CC</th>
						<!--<th>Total Rides</th>-->
					
						<th>Edit</th>
						<th>Block</th>
						<th>Delete</th>
					 </tr>
					  </thead>
				  <tbody>
              <tbody>
					 @foreach($verifiedusers as $i => $user)
					   <tr>
						 
						  <td>{{ date('m').date('d').substr($user['mobile_no'], -4) }}</td>
						  <td>{{ $user['name'] }}</td>
						  <td>{{ $user['country_code'] }}{{ $user['mobile_no'] }}</td>
						  <td>{{ $user['service_type'] }}</td>
						
						  
						  <td>{{ date('Y-m-d',strtotime($user['created_at'])) }}</td>
						  <td class="mwpx-170">{{ $user['address'] }}</td>
						  <td class="inactive"> @if($user['subscribed'] ==1)<span class="text-success">Subscribed</span> 
						  @else <span class="text-danger">
						  Not subscribed </span>@endif
						  <br/>
						  @if($user['my_subscription']) 
						  Plan Name : <?php echo wordwrap($user['my_subscription']['plan_name'],20,"<br>\n");?>
						  <br/> Plan Price : {{$user['my_subscription']['plan_price']}} <br/> 
						  @if($user['my_subscription']['type']==1)
						  Expire on :{{date('Y-m-d',$user['my_subscription']['expire_on'])}}
						  @else
						  Remaining Days : {{$user['my_subscription']['remaining_days']}}
						  @endif
						  
						  @endif
							</br> <span class="text-success plantype" data-plantype="" style="cursor:pointer;">Extend Days </span>
						  
						  </td>
						
						
						  <td class="inactive"> <p data-placement="top" data-toggle="tooltip" title="Details"><button onclick="view_details({{$user['id']}})" class="btn btn-success btn-xs" data-title="details"><span class="fa fa-eye"></span></button></p></td>
						  <td class="inactive text-center"><button class="btn btn-info btn-xs " onclick="seelocation('{{ 'user'.$user['id'] }}')" title="see location"><i class="fa fa-map-marker"></i></button></td>
						  <td>{{ $user['passengers'] }}</td>
						  <td>{{ $user['vehicle_type'] }}</td>
						  <td>{{ ($user['accept_credit_card'])?'Yes':'No' }}</td>
						 
					 
							 <td>
                      <p data-placement="top" data-toggle="tooltip" title="edit"><button   onclick="edit({{$user['id']}});" class="btn btn-danger btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p>
						  </td>
						  <td>
						
							 @if($user['status']==1)
								
								 <p data-placement="top" data-toggle="tooltip" title="unblock"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-success btn-xs" data-title="block" data-toggle="modal" data-target="#unblock" ><span class="fa fa-ban"></span></button></p>
							 @else
							   <p data-placement="top" data-toggle="tooltip" title="block"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-danger btn-xs" data-title="block" data-toggle="modal" data-target="#block" ><span class="fa fa-ban"></span></button></p>
							 @endif
						  </td>
						  <td>
							 <p data-placement="top" data-toggle="tooltip" title="Delete"><button onclick="set_delete_id({{ $user['id'] }})" class="btn btn-danger btn-xs"  ><span class="fa fa-trash"></span></button></p>
						  </td>
					   </tr>
					   @endforeach
					</tbody>
					</tbody>
				 </table>   
				</div>
			  </article>
			<article id="tab2">
			<select name="fabric_color_en[]" id="fabric_color_en[]" multiple="multiple" class="form-control select22">
			 
			  <option selected value="1">Name</option>
			  <option selected value="2">Contact</option>
			  <option selected value="3">Service type</option>
			  <option selected value="4">Date of joining</option>
			  <option selected value="5">Address</option>
			  <option selected value="6">Status</option>
			  <option selected value="8">Details</option>
			  <option selected value="9">Block</option>
			  <option selected value="10">Delete</option>
		   </select>
			<div class="table-responsive">
					  <table id="mytable1" class="table table-striped table-bordered">
						 <thead>
						 <tr>
						
						 <th>Personal ID</th>
						 <th>Name</th>
						 <th>Mobile no.</th>
						 <th>Date of joining</th>
						 <th>Address</th>
					
						 <th>Details</th>
						 <!-- <th>Send reason</th> -->
						
						 <th>Pax</th>
						 <th>Vehicle type</th>
						 <th>CC</th>
						 <th>Edit</th>
						 <th>Block</th>
						 <th>Delete</th>
					  </tr>
						 </thead>
						 <tbody>
							@foreach($unverifiedappuser as $i => $user)
							<tr>
							  
							   <td>{{ date('m').date('d').substr($user['mobile_no'], -4) }}</td>
							   <td>{{ $user['name'] }}</td>
							   <td>{{ $user['country_code'] }}{{ $user['mobile_no'] }}</td>
							   <td>{{ date('Y-m-d',strtotime($user['created_at'])) }}</td>
							   <td class="mwpx-170">{{ $user['address'] }} </td>
							
							   <td class="inactive"> 
							   @if($user['id_proof'] && $user['vehicle_registration_certificate'])
							   <p data-placement="top" data-toggle="tooltip" title="Details"><button onclick="view_details({{$user['id']}})" class="btn btn-success btn-xs" data-title="details"><span class="fa fa-eye"></span></button></p>
							   @else
							   Vehicle Details not added
							   @endif
							   </td>
							   <!-- <td  class="text-center">
								  <p data-placement="top" data-toggle="tooltip" >
									 <span class="d-flex">
										<button title="Send reason to user" onclick="set_reason_id({{ $user['id'] }})" class="btn btn-success btn-xs" data-title="verify" data-toggle="modal" data-target="#reason_modal" ><span class="fa fa-comments"></span></button>
									 </span>
								  </p>
							   </td> -->
							 

							   <td>{{ $user['passengers'] }}</td>
							   <td>{{ $user['vehicle_type'] }}</td>
							   <td>{{ ($user['accept_credit_card'])?'Yes':'No' }}</td>

							   <td>
								  <p data-placement="top" data-toggle="tooltip"  title="edit"><button  onclick="edit({{$user['id']}});" class="btn btn-danger btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p>
							   </td>
							   <td>
								  <!-- <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p> -->
								 @if($user['status']==1)                                    
									 <p data-placement="top" data-toggle="tooltip" title="unblock"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-success btn-xs" data-title="block" data-toggle="modal" data-target="#unblock" ><span class="fa fa-ban"></span></button></p>
								 @else
								   <p data-placement="top" data-toggle="tooltip" title="block"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-danger btn-xs" data-title="block" data-toggle="modal" data-target="#block" ><span class="fa fa-ban"></span></button></p>
								 @endif
							  </td>
							  <td>
								 <p data-placement="top" data-toggle="tooltip" title="Delete"><button onclick="set_delete_id({{ $user['id'] }})" class="btn btn-danger btn-xs"  ><span class="fa fa-trash"></span></button></p>
							  </td>
							</tr>
							@endforeach
						 </tbody>
					  </table>   
				</div>
			</article>
		</section>
	</section>
	</div>
</div>
           
<div class="row">
   <div class="col-md-12">
	  
   </div>
</div>
<div class="modal fade" id="addMember" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
   <div class="modal-dialog">
	  <div class="modal-content model-border">
		 <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times" aria-hidden="true"></span></button>
			<h4 class="modal-title custom_align" id="Heading">Add your Member</h4>
		 </div>
		 <div class="modal-body">
			<div class="form-group">
			   <label>First Name</label>
			   <input class="form-control " type="text" placeholder="Mohsin">
			</div>
			<div class="form-group">
			   <label>Last Name</label>
			   <input class="form-control " type="text" placeholder="Irshad">
			</div>
			<div class="form-group">
			 <label>Gender</label>
			 <div class="radio">
			 <label><input type="radio" name="gender" value="male" checked="">Male</label>
			 <label><input type="radio" name="gender" value="female">Female</label>
			 </div>
			</div>
			<div class="form-group">
			 <label>Email:</label> 
			   <input type="email" class="form-control" id="email" placeholder="Enter email">
			</div>
			<div class="form-group">
			   <label>Description</label>
			   <textarea rows="2" class="form-control" placeholder="CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan"></textarea>
			</div>
		 </div>
		 <div class="modal-footer ">
			<button type="button" class="btn btn-warning btn-lg" style="width: 100%;"><span class="fa fa-check-circle"></span> Update</button>
		 </div>
	  </div>
	  <!-- /.modal-content --> 
   </div>
   <!-- /.modal-dialog --> 
</div>
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
   <div class="modal-dialog">
	  <div class="modal-content model-border">
		 <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times" aria-hidden="true"></span></button>
			<h4 class="modal-title custom_align" id="Heading">Edit Driver</h4>
		 </div>
		 <form action="{{ url('admin/updatedriver') }}" method="post">
		 <div class="modal-body">
	
			   {{ csrf_field() }}
			   <input type="hidden" id="edit_id" name="id">
			<div class="form-group">
			   <label>Name</label>
			   <input class="form-control" id="edit_name" name="name" required type="text" placeholder="Name">
			</div>
			<!-- <div class="form-group">
			   <label>Mobile no</label>
			   <input class="form-control" name="mobile" id="edit_mobile" required type="text" placeholder="Mobile no">
			</div> -->

			<div class="form-group">
			   <label>Organization name</label>
			  <input type="text" class="form-control" name="organizationtype" id="edit_organization" required >
			</div>


			<div class="form-group">
			   <label>Passengers</label>
			  <input type="text" class="form-control" name="passengers" id="edit_passengers" required >
			</div>


			<div class="form-group">
			   <label>Accept credit card</label>
			  <!-- <input type="text" class="form-control" name="organizationtype" id="edit_organization" required > -->
			  <select class="form-control" name="accept_creditcard" id="edit_accept_creditcard">
			   <option value="0">No</option>
			   <option value="1">Yes</option>
			  </select>
			</div>

			<div class="form-group">
				  <label>Service type</label>
				  <select class="form-control" name="servicetype" id="edit_service">
					 @foreach($servicetype as $service)

					 <option value="{{ $service['name'] }}" {{(old('servicetype')==$service['name'])? 'selected':''}}>{{ $service['name'] }}</option>
					 @endforeach
				  </select>
			   </div>
			
		 </div>
		 <div class="modal-footer ">
			<button type="submit" class="btn btn-success btn-lg"  style="width: 100%;"><span class="fa fa-check-circle"></span> Update</button>
		 </div>
	  </form>
	  </div>
	  <!-- /.modal-content --> 
   </div>
   <!-- /.modal-dialog --> 
</div>

<div class="modal fade" id="view_details" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
	 <div class="modal-content model-border">
		<div class="modal-header">
		   <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times" aria-hidden="true"></span></button>
		   <h4 class="modal-title custom_align" id="Heading">Driver Detail</h4>
		</div>
		<div class="modal-body">

		<div class="form-group">
			  <label>image</label>
			  <img src="{{ asset('assets/img/logo_2x.png') }}" class="zoom" id="view_vehicle_image" width="60">                           
		</div>
				 
		   <!-- <div class="form-group">
			  <label>Organization type</label>
			   <p id="view_organization_type">demo name<p>
		   </div> -->
		   <div class="form-group">
			  <label>Vehicle type</label>
			  <p id="view_vehicle_type">demo name<p>
		   </div>
		   <div class="form-group">
			  <label>Vehicle number</label>
			  <p id="view_vehicle_number">demo name<p>
		   </div>


		   <div class="form-group">
				 <label>Vehicle Image</label>
             <!-- <a download="custom-filename.jpg" href="{{url('/files')}}/689481615619658.jpg" title="ImageName">
    <img alt="ImageName" src="{{url('/files')}}/689481615619658.jpg">
</a> -->
<a href="{{url('/files')}}/689481615619658.jpg" download>
  <img src="{{url('/files')}}/689481615619658.jpg" alt="W3Schools" width="104" height="142">
</a>
				 <!-- <p id="view_vehicle_number">demo name<p> -->
				 <a href="javascript:void(0)"  id="vehicle_image_tag" download="custom-filename.jpg"><i class="fa fa-download"></i></a>
              &nbsp; 
              <a class="vehicle_image_preview_tag view_preview" ><i class="fa fa-eye"></i></a>
				 &nbsp;
				 
		   </div>
		   
		   
		   <div class="form-group">
				 <label>ID Proof</label>
				 <!-- <p id="view_vehicle_number">demo name<p> -->
				 <a href="javascript:void(0)"   id="id_proof_tag" download><i class="fa fa-download"></i></a> &nbsp; <a class="id_proof_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
				 &nbsp;
				
				  <p class="id_proof"></p>
				  <p id="unverifyDiv1"></p>
		   </div>

		   <div class="form-group">
				 <label>Driving license</label>
				 <!-- <p id="view_vehicle_number">demo name<p> -->
				 <a href="javascript:void(0)"  id="driving_license_tag" download><i class="fa fa-download"></i></a> &nbsp; <a class="driving_license_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
				 &nbsp;
				
				 <p class="driving_license"></p>
				 <p id="unverifyDiv2"></p>
		   </div>

		   <div class="form-group">
				 <label>Vehicle registration certificate</label>
				 <!-- <p id="view_vehicle_number">demo name<p> -->
				 <a href="javascript:void(0)" download id="vehicle_registration_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="vehicle_registration_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
				 &nbsp;
				
				 <p class="vehicle_registration"></p>
				 <p id="unverifyDiv3"></p>
		   </div>

		   <div class="form-group">
				 <label>Taximeter certificate</label>
				 <!-- <p id="view_vehicle_number">demo name<p> -->
				 <a href="javascript:void(0)" download id="teximeter_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="teximeter_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
				 &nbsp;
			 
				 <p class="teximeter"></p>
				 <p id="unverifyDiv4"></p>
				
		   </div>

		   <!-- <div class="form-group">
				 <label>Description</label>
				 <p  id="view_vehicle_number">demo name<p>
		   </div> -->
		  
				 
		</div>
		
	 </div>
	 <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>


<div id="delete" tabindex="-1" role="dialog" class="modal fade">
<div class="modal-dialog modal-sm" data-dismiss="modal">
   <div class="modal-content model-border">
	  <div class="modal-body">
		 <div class="text-center">
			<h4 style="color: #f28f22;">Do you want to delete this driver</h4>
			<div class="m-t-lg">
			   <button class="btn btn-danger"  type="button" onclick="confirm_delete()" >Continue</button>
			   <button class="btn btn-success" data-dismiss="modal" type="button">Cancel</button>
			</div>
		 </div>
	  </div>
   </div>
</div>
</div>

<div id="block" tabindex="-1" role="dialog" class="modal fade">
	  <div class="modal-dialog modal-sm" data-dismiss="modal">
		 <div class="modal-content model-border">
			<div class="modal-body">
			   <div class="text-center">
				  <h4 style="color: #f28f22;">Do you want to block this driver</h4>
				  <div class="m-t-lg">
					 <button class="btn btn-danger" onclick="confirm_block()" data-dismiss="modal" type="button" >Continue</button>
					 <button class="btn btn-success" data-dismiss="modal" type="button">Cancel</button>
				  </div>
			   </div>
			</div>
		 </div>
	  </div>
</div>


<div id="unblock" tabindex="-1" role="dialog" class="modal fade">
 <div class="modal-dialog modal-sm" data-dismiss="modal">
	<div class="modal-content model-border">
	   <div class="modal-body">
		  <div class="text-center">
			 <h4 style="color: #f28f22;">Do you want to Unblock this driver</h4>
			 <div class="m-t-lg">
				<button class="btn btn-danger" onclick="confirm_block()" data-dismiss="modal" type="button" >Continue</button>
				<button class="btn btn-success" data-dismiss="modal" type="button">Cancel</button>
			 </div>
		  </div>
	   </div>
	</div>
 </div>
 </div>
                     


 <div id="verify_document" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-sm" data-dismiss="modal">
	   <div class="modal-content model-border">
		  <div class="modal-body">
			 <div class="text-center">
				<h4 style="color: #f28f22;">Do you want to verify this driver</h4>
				<div class="m-t-lg">
				   <button class="btn btn-danger" onclick="confirm_verify()" data-dismiss="modal" type="button" >Continue</button>
				   <button class="btn btn-success" data-dismiss="modal" type="button">Cancel</button>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
	</div>
	
<div id="common_msg_modal" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-sm" data-dismiss="modal">
	   <div class="modal-content model-border">
		  <div class="modal-body">
			 <div class="text-center">
				<h4 style="color: #f28f22;" id="common_msg"></h4>
				<div class="m-t-lg">
				   <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
	



                     <!-- <div id="showDiv" tabindex="-1" role="dialog" class="modal fade">
                        <div class="modal-dialog modal-sm" data-dismiss="modal">
                           <div class="modal-content model-border">
                              <div class="modal-body">
                                 <iframe id="iframe" src="javascript:void(0)"></iframe>
                              </div>
                           </div>
                        </div>
                        </div> -->


         </div>
      </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalLong">
  <div class="modal-dialog modal-sm" data-dismiss="modal">
    <div class="modal-content">      
      <div class="modal-body">
        <h4 class="text-warning">Member added Successfully</h4>
        <div class="successful-icon">
         <i class="fa fa-check-square-o" aria-hidden="true" style="font-size: 22px;color: #17b517;"></i>
      </div>
      </div>
      
    </div>
  </div>
</div>
<!-- Modal END-->




<!-- Modal -->
<div class="modal fade" id="reason_modal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">      
          <div class="modal-body">
            <h4 class="text-warning">Send reason to driver</h4>
            <form method="post" action="{{ url('admin/send_reason_to_driver') }}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label>Reason</label>
                  <input type="hidden" name="id" id="reason_user_id">
                  <textarea class="form-control" rows="8" name="reason">
                  We have checked your application and there the following items are missing or incomplete
                  1
                  2
                  3
                  Please correct your documentation and send it again
                  </textarea>
               </div>
               <div class="form-group">
                  <input type="submit" class="btn btn-success" value="send"> 
               </div>
            </form>
          </div>
          
        </div>
      </div>
</div>
<!-- Modal END-->




<!-- Location Modal -->
<div class="modal fade" id="location_modal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">      
          <div class="modal-body">
            <h4 class="text-warning">Location</h4>
            <!-- <div id="over_map" style=" position: absolute;top: 10px;left: 89%;z-index: 99;background-color: #ccffcc;padding: 10px;">
         
            </div> -->

            <div id="map" style=" height:500px;"></div>
          </div>
          
        </div>
      </div>
</div>
<!-- Location Modal END-->




<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js"></script>
<script>
$(document).ready(function(){
	
	$('.plantype').on('click',function(){
		$('#updatePlanModal').modal('show');
		$('#planTypeTxt').html('');
		var result = $(this).data("plantype").split('-');
		var plan_duration = result[2];
		var free_plan_type = result[3];
		$('#plan_current_duration').val(plan_duration);
		$('#plan_type_and_did').val($(this).data("plantype"));
		//$('#did').val(did);
		if(free_plan_type==1){
				
				var Txt = '<span class="text-success">By Days</span>';
		}else{
				var Txt = '<span class="text-success">By Days</span>';
		}
		
		$('#planTypeTxt').append(Txt);
		
		
	});
});

function update_free_service (){
	var plan_duration = document.getElementById('plan_current_duration').value;
	var plan_type_and_did = document.getElementById('plan_type_and_did').value;
	
	var reqData = plan_type_and_did.split('-');
	var sub_id = reqData[0];
	var user_id = reqData[1];
	var free_plan_type = reqData[3];
		if(user_id!='' && plan_duration!=''){
			$.ajax({
				type: "POST",
				dataType: "JSON",
				url: "{{ URL::to('/admin/update-free-plan/') }}",
				data: {'plan_duration': plan_duration,'sub_id':sub_id,'free_plan_type' :free_plan_type,'user_id':user_id, "_token": "{{ csrf_token() }}"},
				success: function (data) {
				   if(data.status!=1){
					   
					   $('#common_msg').text('Plan updated successfully');
					   $('#common_msg_modal').modal('show');
					   //alert('Plan updated successfully!');
					   $('#updatePlanModal').modal('hide');
				   }else{
					   alert('Something went wrong');
				   }
				}
			});
		}
	}
</script>
<script>
   var detaildriver_id = 0;
   var old_value = 0;
   var delete_id = 0;
   var block_id = 0;
   var table = 0;
   var verify_id = 0;
   var changeData = 0;
$('document').ready(function(){
  changeData = "{{ $chnageId }}";
  //alert(changeData);
  if(changeData==2){
    $("#first").removeClass("active");
    $("#second").addClass("active");
    $("#tab2").css('display','block');
    $("#tab1").css('display','none');
  }
  else if(changeData==1){
    $("#first").addClass("active");
    $("#second").removeClass("active");
    $("#tab1").css('display','block');
    $("#tab2").css('display','none');
  }
   table =  $('#mytable').DataTable({
    info:false,
	//aaSorting: [[]],
	order: [],
    columnDefs: [
      //{ targets: 'no-sort', orderable: false }
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


$('document').ready(function(){
  
  table2 =  $('#mytable1').DataTable({
   info:false,
   //aaSorting: [[-1, 'desc']],
   order: [],
   columnDefs: [
     { targets: 'no-sort', orderable: false }
   ]
 });
  $("#mytable1 #checkall").click(function () {
    if($(this).is(':checked')) {
        $("#mytable1 td input[type=checkbox]").prop("checked", true);
    }else {
        $("#mytable1 td input[type=checkbox]").prop("checked", false);
    }
  });

  $("[data-toggle=tooltip]").tooltip();
});  

function changeUrl(type){
  window.location.href = "{{url('admin/user_management')}}"+"/"+type;
}


function confirm_delete(){
   window.location.href="{{ url('admin/deleteuser') }}/"+delete_id;
}

function confirm_block(){
   window.location.href="{{ url('admin/blockuser') }}/"+block_id;
}

function set_block_id(id){
   block_id = id;
  // alert(id);
}

function set_delete_id(id){
   delete_id = id;
   $("#delete").modal('show');
}


function verify_proof(type,status){
	
  //alert(type);
  if(status==1)
   {
     $.ajax({
        url : "{{ url('admin/verify_document') }}/"+type,
        type:"post",
        data : {"driver_id":detaildriver_id,"_token":"{{ csrf_token() }}"},
        dataType:'json',
        success:function(json){
           if(json.verified_user){
              window.location.href = "{{ url('admin/user_management') }}/1";
           }else{
					  $('#common_msg').text(json.message);
					   $('#common_msg_modal').modal('show');
                //alert(json.message);
                  if(type==1){
                      $(".id_proof").html(`<span class="text-success">verified</span>`);
                   }else if(type==2){
                      $(".driving_license").html(`<span class="text-success">verified</span>`);
                   }else if(type==3){
                     var str = `<span class="text-success">verified</span>`;
                      $(".vehicle_registration").html(str);            
                   }else if(type==4){
                      $(".teximeter").html(`<span class="text-success">verified</span>`);
                   }
              
             // window.location.href = "{{ url('admin/user_management') }}/1";
           }           
        }
     })
   }
   else{
      var str = `<input type="hidden" id="vdetaildriver_id" value="`+detaildriver_id+`"></input><input id="v_type" type="hidden" value="`+type+`"></input><br/>
      <label>Unverify Document Reason</label>
      <textarea id="vmessage" class="form-control"></textarea><br/>
      <button class="btn btn-danger btn-xs" type="button" onclick="submitReason()">Submit</button>`;
      $("#unverifyDiv"+type).html(str);
   }
}

function submitReason(){
  var v_driverId = $("#vdetaildriver_id").val();
  var v_typeId = $("#v_type").val();
  var message = $("#vmessage").val();
  if(message.length>0){
    $.ajax({
        url : "{{ url('admin/unverify_document') }}",
        type:"POST",
        data : {"driver_id":v_driverId,"type":v_typeId,"message":message,"_token":"{{ csrf_token() }}"},
        dataType:'json',
        success:function(json){
          $("#unverifyDiv"+v_typeId).html("");
		  $('#common_msg').text(json.message);
		  $('#common_msg_modal').modal('show');
          //alert(json.message);
              if(v_typeId==1){
                  $(".id_proof").html(`<span class="text-danger">un-verified</span>`);
               }else if(v_typeId==2){
                  $(".driving_license").html(`<span class="text-danger">un-verified</span>`);
               }else if(v_typeId==3){
                 var str = `<span class="text-danger">un-verified</span>`;
                  $(".vehicle_registration").html(str);            
               }else if(v_typeId==4){
                  $(".teximeter").html(`<span class="text-danger">un-verified</span>`);
               }    
         // window.location.href = "{{ url('admin/user_management') }}/1";     
        }
     })
  }
  else{
    alert("Please submit reason.");
  }
}

function view_details(id){
   detaildriver_id = id;
   $("#unverifyDiv1").html("");
   $("#unverifyDiv2").html("");
   $("#unverifyDiv3").html("");
   $("#unverifyDiv4").html("");
debugger;
   $.ajax({
      url : '{{ url('admin/userdetails') }}/'+detaildriver_id,
      type:'get',
      dataType:'json',
      success:function(json){
        json = json.appuser;
         $("#view_organization_type").text(json.organizationtype);
         $("#view_vehicle_type").text(json.vehicle_type);
         $("#view_vehicle_number").text(json.vehicle_number);
         $("#vehicle_image_tag").attr('href',"{{url('/files')}}/"+json.vehicle_image);
         
         $("#id_proof_tag").attr('download',true);

         $("#id_proof_tag").attr('href',"{{url('/files')}}/"+json.id_proof);
         $("#driving_license_tag").attr('href',"{{url('/files')}}/"+json.driving_license);
         $("#vehicle_registration_tag").attr('href',"{{url('/files')}}/"+json.vehicle_registration_certificate);
         $("#teximeter_tag").attr('href',"{{url('/files')}}/"+json.taximeter_certificate);
         

         $(".vehicle_image_preview_tag").attr('href',"{{url('/files')}}/"+json.vehicle_image);
         $(".id_proof_preview_tag").attr('href',"{{url('/files')}}//"+json.id_proof);
         $(".driving_license_preview_tag").attr('href',"{{url('/files')}}/"+json.driving_license);
         $(".vehicle_registration_preview_tag").attr('href',"{{url('/files')}}/"+json.vehicle_registration_certificate);
         $(".teximeter_preview_tag").attr('href',"{{url('/files')}}/"+json.taximeter_certificate);

         if(json.id_proof_verified==0){
          var str = `<button class="btn btn-success btn-xs" onclick="verify_proof(1,1)" type="button" >Verify</button> &nbsp;<button class="btn btn-danger btn-xs" onclick="verify_proof(1,0)"  type="button" >Unverify</button>`;
          $(".id_proof").html(str);
         }else if(json.id_proof_verified==1){
          var str = `<span class="text-success">Verified</span> | <button class="btn btn-success btn-xs" onclick="verify_proof(1,2)" type="button" >Re-verification</button>`;
          $(".id_proof").html(str);
         }else{
          var str = `<span class="text-danger">Unverified</span>`;
          $(".id_proof").html(str);
         }

        if(json.driving_license_verified==0){
          var str = `<button class="btn btn-success btn-xs" onclick="verify_proof(2,1)"  type="button" >Verify</button> &nbsp;<button class="btn btn-danger btn-xs" onclick="verify_proof(2,0)"  type="button" >Unverify</button>`;
          $(".driving_license").html(str);
         }else if(json.driving_license_verified==1){
          var str = `<span class="text-success">Verified</span> | <button class="btn btn-success btn-xs" onclick="verify_proof(2,2)" type="button" >Re-verification</button>`;
          $(".driving_license").html(str);
         }else{
          var str = `<span class="text-danger">Unverified</span>`;
          $(".driving_license").html(str);
         }

         if(json.vehicle_registration_certification_verified==0){
          var str = `<button class="btn btn-success btn-xs" onclick="verify_proof(3,1)" type="button" >Verify</button> &nbsp;<button class="btn btn-danger btn-xs" onclick="verify_proof(3,0)" type="button" >Unverify</button>`;
          $(".vehicle_registration").html(str);
         }else if(json.vehicle_registration_certification_verified==1){
          var str = `<span class="text-success">Verified</span> | <button class="btn btn-success btn-xs" onclick="verify_proof(3,2)" type="button" >Re-verification</button>`;
          $(".vehicle_registration").html(str);
         }else{
          var str = `<span class="text-danger">Unverified</span>`;
          $(".vehicle_registration").html(str);
         }

         if(json.taximeter_certificate_verified==0){
          var str = `<button class="btn btn-success btn-xs" onclick="verify_proof(4,1)" type="button" >Verify</button> &nbsp;<button class="btn btn-danger btn-xs" onclick="verify_proof(4,0)" type="button" >Unverify</button>`;
          $(".teximeter").html(str);
         }else if(json.taximeter_certificate_verified==1){
          var str = `<span class="text-success">Verified</span> | <button class="btn btn-success btn-xs" onclick="verify_proof(4,2)" type="button" >Re-verification</button>`;
          $(".teximeter").html(str);
         }else{
          var str = `<span class="text-danger">Unverified</span>`;
          $(".teximeter").html(str);
         }

         if(json.profile_pic){
            $("#view_vehicle_image").attr('src',"{{ asset('public/files') }}/"+json.profile_pic);
            $("#vehicle_image_tag").attr('download',true);

         }else{
            $("#view_vehicle_image").attr('src',"{{ asset('assets/img/logo_2x.png') }}");
         }
      }
    });
   
   $("#view_details").modal('show');
}

function edit(id,name,mobileno,organizationtype,vehicletype,vehicle_number,servicetype,passenger,accept_creditcard){

   $("#edit").modal('show');
   $.ajax({
    type: "GET",
   dataType: "JSON",
  url: "{!! url('admin/edit_driver' ) !!}" + "/" + id,

//    // data: {'id': id, "_token": "{{ csrf_token() }}"},
   success: function (data) {

      console.log(data.data.id);
   $("#edit_id").val(id);
   $("#edit_name").val(data.data.name);
   $("#edit_mobile").val(data.data.mobileno);
   $("#edit_organization").val(data.data.organization_type);
   $("#edit_service").val(data.data.sservice_type);
   $("#edit_passengers").val(data.data.passengers);
   $("#edit_accept_creditcard").val(data.data.accept_credit_card);
   }
});
       
      }
function editAdvisery(id){

//alert(id);
//  $("#UpdateForm").attr('action', "{{url('addAdvertise.update')}}/4");
 $("#edit").modal('show');
 $.ajax({
    type: "GET",
   dataType: "JSON",
  url: "{!! url('offer_management' ) !!}" + "/" + id,
//    // data: {'id': id, "_token": "{{ csrf_token() }}"},
   success: function (data) {

      // $("#UpdateForm").attr('action',"{{url('category.update')}}/"+data.id);
      $("#UpdateForm").attr('action', "{{url('offer_management')}}/" + data.id);
        
      

         }

        });
       
}
$('.select2[multiple]').select2({
    width: '100%',
    closeOnSelect: false
})

$('.select2[multiple]').on('select2:close', function (e)
{

   var arraylist = [0,1,2,3,4,5,6,7,8,9,10];
   
   if($('.select2[multiple]').val()){
         var values = $('.select2[multiple]').val();
         for(i=0;i<arraylist.length;i++){
            for(i=0;i<11;i++){
               var column = table.column( i);
               column.visible(0);
            }
         }
         for (var key of Object.keys(values)) {
            var column = table.column(values[key]);
            column.visible(1);
         }
         

         // foreach
         
      }
   old_value = $('.select2[multiple]').val();
});



$('.select22[multiple]').select2({
    width: '100%',
    closeOnSelect: false
})

$('.select22[multiple]').on('select2:close', function (e)
{

   var arraylist = [0,1,2,3,4,5,6,7,8,9,10];
   
   if($('.select22[multiple]').val()){
         var values = $('.select22[multiple]').val();
         for(i=0;i<arraylist.length;i++){
            for(i=0;i<11;i++){
               var column = table2.column( i);
               column.visible(0);
            }
         }
         for (var key of Object.keys(values)) {
            var column = table2.column(values[key]);
            column.visible(1);
         }
         

         // foreach
         
      }
   old_value = $('.select2[multiple]').val();
});




// $('.clickme').click( function(){
//    //  $('#showDiv').show();
//    //  $('#iframe').attr('src','your url');
//    window.open('http://example.com');
// });


$(".view_preview").click(function(){
   var src = $(this).attr('href');
   window.open(src,'newwindow','width=300,height=250'); 
   return false;

});

$(".verified_status").change(function(){
   window.location.href="{{ url('admin/user_management') }}/"+$(this).val();
});

function set_verify_id(id){
   verify_id = id;
}

function confirm_verify(){
   window.location.href="{{ url('admin/verify_driver') }}/"+verify_id;
}

function set_reason_id(id){
   $("#reason_user_id").val(id);
}




            // Replace your Configuration here..
            var config = {
               apiKey: "AIzaSyAzeG2PmaXS8ak4yV6m_7OIkExaq3IV6lM",
               authDomain: "emergency-taxi.firebaseapp.com",
               databaseURL: "https://emergency-taxi.firebaseio.com",
               projectId: "emergency-taxi",
               storageBucket: "emergency-taxi.appspot.com",
               messagingSenderId: "868787528086",
               appId: "1:868787528086:web:ec952293dafe31344c3db7",
               measurementId: "G-64ML7CXFPL"
            };
            firebase.initializeApp(config);



            

function seelocation(id){
   


   var cars_Ref = firebase.database().ref('/'+id);
   cars_Ref.on('value', function (snapshot) {
      data = snapshot.val();
      console.log(data);
      if(data){
         $("#location_modal").modal('show');
         initMap(data);   
      }else{
         $("#location_modal").modal('hide');
         alert("No user location not found");
      }
      }); 
}

   var map;
   function initMap(data) { // Google Map Initialization... 
          map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: new google.maps.LatLng(data.lat,data.lng),
          mapTypeId: 'terrain'
      });

      AddCar(data);
   }


   function AddCar(data) {

      var markers;

      let base_url = "{{ url('public/files') }}/";

      var icon = {
                  url: base_url+"marker.jpg", // url
                  scaledSize: new google.maps.Size(25, 25), // scaled size
                  origin: new google.maps.Point(0,0), // origin
                  anchor: new google.maps.Point(0, 0) // anchor
                };

      var uluru = { lat: data.lat, lng: data.lng };

      if(data.type == "ambulance"){
      var iconimage = "{{  asset('assets/img/icons8-ambulance-16-.ico') }}";
      }else if(data.type == "individual"){
      var iconimage = "{{  asset('assets/img/icons8-car-16.ico') }}";
      }else if(data.type == "taxi"){
      var iconimage = "{{  asset('assets/img/icons8-taxi-16.ico') }}";
      }

      var marker = new google.maps.Marker({
         position: uluru,
         icon: icon,
         map: map
      });

      markers[data.key] = marker; // add marker in the markers array...
      document.getElementById("cars").innerHTML = cars_count;

      google.maps.event.addListener(marker, 'click', function() {
         infowindow = new google.maps.InfoWindow({
         content: '<h3 onclick="driver_details('+data.id+')">Driver id : '+data.id+'</h3><p>latitude :'+data.lat+' </p><p>longitude :'+data.lng+' </p>'
      });
      infowindow.open(map, marker);
      });


      }

</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.11&sensor=false&key=AIzaSyC2BZWFkWCNsPnWJSpSxJrrWRpAqd2417M" type="text/javascript">
        </script>


<script>
//    $(function(){
//   $('ul.tabs li:first').addClass('active');
//   $('.block article').hide();
//   $('.block article:first').show();
//   $('ul.tabs li').on('click',function(){
//     $('ul.tabs li').removeClass('active');
//     $(this).addClass('active')
//     $('.block article').hide();
//     var activeTab = $(this).find('a').attr('href');
//     $(activeTab).show();
//     return false;
//   });
// })

   $('.service_type').change(function(){

      var user_id = $(this).closest('.form-group').find(".current_user").val();
      var service_type = $(this).val();
      // alert(service_type);
      if(service_type){
       // alert(0);
        $.ajax({
           url:"{{ url('admin/set_user_service_type') }}",
           type:'post',
           data : {'_token':'{{ csrf_token() }}','user_id':user_id,'service_type':service_type},
           success:function(){
              
           }
        })
      }else{
        alert("Please select service type.")
      }
   });  

   </script>
   
 <div class="modal fade" id="updatePlanModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="margin-top: 100px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Extend Free Plan Duration</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
         <div class="form-group-default">
			<div class="controls">
			<label for="user-email">Enter Duration</label>
          <input type="text" id="plan_current_duration" name="plan_current_duration" class="form-control validate">
          <input type="hidden" id="plan_type_and_did" name="plan_type_and_did">
          
		  <div id="planTypeTxt"></div>
         
        </div>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" onclick="update_free_service();">Update</button>
      </div>
	 
    </div>
  </div>
</div>   

@endsection
