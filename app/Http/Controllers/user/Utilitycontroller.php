<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\City;
use App\Staticcontent;
use App\Subscription;

class Utilitycontroller extends Controller
{
    public function index()
    {
        $contents = Staticcontent::query()->get(["id","title","content"]);
        return $this->returnResponse(true,"Message sent successfully.",$contents);    
    }

    public function first_country(){
        $country = Country::with('city')->where('id',1)->get();
        return response()->json(["country"=>$country,"message"=>"first country list getting successfully"]);
    }

    public function filter_country_data($id){
        $country = Country::with('city')->where('id',$id)->get();
        return response()->json(["country"=>$country,"message"=>"country city list getting successfully"]);
    }

    public function all_country_list(){
    	$country = Country::get();
        return response()->json(["country"=>$country,"message"=>"country lists getting successfully"]);
    }
    public function get_list($country_code){

        $data = Subscription::where('country_code',$country_code)->where('status',1);
       
        $data = $data->orderBy('id','DESC')->get();
        return response()->json(["message"=>"success","plan_list"=>$data],200);
    }


    public function sendSMSAPI(Request $request)
    {
        try {
            if (!$request->has('message')) {
                return $this->returnResponse(false,"Message field is required",[]);
            }elseif(!$request->has('mobile')){
               return $this->returnResponse(false,"Mobile field is required with country code.",[]);
            }else{
                $this->sendsms($request->get('message'),$request->get('mobile'));
                return $this->returnResponse(true,"Message sent successfully.",[]);    
            }
        } catch (\Throwable $th) {
            return $this->returnResponse(false,$th->getMessage(),[]);
        }        
    }

}
