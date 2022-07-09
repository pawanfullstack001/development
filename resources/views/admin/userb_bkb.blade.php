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
         <div class="row  m-b-15">
         
         
         
      </div>
<div class="row">
   <div class="col-lg-12">
   <section class="wrapper1">
  <ul class="tabs">
    <li><a href="#tab1" onclick="show_verified()">Verified</a></li>
    <li><a href="#tab2"  onclick="show_unverified()">Unverified</a></li>
  </ul>
  <div class="clr"></div>
  <section class="block">
    <article id="tab1">
      <select name="fabric_color_en[]" id="fabric_color_en1[]" multiple="multiple" class="form-control select2">
            <option selected value="0">S.no</option>
            <option selected value="1">Name</option>
            <option selected  value="2">Contact</option>
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
                        <th>S.no</th>
                        <th>Name</th>
                        <th>Mobile no.</th>
                        <th>Service type</th>
                        <th>Date of joining</th>
                        <th class="mwpx-170">Address</th>
                        <th>Subscription</th>
                        <th>Verified by</th>
                        <th>Details</th>
                        <th>See position</th>
                        <th>Pax</th>
                        <th>Vehicle type</th>
                        <th>CC</th>
                        <th>Edit</th>
                        <th>Block</th>
                        <th>Delete</th>
                     </tr>
                      </thead>
                      <tbody>
                         @foreach($verifiedusers as $i => $user)
                           <tr>
                              <td>
                                 <!-- <label class="checkbox">
                                 <input type="checkbox"/> 
                                 <span class="text-label"></span>     
                                 </label> -->
                                 {{ ++$i }}
                              </td>
                              <td>{{ $user['name'] }}</td>
                              <td>{{ $user['mobile_no'] }}</td>
                              <td>{{ $user['servicetype']['name'] }}</td>
                              <td>{{ date('Y-m-d',strtotime($user['created_at'])) }}</td>
                              <td class="mwpx-170">{{ $user['city']['name'] }} , {{ $user['country']['name']  }} </td>
                              <td class="inactive">{{ ($user['subscribed'])?'Subscribed':'Not subscribed' }}</td>
                              <td class="inactive">@if($user['document_verified']) {{ ($user['verified']["username"])? $user['verified']["username"]:'Admin' }} {{ date('Y-m-d H:i', $user['document_verified_at']) }} @endif</td>
                              <td class="inactive"> <p data-placement="top" data-toggle="tooltip" title="Details"><button onclick="view_details({{ $user['id']  }},'{{ $user['organizationtype']['name']  }}','{{ $user['vehicle_type']  }}','{{ $user['vehicle_number']  }}','{{ $user['vehicle_image']  }}','{{ $user['id_proof']  }}','{{ $user['driving_license']  }}','{{ $user['profile_pic']  }}','{{ $user['vehicle_registration_certificate']  }}','{{ $user['taximeter_certificate']  }}',{{ $user['id_proof_verified']  }},{{ $user['driving_license_verified']  }},{{ $user['vehicle_registration_certification_verified']  }},{{ $user['taximeter_certificate_verified']  }})" class="btn btn-success btn-xs" data-title="details"><span class="fa fa-eye"></span></button></p></td>
                              <td class="inactive text-center"><button class="btn btn-info btn-xs " onclick="seelocation('{{ 'user'.$user['id'] }}')" title="see location"><i class="fa fa-map-marker"></i></button></td>
                              <td>{{ $user['passengers'] }}</td>
                              <td>{{ $user['vehicle_type'] }}</td>
                              <td>{{ ($user['accept_credit_card'])?'Yes':'No' }}</td>               
                              <td>
                                 <p data-placement="top" data-toggle="tooltip" title="edit"><button  onclick="edit({{ $user['id']  }},'{{ $user['name'] }}','{{ $user['mobile_no'] }}','{{ $user['organization_type']  }}','{{ $user['vehicletype']['id']  }}','{{ $user['vehicle_number']  }}','{{ $user['servicetype']['id'] }}','{{ $user['passengers'] }}','{{ $user['accept_credit_card'] }}')" class="btn btn-danger btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p>
                              </td>
                              <td>
                                 <!-- <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p> -->
                                 @if($user['status'])
                                    <p data-placement="top" data-toggle="tooltip" title="block"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-danger btn-xs" data-title="block" data-toggle="modal" data-target="#block" ><span class="fa fa-ban"></span></button></p>
                                 @else
                                    <p data-placement="top" data-toggle="tooltip" title="unblock"><button onclick="set_block_id({{ $user['id'] }})" class="btn btn-success btn-xs" data-title="block" data-toggle="modal" data-target="#unblock" ><span class="fa fa-ban"></span></button></p>
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
    <article id="tab2">
    <select name="fabric_color_en[]" id="fabric_color_en[]" multiple="multiple" class="form-control select22">
      <option selected value="0">S.no</option>
      <option selected value="1">Name</option>
      <option selected  value="2">Contact</option>
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
         <th>S.no</th>
         <th>Name</th>
         <th>Mobile no.</th>
         <th>Date of joining</th>
         <th>Address</th>
       
         <th>Details</th>
         <!-- <th>Send reason</th> -->
         <th>Set service type</th>
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
               <td>
                  {{ ++$i }}
               </td>
               <td>{{ $user['name'] }}</td>
               <td>{{ $user['mobile_no'] }}</td>
               <td>{{ date('Y-m-d',strtotime($user['created_at'])) }}</td>
               <td class="mwpx-170">{{ $user['city']['name'] }} , {{ $user['country']['name']  }} </td>
            
               <td class="inactive"> 
               @if($user['id_proof'] && $user['vehicle_registration_certificate'])
               <p data-placement="top" data-toggle="tooltip" title="Details"><button onclick="view_details({{ $user['id']  }},'{{ $user['organizationtype']['name']  }}','{{ $user['vehicletype']['name']  }}','{{ $user['vehicle_number']  }}','{{ $user['vehicle_image']  }}','{{ $user['id_proof']  }}','{{ $user['driving_license']  }}','{{ $user['profile_pic']  }}','{{ $user['vehicle_registration_certificate']  }}','{{ $user['taximeter_certificate']  }}',{{ $user['id_proof_verified']  }},{{ $user['driving_license_verified']  }},{{ $user['vehicle_registration_certification_verified']  }},{{ $user['taximeter_certificate_verified']  }})" class="btn btn-success btn-xs" data-title="details"><span class="fa fa-eye"></span></button></p>
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
               <td>
                  <div class="form-group">
                    <input type="hidden" class="current_user" value="{{ $user['id'] }}">
                      <select class="form-control service_type">
                        <option value="">Select service type</option>
                        @foreach($servicetype as $type)
                        <option @if($type['id']==$user['service_type']) selected @endif value="{{ $type['id'] }}">
                           {{ $type['name'] }}
                        </option>
                        @endforeach
                      </select>
                  </div>
                  
               </td>

               <td>{{ $user['passengers'] }}</td>
               <td>{{ $user['vehicle_type'] }}</td>
               <td>{{ ($user['accept_credit_card'])?'Yes':'No' }}</td>

               <td>
                  <button data-placement="top" data-toggle="tooltip" title="edit" onclick="edit({{ $user['id']  }},'{{ $user['name'] }}','{{ $user['mobile_no'] }}','{{ $user['organizationtype']['id']  }}','{{ $user['vehicle_type']  }}','{{ $user['vehicle_number']  }}','{{ $user['servicetype']['id'] }}')" class="btn btn-danger btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button>
               </td>
               <td>
                  <!-- <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil"></span></button></p> -->
                  @if($user['status'])
                     <button data-placement="top" data-toggle="tooltip" title="block" onclick="set_block_id({{ $user['id'] }})" class="btn btn-danger btn-xs" data-title="block" data-toggle="modal" data-target="#block" ><span class="fa fa-ban"></span></button>
                  @else
                     <button data-placement="top" data-toggle="tooltip" title="unblock" onclick="set_block_id({{ $user['id'] }})" class="btn btn-success btn-xs" data-title="block" data-toggle="modal" data-target="#unblock" ><span class="fa fa-ban"></span></button>
                  @endif
               </td>
               <td>
                  <button data-placement="top" data-toggle="tooltip" title="Delete" onclick="set_delete_id({{ $user['id'] }})" class="btn btn-danger btn-xs" ><span class="fa fa-trash"></span></button>
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
                        <div class="form-group">
                           <label>Mobile no</label>
                           <input class="form-control" name="mobile" id="edit_mobile" required type="text" placeholder="Mobile no">
                        </div>

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
                                 <option value="{{ $service['id'] }}">{{ $service['name'] }}</option>
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
                                 <!-- <p id="view_vehicle_number">demo name<p> -->
                                 <a href="javascript:void(0)"  download id="vehicle_image_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="vehicle_image_preview_tag view_preview"  target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                 &nbsp;
                                 
                           </div>
                           
                           
                           <div class="form-group">
                                 <label>ID Proof</label>
                                 <!-- <p id="view_vehicle_number">demo name<p> -->
                                 <a href="javascript:void(0)"  download id="id_proof_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="id_proof_preview_tag view_preview"  target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                 &nbsp;
                                
                                 <input type="checkbox" id="id_proof_checkbox"  onclick="verify_proof(1)">
                                 
                           </div>

                           <div class="form-group">
                                 <label>Driving license</label>
                                 <!-- <p id="view_vehicle_number">demo name<p> -->
                                 <a href="javascript:void(0)" download id="driving_license_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="driving_license_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                 &nbsp;
                                
                                 <input type="checkbox"  id="driving_license_checkbox"  onclick="verify_proof(2)">
                                 
                           </div>

                           <div class="form-group">
                                 <label>Vehicle registration certificate</label>
                                 <!-- <p id="view_vehicle_number">demo name<p> -->
                                 <a href="javascript:void(0)" download id="vehicle_registration_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="vehicle_registration_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                 &nbsp;
                                
                                 <input type="checkbox"  id="vehicle_registration_checkbox" onclick="verify_proof(3)">
                                 
                           </div>

                           <div class="form-group">
                                 <label>Taximeter certificate</label>
                                 <!-- <p id="view_vehicle_number">demo name<p> -->
                                 <a href="javascript:void(0)" download id="teximeter_tag"><i class="fa fa-download"></i></a> &nbsp; <a class="teximeter_preview_tag view_preview" target="_blank" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                 &nbsp;
                             
                                 <input type="checkbox"  id="taximeter_certification_checkbox"  onclick="verify_proof(4)">
                                
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
                        <h4 style="color: #f28f22;">Do you want to delete this item</h4>
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
                              <h4 style="color: #f28f22;">Do you want to block this item</h4>
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
                                 <h4 style="color: #f28f22;">Do you want to Unblock this item</h4>
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
   var detaildriver_id = 0;
   var old_value = 0;
   var delete_id = 0;
   var block_id = 0;
   var table = 0;
   var verify_id = 0;
