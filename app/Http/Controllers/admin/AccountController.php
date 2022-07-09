<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\User;
use Auth;
use Log;
use Hash;
use Validator;
use App\Appuser;
use App\Country;
use Mail;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use App\Search;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Documentverfied;
use App\RegImage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;


class AccountController extends Controller
{





  
    public function login(Request $request){

        if($request->method() =='GET'){
            return view('admin.login');
        }else{
            $this->validate($request,[
                "email"=>'required',
                "password"=>'required'
            ]);
            
            $auth = Auth::guard('admin')->attempt(["email"=>$request['email'],"password"=>$request['password']]);
      

            if($auth){
                return redirect('admin/home');
            }else{
                return redirect()->back()->with('error','Invalid email password');
            }
        }
    }

    public function forgetpassword(Request $request){
        if($request->method()=='GET'){
            return view('admin.forgetpassword');
        }else{
            $this->validate($request,[
                "email"=>'required'
            ]);
            $admin = Admin::where('email',$request["email"])->first();
            if($admin){

                $token = md5(uniqid(rand(), true));
                $forgetpassword_link = url('admin/resetpassword')."/".$token;

                $html = "<h3>".$forgetpassword_link ."</h3>";

                $data =  ["email"=>$request["email"],"html"=>$html];

                Mail::send(array(), array(), function ($message) use ($data) {
                    $message->to($data['email'])
                      ->subject('Reset your password')
                      ->from('notification@emergencytext.com')
                      ->setBody($data['html'], 'text/html');
                });

                Admin::where('email',$request["email"])->update(["forgetpassword_token"=>$token]);
                return redirect()->back()->with('message','Email send to your email');
            
            }else{
                return redirect()->back()->with('error','Email not found');
            }
        }
    }

    public function resetpassword(Request $request){
        
        if($request->method()=='GET'){
            return view('admin.resetpassword');
        }else{
            $this->validate($request,[
                "password"=>'required',
                "confirm_password"=>'required|same:password'
            ]);
            $token = $request['token'];
            $admin = Admin::where('forgetpassword_token',$request["token"])->first();
            if($admin){
                $admin->password = Hash::make($request['password']);
                $admin->save();
                return redirect()->back()->with('success','Password reset successfully');
            }else{
                return redirect()->back()->with('error','Invalid Forgetpassword token');
            }
        }
    }




