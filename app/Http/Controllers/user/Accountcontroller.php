<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Appuser;
use Response;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use App\Search;
use Hash;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Staticcontent;
use Carbon\Carbon;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class Accountcontroller extends Controller
{
    public  function DriverDetails(Request $request)
    {
        $status = $request->status;
        $validation = array(
            "status"=>"required",
            "booking_id"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
        $driver_id = Appuser::where('accesstoken',$request->header('accesstoken'))->pluck('id')->first();
        $data = \DB::table('search_map')->where('driver_id',$driver_id)->where('id',$request->booking_id)->first();
        if($data){
            \DB::table('search_map')->where('driver_id',$driver_id)->where('id',$request->booking_id)->update(['status'=>$status]);
             return response()->json(["message"=>"request approved  successfully"],200);
        }else{
            return response()->json(["message"=>"No booking found."],400);
        }    
    }



    public function TotalRide(Request $request){
        $user = $request['userDetail']->id;
        $rides = Search::where(['driver_id' => $user])->where('status','!=',0)->orderBy('id','DESC')->select('id','driver_id','latitude','longitude','price','destLngd','destLatd','distance','duration','status','source','destination','created_at','updated_at')->get();
        if(!empty($rides)){
        foreach($rides as $key => $ride){
            
            $created_at1 = \Carbon\Carbon::parse($ride->created_at)->format('Y-m-d H:i:s A');
            $rides[$key]['booking_date'] = $created_at1;
            
        }
        return response()->json(["message"=>"total rides","rides"=>$rides],200);
       }
        
    }
    
  public function  language_change(Request $request){
      $user_details = $request['userDetail'];
    Appuser::where("id",$user_details['id'])->update(["locale"=>$request['locale']]);
    $appuser = Appuser::with(["country"=>function($query){
        $query->select(['id','name'])->get();
    }])->where('id',$user_details['id'])->first();
    
    return response()->json(["message"=>"user language changed successfully","userdetails"=>$appuser],200);

  }

  public function changepassword(Request $request){



    $user_details = $request['userDetail'];
   
        $validation = array(
            "old_password"=>"required",
            "new_password"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
        if(Hash::check($request['old_password'],$user_details['password'])){
            Appuser::where('id',$user_details['id'])->update(["password"=>Hash::make($request['new_password'])]);  
        }else{
            return response()->json(["message"=>"invalid old password"],400);
        }
        return response()->json(["message"=>"password changed successfully"],200);
}


public  function terms(Request $request)
{

    $user_details = $request['userDetail'];

    $locale = ($user_details['locale'])==1?0:1;
    $type = $request['type'];

    $static_content = Staticcontent::where(['type'=>$type,"language"=>$locale])->first();
    
    return view('admin/static_content',["content"=>$static_content->content]);
    
}

 
    public function getsignupdropdowns(Request $request){
        $data['organizationtype'] = Organizationtype::select(["id","name"])->get();
        $url = url("public/files/");
        $data['servicetype'] = Servicetype::selectRaw("id,servicetype_id,concat('".$url."/',image) as image,name")->get();
        $data['vehicletype'] = Vehicletype::select(["id","name"])->get();
        return response()->json(["message"=>"dropdowns getting successfully","data"=>$data]);
    }


    public function signup(Request $request){
        //dd($request->all());
        $validation = array(
           
            "name"=>"required",
            "country_code"=>"required",
            "mobile_no"=>"required",
            "email"=>"required",
            "city"=>"required",
            "country"=>"required",
           // "home_town"=>"required",
            "locality"=>"required",
            "password"=>"required",
            "device_type"=>"required",
            "device_token"=>"required",
            "latitude"=>"required",
            "longitude"=>"required",
            "postal_code" =>'required',
            "personal_id" =>""
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        if($request->file('profile_pic')){
            $profle_pic = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('files'), $profle_pic);
        }

        if(Appuser::where(["country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"verified"=>0])->first()){
            Appuser::where(["country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"verified"=>0])->delete();
        }

        if(Appuser::where(["country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],'verified'=>1])->first()){
            return response()->json(["message"=>"mobile no already taken"],400);
        }

        $password  = Hash::make($request['password']);

        $accesstoken = md5(uniqid(rand(), true));

        $otp = rand(999,9999);

        $this->sendsms("emergency taxi verification OTP ".$otp,$request['country_code'].$request['mobile_no']);

        $personalID = "T_".date('m').date('d').substr($request['mobile_no'], -4);

        $address = "{$request['locality']} {$request['city']} {$request['country']}-{$request['postal_code']}";

       // dd($personalID);
 if($request->file('profile_pic')){
        $appuser = Appuser::insertGetId(["email"=>$request['email'],"locality"=>$request['locality'],"profile_pic"=>$profle_pic,"name"=>$request['name'],"country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"country"=>$request['country'],"city"=>$request['city'],"address"=> $address,
        "password"=>$password,"device_type"=>$request['device_type'],"device_token"=>$request['device_token'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"accesstoken"=>$accesstoken,"otp"=>$otp,'personal_id'=>$personalID,
        'postal_code'=>$request['postal_code'],'service_type'=>53]);

        $appuser = Appuser::with(["country"=>function($query){
            $query->select(['id','name'])->get();
        }])->where('id',$appuser)->first();
        
        return response()->json(["message"=>"user registered successfully","userdetails"=>$appuser],200);
    }
    else{
        $appuser = Appuser::insertGetId(["email"=>$request['email'],"locality"=>$request['locality'],"name"=>$request['name'],"country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"country"=>$request['country'],"city"=>$request['city'],"address"=> $address,
        "password"=>$password,"device_type"=>$request['device_type'],"device_token"=>$request['device_token'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"accesstoken"=>$accesstoken,"otp"=>$otp,'personal_id'=>$personalID,'postal_code'=>$request['postal_code'],'service_type'=>53]);

        $appuser = Appuser::with(["country"=>function($query){
            $query->select(['id','name'])->get();
        }])->where('id',$appuser)->first();
        
        return response()->json(["message"=>"user registered successfully","userdetails"=>$appuser],200);
    }
    }


    public function addvehicledetails(Request $request){
        $validation = array(
            "organization_type"=>"required",
            "vehicle_image"=>"required",
            "vehicle_type"=>"required",
            "vehicle_number"=>"required",
            "passengers"=>"required",
            "accept_credit_card"=>"required",
            "brand"=>"required",
            "year"=>"required"
        );
        $organization_type = trim(strtolower($request->organization_type));
        $organization_name = trim(strtolower($request->organization_type));
        if($organization_name=='personal'){
            $organization_name = trim($request->organization_type);
        }else{

              $org = \DB::table('organization')->where('organization_name',$request->organization_type)->where('organization_name','<>','personal')->first();
               if(empty($org)){
                return response()->json(["message"=>"Organization name does not exist in our database please try again!"],200);
               }else{

                    $organization_name = $org->id;
               }
        }
     
        
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        $vehicle_image = NULL;
        if($request->file('vehicle_image')){
            $vehicle_image = rand(9999,99999).time().'.'.$request->file('vehicle_image')->getClientOriginalExtension();
            $request->file('vehicle_image')->move(public_path('files'), $vehicle_image);
        }

        $userdetails = $request['userDetail'];

        $servicetype = Servicetype::where(['pax'=>$request['passengers'],'accept_ccard'=>$request['accept_credit_card']])->first();
           $service_name       = $servicetype->name;
           if($servicetype){


     $updated =  Appuser::where("id",$userdetails['id'])->update(["service_type"=>$service_name,"brand"=>$request['brand'],"year"=>$request['year'],"organization_type"=>$organization_name,"vehicle_image"=>$vehicle_image,"vehicle_type"=>$request['vehicle_type'],"vehicle_number"=>$request['vehicle_number'],"passengers"=>$request['passengers'],"accept_credit_card"=>$request['accept_credit_card'],"account_status"=>2]);
     $service_type = Servicetype::where('id',$request['servicetype'])->first();
        
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/emergency-taxi-firebase-adminsdk-ngvhf-4bf66d8b87.json');
         $firebase = (new Factory)
         ->withServiceAccount($serviceAccount)
         ->withDatabaseUri('https://emergency-taxi.firebaseio.com/')
         ->create();
         $database = $firebase->getDatabase();
         $newPost = $database
         ->getReference('user'.$userdetails['id']);

         $currentnodevalue = $newPost->getSnapshot()->getvalue();
         $currentnodevalue['service_type'] = (int)$service_name;
         $currentnodevalue['servicetypeimage'] = $servicetype->image;
         $newPost->set($currentnodevalue);
     
    
    }
else{
            $updated =  Appuser::where("id",$userdetails['id'])->update(["brand"=>$request['brand'],"year"=>$request['year'],"organization_type"=>$organization_name,"vehicle_image"=>$vehicle_image,"vehicle_type"=>$request['vehicle_type'],"vehicle_number"=>$request['vehicle_number'],"passengers"=>$request['passengers'],"accept_credit_card"=>$request['accept_credit_card'],"account_status"=>2]);

}
            //             foreach($servicetype as $type){
//             if($type['pax']==$request['passengers'] && $type['accept_ccard']==$request['accept_credit_card'])
//         {
//             Appuser::where("id",$userdetails['id'])->update(["service_type"=>$type['name']]);
//       }
// }
      
        $appuser = Appuser::where('id',$userdetails['id'])->first();

        return response()->json(["message"=>"Vehicle details uploaded successfully","userdetails"=>$appuser],200);
    }

    public function uploaddocument(Request $request){

        $url = url("public/files/");
        $userdetails = $request['userDetail'];
        $validation = array(
            // "id_proof"=>"required",
            // "driving_license"=>"required",
            // "vehicle_registration_certificate"=>"required",
            // "taximeter_certificate"=>"required",
            "vehicle_testing_date"=>"required",
            "expiration_date_of_insurance"=>"required",
            "expiration_date_of_license"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
        $DOCUMENT = Appuser::find($userdetails->id);
        $id_proof = $DOCUMENT->id_proof;
        $driving_license = $DOCUMENT->driving_license; 
        $vehicle_registration_certificate =  $DOCUMENT->vehicle_registration_certificate;
        $taximeter_certificate = $DOCUMENT->taximeter_certificate;

        $type1 = $DOCUMENT->id_proof_verified;
        $type2 = $DOCUMENT->driving_license_verified;
        $type3 = $DOCUMENT->vehicle_registration_certification_verified;
        $type4 = $DOCUMENT->taximeter_certificate_verified;

        if($type1==0 && $type2==0 && $type3==0 && $type4==0 ){
            $msg = 'Thanks for registering, you will be able to receive calls and rides after we check all your documentation.Thanks TaxiRader18 Team'; 
        }else{            
            $msg = 'Thanks for re-uploading your document, You will be able to receive calls and rides after Admin check all your documentation.Thanks TaxiRader18 Team';  
        }
        $this->sendsms($msg,$DOCUMENT->country_code.$DOCUMENT->mobile_no);

        if($request->file('id_proof')){
            $id_proof = rand(9999,99999).time().'.'.$request->file('id_proof')->getClientOriginalExtension();
            $request->file('id_proof')->move(public_path('files'), $id_proof);
            $type1 = 0;
        }


        if($request->file('driving_license')){
            $driving_license = rand(9999,99999).time().'.'.$request->file('driving_license')->getClientOriginalExtension();
            $request->file('driving_license')->move(public_path('files'), $driving_license);
            $type2 = 0;
        }

        if($request->file('vehicle_registration_certificate')){
            $vehicle_registration_certificate = rand(9999,99999).time().'.'.$request->file('vehicle_registration_certificate')->getClientOriginalExtension();
            $request->file('vehicle_registration_certificate')->move(public_path('files'), $vehicle_registration_certificate);
            $type3 = 0;
        }

        if($request->file('taximeter_certificate')){
            $taximeter_certificate = rand(9999,99999).time().'.'.$request->file('taximeter_certificate')->getClientOriginalExtension();
            $request->file('taximeter_certificate')->move(public_path('files'), $taximeter_certificate);
            $type4 = 0;
        }        


        $appuser = Appuser::where('id',$userdetails->id)->update(["vehicle_testing_date"=>$request->vehicle_testing_date,
        "expiration_date_insurance"=>$request->expiration_date_of_insurance,
        "license_expiry_date"=>$request->expiration_date_of_license,
        "id_proof"=>$id_proof,"driving_license"=>$driving_license,
        "is_uploaded"=>1,
        "vehicle_registration_certificate"=>$vehicle_registration_certificate,"taximeter_certificate"=>$taximeter_certificate,"account_status"=>3,"id_proof_verified"=>$type1,"driving_license_verified"=>$type2,"vehicle_registration_certification_verified"=>$type3,"taximeter_certificate_verified"=>$type4]);


        $appuser = Appuser::with(["city"=>function($query){
            $query->select(['id','name'])->get();
        },"country"=>function($query){
            $query->select(['id','name'])->get();
        }])->where('id',$userdetails['id'])->selectRaw("*,concat('".$url."/',profile_pic) as profile_pic")->first();

        return response()->json(["message"=>"Vehicle details uploaded successfully","userdetails"=>$appuser],200);

    }

    public function signup1(Request $request){
        $validation = array(
            "profile_pic"=>"max:2048",
            "name"=>"required",
            "country_code"=>"required",
            "mobile_no"=>"required",
            "city"=>"required",
            "country"=>"required",
            "password"=>"required",
            "organization_type"=>"required",
            "service_type"=>"required",
            "vehicle_type"=>"required",
            "vehicle_number"=>"required",
            "id_proof"=>"required|max:2048",
            "driving_license"=>"required|max:2048",
            "vehicle_registration_certificate"=>"required|max:2048",
            "taximeter_certificate"=>"required|max:2048",
            "device_type"=>"required",
            "device_token"=>"required",
            "latitude"=>"required",
            "longitude"=>"required",
            "vehicle_testing_date"=>"required",
            "expiration_date_insurance"=>"required",
            "license_expiry_date"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        if($request->file('profile_pic')){
            $profle_pic = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('files'), $profle_pic);
        }


        if($request->file('id_proof')){
            $id_proof = rand(9999,99999).time().'.'.$request->file('driving_license')->getClientOriginalExtension();
            $request->file('id_proof')->move(public_path('files'), $id_proof);
        }


        if($request->file('driving_license')){
            $driving_license = rand(9999,99999).time().'.'.$request->file('driving_license')->getClientOriginalExtension();
            $request->file('driving_license')->move(public_path('files'), $driving_license);
        }

        if($request->file('vehicle_registration_certificate')){
            $vehicle_registration_certificate = rand(9999,99999).time().'.'.$request->file('vehicle_registration_certificate')->getClientOriginalExtension();
            $request->file('vehicle_registration_certificate')->move(public_path('files'), $vehicle_registration_certificate);
        }

        if($request->file('taximeter_certificate')){
            $taximeter_certificate = rand(9999,99999).time().'.'.$request->file('taximeter_certificate')->getClientOriginalExtension();
            $request->file('taximeter_certificate')->move(public_path('files'), $taximeter_certificate);
        }
        $vehicle_image = NULL;
        if($request->file('vehicle_image')){
            $vehicle_image = rand(9999,99999).time().'.'.$request->file('vehicle_image')->getClientOriginalExtension();
            $request->file('vehicle_image')->move(public_path('files'), $vehicle_image);
        }

        $password  = Hash::make($request['password']);

        $accesstoken = md5(uniqid(rand(), true));

        $otp = rand(999,9999);


        $this->sendsms("emergency taxi verification OTP ".$otp,$request['country_code'].$request['mobile_no']);

        // $this->sendmail(["subject"=>"emergency taxi verification","email"=>$request['country_code']]);       


        if(Appuser::where(["country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no']])->first()){
            return response()->json(["message"=>"mobile no already taken"],400);
        }

        if($request->file('profile_pic')){
            $appuser = Appuser::insertGetId(["profile_pic"=>$profle_pic,"name"=>$request['name'],"country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"country"=>$request['country'],"city"=>$request['city'],
            "password"=>$password,"organization_type"=>$request['organization_type'],"service_type"=>$request['service_type'],"vehicle_type"=>$request['vehicle_type'],"vehicle_number"=>$request['vehicle_number'],
            "vehicle_testing_date"=>$request->vehicle_testing_date,
            "expiration_date_insurance"=>$request->expiration_date_insurance,
            "license_expiry_date"=>$request->license_expiry_date,
            "vehicle_image"=>$vehicle_image,
            "id_proof"=>$id_proof,"driving_license"=>$driving_license,
            "vehicle_registration_certificate"=>$vehicle_registration_certificate,"taximeter_certificate"=>$taximeter_certificate,"device_type"=>$request['device_type'],
            "device_token"=>$request['device_token'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"accesstoken"=>$accesstoken,"otp"=>$otp]);
        }else{
            $appuser = Appuser::insertGetId(["name"=>$request['name'],"country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],"country"=>$request['country'],"city"=>$request['city'],
            "password"=>$password,"organization_type"=>$request['organization_type'],"service_type"=>$request['service_type'],"vehicle_type"=>$request['vehicle_type'],"vehicle_number"=>$request['vehicle_number'],
            "vehicle_testing_date"=>$request->vehicle_testing_date,
            "expiration_date_insurance"=>$request->expiration_date_insurance,
            "license_expiry_date"=>$request->license_expiry_date,
            "vehicle_image"=>$vehicle_image,
            "id_proof"=>$id_proof,"driving_license"=>$driving_license,"vehicle_registration_certificate"=>$vehicle_registration_certificate,"taximeter_certificate"=>$taximeter_certificate,"device_type"=>$request['device_type'],
            "device_token"=>$request['device_token'],"latitude"=>$request['latitude'],"longitude"=>$request['longitude'],"accesstoken"=>$accesstoken,"otp"=>$otp]);
        }

        

        $appuser = Appuser::with(["city"=>function($query){
            $query->select(['id','name'])->get();
        },"country"=>function($query){
            $query->select(['id','name'])->get();
        },"servicetype"=>function($query){
            $url = url("public/files/");
            $query->selectRaw("id,servicetype_id,concat('".$url."/',image) as image,name")->get();
        },"organizationtype"])->where('id',$appuser)->first();
        
        return response()->json(["message"=>"user registered successfully","userdetails"=>$appuser]);
    }


    public function verify(Request $request){
        $validation = array(
            "otp"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        $user_id = $request['userDetail']->id;

        $url = url("public/files/");

        $app_user = Appuser::where(['id'=>$user_id,"otp"=>$request['otp']])->first();

        if($app_user){

            $app_user->verified = 1;
            if(!$app_user->account_status){
                $app_user->account_status = 1;
            }
            
            $app_user->save();
            $app_user = Appuser::where(['id'=>$user_id])->first();
            // $app_user = Appuser::with(["city"=>function($query){
            //     $query->select(['id','name'])->get();
            // },"country"=>function($query){
            //     $query->select(['id','name'])->get();
            // },"servicetype"=>function($query){
            //     $url = url("public/files/");
            //     $query->selectRaw("id,servicetype_id,concat('".$url."/',image) as image,name")->get();
            // },"organizationtype"])->selectRaw("*,concat('".$url."/',profile_pic) as profile_pic")->where(['id'=>$user_id])->first();

            return response()->json(["message"=>"Driver verified successfully","userdetails"=>$app_user],200);
        }else{
            return response()->json(["message"=>"Invalid OTP"],400);
        }
    }

    public function forgetpassword(Request $request){
        $validation = array(
            "mobile_no"=>"required",
            "country_code"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        $appuser = Appuser::where(["mobile_no"=>$request['mobile_no'],"country_code"=>$request['country_code']])->first();
        if($appuser){
            // $otp = 1234;
            $otp = rand(999,9999);

            $this->sendsms("emergency taxi forgetpassword OTP ".$otp,$request['country_code'].$request['mobile_no']);

            Appuser::where(["id"=>$appuser->id])->update(["otp"=>$otp]);
            return response()->json(["message"=>"OTP send to your mobile","user_id"=>$appuser->id],200);
        }else{
            return response()->json(["message"=>"This number in not registered"],400);
        }
    }

    public function forgetpassword_verification(Request $request){
        $validation = array(
            "otp"=>"required", 
            "user_id"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        $appuser = Appuser::where(["otp"=>$request['otp'],"id"=>$request['user_id']])->first();
        if($appuser){
            return response()->json(["message"=>"OTP verified successfully","user_id"=>$appuser->id],200);
        }else{
            return response()->json(["message"=>"Invalid OTP"],400);
        }
    }

    public function resetpassword(Request $request){
        $validation = array(
            "password"=>"required",
            "user_id"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }

        $password = Hash::make($request['password']);

        $appuser = Appuser::where(["id"=>$request['user_id']])->update(["password"=>$password]);

        return response()->json(["message"=>"Password changed successfully please login"],200);
    }


    public function resendotp(Request $request){
        $validation = array(
            "user_id"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
        // $otp = 1234;
        $otp = rand(999,9999);

        $appuser = Appuser::where(["id"=>$request['user_id']])->first();

        $this->sendsms("emergency taxi  OTP ".$otp,$appuser->country_code.$appuser->mobile_no);

        $appuser = Appuser::where(["id"=>$request['user_id']])->update(["otp"=>$otp]);
        return response()->json(["message"=>"otp successfully send to your mobile no","user_id"=>$request["user_id"]],200);
    }

    public function login(Request $request){
        $validation = array(
            "country_code"=>"required",
            "mobile_no"=>"required",
            "password"=>"required",
            "latitude"=>"required",
            "longitude"=>"required",
            "device_type"=>"required",
            "device_token"=>"required",
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
  
        $user_details = Appuser::where(["country_code"=>$request['country_code'],"mobile_no"=>$request['mobile_no'],'verified'=>1])->first();
        if($user_details){
            $date = Carbon::parse($user_details->last_logged_in);
            $now = Carbon::now();
            $diff = $date->diffInHours($now);
            /*if ($user_details->is_logged_in=="1" && $diff<24) {
                return response()->json(["message"=>"You are already logged in another device."],400); 
            }*/
            $accesstoken = md5(uniqid(rand(), true));
            if(Hash::check($request["password"],$user_details->password)){
                if($user_details->status==1){
                   return response()->json(["message"=>"Currently, You are block by admin."],401); 
                }
                if($user_details->verified==0){
                    $otp = rand(999,9999);
                    $user_details->otp = $otp;
                }
                $user_details->device_type = $request['device_type'];
                $user_details->device_token = $request['device_token'];
                $user_details->latitude = $request['latitude'];
                $user_details->longitude = $request['longitude'];
                $user_details->accesstoken = $accesstoken;
                $user_details->available_status = 1;
                $user_details->is_logged_in = '1';
                $user_details->last_logged_in = Carbon::now();
                $user_details->save();
                return response()->json(["message"=>"Driver logged in successfully","userdetails"=>$user_details]);
            }else{
                return response()->json(["message"=>"invalid password"],400);
            }
        }else{
            return response()->json(["message"=>"This number in not registered"],400);
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
    

    public function sendmail($data){
        Mail::send(array(), array(), function ($message) use ($data) {
            $message->to($data['email'])
              ->subject($data['subject'])
              ->from('notification@emergencytext.com')
              ->setBody($data['html'], 'text/html');
        });
    }
	
	
	public function editDriverProfile(Request $request){

        $url = url("public/files/");
        $userdetails = $request['userDetail'];
       
        $DOCUMENT = Appuser::find($userdetails->id);
        $id_proof = $DOCUMENT->id_proof;
        $driving_license = $DOCUMENT->driving_license; 
        $vehicle_registration_certificate =  $DOCUMENT->vehicle_registration_certificate;
        $taximeter_certificate = $DOCUMENT->taximeter_certificate;

        $type1 = $DOCUMENT->id_proof_verified;
        $type2 = $DOCUMENT->driving_license_verified;
        $type3 = $DOCUMENT->vehicle_registration_certification_verified;
        $type4 = $DOCUMENT->taximeter_certificate_verified;
		
	
        
		$uploadfiles = array();
        if($request->file('id_proof')){
            $id_proof = rand(9999,99999).time().'.'.$request->file('id_proof')->getClientOriginalExtension();
            $request->file('id_proof')->move(public_path('files'), $id_proof);
            $type1 = 0;
			$uploadfiles['id_proof'] = $id_proof;
			$uploadfiles['id_proof_verified'] = $type1;
			$uploadfiles['document_verified'] = 0;
			
        }


        if($request->file('driving_license')){
            $driving_license = rand(9999,99999).time().'.'.$request->file('driving_license')->getClientOriginalExtension();
            $request->file('driving_license')->move(public_path('files'), $driving_license);
            $type2 = 0;
			$uploadfiles['driving_license'] = $driving_license;
			$uploadfiles['driving_license_verified'] = $type2;
			$uploadfiles['document_verified'] = 0;
        }

        if($request->file('vehicle_registration_certificate')){
            $vehicle_registration_certificate = rand(9999,99999).time().'.'.$request->file('vehicle_registration_certificate')->getClientOriginalExtension();
            $request->file('vehicle_registration_certificate')->move(public_path('files'), $vehicle_registration_certificate);
            $type3 = 0;
			$uploadfiles['vehicle_registration_certificate'] = $vehicle_registration_certificate;
			$uploadfiles['vehicle_registration_certification_verified'] = $type3;
			$uploadfiles['document_verified'] = 0;
        }

        if($request->file('taximeter_certificate')){
            $taximeter_certificate = rand(9999,99999).time().'.'.$request->file('taximeter_certificate')->getClientOriginalExtension();
            $request->file('taximeter_certificate')->move(public_path('files'), $taximeter_certificate);
            $type4 = 0;
			$uploadfiles['taximeter_certificate'] = $taximeter_certificate;
			$uploadfiles['taximeter_certificate_verified'] = $type4;
			$uploadfiles['document_verified'] = 0;
        } 

		// if(isset($request->vehicle_testing_date) && !empty($request->vehicle_testing_date)){
		// 	$uploadfiles['vehicle_testing_date'] = $request->vehicle_testing_date;
		// }
		// if(isset($request->expiration_date_of_insurance) && !empty($request->expiration_date_of_insurance)){
		// 	$uploadfiles['expiration_date_insurance'] = $request->expiration_date_of_insurance;
		// }
		// if(isset($request->expiration_date_of_license) && !empty($request->expiration_date_of_license)){
		// 	$uploadfiles['license_expiry_date'] = $request->expiration_date_of_license;
		// }

		/************************Add Vehicle ***********************************/
		
		 $vehicle_image = NULL;
        if($request->file('vehicle_image')){
            $vehicle_image = rand(9999,99999).time().'.'.$request->file('vehicle_image')->getClientOriginalExtension();
            $request->file('vehicle_image')->move(public_path('files'), $vehicle_image);
			$uploadfiles['vehicle_image'] = $vehicle_image;
			$uploadfiles['document_verified'] = 0;
        }
		
		// if(isset($request->organization_type) && !empty($request->organization_type)){
		// 	$uploadfiles['organization_type'] = $request->organization_type;
		// }
		
		// if(isset($request->vehicle_type) && !empty($request->vehicle_type)){
		// 	$uploadfiles['vehicle_type'] = $request->vehicle_type;
		// }
		
		// if(isset($request->vehicle_number) && !empty($request->vehicle_number)){
		// 	$uploadfiles['vehicle_number'] = $request->vehicle_number;
		// }
		
		// if(isset($request->passengers) && !empty($request->passengers)){
		// 	$uploadfiles['passengers'] = $request->passengers;
		// }
		
		// if(isset($request->accept_credit_card) && !empty($request->accept_credit_card)){
		// 	$uploadfiles['accept_credit_card'] = $request->accept_credit_card;
		// }

       
		
		/************************End Vehicle ***********************************/
		
		
		/************************Profile ***********************************/
		
		if($request->file('profile_pic')){
            $profle_pic = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('files'), $profle_pic);
			$uploadfiles['profile_pic'] = $profle_pic;
        }
		
		// if(isset($request->name) && !empty($request->name)){
		// 	$uploadfiles['name'] = $request->name;
		// }
		// if(isset($request->country) && !empty($request->country)){
		// 	$uploadfiles['country'] = $request->country;
		// }
		
		// if(isset($request->city) && !empty($request->city)){
		// 	$uploadfiles['address'] = $request->city;
		// }
		
		$uploadfiles['account_status'] = 0;
		/************************Profile ***********************************/
		
		
		

        $appuser = Appuser::where('id',$userdetails->id)->update($uploadfiles);
		
		
		$DOCUMENT = Appuser::find($userdetails->id);
        $id_proof = $DOCUMENT->id_proof;
        $driving_license = $DOCUMENT->driving_license; 
        $vehicle_registration_certificate =  $DOCUMENT->vehicle_registration_certificate;
        $taximeter_certificate = $DOCUMENT->taximeter_certificate;

        $type1 = $DOCUMENT->id_proof_verified;
        $type2 = $DOCUMENT->driving_license_verified;
        $type3 = $DOCUMENT->vehicle_registration_certification_verified;
        $type4 = $DOCUMENT->taximeter_certificate_verified;

        
		$appuser = Appuser::where('id',$userdetails->id)->update(['account_status'=>0]);


        $appuser = Appuser::with(["city"=>function($query){
            $query->select(['id','name'])->get();
        },"country"=>function($query){
            $query->select(['id','name'])->get();
        }])->where('id',$userdetails['id'])->selectRaw("*,concat('".$url."/',profile_pic) as profile_pic")->first();

        return response()->json(["message"=>"Profile Updated Successfully","userdetails"=>$appuser],200);
		

    }
	
    public function edituploaddocument(Request $request){

        $url = url("public/files/");
        $userdetails = $request['userDetail'];
        $validation = array(
            // "id_proof"=>"required",
            // "driving_license"=>"required",
            // "vehicle_registration_certificate"=>"required",
            // "taximeter_certificate"=>"required",
            "vehicle_testing_date"=>"required",
            "expiration_date_of_insurance"=>"required",
            "expiration_date_of_license"=>"required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if($Validator->fails()){
            $response['message'] = $Validator->errors($Validator)->first();
            return Response::json($response, 400);
        }
        $DOCUMENT = Appuser::find($userdetails->id);
        $id_proof = $DOCUMENT->id_proof;
        $driving_license = $DOCUMENT->driving_license; 
        $vehicle_registration_certificate =  $DOCUMENT->vehicle_registration_certificate;
        $taximeter_certificate = $DOCUMENT->taximeter_certificate;
        $profle_pic = $DOCUMENT->profle_pic;
        $vehicle_image = $DOCUMENT->vehicle_image; 
        $type1 = $DOCUMENT->id_proof_verified;
        $type2 = $DOCUMENT->driving_license_verified;
        $type3 = $DOCUMENT->vehicle_registration_certification_verified;
        $type4 = $DOCUMENT->taximeter_certificate_verified;

        $msg ="emergency taxi verification OTP ";
        $this->sendsms($msg,$DOCUMENT->country_code.$DOCUMENT->mobile_no);

        if($request->file('id_proof')){
            $id_proof = rand(9999,99999).time().'.'.$request->file('id_proof')->getClientOriginalExtension();
            $request->file('id_proof')->move(public_path('files'), $id_proof);
            $type1 = 0;
        }


        if($request->file('driving_license')){
            $driving_license = rand(9999,99999).time().'.'.$request->file('driving_license')->getClientOriginalExtension();
            $request->file('driving_license')->move(public_path('files'), $driving_license);
            $type2 = 0;
        }

        if($request->file('vehicle_registration_certificate')){
            $vehicle_registration_certificate = rand(9999,99999).time().'.'.$request->file('vehicle_registration_certificate')->getClientOriginalExtension();
            $request->file('vehicle_registration_certificate')->move(public_path('files'), $vehicle_registration_certificate);
            $type3 = 0;
        }

        if($request->file('taximeter_certificate')){
            $taximeter_certificate = rand(9999,99999).time().'.'.$request->file('taximeter_certificate')->getClientOriginalExtension();
            $request->file('taximeter_certificate')->move(public_path('files'), $taximeter_certificate);
            $type4 = 0;
        }        

        if($request->file('profile_pic')){
            $profle_pic = rand(9999,99999).time().'.'.$request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('files'), $profle_pic);
			
        }
        
        
        if($request->file('vehicle_image')){
            $vehicle_image = rand(9999,99999).time().'.'.$request->file('vehicle_image')->getClientOriginalExtension();
            $request->file('vehicle_image')->move(public_path('files'), $vehicle_image);
		
        }

        $appuser = Appuser::where('id',$userdetails->id)->update(["vehicle_testing_date"=>$request->vehicle_testing_date,
        "expiration_date_insurance"=>$request->expiration_date_of_insurance,
        "license_expiry_date"=>$request->expiration_date_of_license,
        "id_proof"=>$id_proof,"driving_license"=>$driving_license,"profile_pic"=>$profle_pic,
        "is_uploaded"=>1,'account_status'=>0,"vehicle_image"=>$vehicle_image,
        "vehicle_registration_certificate"=>$vehicle_registration_certificate,"taximeter_certificate"=>$taximeter_certificate,"account_status"=>3,"id_proof_verified"=>$type1,"driving_license_verified"=>$type2,"vehicle_registration_certification_verified"=>$type3,"taximeter_certificate_verified"=>$type4]);


        $appuser = Appuser::with(["city"=>function($query){
            $query->select(['id','name'])->get();
        },"country"=>function($query){
            $query->select(['id','name'])->get();
        }])->where('id',$userdetails['id'])->selectRaw("*,concat('".$url."/',profile_pic) as profile_pic")->first();

        return response()->json(["message"=>"Vehicle details uploaded successfully","userdetails"=>$appuser],200);

    }
}
