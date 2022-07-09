<?php

namespace App\Http\Controllers\user;

use App\Appuser;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\PricePlan;
use App\Servicetype;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userdetails = $request['userDetail'];
        $bookings = Booking::query()->where("driver_id",$userdetails["id"])->orderBy("created_at","desc")->get();
       return $this->returnResponse(true,"Bookings List",$bookings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	/////// Pawan ///////
	public function bookingList($uid){
		$date = date("Y-m-d h:i:s");
		$todayBooking = Booking::query()->where("driver_id",$uid)->select('id','created_at','price')->get();
		$today = [];$oneWeek = [];$oneMonth = [];$t=0;$s=0;$m=0;
		$todayAmt = 0;$oneWeekAmt = 0;$oneMonthAmt = 0;
		foreach ($todayBooking as $key => $val){
			$dbtime = $val->created_at;
			$minetime = $date;
			$diff = abs(strtotime($minetime) - strtotime($dbtime));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			if($days<=1){
				$todayAmt+= $val->price;
				$t++;
			}
			if($days<=7 && $days>1){
				$oneWeekAmt+= $val->price;
				$s++;
			}
			if($days<=30 && $days>1){
				$oneMonthAmt+= $val->price;
				$m++;
			}
        }
		$arrayOne = array('booking_text'=>'Hoy Viajes','amt_text'=>'Cobrado','total_booking'=>$t,'total_amount'=>$todayAmt,'user_id'=>$uid,'day'=>1);
		$arrayTwo = array('booking_text'=>'7 Dias Viajes','amt_text'=>'Cobrado','total_booking'=>$s,'total_amount'=>$oneWeekAmt,'user_id'=>$uid,'day'=>7);
		$arrayThree = array('booking_text'=>'30 dias Viajes','amt_text'=>'Cobrado','total_booking'=>$m,'total_amount'=>$oneMonthAmt,'user_id'=>$uid,'day'=>30);
		$result=array('status'=>200,'todayBooking'=>$arrayOne,'oneWeek'=>$arrayTwo,'oneMonth'=>$arrayThree,'message'=>'booking list loaded successfully');
		echo json_encode($result);
    }
	public function customerBookings($uid,$day){
		$date = date("Y-m-d h:i:s");
		$todayBooking = Booking::query()->where("driver_id",$uid)->select('id','created_at','price')->get();
		$today = [];$oneWeek = [];$oneMonth = [];$t=0;$s=0;$m=0;
		$todayAmt = 0;$oneWeekAmt = 0;$oneMonthAmt = 0;
		foreach ($todayBooking as $key => $val){
			$dbtime = $val->created_at;
			$minetime = $date;
			$diff = abs(strtotime($minetime) - strtotime($dbtime));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			if($days<=1){
				$today[$t]['id'] = $val->id;
				$today[$t]['date'] = date("Y-m-d h:i:s",strtotime($dbtime));
				$today[$t]['customer'] = $uid;
				$today[$t]['price'] = $val->price;
				$t++;
			}
			if($days<=7 && $days>1){
				$oneWeek[$s]['id'] = $val->id;
				$oneWeek[$s]['date'] = date("Y-m-d h:i:s",strtotime($dbtime));
				$oneWeek[$s]['customer'] = $uid;
				$oneWeek[$s]['price'] = $val->price;
				$s++;
			}
			if($days<=30 && $days>1){
				$oneMonth[$m]['id'] = $val->id;
				$oneMonth[$m]['date'] = date("Y-m-d h:i:s",strtotime($dbtime));
				$oneMonth[$m]['customer'] = $uid;
				$oneMonth[$m]['price'] = $val->price;
				$m++;
			}
        }
		if($day==1){
			$array = $today;
		}else if($day==7){
			$array = $oneWeek;
		}else{
			$array = $oneMonth;
		}
		$result=array('status'=>200,'list'=>$array,'message'=>'booking list loaded successfully');
		echo json_encode($result);
    }
	public function bookingDetails($id){
		$date = date("Y-m-d h:i:s");
		$booking = Booking::query()->where("id",$id)->first();
		
		$result=array('status'=>200,'data'=>$booking,'message'=>'Detail Found');
		echo json_encode($result);
    }
	/////// Pawan ///////
    public function create()
    {
        //
    }


    public function getDriverKpis(Request $request)
    {
       $type = request('type');
       if (empty($type)) {
        return response()->json(["status"=>false,"message" => "Type key is required. Possible types are:m,q,y,r"]);
       }
       $userdetails = $request['userDetail'];
       $driverId = $userdetails["id"];
       $to = Carbon::now();
       $from = Carbon::now();       
       switch ($type) {
           case 'm':
               $from = $from->subMonth(1)->format('Y-m-d H:i:s');
               break;
           case 'q':
            $from = $from->subMonth(3)->format('Y-m-d H:i:s');
               break;
           case 'y':
            $from = $from->subMonth(12)->format('Y-m-d H:i:s');          
               break;
           case 'r':
            $from =  request('from');            
            $to =  request('to');            
               break;           
           default:
               break;
       }
      // $to = $to->format('Y-m-d H:i:s');

       $totalBookings = Booking::query()->where("driver_id",$driverId)->whereBetween("created_at",[$from,$to])->count();
       $completedBookings = Booking::query()->where("driver_id",$driverId)->where("status","3")->whereBetween("created_at",[$from,$to])->count();
       $rejectedBookings = Booking::query()->where("driver_id",$driverId)->where("status","2")->whereBetween("created_at",[$from,$to])->count();

       //dd(DB::getQueryLog());

       $bookingCounts = [
           "total"=>$totalBookings,
           "completed"=>$completedBookings,
           "rejected"=>$rejectedBookings
       ];

       return response()->json(["status"=>true,"message" => "Driver KPIs fetched successfully.","bookingCounts"=>$bookingCounts]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(BookingRequest $request)
    {
        try {
            $driver = Appuser::query()->find(request('driver_id'));
            $booking = new Booking();
            $booking->driver_id = request('driver_id');
            $booking->booking_id = "RT".time();
            
            $booking->source = request('source');
            $booking->destination = request('destination');
            $booking->latitude = request('latitude');
            $booking->longitude = request('longitude');
            $booking->taxi_lat = $driver->latitude;
            $booking->taxi_lng = $driver->longitude;
            $booking->cust_name = request('customer_name');
            $booking->cust_mob = request('customer_mob');
            $booking->destination_lat = request('destination_lat');
            $booking->destination_lng = request('destination_lng');
            $booking->distance = request('distance');
            $booking->country = request('country');
            $booking->duration = request('duration');

            $serviceTypeId = $driver->service_type;

            $distance = round(str_replace(" km","",request('distance')),2);
            $meter = ($distance*1000);

            $service = Servicetype::query()->find($serviceTypeId);
            $vehicleType = $service->vehicle_type;
            $planObj = PricePlan::query()->where("country",request('country'))->where("service_type",$vehicleType)->first();
            $booking->plan_id = $planObj->id;
            $price = $planObj->first_p_price+((($meter-$planObj->first_x_mt)/$planObj->segment_mt)*$planObj->segment_price);
            $booking->price = (int) round($price,2);
            $booking->status = "0";
            $booking->save();
                if($driver->device_token != "" || $driver->device_token){
                    $datatosend = [
                        "to" => $driver->device_token,                        
                        "data" =>   [
                            "body" => "New taxi request",
                            "title" => "Emergency taxi",
                            "booking_obj" => $booking,                       
                        ]
                    ];                
                    $datatosend = json_encode($datatosend);  
                    $this->sendPushNotification($datatosend);
                    return $this->returnResponse(true,"Booking created successfully, please wait for driver confirmation",$booking);
                }else{
                    return  $this->returnResponse(true,"Booking created successfully,please wait for driver confirmation",$booking);
                }             
            } catch (\Throwable $th) {             
             return $this->returnResponse(false,$th->getMessage(),[]);
        }           
    }

    public function showBookingFeedbackForm($booking_id)
    {
        $booking = Booking::query()->where('booking_id',$booking_id)->first();
        if ($booking) {
            return view('admin.booking_review');
        }else{
            abort(404);
        }       
    }

    public function storeBookingFeedbackForm(Request $request,$booking_id)
    {     
        try {
            $booking = Booking::query()->where('booking_id',$booking_id)->first();
        if ($booking) {
            $booking->rating = request('rating');
            $booking->review = request('review');
            $booking->save();
            return back()->with("message","Thanks for your feedback.");
        }else{
            abort(404);
        }
        } catch (\Throwable $th) {
            return back();
        }  
               
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $id)
    {
        return $this->returnResponse(true,"Booking Details",$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Booking $id)
    {
        try {
            $booking = $id;
            $status = request('status');
            if ($status=='0') {
                return $this->returnResponse(false,"Something went wrong, booking status cannot be changed",[]);
            }else{                
                // 1 For accept, 2 for reject,3 for completed,4-cancelled
                if ($status=="1" || $status=="2" || $status=="3" || $status=="4") {
                    if ($status==3) {
                       $driver = Appuser::query()->find($booking->driver_id);
                       $driver->available_status = 1;
                       $booking->boarded_time = Carbon::now();
                       $driver->save();
                       $route = route('booking_feedback',['booking_id'=>$booking->booking_id]);
                       $this->sendsms("How was your ride? Please give a feedback to this ride by clicking on this link {$route}",$booking->cust_mob);
                    }elseif($status==2){
                        $driver = Appuser::query()->find($booking->driver_id);
                        $driver->available_status = 0;
                        $driver->save();
                    }
                    $booking->status = $status;
                    $booking->save();
                    return $this->returnResponse(true,"Booking status changed successfully.",$booking);
                }else{
                    return $this->returnResponse(false,"Something went wrong, booking status cannot be changed.",[]);
                }
            }
        } catch (\Throwable $th) {
            return $this->returnResponse(false,$th->getMessage(),[]);
        }
        
    }
    public function update(Request $request, Booking $id)
    {
        try {
            $booking = $id;
            if (empty(request('note'))) {
                return $this->returnResponse(false,"Please provide your message for the driver",[]);
            }else{                
                $booking->driver_notes =  request('note');
                $booking->save();
                $booking->load('driver');
                if($booking->driver->device_token != "" || $booking->driver->device_token){
                    $datatosend = [
                        "to" =>$booking->driver->device_token,                        
                        "data" =>   [
                            "body" => request('note'),
                            "title" => "Note from customer",
                            "booking_obj" => $booking,                       
                        ]
                    ];                
                    $datatosend = json_encode($datatosend);  
                    $this->sendPushNotification($datatosend);                   
                }
                return $this->returnResponse(true,"Your message sent to the driver.",$booking);               
            }
        } catch (\Throwable $th) {
            return $this->returnResponse(false,$th->getMessage(),[]);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