$('document').ready(function(){
  
   table =  $('#mytable').DataTable({
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


$('document').ready(function(){
  
  table2 =  $('#mytable1').DataTable({
   info:false,
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




function confirm_delete(){
   window.location.href="{{ url('admin/deleteuser') }}/"+delete_id;
}

function confirm_block(){
   window.location.href="{{ url('admin/blockuser') }}/"+block_id;
}

function set_block_id(id){
   block_id = id;
}

function set_delete_id(id){
   delete_id = id;
   $("#delete").modal('show');
}


function verify_proof(type){
   $.ajax({
      url : "{{ url('admin/verify_document') }}/"+type,
      type:"post",
      data : {"driver_id":detaildriver_id,"_token":"{{ csrf_token() }}"},
      dataType:'json',
      success:function(json){
         if(json.verified_user){
            window.location.href = "{{ url('admin/user_management') }}/1";
         }else{
            alert(json.message);
         }
      }
   })
}

function view_details(id,organizationtype,vehicletype,vehicle_number,vehicle_image,id_proof,driving_license,profile_pic,vehicle_registration_certificate,taximeter_certificate,id_proof_verified,driving_license_verified,vehicle_registration_certification_verified,taximeter_certificate_verified){
   detaildriver_id = id;
   $("#view_organization_type").text(organizationtype);
   $("#view_vehicle_type").text(vehicletype);
   $("#view_vehicle_number").text(vehicle_number);

   $("#vehicle_image_tag").attr('href',"{{ asset('public/files') }}/"+vehicle_image);
   $("#id_proof_tag").attr('href',"{{ asset('public/files') }}/"+id_proof);
   $("#driving_license_tag").attr('href',"{{ asset('public/files') }}/"+driving_license);
   $("#vehicle_registration_tag").attr('href',"{{ asset('public/files') }}/"+vehicle_registration_certificate);
   $("#teximeter_tag").attr('href',"{{ asset('public/files') }}/"+taximeter_certificate);
   

   $(".vehicle_image_preview_tag").attr('href',"{{ asset('public/files') }}/"+vehicle_image);
   $(".id_proof_preview_tag").attr('href',"{{ asset('public/files') }}/"+id_proof);
   $(".driving_license_preview_tag").attr('href',"{{ asset('public/files') }}/"+driving_license);
   $(".vehicle_registration_preview_tag").attr('href',"{{ asset('public/files') }}/"+vehicle_registration_certificate);
   $(".teximeter_preview_tag").attr('href',"{{ asset('public/files') }}/"+taximeter_certificate);

   // $.ajax({
   //    url:"{{ url('admin/document_verified') }}",
   //    type:"post",
   //    data:{"_token":"{{ csrf_token() }}","driver_id":detaildriver_id},
   //    dataType:'json',
   //    success:function(json){
   //      console.log(json);
   //      if(json.id_proof==1){
   //       $("#id_proof_checkbox").prop('checked',true);
   //      }else{
   //       $("#id_proof_checkbox").prop('checked',false);
   //      }
   //      if(json.driving_license==1){
   //       $("#driving_license_checkbox").prop('checked',true);
   //      }else{
   //       $("#driving_license_checkbox").prop('checked',false);
   //      }
   //      if(json.vehicle_registration_certificate==1){
   //       $("#vehicle_registration_checkbox").prop('checked',true);
   //      }else{
   //       $("#vehicle_registration_checkbox").prop('checked',false);
   //      }
   //      if(json.taximeter_certificate==1){
   //       $("#taximeter_certification_checkbox").prop('checked',true);
   //      }else{
   //       $("#taximeter_certification_checkbox").prop('checked',false);
   //      }
   //    }
   // });

   if(profile_pic){
      $("#view_vehicle_image").attr('src',"{{ asset('public/files') }}/"+profile_pic);
   }else{
      $("#view_vehicle_image").attr('src',"{{ asset('assets/img/logo_2x.png') }}");
   }
   
   $("#view_details").modal('show');
}

function edit(id,name,mobileno,organizationtype,vehicletype,vehicle_number,servicetype,passenger,accept_creditcard){
   $("#edit_id").val(id);
   $("#edit_name").val(name);
   $("#edit_mobile").val(mobileno);
   $("#edit_organization").val(organizationtype);
   $("#edit_service").val(servicetype);
   $("#edit_passengers").val(passenger);
   $("#edit_accept_creditcard").val(accept_creditcard);
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
   $(function(){
  $('ul.tabs li:first').addClass('active');
  $('.block article').hide();
  $('.block article:first').show();
  $('ul.tabs li').on('click',function(){
    $('ul.tabs li').removeClass('active');
    $(this).addClass('active')
    $('.block article').hide();
    var activeTab = $(this).find('a').attr('href');
    $(activeTab).show();
    return false;
  });
})

   $('.service_type').change(function(){
      var user_id = $(this).closest('.form-group').find(".current_user").val();
      var service_type = $(this).val();
      $.ajax({
         url:"{{ url('admin/set_user_service_type') }}",
         type:'post',
         data : {'_token':'{{ csrf_token() }}','user_id':user_id,'service_type':service_type},
         success:function(){
            
         }
      })
   });

   // $(document).ready(function(){
   //    show_verified();
   // })

   // function show_verified(){
   //    $("#id_proof_checkbox").hide()
   //    $("#driving_license_checkbox").hide()
   //    $("#vehicle_registration_checkbox").hide()
   //    $("#taximeter_certification_checkbox").hide()
      
   // }

   // function show_unverified(){
   //    $("#id_proof_checkbox").show()
   //    $("#driving_license_checkbox").show()
   //    $("#vehicle_registration_checkbox").show()
   //    $("#taximeter_certification_checkbox").show()
   // }

   </script>

@endsection
