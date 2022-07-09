<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PSTPagoFacil\PSTPagoFacil;
use App\subadmin;
use Log;
use Auth;
use Hash;
use App\Appuser;
use App\Coupon;
use App\CouponLog;
use Mail;
use App\Organizationtype;
use App\Servicetype;
use App\Vehicletype;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Documentverfied;
use App\Subscriber;
use App\DriverAvailability;
use App\Subscription;
use App\Order;
use App\ReferEarnLog;
use Carbon\Carbon;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    //
    public function availableStatus(Request $request, $type)
    {
        $userdetails = $request['userDetail'];
        Log::info(["userdetails" => $request['userDetail'], "type" => $type]);
        //dd($type);
        switch ($type) {
            case 1:
                if ($userdetails['subscribed'] == 1) {
                    $subs = Subscriber::where(['driver_id' => $userdetails['id'], 'expire' => 0])->orderBy('id', 'DESC')->first();
                    if ($subs) {
                        if ($subs->type == 2) {
                            $data = DriverAvailability::where('subscriber_id', $subs->id)->count();
                            if ($data <= $subs->duration) {
                                DriverAvailability::firstOrCreate(['driver_id' => $userdetails['id'], 'subscriber_id' => $subs->id, 'date' => date('Y-m-d')]);
                            } else {
                                DriverAvailability::where('subscriber_id', $subs->id)->update(['status' => 1]);
                                Subscriber::where('id', $subs->id)->update(['expire' => 1]);
                                $userdetails->subscribed = 0;
                            }
                        } elseif ($subs->type == 1) {
                            $now = strtotime(date('Y-m-d')); // or your date as well
                            $your_date = strtotime(date('Y-m-d', strtotime($subs->created_at)));
                            $datediff = $now - $your_date;
                            $sss = round($datediff / (60 * 60 * 24));
                            $remaindays = $subs->duration - $sss;
                            if ($remaindays <= 0) {
                                Subscriber::where('id', $subs->id)->update(['expire' => 1]);
                                $userdetails->subscribed = 0;
                            }
                        }
                    }
                }
                $userdetails->available_status = 1;
                $userdetails->save();
                break;

            case 0:
                $userdetails->available_status = 0;
                $userdetails->save();
                break;
        }
        $appuser = Appuser::where('id', $userdetails['id'])->first();
        return response()->json(["message" => "Vehicle details uploaded successfully", "userdetails" => $appuser], 200);
    }
    public function applycoupen(Request $request)
    {
        $userdetails = $request['userDetail'];
        $validation = array(
            "coupen" => "required"

        );
        $Validator = Validator::make($request->all(), $validation);
        if ($Validator->fails()) {
            $response['message'] = $Validator->errors($Validator)->first();
            return response()->json($response, 400);
        }
        $appuser = Appuser::where('id', $userdetails['id'])->get();
        return response()->json(["message" => "coupon applied successfully", "userdetails" => $appuser], 200);
    }
    public function coupen(Request $request)
    {
        $userdetails = $request['userDetail'];
        $validation = array(
            "coupen" => "required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if ($Validator->fails()) {
            $response['message'] = $Validator->errors($Validator)->first();
            return response()->json($response, 400);
        }
        $driver = Appuser::where(['personal_id' => $request->coupen])->first();
        $subscription = Subscription::where('id', 5)->first();
        if (!empty($driver)) {

            $datasubscribe = Subscriber::where(['driver_id' => $userdetails['id']])->first();
            if ($datasubscribe) {
                $datasubscribe = Subscriber::where(['driver_id' => $userdetails['id']])->first();
                return response()->json(["message" => "you have already coupon applied successfully", "userdetails" => $datasubscribe], 200);
            }
            $data = Subscriber::insert(['driver_id' => $userdetails['id'], 'subscription_id' => $subscription->id, 'expire' => 0, 'duration' => $subscription->day, 'type' => 2]);

            $appuser = Appuser::where('id', $userdetails['id'])->update(['subscribed' => 1]);

            return response()->json(["message" => "coupon applied successfully", "userdetails" => $appuser], 200);
        } else {
            if ($request->coupen == 'gift2021') {
                $data = Subscriber::insert(['driver_id' => $userdetails['id'], 'expire' => 0, 'subscription_id' => $subscription->id, 'duration' => $subscription->day, 'type' => 2]);

                $appuser = Appuser::where('id', $userdetails['id'])->update(['subscribed' => 1]);
                return response()->json(["message" => "coupon applied successfully", "userdetails" => $appuser], 200);
            } else {
                return response()->json(["message" => "invalid COUPEN"], 400);
            }
        }
    }

    //This function will handle both coupon codes, coupon code generated by admin and personal_id of driver.
    public function redeemCoupon(Request $request)
    {
        try {
            $fields = [
                "coupon_code" => "required"
            ];
            $validator  = Validator::make($request->all(), $fields);
            if ($validator->fails()) {
                $errMsg = $validator->errors($validator)->first();
                return $this->returnResponse(false, $errMsg, [], [], 400);
            } else {
                $driver = $request['userDetail'];

                $activeSubscription = Subscriber::query()
                    ->where("driver_id", $driver->id)
                    //->where("is_active", "1")
                    ->orderBy("created_at", "DESC")
                    ->first();
                if ($activeSubscription) {
                    $isActive = "0";
                    if ($activeSubscription->expired_on<date('Y-m-d H:i:s')) {
                        $subscriptionStartDate =  Carbon::now();
                    }else{
                        $subscriptionStartDate = new Carbon($activeSubscription->expired_on);
                    }
                } else {
                    $subscriptionStartDate =  Carbon::now();
                    $isActive = "1";
                }
                if ($driver) {
                    $code = request('coupon_code');

                    // If coupon code is numeric then it means the coupon code is personal id of the driver.
                    if (strpos($code,'T_')>-1) {
                        $referByUser = Appuser::query()->where("personal_id", $code)->first();
                        $referToUser = $driver;
                        if ($referByUser) {
                            $isAlreadyClaimed = ReferEarnLog::query()
                                ->where("refer_to", $referToUser->id)
                                ->exists();
                            $isAlreadyClaimed = Subscriber::query()
                                ->where("driver_id", $referToUser->id)
                                ->exists();
                            if ($isAlreadyClaimed) {
                                return $this->returnResponse(false, "You can't redeem this coupon, you're already susbscribed.", [], [], 200);
                            } else {
                                $relObj = new ReferEarnLog();
                                $relObj->refer_by = $referByUser->id;
                                $relObj->refer_to = $referToUser->id;
                                $relObj->free_days_to_refer_by = 0; // This driver will get his bonus when the 2nd driver will   a subscription.
                                $relObj->free_days_to_refer_to = config("constants.refer_to_free_days");
                                $relObj->save();

                                $referTOSubsObj = new Subscriber();
                                $referTOSubsObj->driver_id = $referToUser->id;
                                $referTOSubsObj->subscription_id = 12;
                                $referTOSubsObj->duration = config("constants.refer_to_free_days");
                                $referTOSubsObj->type = 1; // By calendar
                                $referTOSubsObj->is_active = $isActive;

                                $addDays = $subscriptionStartDate->addDays(config("constants.refer_to_free_days"));

                                $referTOSubsObj->expired_on = $addDays;
                                $referTOSubsObj->save();

                                $driver->subscribed = "1";
                                $driver->subscription_expire_date =  $addDays;
                                $driver->save();
                                return $this->returnResponse(true, "Coupon redeemed successfully.", [], [], 200);
                            }
                        } else {
                            return $this->returnResponse(false, "Invalid Coupon.", [], [], 200);
                        }
                    } else {
                        $coupon = Coupon::query()->where("coupon_code", $code)->first();
                        if ($coupon) {
                            // only selected drivers can use this coupon
                            $couponLog = CouponLog::query()
                                ->where("driver_id", $driver->id)
                                ->where("coupon_id", $coupon->id)
                                ->where("is_used", "0")
                                ->first();
                            if ($coupon->type == "1") {
                                if (!$couponLog) {
                                    return $this->returnResponse(false, "Either this coupon code is already redeemed or it can be rededmed only by those drivers who got this by email or sms.", [], [], 200);
                                }
                                $couponLog->is_used = "1";
                            } else {
                                $couponLog = CouponLog::query()
                                    ->where("driver_id", $driver->id)
                                    ->where("coupon_id", $coupon->id)
                                    ->where("is_used", "1")
                                    ->first();
                                if ($couponLog) {
                                    return $this->returnResponse(false, "This coupon code is already redeemed by you.", [], [], 200);
                                }
                                $couponLog = new CouponLog();
                                $couponLog->driver_id = $driver->id;
                                $couponLog->coupon_id = $coupon->id;
                                $couponLog->is_used = "1";
                            }
                            $couponLog->save();

                            $referTOSubsObj = new Subscriber();
                            $referTOSubsObj->driver_id = $driver->id;
                            $referTOSubsObj->subscription_id = 12;
                            $referTOSubsObj->duration = $coupon->days;
                            $referTOSubsObj->type = 1; // By calendar
                            $addDays = $subscriptionStartDate->addDays($coupon->days);
                            $referTOSubsObj->expired_on = $addDays;
                            $referTOSubsObj->is_active = $isActive;
                            $referTOSubsObj->save();
                            $driver->subscribed = "1";
                            $driver->subscription_expire_date =  $addDays;
                            $driver->save();
                            return $this->returnResponse(true, "Coupon redeemed successfully.", [], [], 200);
                        } else {
                            return $this->returnResponse(false, "Invalid Coupon.", [], [], 200);
                        }
                    }
                } else {
                    return $this->returnResponse(false, "Driver not found.", [], [], 401);
                }
            }
        } catch (\Throwable $th) {
            return $this->returnResponse(false, $th->getMessage(), [], [], 500);
        }
    }

    public function payNow(Request $request)
    {
        $userdetails = $request['userDetail'];
        $validation = array(
            "subscription_id" => "required"
        );
        $Validator = Validator::make($request->all(), $validation);
        if ($Validator->fails()) {
            $response['message'] = $Validator->errors($Validator)->first();
            return response()->json($response, 400);
        }
        try {
            $data = Subscription::find($request->subscription_id);
            if ($data) {

                $order_id = rand(9999, 99999);
                Order::create(['order_id' => $order_id, 'driver_id' => $userdetails['id'], 'amount' => $data->plan_price, 'subscription_id' => $request->subscription_id]);

                $token_user = '1e81dd1b-678c-4d42-8ee6-be7c926b419c';

                // instantiate the class
                $pagoFacil = new PSTPagoFacil($token_user);
                $pagoFacil->sandbox_mode(true);
                $transaction = array(
                    'x_url_callback' => url('notify') . "?order_id=" . $order_id,
                    'x_url_cancel' => url('cancel') . "?order_id=" . $order_id,
                    'x_url_complete' => url('complete') . "?order_id=" . $order_id,
                    'x_customer_email' => 'info@emergencytaxi.com',
                    'x_reference' => time(),
                    'x_account_id' => '1234', // Id service
                    'x_amount' => $data->plan_price,
                    'x_currency' => 'CLP',
                    'x_shop_country' => 'CL',
                    'x_signature' => hash_hmac('sha256', '', $token_user),
                    'x_session_id' => date('Ymdhis') . rand(0, 9) . rand(0, 9) . rand(0, 9)
                );
                $data = $pagoFacil->createPayTransaction($transaction);

                if (@$data->error) {
                    return response()->json(["message" => "something went wrong"], 400);
                }
                $response['message'] = "Succees";
                $response['data'] = ['idTrx' => $data->idTrx, 'payUrl' => $data->payUrl];
                return response()->json($response, 200);
            } else {
                $response['message'] = "Invalid Id.";
                return response()->json($response, 400);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function complete(Request $request)
    {
        $order = Order::where('order_id', $request['order_id'])->first();
        $order->status = 1;
        $order->save();
        $subs = Subscription::find($order->subscription_id);
        $activeSubscription = Subscriber::query()
        ->where("driver_id", $order->driver_id)
        ->orderBy("created_at", "DESC")
        ->first();
        if ($activeSubscription) {
            $isActive = "0";
            $subscriptionStartDate = new Carbon($activeSubscription->expired_on);
        } else {
            $subscriptionStartDate =  Carbon::now();
            $isActive = "1";
        }
        $addDays = $subscriptionStartDate->addDays($subs->days);
        Subscriber::create(['driver_id' => $order->driver_id, 'subscription_id' => $order->subscription_id, 'duration' => @$subs->days, 'type' => @$subs->plan_type,"expired_on"=>$addDays,"is_active"=>$isActive]);
        Appuser::where('id', $order->driver_id)->update(['subscribed' => 1,"subscription_expire_date"=>$addDays]);
        echo "Subscription - Payment successfully.";
    }
    public function notify(Request $request)
    {
        $order = Order::where('order_id', $request['order_id'])->first();
        $order->status = 3;
        $order->save();
        echo "Something went wrong.";
    }
    public function cancel(Request $request)
    {
        $order = Order::where('order_id', $request['order_id'])->first();
        $order->status = 2;
        $order->save();
        echo "Subscription - Payment cancel.";
    }

    public function getDriverDetail(Request $request)
    {

        $userdetails = $request['userDetail'];
        $appuser = Appuser::with(["organizationtype" => function ($query) {
            $query->get();
        }, "vehicletype" => function ($query) {
            $query->select(['id', 'name'])->get();
        }, "verified"])->where('id', $userdetails->id)->first();


        return response()->json(["message" => "details getting successfully", "appuser" => $appuser]);
    }

    public function driverSubscription(Request $request)
    {
        $userdetails = $request['userDetail'];
        $data = Subscriber::select('*', 'subscribers.id as s_id', 'subscribers.created_at as created_at')->where('driver_id', $userdetails->id)
        
        ->join('subscriptions', 'subscribers.subscription_id', '=', 'subscriptions.id')->orderBy('subscribers.created_at', 'DESC')->first();
        $arr = [];
        if ($data) {
            $couponLog = null;
            if ($data->subscription_id==12) {
                $couponLog = CouponLog::query()
                ->with("coupon")
                ->where('driver_id',$userdetails->id )
                ->where('is_used',"1")
                ->orderBy('created_at','desc')
                ->first();
            }
            $value = $data;
            $key = 0;
                if ($value->type == 2) {
                    $d = DriverAvailability::where('subscriber_id', $value->s_id)->get();
                    $count = $d->count();
                    $arr[$key]['remaining_days'] = ($count <= $value->days) ? $value->duration - $count : 0;
                    $expire = $value->expire;
                    if ($arr[$key]['remaining_days'] == 0) {
                        Subscriber::where('id', $value->s_id)->update(['expire' => 1]);
                        $expire = 1;
                    }
                    $arr[$key]['expire'] = $expire;
                } elseif ($value->type == 1) {
                    $date = strtotime($value->created_at);

                    $d = "+" . $value->duration . " days";
                    $newDate = strtotime($d, $date);
                    $checkDatePlan =  date('Y-m-d', $newDate);

                    $expire = $value->expire;
                    if (strtotime($checkDatePlan) <= strtotime(date('Y-m-d'))) {
                        Subscriber::where('id', $value->s_id)->update(['expire' => 1]);
                        $expire = 1;
                    }
                    $arr[$key]['expire_on'] = strtotime($value->expired_on);
                    $arr[$key]['expire'] = $expire;
                }
            
                if ($couponLog) {
                    $arr[$key]['duration'] = $couponLog->coupon->days;
                    $arr[$key]['plan_name'] = $couponLog->coupon->coupon_code;
                    $arr[$key]['description'] =  $couponLog->coupon->description;
                    $arr[$key]['plan_price'] = 0;
                    $arr[$key]['type'] = $value->type;
                }else{
                    $arr[$key]['duration'] = $value->duration;
                    $arr[$key]['plan_name'] = $value->plan_name;
                    $arr[$key]['description'] = $value->plan_description;
                    $arr[$key]['plan_price'] = $value->plan_price;
                    $arr[$key]['type'] = $value->type;
                }
                
            
        }
        return response()->json(["message" => "list", "data" => $arr, 'subscribed' => $userdetails->subscribed], 200);
    }


    // This function will send Notification to all drivers whose subscription date is near to expire day before 3,4 or 5 days
    public function subscriptionExpirePreNotification(){        
        $to = Carbon::now();
        $from = $to->subDay(5);
        $driversIds = Appuser::query()->whereBetween("subscription_expire_date",[$from,$to])->get(["id","country_code","mobile_no"]);
        $message = "Dear drivers, your subscription is near to expire. Please renew your subscription before expire to continue your services. Please ignore if already renewed. Radar Taxi";
        $mobileNumbers = [];
        
        if(!blank($driversIds)){
            $drivers = Appuser::query()->find($driversIds);
            foreach ($drivers as $key => $value) {
                if(!empty($value->country_code) && !empty($value->mobile_no)){
                    array_push($mobileNumbers,"{$value->country_code}{$value->mobile_no}");
                }
            }
            if (!empty($mobileNumbers)) {
                $resp = $this->sendsms($message,$mobileNumbers);
                return $this->returnResponse(true,"Subscription expire notification sent successfully.");
            }
        }else{
          return  $this->returnResponse(false,"No drivers found");
        }      
    }

    // This function will update subscribed column for all drivers of they have not active subscription and their subscription date is expired
    public function subscriptionExpire(){
        $now = Carbon::now();
        Appuser::query()->where("subscription_expire_date","<",$now)->update(["subscribed"=>"0"]);
        return $this->returnResponse(true,"Subscription expired");
    }

    public function logout(Request $request)
    {
        $userdetails = $request['userDetail'];
        if ($userdetails) {
            $userdetails->is_logged_in = '0';
            $userdetails->available_status = 0;
            $userdetails->accesstoken = "";
            $userdetails->device_token = "";
            $userdetails->save();
            return response()->json(["message" => "Driver logged out successfully"]);
        } else {
            return response()->json(["message" => "Something went wrong, please try again later."], 400);
        }
    }
}
