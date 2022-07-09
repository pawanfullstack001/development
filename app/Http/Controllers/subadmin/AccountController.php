<?php

namespace App\Http\Controllers\subadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\subadmin;
use Auth;
use Hash;
use Validator;
use App\Appuser;
use Mail;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Documentverfied;

class AccountController extends Controller
{
    public function login(Request $request){

        if($request->method() =='GET'){
            return view('subadmin.login');
        }else{
            $this->validate($request,[
                "email"=>'required',
                "password"=>'required'
            ]);
            
            $auth = Auth::guard('subadmin')->attempt(["email"=>$request['email'],"password"=>$request['password']]);
      

            if($auth){
                return redirect('subadmin/home');
            }else{
                return redirect()->back()->with('error','Invalid email password');
            }
        }
    }

    public function forgetpassword(Request $request){
        if($request->method()=='GET'){
            return view('subadmin.forgetpassword');
        }else{
            $this->validate($request,[
                "email"=>'required'
            ]);
            $subadmin = subadmin::where('email',$request["email"])->first();
            if($subadmin){

                $token = md5(uniqid(rand(), true));
                $forgetpassword_link = url('subadmin/resetpassword')."/".$token;

                $html = "<h3>".$forgetpassword_link ."</h3>";

                $data =  ["email"=>$request["email"],"html"=>$html];

                Mail::send(array(), array(), function ($message) use ($data) {
                    $message->to($data['email'])
                      ->subject('Reset your password')
                      ->from('notification@emergencytext.com')
                      ->setBody($data['html'], 'text/html');
                });

                subadmin::where('email',$request["email"])->update(["forgetpassword_token"=>$token]);
                return redirect()->back()->with('message','Email send to your email');
            
            }else{
                return redirect()->back()->with('error','Email not found');
            }
        }
    }

    public function resetpassword(Request $request){
        
        if($request->method()=='GET'){
            return view('subadmin.resetpassword');
        }else{
            $this->validate($request,[
                "password"=>'required',
                "confirm_password"=>'required|same:password'
            ]);
            $token = $request['token'];
            $subadmin = subadmin::where('forgetpassword_token',$request["token"])->first();
            if($subadmin){

                $subadmin->password = Hash::make($request['password']);
                $subadmin->plain_password = $request['password'];
                $subadmin->save();
                return redirect()->back()->with('success','Password reset successfully');
            }else{
                return redirect()->back()->with('error','Invalid Forgetpassword token');
            }
        }
    }


    public function document_verified(Request $request){
        $subadmin = Auth::guard('subadmin')->user();
        $data["id_proof"] = (Documentverfied::where(["document_type"=>1,"driver_id"=>$request["driver_id"],"subadmin_id"=>$subadmin->id,"verified"=>1])->first())?1:0;
        $data["driving_license"] = (Documentverfied::where(["document_type"=>2,"driver_id"=>$request["driver_id"],"subadmin_id"=>$subadmin->id,"verified"=>1])->first())?1:0;
        $data["vehicle_registration_certificate"] = (Documentverfied::where(["document_type"=>3,"driver_id"=>$request["driver_id"],"subadmin_id"=>$subadmin->id,"verified"=>1])->first())?1:0;
        $data["taximeter_certificate"] = (Documentverfied::where(["document_type"=>4,"driver_id"=>$request["driver_id"],"subadmin_id"=>$subadmin->id,"verified"=>1])->first())?1:0;
        return response()->json($data,200);
    }



