<?php

namespace App\Http\Controllers\organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Organization;
use Auth;
use Hash;
use Validator;
use App\Appuser;
use Mail;
use App\Search;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;


class AccountController extends Controller
{
    public function login(Request $request){

        if($request->method() =='GET'){
            return view('organization.login');
        }else{
            $this->validate($request,[
                "email"=>'required',
                "password"=>'required'
            ]);
            
            $auth = Auth::guard('organization')->attempt(["email"=>$request['email'],"password"=>$request['password']]);
            if($auth){
                return redirect('organization/home');
            }else{
                return redirect()->back()->with('error','Invalid email password');
            }
        }
    }

    public function forgetpassword(Request $request){
        if($request->method()=='GET'){
            return view('organization.forgetpassword');
        }else{
            $this->validate($request,[
                "email"=>'required'
            ]);
            $admin = Organization::where('email',$request["email"])->first();
            if($admin){

                $token = md5(uniqid(rand(), true));
                $forgetpassword_link = url('organization/resetpassword')."/".$token;

                $html = "<h3>".$forgetpassword_link ."</h3>";
                $data =  ["email"=>$request["email"],"html"=>$html];

                Mail::send(array(), array(), function ($message) use ($data) {
                    $message->to($data['email'])
                      ->subject('Reset your password')
                      ->from('notification@emergencytext.com')
                      ->setBody($data['html'], 'text/html');
                });

                Organization::where('email',$request["email"])->update(["forgetpassword_token"=>$token]);
                return redirect()->back()->with('message','Email send to your email');
            
            }else{
                return redirect()->back()->with('error','Email not found');
            }
        }
    }

