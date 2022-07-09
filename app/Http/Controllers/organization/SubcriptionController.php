<?php

namespace App\Http\Controllers\organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\PricePlan;
use App\Appuser;
use Auth;

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
    		Subscription::where('id',$request->id)->update(['plan_name'=>$request->name,'plan_description'=>$request->description,'plan_price'=>$request->price,'days'=>$request->no_of_day,'plan_type'=>$request->plan_type,'image'=>$image]);
    		$msg = "Plan edit successfully";
    	}else{
            $image = "";
            if($request->file('image')){
                $image = rand(9999,99999).time().'.'.$request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('files'), $image);
            }
    		Subscription::create(['plan_name'=>$request->name,'plan_description'=>$request->description,'plan_price'=>$request->price,'days'=>$request->no_of_day,'plan_type'=>$request->plan_type,'image'=>$image]);
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
            $data = PricePlan::where('country',$request->country)->where('id','<>',$request->id)->first();
            //dd($data);
            if(!empty($data)){
                return redirect('admin/price-mgnt')->with('country-error',array('cartSuccess' => 'Country already added', 'cartItems' => $request->id));
            }
            PricePlan::where('id',$request->id)->update([
                'country'=>$request->country,
                'first_distance_meter'=>$request->first_distance_meter,
                'first_fixed_price'=>$request->first_fixed_price,
                'next_price'=>$request->next_price

                ]);
            $msg = "Price edit successfully";
        }else{
            $data = PricePlan::where('country',$request->country)->first();
            if(!empty($data)){
                return redirect('admin/price-mgnt')->with('country-error',array('cartSuccess' => 'Country already added', 'cartItems' => 0));
            }
            PricePlan::create([
                'country'=>$request->country,
                'first_distance_meter'=>$request->first_distance_meter,
                'first_fixed_price'=>$request->first_fixed_price,
                'next_price'=>$request->next_price

                ]); 
            $msg = "Price added successfully";
        }
        return redirect('admin/price-mgnt')->with('success-message',$msg);
    }
    public function getPrice($country,$distance){
        $data = PricePlan::where('country',$country)->first();
        $d = 0;
        if(!empty($data)){
            $amount = (($distance*1000)-$data->first_distance_meter)/$data->first_distance_meter;
            $nextamount = (int)($amount)*$data->next_price;
            $d = $data->first_fixed_price + $nextamount;
            
        }
        return $d;
         
    }
    public function deletePrice($id){
        $data = PricePlan::find($id);
        $data->delete();
        $msg = "Price deleted successfully";
        return redirect('admin/price-mgnt')->with('success-message',$msg);
    }
    public function serviceRequest(){
        $u_id = Auth::guard('organization')->user()->id;
        $data = \DB::table('search_map')->join('appuser','appuser.id','=','search_map.driver_id')->where('appuser.organization_type',$u_id)->where('search_map.status','!=',0)->orderBy('search_map.id','DESC')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]->driver = @Appuser::find($value->driver_id);
        }
       // dd($data);
        return view('organization.booking',compact('data'));
    }
}