    public function verifydocument(Request $request,$type){
        $subadmin = Auth::guard('subadmin')->user();
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
        $vehicle_registration_certificate = $details->vehicle_registration_certificate;
        $taximeter_certificate = $details->taximeter_certificate_verified;

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
            Appuser::where(["id"=>$request["driver_id"]])->update(["document_verified"=>1,"document_verified_at"=>time(),"verified_by"=>$subadmin->id]);
            return response()->json(["message"=>"user verified successfully","verified_user"=>1],200);
        }else{
            return response()->json(["message"=>"document verified successfully","verified_user"=>0],200);  
        }       
    }

    public function unverifydocument(Request $request){
        //dd($request->all());
        if($request['type']==1){
         Appuser::where(["id"=>$request["driver_id"]])->update(["id_proof_verified"=>2,'account_status'=>2]);
        }elseif($request['type']==2){
         Appuser::where(["id"=>$request["driver_id"]])->update(["driving_license_verified"=>2,'account_status'=>2]);
        }elseif($request['type']==3){
         Appuser::where(["id"=>$request["driver_id"]])->update(["vehicle_registration_certification_verified"=>2,'account_status'=>2]);
        }elseif($request['type']==4){
         Appuser::where(["id"=>$request["driver_id"]])->update(["taximeter_certificate_verified"=>2,'account_status'=>2]);
        }
        $data = Appuser::find($request['driver_id']);
        \DB::table('un_verify_reasons')->insert(['document_type'=>$request['type'],'driver_id'=>$request['driver_id'],'message'=>$request['message']]);
        return response()->json(["message"=>"Message sent successfully"],200); 
    }
    


    public function editprofile(Request $request){
        if($request->method()=='GET'){
            $subadmin = Auth::guard('subadmin')->user();
            return view('subadmin.editprofile',["subadmin"=>$subadmin]);
        }else{
          
            $this->validate($request,[
                "email"=>'required',
                "phone"=>'required',
                "location"=>"required",
                "about"=>"required",
                "address"=>"required"
            ]);

            $subadmin = Auth::guard('subadmin')->user();

            if($request->file('profile_pic')){
                    $image = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
                    $request->file('profile_pic')->move(public_path('files'), $image);
               
                    subadmin::where('id',$subadmin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"image"=>$image,"about"=>$request["about"],"address"=>$request['address']]);
                }else{
                    subadmin::where('id',$subadmin['id'])->update(["email"=>$request['email'],"phone_no"=>$request['phone'],"location"=>$request['location'],"about"=>$request["about"],"address"=>$request['address']]);
                }
            return redirect()->back();
        }
    }

    public function profile(){
        $subadmin = Auth::guard('subadmin')->user();
        return view('subadmin.profile',["subadmin"=>$subadmin]);  
    }

    public function logout(){
        Auth::guard('subadmin')->logout();
        return redirect()->to('subadmin/login');
    }

    public function home(){
        return view('subadmin.home');
    }

    public function changepassword(Request $request){
        if($request->method()=="GET"){
            $subadmin = Auth::guard('subadmin')->user();
            return view('subadmin.changepassword',["subadmin"=>$subadmin]);
        }else{
            $this->validate($request,[
                "old_password"=>'required',
                "new_password"=>'required',
                "confirm_password"=>"required"
            ]);
            $userdetails = Auth::guard('subadmin')->user();
            if(Hash::check($request['old_password'],$userdetails['password'])){
                subadmin::where('id',$userdetails['id'])->update(["password"=>Hash::make($request['new_password'])]);  
            }else{
                return redirect()->back()->with('error','Invalid old password');
            }
            return redirect()->back()->with('success','Password changed successfully');
        }
    }

    public function usermanagement(Request $request){


        $organizationtype = Organizationtype::select(["id","name"])->where('id','!=',1)->get();
        $servicetype = Servicetype::select(["id","name"])->where('id','!=',1)->get();
        

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
        },"verified"])->where('document_verified',1)->where("account_status",3)->orderBy('id','desc')->get()->toArray();
        
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
        },"verified"])->where('document_verified',0)->whereIn("account_status",[2,3])->orderBy('id','desc')->get()->toArray();
    
        

        return view('subadmin.useraccount',["verifiedusers"=>$verifiedappuser,"unverifiedappuser"=>$unverifiedappuser,"type"=>$request['type'],"organizationtype"=>$organizationtype,"servicetype"=>$servicetype]);
    }


    public function sendreasontodriver(Request $request){
        $appuser = Appuser::where('id',$request['id'])->first();
        $this->sendsms($request['reason'],$appuser->country_code.$appuser->mobile_no);
        return redirect()->back()->with('success','Reason sent to driver successfully');
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

}