    public function resetpassword(Request $request){
        
        if($request->method()=='GET'){
            return view('organization.resetpassword');
        }else{
            $this->validate($request,[
                "password"=>'required',
                "confirm_password"=>'required|same:password'
            ]);
            $token = $request['token'];
            $admin = Organization::where('forgetpassword_token',$request["token"])->first();
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
            $admin = Auth::guard('organization')->user();
            return view('organization.editprofile',["admin"=>$admin]);
        }else{
          
            $this->validate($request,[
                "email"=>'required',
                "phone"=>'required',
                "location"=>"required",
                "about"=>"required",
                "address"=>"required"
            ]);

            $admin = Auth::guard('organization')->user();

            if($request->file('profile_pic')){
                    $image = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
                    $request->file('profile_pic')->move(public_path('files'), $image);
               
                    Organization::where('id',$admin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"image"=>$image,"about"=>$request["about"],"address"=>$request['address']]);
                }else{
                    Organization::where('id',$admin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"about"=>$request["about"],"address"=>$request['address']]);
                }
            return redirect()->back();
        }
    }

    public function profile(){
        $admin = Auth::guard('organization')->user();
      
        return view('organization.profile',["admin"=>$admin]);  
    }

    public function logout(){
        Auth::guard('organization')->logout();
        return redirect()->to('organization/login');
    }

    public function home(){
        $userdata = Auth::guard('organization')->user();
        $userdata = Organization::with('type')->where('type',$userdata->type)->first()->toArray();
        return view('organization.home',["userdata"=>$userdata]);
    }

    public function changepassword(Request $request){
        if($request->method()=="GET"){
            $admin = Auth::guard('organization')->user();
            return view('organization.changepassword',["admin"=>$admin]);
        }else{
            $this->validate($request,[
                "old_password"=>'required',
                "new_password"=>'required',
                "confirm_password"=>"required"
            ]);
            $userdetails = Auth::guard('organization')->user();
            if(Hash::check($request['old_password'],$userdetails['password'])){
                Organization::where('id',$userdetails['id'])->update(["login_password"=>$request['new_password'],"password"=>Hash::make($request['new_password'])]);  
            }else{
                return redirect()->back()->with('error','Invalid old password');
            }
            return redirect()->back()->with('success','Password changed successfully');
        }
    }

    

    


    public function usermanagementBKP(Request $request){

        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        

        if($request['type']==1){
            $appuser = Appuser::with(["city"=>function($query){
                $query->select(['id','name'])->get();
            },"country"=>function($query){
                $query->select(['id','name'])->get();
            },"servicetype"=>function($query){
                $query->select(['id','name'])->get();
            },"organizationtype"=>function($query){
                $query->select(['id','name'])->get();
            },"vehicletype"=>function($query){
                $query->select(['id','name'])->get();
            }])->where('document_verified',1)->orderBy('id','desc')->get()->toArray();
            
        }else{
            $appuser = Appuser::with(["city"=>function($query){
                $query->select(['id','name'])->get();
            },"country"=>function($query){
                $query->select(['id','name'])->get();
            },"servicetype"=>function($query){
                $query->select(['id','name'])->get();
            },"organizationtype"=>function($query){
                $query->select(['id','name'])->get();
            },"vehicletype"=>function($query){
                $query->select(['id','name'])->get();
            }])->where('document_verified',0)->orderBy('id','desc')->get()->toArray();
        }
        

        return view('organization.useraccount',["users"=>$appuser,"type"=>$request['type'],"organizationtype"=>$organizationtype,"servicetype"=>$servicetype]);
    }


    public function updatedriver(Request $request){
        // dd($request);
        Appuser::where('id',$request['id'])->update(["name"=>$request['name'],"mobile_no"=>$request['mobile'],"organization_type"=>$request['organizationtype'],"service_type"=>$request['servicetype']]);
        return redirect()->back()->with('success','driver modified successfully');
    }

    

    // public function edit(){

    // }

    public function delete_user($id){
        Appuser::where('id',$id)->delete();
        return redirect()->back()->with('success','Driver deleted successfully');
    }

    public function block_user($id){
        $appuser = Appuser::where('id',$id)->first();
        if($appuser->status){
            $appuser->status = 0;
            $appuser->save();
            return redirect()->back()->with('success','Driver blocked successfully');
        }else{
            $appuser->status = 1;
            $appuser->save();
            return redirect()->back()->with('success','Driver unblocked successfully');
        }
        
       
    }

    public function verifydriver($id){
        $appuser = Appuser::where('id',$id)->first();
        $appuser->document_verified = 1;
        $appuser->save();
        $url = url('organization/user_management/1');

        $this->sendsms('your emergency taxi account verified successfully',$appuser->country_code.$appuser->mobile_no);

        return redirect()->to($url)->with('success','Driver verified successfully');
    }


    public function sendreasontodriver(Request $request){
        $appuser = Appuser::where('id',$request['id'])->first();
        $this->sendsms($request['reason'],$appuser->country_code.$appuser->mobile_no);
        return redirect()->back()->with('success','Reason sent to driver successfully');
    }

    public function verifydocument(Request $request){
        $appuser = Appuser::where('id',$request['driver_id'])->first();
        if($request["type"]==1){
            $appuser->id_proof_verified = ($appuser->id_proof_verified)?0:1;  
        }
        if($request["type"]==2){
            $appuser->driving_license_verified = ($appuser->driving_license_verified)?0:1;   
        }
        if($request["type"]==3){
            $appuser->vehicle_registration_certification_verified = ($appuser->vehicle_registration_certification_verified)?0:1;   
        }
        if($request["type"]==4){
            $appuser->taximeter_certificate_verified = ($appuser->taximeter_certificate_verified)?0:1;     
        }
        
       
        
        if($appuser->id_proof_verified&&$appuser->driving_license_verified&&$appuser->vehicle_registration_certification_verified&&$appuser->taximeter_certificate_verified){
           
            $userdata = Auth::guard('organization')->user();
            $appuser->document_verified = 1;
            $appuser->verified_by =  $userdata->id;
            $appuser->save();
            $this->sendsms('Your driver application was successfully approved',$appuser->country_code.$appuser->mobile_no);
            return response()->json(["message"=>"user verified successfully","verified_user"=>1],200);
        }else{
            $appuser->document_verified = 0;
            $appuser->verified_by =  0;
            $appuser->save();
            return response()->json(["message"=>"document verified successfully","verified_user"=>0],200);
        }
    }


    public function sendsms($msg,$mobile){
        try{
          $sid = 'AC9774bc1e46370ed29b62efd20be8f348';
            $token = '56e2f9d9f0ff565e52608b3c0e0d0d27';
            $client = new Client($sid, $token);
            $number = $client->lookups
                  ->phoneNumbers("+17574483216")
                  ->fetch(array("type" => "carrier"));
            $client->messages->create(
                  $mobile, array(
                    'from' => '+17574483216',
                    'body' => $msg
                  )
                );
            return 1;
      } catch(\Exception $e){
              $response = [
                'message' => $e->getMessage()
              ];
              
              // return response();
              return $response;
                    }
        }


        public function usermanagement(Request $request,$chnageId){

            $u_id = Auth::guard('organization')->user()->id;
            $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
            $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
            $count = Search::where(['driver_id' => 1])->count();
                $verifiedappuser = Appuser::with(["city"=>function($query){
                    $query->select(['id','name'])->get();
                },"country"=>function($query){
                    $query->select(['id','name'])->get();
                },"servicetype"=>function($query){
                    $query->select(['id','name'])->get();
                },"organizationtype"=>function($query){
                    $query->select(['id','name'])->get();
                },"vehicletype"=>function($query){
                    $query->select(['id','name'])->get();
          
                },"verified"])->where('organization_type',$u_id)->where('document_verified',1)->where("account_status",3)->orderBy('id','desc')->get()->toArray();
                
                $unverifiedappuser = Appuser::with(["city"=>function($query){
                    $query->select(['id','name'])->get();
                },"country"=>function($query){
                    $query->select(['id','name'])->get();
                },"servicetype"=>function($query){
                    $query->select(['id','name'])->get();
                },"organizationtype"=>function($query){
                    $query->select(['id','name'])->get();
                },"vehicletype"=>function($query){
                    $query->select(['id','name'])->get();
                },"verified"])->where('organization_type',$u_id)->where('document_verified',0)->whereIn("account_status",[2,3])->orderBy('id','desc')->get()->toArray();
            //print_r($verifiedappuser);die;
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
                   $verifiedappuser[$key]['my_subscription'] = $arr;
                 }else{
                 $verifiedappuser[$key]['my_subscription'] = "";
                }
            }
        // dd($verifiedappuser);
            return view('organization.useraccount',["verifiedusers"=>$verifiedappuser,"unverifiedappuser"=>$unverifiedappuser,"type"=>$request['type'],"organizationtype"=>$organizationtype,"servicetype"=>$servicetype,'chnageId'=>$chnageId]);
        }


}
