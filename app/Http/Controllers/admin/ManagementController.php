<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use App\Organization;
use App\Subadmin;
use App\Admin;
use App\Staticcontent;
use Mail;
use Hash;


class ManagementController extends Controller
{
    public function servicetype(){
        $servicetype = Servicetype::with('organizationtype')->where('id','!=',1)->orderBy('id','desc')->get();
        $organizationtype = organizationtype::where('id','!=',1)->get();
        return view('admin.servicetype',["servicetype"=>$servicetype,"organizationtype"=>$organizationtype]);
    }

    public function addservicetype(Request $request){
        if($request->file('image')){
            $image = rand(9999,99999).time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('files'), $image);
        }

        $servicetype = Servicetype::insert(["servicetype_id"=>$request['service_typeid'],"organization_id"=>$request['organization_type'],"image"=>$image,"name"=>$request['name'],"pax"=>$request['pax'],"accept_ccard"=>$request['accept_ccard']]);
        return redirect()->back()->with('success','Service type added successfully');
    }

    public function editservicetype(Request $request){
        if($request->file('image')){
            $image = rand(9999,99999).time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('files'), $image);
        }

        if($request->file('image')){
            $servicetype = Servicetype::where('id',$request['id'])->update(["servicetype_id"=>$request['service_typeid'],"organization_id"=>$request['organization_type'],"image"=>$image,"name"=>$request['name'],"pax"=>$request['pax'],"accept_ccard"=>$request['accept_ccard']]);
        }else{
            $servicetype = Servicetype::where('id',$request['id'])->update(["servicetype_id"=>$request['service_typeid'],"organization_id"=>$request['organization_type'],"name"=>$request['name'],"pax"=>$request['pax'],"accept_ccard"=>$request['accept_ccard']]);
        }

        return redirect()->back()->with('success','Service type updated successfully');
    }

    public function deleteservicetype($id){
        Servicetype::where('id',$id)->delete();
        return redirect()->back()->with('success','Service type deleted successfully');
    }


    public function vehicletype(){
         $vehicletype = Vehicletype::with('servicetype')->where('id','!=',1)->get();
         $servicetype = Servicetype::where('id','!=',1)->get();
        return view('admin.vehicletype',["vehicletype"=>$vehicletype,"servicetype"=>$servicetype]);
    }

    public function addvehicletype(Request $request){
        $vehicletype = Vehicletype::insert(["name"=>$request['name'],"service_type"=>$request['service_type']]);
        return redirect()->back();
    }

    public function editvehicletype(Request $request){
        Vehicletype::where('id',$request['id'])->update(["name"=>$request['name'],"service_type"=>$request['service_type']]);
        return redirect()->back();
    }

    public function deletevehicletype($id){
        Vehicletype::where('id',$id)->delete();
        return redirect()->back();
    }

    public function organizationtype(){
        $organization = Organizationtype::where('id','!=',1)->get();
        return view('admin.organizationtype',["organization"=>$organization]);
    }

    public function editorganizationtype(Request $request){
        Organizationtype::where('id',$request['id'])->update(["name"=>$request['name'],"radius"=>$request['radius'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"location"=>$request['location']]);
        return redirect()->back()->with('success','Organization type deleted successfully');
    }

    public function addorganizationtype(Request $request){
        Organizationtype::insert(["name"=>$request['name'],"radius"=>$request['radius'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"location"=>$request['location']]);
        return redirect()->back()->with('success','Organization type updated successfully');
    }

    public function deleteorganizationtype($id){
        Organizationtype::where(["id"=>$id])->delete();
        return redirect()->back()->with('success','Organization type deleted successfully');
    }

    public function seturlvalue(){
        if(session()->has('show_side_bar')){
            session()->forget('show_side_bar');
        }else{
            session()->put('show_side_bar',1);
        }
        response()->json(["message"=>"status changes successfully"]);
    }


    public function organization(Request $request){
        $organizations = Organization::orderBy('id','desc')->get()->toArray(); 
        //dd($organizations);
        $organizationtype = organizationtype::where('id','!=',1)->get();
        return view('admin.organization',["organizations"=>$organizations,"organizationtype"=>$organizationtype]);
    }

    public function editorganization(Request $request){
        if($request->file('edit_organization_logo')){
            $image = rand(9999,99999).time().'.'.$request->file('edit_organization_logo')->getClientOriginalExtension();
            $request->file('edit_organization_logo')->move(public_path('files'), $image);
            Organization::where('id',$request['organization_id'])->update(["organization_logo"=>$image]);
            
           
        }
        Organization::where('id',$request['organization_id'])->update(["email"=>$request["add_organization_email"],"type"=>$request["organization_type"],"address"=>$request["add_organization_address"],"phone_no"=>$request["add_organization_phone_no"],"mobile_no"=>$request["add_organization_mobile_no"],"rut"=>$request["add_organization_rut"],"manager_name"=>$request["add_organization_manager_name"],"manager_mobile"=>$request["add_organization_manager_mobile"],"quantity_of_vehicle"=>$request["add_organization_quantity_of_vehicle"],"note1"=>$request["add_organization_node_1"],"note2"=>$request["add_organization_node_2"],"radius"=>$request['radius'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"location"=>$request['location'],'organization_name'=>trim(strtolower($request['edit_organization_name']))]);
        return redirect()->back()->with('success','organization edited successfully');
    }

    public function addorganization(Request $request){
        //$username = "demo_user";
        $username = trim(strtolower($request['organization_name']));
        $useremail = trim($request["add_organization_email"]);
      
        $password = rand(999999999,9999999999);

            $str = ''; 
            $str .= "<p>Hi,\n</p>";
            $str .= "<p>Your Organization Has Been Successfully Registered.\n</p>";    
            $str .= "<p>Please login through with your email ID and password.\n</p>";    
            $str .= "<p>Please click below link to get login.\n</p>";    
            $str .= "<p><b>URL:</b>- <a href='https://taxiradar18.com/organization/login'>Click here to login</a> \n</p>";
            $str .= "<p><b>Organization Name:</b>- '".$request['organization_name']."' \n</p>";  
            $str .= "<p><b>Email:</b>- '".$useremail."' \n</p>";   
            $str .= "<p><b>Password:</b>- '".$password."' \n</p>";   
             Mail::raw('Text to e-mail', function($message) use ($str,$useremail)
             {
             $message->from('notification@emergencytext.com', 'Emergency Taxi');
             $message->to($useremail)->subject("Emergency Taxi Organization Registration Detail")->setBody("$str", 'text/html');
             });


        $hashpassword = Hash::make($password);
        $image = '';
        if($request->file('organization_logo')){
            $image = rand(9999,99999).time().'.'.$request->file('organization_logo')->getClientOriginalExtension();
            $request->file('organization_logo')->move(public_path('files'), $image);
    
        }


        Organization::insert(['organization_name'=>$request->organization_name,'organization_logo'=>$image,"email"=>$request["add_organization_email"],"type"=>$request["organization_type"],"address"=>$request["add_organization_address"],"phone_no"=>$request["add_organization_phone_no"],"mobile_no"=>$request["add_organization_mobile_no"],"rut"=>$request["add_organization_rut"],"manager_name"=>$request["add_organization_manager_name"],"manager_mobile"=>$request["add_organization_manager_mobile"],
        "quantity_of_vehicle"=>$request["add_organization_quantity_of_vehicle"],"note1"=>$request["add_organization_node_1"],"note2"=>$request["add_organization_node_2"],"login_name"=>$username,"login_password"=>$password,"password"=>$hashpassword ,"radius"=>$request['radius'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"location"=>$request['location']]);
        return redirect()->back()->with('success','organization added successfully');
    }

    public function deleteorganization(Request $request){
        Organization::where('id',$request['id'])->delete();
        return redirect()->back()->with('success','organization deleted successfully');
    }

    public function subadmin(){
        $permissions = \DB::table('permissions')->where('status',1)->get();
        $subadmin = Admin::where('is_sub_admin',1)->get();
        return view('admin.subadmin',["subadmins"=>$subadmin,'permissions'=>$permissions]);
    }

    public function addsubadmin(Request $request){

        //echo '<pre>'; print_r($request->permission_id); die;
        $subadmin = new Admin;
        $subadmin->email = $request['email'];
        //$subadmin->unique_id = $request['username'];
        $subadmin->username = $request['username'];
        $subadmin->is_sub_admin = 1;
        $subadmin->password = Hash::make($request['password']);
        $subadmin->plain_password = $request['password'];
        if($request['driver_management']){ $subadmin->driver_management_role =  $request['driver_management'];   }
        if($request['organization_management']){ $subadmin->organization_management_role =  $request['organization_management'];     }
        if($request['service_type']){ $subadmin->service_type_role =  $request['service_type'];     }
        if($request['service_request']){ $subadmin->service_request_role =  $request['service_request'];     }
        if($request['subscription_plan']){  $subadmin->subscription_plan_role =  $request['subscription_plan'];    }
        if($request['booking']){  $subadmin->booking_role =  $request['booking'];    }
        if($request['sms_management']){ $subadmin->sms_management_role =  $request['sms_management'];     }
        if($request['price_and_eta']){ $subadmin->price_and_eta_role =  $request['price_and_eta'];     }
        if($request['notification']){ $subadmin->notification_role =  $request['notification'];     }
        if($request['report_generation']){ $subadmin->report_generation_role =  $request['report_generation'];     }
        if($request['rating_and_review']){ $subadmin->rating_and_review_role =  $request['rating_and_review'];     }
        if($request['setting']){  $subadmin->setting_role =  $request['setting'];    }

        $subadmin->save();
        if(isset($request->permission_id) && !empty($request->permission_id)){
            $permission_ids = $request->permission_id;
            foreach($permission_ids as $permission_id) {
                $permissionArray['permission_id'] = $permission_id;
                $permissionArray['admin_id'] = $subadmin->id;
               //echo '<pre>'; print_r($permissionArray); die;
                \DB::table('permission_user')->insert($permissionArray);
            }
            
        }
        
        $data["email"] = $request['email'];
        $data["html"] = "<h2>Your emergency taxi subadmin credentails</h2><p>email : ".$request['email']."  </p><p> unique id : ".$request['unique_id']."</p><p> password : ".$request['password']."</p>";
        Mail::send(array(), array(), function ($message) use ($data) {
            $message->to($data['email'])
              ->subject('Your subadmin credentials')
              ->from('notification@emergencytext.com')
              ->setBody($data['html'], 'text/html');
        });

        return redirect()->back()->with('success','Subadmin added successfully');
    }

    public function getSubAdminPermissionDetails(Request $request) {

        $permissions = \DB::table('permissions')->where('status',1)->get();
        $subadmin = Admin::where('id',$request->id)->first();
        $userPermissions = \DB::table("permission_user")->where("permission_user.admin_id",$request->id)
        ->pluck('permission_user.permission_id')
        ->all();
        return view('admin.editsubadminpermissions',compact('permissions','subadmin','userPermissions'));
    }

    public function editsubadmin(Request $request){
     
        $permissions = \DB::table('permissions')->where('status',1)->get();

        $subadmin = Admin::where('id',$request['admin_id'])->where('is_sub_admin',1)->first();
        $subadmin->email = $request['email'];
        //$subadmin->unique_id = $request['unique_id'];
        $subadmin->username = $request['username'];
        $subadmin->password = Hash::make($request['password']);
        $subadmin->plain_password = $request['password'];

        if($request['driver_management']){ $subadmin->driver_management_role =  $request['driver_management'];   }
        if($request['organization_management']){ $subadmin->organization_management_role =  $request['organization_management'];     }
        if($request['service_type']){ $subadmin->service_type_role =  $request['service_type'];     }
        if($request['service_request']){ $subadmin->service_request_role =  $request['service_request'];     }
        if($request['subscription_plan']){  $subadmin->subscription_plan_role =  $request['subscription_plan'];    }
        if($request['booking']){  $subadmin->booking_role =  $request['booking'];    }
        if($request['sms_management']){ $subadmin->sms_management_role =  $request['sms_management'];     }
        if($request['price_and_eta']){ $subadmin->price_and_eta_role =  $request['price_and_eta'];     }
        if($request['notification']){ $subadmin->notification_role =  $request['notification'];     }
        if($request['report_generation']){ $subadmin->report_generation_role =  $request['report_generation'];     }
        if($request['rating_and_review']){ $subadmin->rating_and_review_role =  $request['rating_and_review'];     }
        if($request['setting']){  $subadmin->setting_role =  $request['setting'];    }

        $subadmin->save();
        if(isset($request->permission_id) && !empty($request->permission_id)){
            \DB::table('permission_user')->where('admin_id',$subadmin->id)->delete();
            $permission_ids = $request->permission_id;
            foreach($permission_ids as $permission_id) {
                $permissionArray['permission_id'] = $permission_id;
                $permissionArray['admin_id'] = $subadmin->id;
                //echo '<pre>'; print_r($permissionArray); die;
                \DB::table('permission_user')->insert($permissionArray);
            }
        }
        return redirect()->back()->with('success','Subadmin updated successfully');
    }

    public function deletesubadmin($id){
        Admin::where('id',$id)->where('is_sub_admin',1)->delete();
        \DB::table('permission_user')->where('admin_id',$id)->delete();
        return redirect()->back()->with('success','Subadmin deleted successfully');
    }

    public function contentmanagement(){




        $termen = "";
        $termsp = "";
        $policyen = "";
        $policysp ="";
        $faqen ="";
        $faqsp = "";
        $abouten = "";
        $aboutsp = "";
      $staticdata =  Staticcontent::get();


      foreach($staticdata as $data){
            if($data['type']==1&&$data['language']==0){
                $termen =   $data['content'];
            }
            if($data['type']==1&&$data['language']==1){
                $termsp =   $data['content'];
            }
            if($data['type']==2&&$data['language']==0){
                $policyen =   $data['content'];
            }
            if($data['type']==2&&$data['language']==1){
                $policysp =   $data['content'];
            }
            if($data['type']==3&&$data['language']==0){
                $faqen =   $data['content'];
            }
            if($data['type']==3&&$data['language']==1){
                $faqsp =   $data['content'];
            }
            if($data['type']==4&&$data['language']==0){
                $abouten =   $data['content'];
            }
            if($data['type']==4&&$data['language']==1){
                $aboutsp =   $data['content'];
            }
        }

        return view('admin.content-management',["termen"=>$termen,"termsp"=>$termsp,"policyen"=>$policyen,"policysp"=>$policysp,"faqen"=>$faqen,"faqsp"=>$faqsp,"abouten"=>$abouten,"aboutsp"=>$aboutsp]);
    }
    public function contentmanagementsubmit(Request $request){
    
        
        Staticcontent::where('id','!=','null')->delete();
        Staticcontent::insert([
        ["content"=>$request['terms_en'],"type"=>1,"language"=>0],
        ["content"=>$request['terms_sp'],"type"=>1,"language"=>1],
        ["content"=>$request['faq_en'],"type"=>2,"language"=>0],
        ["content"=>$request['faq_sp'],"type"=>2,"language"=>1],
        ["content"=>$request['about_en'],"type"=>3,"language"=>0],
        ["content"=>$request['About_sp'],"type"=>3,"language"=>1],
        ["content"=>$request['Policy_en'],"type"=>4,"language"=>0],
        ["content"=>$request['Policy_sp'],"type"=>4,"language"=>1]]);








        return redirect()->back();
    }

}
