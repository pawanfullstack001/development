<?php

namespace App\Http\Controllers;

use App\PricePlan;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function returnResponse($status = true, $message = null, $data = [], $metaParams = null, $httpStatus = 200)
    {
        return response()->json(["status" => $status, "message" => $message,  "data" => $data, "metaParams" => $metaParams], $httpStatus);
    }


    public function sendsms($msg, $mobile)
    {
      try {
        if(strpos('+',$mobile)===false){
          $mobile='+'.$mobile;
        }  
        $sid = 'AC9774bc1e46370ed29b62efd20be8f348';
        $token = '56e2f9d9f0ff565e52608b3c0e0d0d27';
        $client = new Client($sid, $token);    
        $message =  $client->messages->create(
          $mobile,
          array(
            'from' => '+17574483216',
            'body' => $msg
          )
        );
        return 1;
      } catch (\Exception $e) {
        $response = [
          'message' => $e->getMessage()
        ];     
        return $response;
      }
    }
    public function sendPushNotification($payload)
    {
      try {       
        $header=array('Content-Type: application/json',"Authorization: key=AAAAykfFoZY:APA91bGbTXt4WXKqvvRJ9aQwPjEPe8FY73ansz5auxJUhS6xAB1wf3wO7osTDgzBGfrz5E6qwd6P3rRidj1Y0OOn8su2pvhVHkdX2fGWRqwIE3x2NESSjWXHR7m6qNpaKGaLcOBJfOXz");
        $url = "https://fcm.googleapis.com/fcm/send";
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        Log::info("Push Noti Resp ".json_encode($result));       
        return true;        
      } catch (\Exception $e) {
        $response = [
          'message' => $e->getMessage()
        ];   
        Log::info("Push Noti Resp ".json_encode($response));
        return false;
      }
    }

    public function calculatePrice($country,$distance)
    {
        $planCollection = PricePlan::query()
        ->where('country',$country)
        ->get();
        
        $price = 0;
        $plans = [];
        if(!empty($planCollection)){
            $meter = ($distance*1000);
            foreach ($planCollection as $key => $planObj) {                
                $price = $planObj->first_p_price+((($meter-$planObj->first_x_mt)/$planObj->segment_mt)*$planObj->segment_price);
                $planArray = [
                    "fareName"=>$planObj->fare_name,
                    "currency"=>$planObj->currency,
                    "price"=>round($price,2),
                ];
                array_push($plans,$planArray);
            }         
        }
        return response()->json($plans);
    }
}
