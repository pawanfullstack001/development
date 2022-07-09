<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix"=>"user","namespace"=>"user"],function(){
    Route::get('first_country','Utilitycontroller@first_country');
    Route::get('get_contents','Utilitycontroller@index');
    Route::get('filter_country_data/{id}','Utilitycontroller@filter_country_data');
    Route::get('all_country_list','Utilitycontroller@all_country_list');
    Route::get('getsignupdropdowns','Accountcontroller@getsignupdropdowns');
    
    Route::post('signup','Accountcontroller@signup');
    Route::group(["middleware"=>"userAuth"],function(){
        Route::post('verify_otp','Accountcontroller@verify');
    });
    Route::post('forgetpassword','Accountcontroller@forgetpassword');
    Route::post('forgetpassword_verification','Accountcontroller@forgetpassword_verification');
    Route::post('resetpassword','Accountcontroller@resetpassword');
    Route::post('resendotp','Accountcontroller@resendotp');
    Route::post('login','Accountcontroller@login');    
    // Route::get('TotalBooking','Accountcontroller@TotalRide');

    // Subscription expire notification Cron
    Route::get('sub-notification','DriverController@subscriptionExpirePreNotification');
    Route::get('sub-expire','DriverController@subscriptionExpire');
	
    Route::group(["middleware"=>"userAuth"],function(){
        Route::post('language_change','Accountcontroller@language_change');        
        Route::post('send_sms','Utilitycontroller@sendSMSAPI');        
        Route::post('get-driver-detail','DriverController@getDriverDetail');
        Route::get('TotalBooking','Accountcontroller@TotalRide');
        Route::post('change_password','Accountcontroller@changepassword');
        Route::get('terms/{type}','Accountcontroller@terms');
        Route::post('add_vehicle_details','Accountcontroller@addvehicledetails'); 
        Route::post('upload_documents','Accountcontroller@uploaddocument');
        Route::get('subcription_list/{country_code}','Utilitycontroller@get_list');
        Route::get('driver_available_status/{type}','DriverController@availableStatus');
        
        Route::post('applycoupen','DriverController@applycoupen');
        Route::post('pay_now','DriverController@payNow');
        Route::get('driver_subscription','DriverController@driverSubscription');
        Route::post('request','Accountcontroller@DriverDetails');
		Route::post('edit-driver-profile','Accountcontroller@editDriverProfile');
        Route::get('logout','DriverController@logout');
		
        Route::post('checkcoupen','DriverController@redeemCoupon');
      
        Route::post('coupen','DriverController@coupen');
        Route::post('edituploaddocument','Accountcontroller@edituploaddocument');
        Route::group(["prefix"=>"bookings"],function(){
            Route::get('',"BookingController@index");
            Route::post('/counts',"BookingController@getDriverKpis")->name('booking_kpis');
            Route::put('/{id}/updateStatus',"BookingController@updateStatus");
        });
    });
    Route::group(["prefix"=>"bookings"],function(){
		/////// Pawan ///////
		Route::get('/bookinglist/{uid}', 'BookingController@bookingList');
		Route::get('/customerBookings/{uid}/{day}', 'BookingController@customerBookings');
		Route::get('/bookingDetails/{id}', 'BookingController@bookingDetails');
		/////// Pawan ///////
        Route::get('/{id}',"BookingController@show")->name('booking_details');
        Route::post('/',"BookingController@store")->name('booking_taxi');
        Route::put('/{id}',"BookingController@update")->name('update_booking');
    });
});

Route::get("setting/{type}/{locale}",function($type,$locale){
$data = \DB::table('seeting_name')->where('type',$type)->where('locale',$locale)->first();

return response()->json(["message"=>"data getting successfully","data"=>$data]);
});

