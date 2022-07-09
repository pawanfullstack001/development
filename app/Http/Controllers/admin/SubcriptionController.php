<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\PricePlan;
use App\Appuser;

class SubcriptionController extends Controller
{
    //
    public function index(){
    	$data = Subscription::whereIn('status',[0,1])->orderBy('id','DESC')->get()->toArray();
        $freeplan = Subscription::where('status',2)->first();
    	return view('admin/subscription',compact('data','freeplan'));
    }
    public function create(Request $request){
    	//dd($request->all());
    	$date_from = strtotime($request->date_from);
    	$date_to = strtotime($request->date_to);
    	if($request->id){
            $image = Subscription::find($request->id)->image;
            if($request->file('image')){
                $image = rand(9999,99999).time().'.'.$request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('files'), $image);
            }
    		Subscription::where('id',$request->id)->update(['country'=>$request->country,'country_code'=>$request->country_code,'currency'=>$request->currency,'per_day'=>$request->per_day,'payment'=>$request->payment,'daily_text'=>$request->daily_text,'plan_name'=>$request->name,'plan_description'=>$request->description,'plan_price'=>$request->price,'days'=>$request->no_of_day,'plan_type'=>$request->plan_type,'image'=>$image]);
    		$msg = "Plan edit successfully";
    	}else{
            $image = "";
            if($request->file('image')){
                $image = rand(9999,99999).time().'.'.$request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('files'), $image);
            }
    		Subscription::create(['country'=>$request->country,'country_code'=>$request->country_code,'currency'=>$request->currency,'per_day'=>$request->per_day,'payment'=>$request->payment,'daily_text'=>$request->daily_text,'plan_name'=>$request->name,'plan_description'=>$request->description,'plan_price'=>$request->price,'days'=>$request->no_of_day,'plan_type'=>$request->plan_type,'image'=>$image]);
    		$msg = "Plan added successfully";
    	}
    	return redirect('admin/subscription-plan')->with('success-message',$msg);
    }
    public function deletePlan($id){
    	Subscription::find($id)->delete();
    	return redirect('admin/subscription-plan')->with('success-message',"Plan deleted successfully");
    }
    public function status($id,$status){
    	Subscription::where('id',$id)->update(['status'=>$status]);
    	return redirect('admin/subscription-plan')->with('success-message',"Plan status updated successfully");
    }

    public function updateFreePlan(Request $request){
        //dd($request->all());

        if($request->id){
            Subscription::where('id',$request->id)->update(['plan_name'=>$request->name,'plan_description'=>$request->description,'plan_price'=>$request->price,'days'=>$request->no_of_day,'plan_type'=>1,'status'=>2]);
            $msg = "Free Plan edit successfully";
        }else{
            Subscription::create(['plan_name'=>$request->name,'plan_description'=>$request->description,'days'=>$request->no_of_day,'plan_price'=>$request->price,'plan_type'=>1,'status'=>2]);
            $msg = "Free Plan added successfully";
        }
        return redirect('admin/subscription-plan')->with('success-message',$msg);
    }
    public function priceMgnt(){
        $data = PricePlan::orderBy('id','DESC')->get();
        return view('admin.priceMgnt',compact('data'));
    }
    public function addPrice(Request $request){
       // dd($request->all());
        if($request->id){
            // $data = PricePlan::where('country',$request->country)->where('id','<>',$request->id)->first();
            //dd($data);
            // if(!empty($data)){
            //     return redirect('admin/price-mgnt')->with('country-error',array('cartSuccess' => 'Country already added', 'cartItems' => $request->id));
            // }
            PricePlan::where('id',$request->id)->update([
                'country'=>$request->country,
                'first_distance_meter'=>$request->first_distance_meter,
                'first_fixed_price'=>$request->first_fixed_price,
                'next_price'=>$request->next_price,
                'service_type'=>$request->service_type

                ]);
            $msg = "Price edit successfully";
        }else{
            // $data = PricePlan::where('country',$request->country)->first();
            // if(!empty($data)){
            //     return redirect('admin/price-mgnt')->with('country-error',array('cartSuccess' => 'Country already added', 'cartItems' => 0));
            // }
            PricePlan::create([
                'country'=>$request->country,
                'first_distance_meter'=>$request->first_distance_meter,
                'first_fixed_price'=>$request->first_fixed_price,
                'next_price'=>$request->next_price,
                'service_type'=>$request->service_type

                ]); 
            $msg = "Price added successfully";
        }
        return redirect('admin/price-mgnt')->with('success-message',$msg);
    }
    /*
       
        35 Km = 35000 meters less 200 meters = 34800
        divide 34800 by 150 = 232

        multiply 232 by 150 = 34800
        plus 300
        Total fare is 35100
    */
      
    public function getPrice(Request $request){        
        $country = $request->country;
        $distance = str_replace(',', '', $request->distance);
        return $this->calculatePrice($country,$distance);
    }

  


    public function deletePrice($id){
        $data = PricePlan::find($id);
        $data->delete();
        $msg = "Price deleted successfully";
        return redirect('admin/price-mgnt')->with('success-message',$msg);
    }
    public function serviceRequest(){
        $data = \DB::table('search_map')->where('status','!=',0)->orderBy('id','DESC')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]->driver = @Appuser::find($value->driver_id);
        }
       // dd($data);
        return view('admin.booking',compact('data'));
    }
}