    public function editprofile(Request $request){
        if($request->method()=='GET'){
            $admin = Auth::guard('admin')->user();
            return view('admin.editprofile',["admin"=>$admin]);
        }else{
          
            $this->validate($request,[
                "email"=>'required',
                "phone"=>'required',
                "location"=>"required",
                "about"=>"required",
                "address"=>"required"
            ]);

            $admin = Auth::guard('admin')->user();

            if($request->file('profile_pic')){
                    $image = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
                    $request->file('profile_pic')->move(public_path('files'), $image);
               
                    Admin::where('id',$admin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"image"=>$image,"about"=>$request["about"],"address"=>$request['address']]);
                }else{
                    Admin::where('id',$admin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"about"=>$request["about"],"address"=>$request['address']]);
                }
            return redirect()->back();
        }
    }

    public function profile(){
        $admin = Auth::guard('admin')->user();
        return view('admin.profile',["admin"=>$admin]);  
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->to('admin/login');
    }

    public function index(Request $request){
        $latitude = 28.610844;
        $longitude = 77.3839504;
        if($request['latitude']){
            $latitude = $request['latitude'];
        }
        if($request['longitude']){
            $longitude = $request['longitude'];   
        }   
        if($request['source']){
            $source = $request['source'];
        }
        if($request['destination']){
            $destination = $request['destination'];

        }
        if($request['distance']){
            $distance = $request['distance'];
            
        }
        if($request['destLngd']){
            $destLngd = $request['destLngd'];
            
        }
        if($request['destLatd']){
            $destLatd = $request['destLatd'];
            
        }
      
        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();


        
        $filterDrivers = Appuser::query()
        ->where('verified','1')
        ->where('subscribed','1')
        ->where('available_status','1')
        ->pluck('id');
  
        return view('admin.index',["organizationtype"=>$organizationtype,"servicetype"=>$servicetype,'latitude'=>$latitude,'longitude'=>$longitude,'source'=>$request['source'],'destination'=>$request['destination'],'destLngd'=>$request['destLngd'],'destLatd'=>$request['destLatd'],'price'=>$request['price'],'filterDrivers'=>$filterDrivers]);
    }
    public function search(){
 
        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        $image = RegImage::query()->value("image");
        $countries = json_decode(CountryController::$countriesPhoneCodes);        
        return view('admin.search',compact('countries','organizationtype','servicetype','image'));
    }
    public function searchfirst(){
        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        return view('admin.landpage',["organizationtype"=>$organizationtype,"servicetype"=>$servicetype]);
    }
	
	public function createUserAccount(Request $request) {
		
		$validation = array(
                'register_name' => 'required',
                'register_email' => 'required',
                'register_password' => 'required'    
        );
        $validator = FacadesValidator::make($request->all(),$validation);
        if ($validator->fails()){
		   return response()->json(['status'=>1,'error' => 'Mandotry field cannot be empty'],401);
       
        }
		$user_exist = User::where('email',$request->input('register_email'))->first();
		if(!empty($user_exist)){
			
			return response()->json(['status'=>1,'error' => 'Email already in use. Please try another to create account!'],401);
		}
		$user = new User;
		$user->name = trim($request->input('register_name'));
		$user->email = trim($request->input('register_email'));
		$user->password = bcrypt($request->get('register_password'));
        $user->save();
	}
	
	public function userLogin(Request $request){
		$validation = array(
               
                'user_email' => 'required',
                'user_password' => 'required'    
        );
        $validator = Validator::make($request->all(),$validation);
        if ($validator->fails()){
		   return response()->json(['status'=>1,'error' => 'Mandotry field cannot be empty'],401);
       
        }
		if(Auth::attempt(['email' => $request->user_email, 'password' => $request->user_password])) {
            return response()->json(['status'=>1,'message' => 'Yor are successfully loggedin'],200);
        }else{
			return response()->json(['status'=>0,'message' => 'User does not exist'],200);
		}
	}
	
	public function checkUserLoginStatus() {
		
		if(Auth::guard()->check()){
			return response()->json(['status'=>1,'message' => 'Yor are successfully loggedin'],200);
		}else{
			
			return response()->json(['status'=>0,'message' => 'Something went wrong'],200);
		}
	}

    public function user($id){
        
        $appuser = Appuser::with(["city"=>function($query){
            $query->select(['id','name'])->get();
        },"country"=>function($query){
            $query->select(['id','name'])->get();
        },"servicetype"=>function($query){
            $query->get();
        },"organizationtype"=>function($query){
            $query->get();
        },"vehicletype"=>function($query){
            $query->select(['id','name'])->get();
        },"verified"])->where('id',$id)->first();
        
        return response()->json(["message"=>"details getting successfully","appuser"=>$appuser]);
    }
    public function home(){
        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();



        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        return view('admin.home',["organizationtype"=>$organizationtype,"servicetype"=>$servicetype]);
    }

    public function changepassword(Request $request){
        if($request->method()=="GET"){
            $admin = Auth::guard('admin')->user();
            return view('admin.changepassword',["admin"=>$admin]);
        }else{
            $this->validate($request,[
                "old_password"=>'required',
                "new_password"=>'required',
                "confirm_password"=>"required"

            ]);
            $userdetails = Auth::guard('admin')->user();
            if(Hash::check($request['old_password'],$userdetails['password'])){
                Admin::where('id',$userdetails['id'])->update(["password"=>Hash::make($request['new_password'])]);  
            }else{
                return redirect()->back()->with('error','Invalid old password');
            }
            return redirect()->back()->with('success','Password changed successfully');
        }
    }

    public function usermanagement(Request $request,$chnageId){


        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        $count = Search::where(['driver_id' => 1])->count();
            $verifiedappuser = Appuser::with(["organizationtype"=>function($query){
                $query->select(['id','name'])->get();
            },"vehicletype"=>function($query){
                $query->select(['id','name'])->get();
            },"verified"])->where('document_verified',1)->where("account_status",3)->orderBy('id','desc')->get()->toArray();
            
            $unverifiedappuser = Appuser::with(["organizationtype"=>function($query){
                $query->select(['id','name'])->get();
            },"vehicletype"=>function($query){
                $query->select(['id','name'])->get();
            },"verified"])->where('document_verified',0)->orderBy('id','desc')->get()->toArray();
        // print_r($verifiedappuser);die;
        foreach ($verifiedappuser as $key => $value) {
            $arr = [];
             $subs = \App\Subscriber::select('*','subscribers.id as s_id','subscribers.created_at as created_at')->where('driver_id',$value['id'])->join('subscriptions','subscribers.subscription_id','=','subscriptions.id')->orderBy('subscribers.id','DESC')->first();
             if($subs){
                if($subs->type==2){
                 $d = \App\DriverAvailability::where('subscriber_id',$subs->s_id)->get();
                 $count = $d->count();
                 //dd($count);
                 $arr['remaining_days'] = ($count<=$subs->days)?$subs->days-$count:0;
                }elseif($subs->type==1){
                 $date = strtotime(date('Y-m-d'),strtotime($subs->created_at));
					
					if($subs->subscription_id==12){
						
						$d = "+".$subs->duration." days";
					}else{
						$d = "+".$subs->days." days";
					}
				 
                 //$d = "+".$subs->days." days";
                 $newDate = strtotime($d,$date);
                 //dd($newDate);
                 $arr['expire_on'] =  $newDate;
                }
				if($subs->subscription_id==12){
				    $arr['duration'] = $subs->duration;
				}else{
					$arr['duration'] = $subs->days;
				}
               $arr['plan_name'] = $subs->plan_name;
               $arr['description'] = $subs->plan_description;
               $arr['plan_price'] = $subs->plan_price;
               $arr['type'] = $subs->type;
               $arr['expire'] = $subs->expire;
               $arr['driver_id'] = $subs->driver_id;
               $arr['subscription_id'] = $subs->subscription_id;
               $arr['service_type'] = $subs->service_type;
               $verifiedappuser[$key]['my_subscription'] = $arr;
             }else{
             $verifiedappuser[$key]['my_subscription'] = "";
            }
        }
        return view('admin.useraccount',["verifiedusers"=>$verifiedappuser,"unverifiedappuser"=>$unverifiedappuser,"type"=>$request['type'],"organizationtype"=>$organizationtype,"servicetype"=>$servicetype,'chnageId'=>$chnageId]);
    }
	
	public function updateFreePlanDuration(Request $request) {
		
		$plan_type = $request->free_plan_type;
		$plan_duration = $request->plan_duration;
		$sub_id = $request->sub_id;
		$driver_id = $request->user_id;
		$plan = \DB::table('subscribers')->where(['driver_id'=>$driver_id,'subscription_id'=>$sub_id,'type'=>$plan_type])->first();
		/*if($plan_type==1){
			$date = strtotime(date('Y-m-d'),strtotime($plan->created_at));
					 $d = "+".$plan_duration." days";
					 $newDate = strtotime($d,$date);
		}*/
		//return response()->json(['date'=>date('Y-m-d H:i:s',$newDate)]);
		if(\DB::table('subscribers')->where(['driver_id'=>$driver_id,'subscription_id'=>$sub_id,'type'=>$plan_type])->update(['duration'=>$plan_duration])){
			return response()->json([
				'status' => 0,
				'message' => 'Plan updated successfully!'
			]);
		}else{
			
			return response()->json([
				'status' => 1,
				'message' => 'Something went wrong'
			]);
		}
		
	}

    public function setuserservicetype(Request $request){
        
        Appuser::where('id',$request['user_id'])->update(["service_type"=>$request['service_type']]);

        $service_type = Servicetype::where('id',$request['service_type'])->first();
       // print_r($service_type);
        //die;
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
            $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database
            ->getReference('user'.$request['user_id']);

            $currentnodevalue = $newPost->getSnapshot()->getvalue();
            $currentnodevalue['service_type'] = (int)$request['service_type'];
            $currentnodevalue['servicetypeimage'] = $service_type->image;
            $newPost->set($currentnodevalue);
            
        return response()->json(["message"=>"service type updated successfully"]);
    }
    public function editdriver(Request $request){
    
     $data   = Appuser::where('id',$request['id'])->first();
     $service_type = Servicetype::where('id',$data->service_type)->first();
     return response()->json(["message"=>"data geeting successgylly","data"=>$data,"service_type"=>$service_type]);
        }

    public function updatedriver(Request $request){

        Appuser::where('id',$request['id'])->update(["service_type"=>$request['servicetype'],"name"=>$request['name'],"mobile_no"=>$request['mobile'],"organization_type"=>$request['organizationtype'],"service_type"=>$request['servicetype'],"passengers"=>$request['passengers'],"accept_credit_card"=>$request['accept_creditcard']]);
        if($request['servicetype']){
        $service_type = Servicetype::where('id',$request['servicetype'])->first();      
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
            $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database
            ->getReference('user'.$request['id']);

            $currentnodevalue = $newPost->getSnapshot()->getvalue();
            $currentnodevalue['service_type'] = (int)$request['servicetype'];
            $currentnodevalue['servicetypeimage'] = $service_type->image;
            $newPost->set($currentnodevalue);
        }

        return redirect()->back()->with('success','driver modified successfully');
    }

   

    // public function edit(){

    // }

    public function delete_user($id){
        Appuser::where('id',$id)->delete();

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
            $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database
            ->getReference('user'.$id);
            $newPost->remove();

        return redirect()->back()->with('success','Driver deleted successfully');
    }

    public function block_user($id){
        $appuser = Appuser::where('id',$id)->first();
        if($appuser->status==1){
            $appuser->status = 0;
            $appuser->save();
            return redirect()->back()->with('success','Driver unblocked successfully');
        }else{
            $appuser->status = 1;
            $appuser->save();
            return redirect()->back()->with('success','Driver blocked successfully');
        }
        
       
    }

    public function verifydriver($id){
        $appuser = Appuser::where('id',$id)->first();
        $appuser->document_verified = 1;
        $appuser->save();
        $url = url('admin/user_management/1');

        $this->sendsms('your emergency taxi account verified successfully',$appuser->country_code.$appuser->mobile_no);

        return redirect()->to($url)->with('success','Driver verified successfully');
    }


    public function sendreasontodriver(Request $request){
        $appuser = Appuser::where('id',$request['id'])->first();
        $this->sendsms($request['reason'],$appuser->country_code.$appuser->mobile_no);
        return redirect()->back()->with('success','Reason sent to driver successfully');
    }


    public function document_verified(Request $request){
        $data["id_proof"] = (Documentverfied::where(["document_type"=>1,"driver_id"=>$request["driver_id"],"subadmin_id"=>0,"verified"=>1])->first())?1:0;
        $data["driving_license"] = (Documentverfied::where(["document_type"=>2,"driver_id"=>$request["driver_id"],"subadmin_id"=>0,"verified"=>1])->first())?1:0;
        $data["vehicle_registration_certificate"] = (Documentverfied::where(["document_type"=>3,"driver_id"=>$request["driver_id"],"subadmin_id"=>0,"verified"=>1])->first())?1:0;
        $data["taximeter_certificate"] = (Documentverfied::where(["document_type"=>4,"driver_id"=>$request["driver_id"],"subadmin_id"=>0,"verified"=>1])->first())?1:0;
        return response()->json($data,200);
    }

    public function verifydocument(Request $request,$type){
        if($type==1){
         Appuser::where(["id"=>$request["driver_id"]])->update(["id_proof_verified"=>1]);
        }elseif($type==2){
         Appuser::where(["id"=>$request["driver_id"]])->update(["driving_license_verified"=>1]);
        }elseif($type==3){
         Appuser::where(["id"=>$request["driver_id"]])->update(["vehicle_registration_certification_verified"=>1]);
        }elseif($type==4){
         Appuser::where(["id"=>$request["driver_id"]])->update(["taximeter_certificate_verified"=>1]);
        }

        $details = Appuser::find($request['driver_id']);
        $id_proof = $details->id_proof_verified;
        $driving_license = $details->driving_license_verified;
        $vehicle_registration_certificate = $details->vehicle_registration_certification_verified;
        $taximeter_certificate = $details->taximeter_certificate_verified;

        if($id_proof==1 && $driving_license==1 && $vehicle_registration_certificate==1 && $taximeter_certificate==1){           

            $subs = \App\Subscription::where('status',2)->first();
            if($subs){
               // dd($subs->);
            \App\Subscriber::create(['driver_id'=>$request['driver_id'],'subscription_id'=>$subs->id,'duration'=>@$subs->days,'type'=>@$subs->plan_type]);
            
            }
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
            $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database->getReference('user'.$request['driver_id']);

            $currentnodevalue = $newPost->getSnapshot()->getvalue();
            $currentnodevalue['documentverified'] = "1";
            $newPost->set($currentnodevalue);
            Appuser::where(["id"=>$request["driver_id"]])->update(["document_verified"=>1,"document_verified_at"=>time(),"verified_by"=>0,"account_status"=>3]);

            $data = Appuser::find($request['driver_id']);

            $msg = 'Thank you for your registration ! Your profile has been verified by admin, Now you will be able to receive calls for booking request.Thanks Team Taxirader18 ';

            $this->sendsms($msg,$data->country_code.$data->mobile_no);

            return response()->json(["message"=>"user verified successfully","verified_user"=>1],200);
        }else{
            return response()->json(["message"=>"document verified successfully","verified_user"=>0],200);  
        }       
    }
    public function unverifydocument(Request $request){
        //dd($request->all());
        if($request['type']==1){
         Appuser::where(["id"=>$request["driver_id"]])->update(["id_proof_verified"=>2,'account_status'=>2]);
         $name = "ID Proof";
        }elseif($request['type']==2){
         Appuser::where(["id"=>$request["driver_id"]])->update(["driving_license_verified"=>2,'account_status'=>2]);
         $name = "Driving License";
        }elseif($request['type']==3){
         Appuser::where(["id"=>$request["driver_id"]])->update(["vehicle_registration_certification_verified"=>2,'account_status'=>2]);
         $name = "Vehicle Registration Certificate";
        }elseif($request['type']==4){
         Appuser::where(["id"=>$request["driver_id"]])->update(["taximeter_certificate_verified"=>2,'account_status'=>2]);
         $name = "Taximeter Certificate";
        }
        $data = Appuser::find($request['driver_id']);
        $msg = 'Thank you for your registration ! Your profile has been rejected due to below Incomplete document name : '.$name.'.Reason :'.$request['message'].'.Please login again into the application and re-upload the document.';

        $this->sendsms($msg,$data->country_code.$data->mobile_no);
        \DB::table('un_verify_reasons')->insert(['document_type'=>$request['type'],'driver_id'=>$request['driver_id'],'message'=>$request['message']]);
        return response()->json(["message"=>"Message sent successfully"],200); 
    }

    public function verifydocument1(Request $request){
        $document = Documentverfied::where(["driver_id"=>$request["driver_id"],"subadmin_id"=>0,"document_type"=>$request["type"]])->first();
        
        if($document){
            $document->verified = 1;
            $document->save();
        }else{
            Documentverfied::insert(["driver_id"=>$request["driver_id"],"subadmin_id"=>0,"verified"=>1,"document_type"=>$request["type"]]);
        }

        
        $id_proof = (Documentverfied::where(["document_type"=>1,"subadmin_id"=>0,"driver_id"=>$request["driver_id"],"verified"=>1])->first())?1:0;
        $driving_license = (Documentverfied::where(["document_type"=>2,"subadmin_id"=>0,"driver_id"=>$request["driver_id"],"verified"=>1])->first())?1:0;
        $vehicle_registration_certificate = (Documentverfied::where(["document_type"=>3,"subadmin_id"=>0,"driver_id"=>$request["driver_id"],"verified"=>1])->first())?1:0;
        $taximeter_certificate = (Documentverfied::where(["document_type"=>4,"subadmin_id"=>0,"driver_id"=>$request["driver_id"],"verified"=>1])->first())?1:0;

        if($id_proof && $driving_license && $vehicle_registration_certificate && $taximeter_certificate){
            

            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
            $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database
            ->getReference('user'.$request['driver_id']);

            $currentnodevalue = $newPost->getSnapshot()->getvalue();
            $currentnodevalue['documentverified'] = 1;
            $newPost->set($currentnodevalue);
            Appuser::where(["id"=>$request["driver_id"]])->update(["document_verified"=>1,"document_verified_at"=>time(),"verified_by"=>0,"id_proof_verified"=>1,"driving_license_verified"=>1,"vehicle_registration_certification_verified"=>1,"taximeter_certificate_verified"=>1]);
            return response()->json(["message"=>"user verified successfully","verified_user"=>1],200);
        }else{
            return response()->json(["message"=>"document verified successfully","verified_user"=>0],200);  
        }

       
    }
    

    public function subadmin(Request $request){
        
    }


    public function userdetails($id){
        $appuser = Appuser::where('id',$id)->first();
   
        return response()->json(["message"=>"details getting successfully","appuser"=>$appuser]);
    }

        // public function connectfirebase(){
        //     $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
        //     $firebase = (new Factory)
        //     ->withServiceAccount($serviceAccount)
        //     ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
        //     ->create();
        //     $database = $firebase->getDatabase();
        //     $newPost = $database
        //     ->getReference('user61');


        //     $currentnodevalue = $newPost->getSnapshot()->getvalue();
        //     $currentnodevalue['service_type'] = 1;
        //     $currentnodevalue['servicetypeimage'] = 'kjkajakjs';
        //     $newPost->set($currentnodevalue);
        //     print_r($newPost->getvalue());
        //     }

    
}
