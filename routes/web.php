<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//
//
//

Route::get('/version', function() {
   echo phpinfo();
});

Route::get('/booking-feedback/{booking_id}','user\BookingController@showBookingFeedbackForm')->name('booking_feedback');
Route::post('/booking-feedback/{booking_id}','user\BookingController@storeBookingFeedbackForm')->name('booking_feedback');
Route::get('/','admin\AccountController@search');
  Route::get('/search','admin\AccountController@index');
  Route::post('/register-new-user','admin\AccountController@createUserAccount');
  Route::post('/register-user-login','admin\AccountController@userLogin');
  Route::post('/check-login-status','admin\AccountController@checkUserLoginStatus');
 Route::get('/user/{id}','admin\AccountController@user'); 

Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    return "Config is cleared";
});

Route::get('find-route',function(){
    return view('admin.tracking');
});

Route::get('read-all',function(){
    \App\Appuser::where('is_read',0)->update(['is_read'=>1]);
    return redirect('admin/user_management/2');
});
 Route::get('admin/userdetails/{userid}','admin\AccountController@userdetails');
 Route::post('admin/bookTaxi','admin\AccountController@bookTaxi');

Route::group(["prefix"=>"admin"],function(){
    Route::get('/',function(){
        return redirect('admin/login');
    });
    Route::match(['get','post'],'login','admin\AccountController@login');
    Route::get('terms/{type}/{locale}','admin\AccountController@terms');

    
    Route::get('logout','admin\AccountController@logout');
    Route::match(['get','post'],'forgetpassword','admin\AccountController@forgetpassword');
    Route::match(['get','post'],'resetpassword/{token}','admin\AccountController@resetpassword');
    Route::post('get_price','admin\SubcriptionController@getPrice');
    Route::group(["middleware"=>"checkadmin"],function(){
        Route::get('home','admin\AccountController@home'); 
        Route::post('update-free-plan','admin\AccountController@updateFreePlanDuration'); 
        Route::match(['get','post'],'editprofile','admin\AccountController@editprofile');
        Route::get('profile','admin\AccountController@profile');
        Route::match(['get','post'],'changepassword','admin\AccountController@changepassword');
        Route::get('user_management/{type}','admin\AccountController@usermanagement');
        Route::get('deleteuser/{id}','admin\AccountController@delete_user');
        Route::get('blockuser/{id}','admin\AccountController@block_user');
        Route::get('service_type','admin\ManagementController@servicetype')->middleware('haspermission:service type management');
        Route::post('add_service_type','admin\ManagementController@addservicetype')->middleware('haspermission:service type management');
        Route::post('edit_service_type','admin\ManagementController@editservicetype')->middleware('haspermission:service type management');
        Route::get('delete_service_type/{id}','admin\ManagementController@deleteservicetype')->middleware('haspermission:service type management');
        Route::get('organization_type','admin\ManagementController@organizationtype');
        Route::post('edit_organization_type','admin\ManagementController@editorganizationtype');
        Route::post('add_organization_type','admin\ManagementController@addorganizationtype');
        Route::get('delete_organization_type/{id}','admin\ManagementController@deleteorganizationtype');
        Route::get('organization','admin\ManagementController@organization')->middleware('haspermission:organisation management');
        Route::post('edit_organization','admin\ManagementController@editorganization')->middleware('haspermission:organisation management');
        Route::post('add_organization','admin\ManagementController@addorganization')->middleware('haspermission:organisation management');
        Route::get('delete_organization/{id}','admin\ManagementController@deleteorganization')->middleware('haspermission:organisation management');
        Route::get('verify_driver/{id}','admin\AccountController@verifydriver');
        Route::get('set_url_value','admin\ManagementController@seturlvalue');
        Route::post('send_reason_to_driver','admin\AccountController@sendreasontodriver');
        Route::post('verify_document/{type}','admin\AccountController@verifydocument');
        Route::post('unverify_document','admin\AccountController@unverifydocument');
        Route::post('document_verified','admin\AccountController@document_verified');
        Route::post('updatedriver','admin\AccountController@updatedriver');
        Route::get('subadmin','admin\ManagementController@subadmin')->middleware('haspermission:subadmin management');
        Route::post('add_subadmin','admin\ManagementController@addsubadmin')->middleware('haspermission:subadmin management');
        Route::post('edit_subadmin','admin\ManagementController@editsubadmin')->middleware('haspermission:subadmin management');
        Route::get('delete_subadmin/{id}','admin\ManagementController@deletesubadmin')->middleware('haspermission:subadmin management');
        Route::post('edit-subadmin-permission','admin\ManagementController@getSubAdminPermissionDetails')->middleware('haspermission:subadmin management');
       
        Route::post('set_user_service_type','admin\AccountController@setuserservicetype');
        Route::get('connectfirebase','admin\AccountController@connectfirebase');
        Route::get('subscription-plan','admin\SubcriptionController@index')->middleware('haspermission:subscription plan management');
        Route::post('add_plan','admin\SubcriptionController@create');
        Route::get('delete_plan/{id}','admin\SubcriptionController@deletePlan');
        Route::get('plan_status/{id}/{status}','admin\SubcriptionController@status');
        Route::post('update_free_plan','admin\SubcriptionController@updateFreePlan');
        Route::get('price-mgnt','admin\SubcriptionController@priceMgnt')->middleware('haspermission:price management');
        Route::post('add_price','admin\SubcriptionController@addPrice')->middleware('haspermission:price management');
        Route::get('delete_price/{id}','admin\SubcriptionController@deletePrice')->middleware('haspermission:price management');
        Route::post('submit_static_content','admin\ManagementController@contentmanagementsubmit');
        Route::get('content_management','admin\ManagementController@contentmanagement')->middleware('haspermission:content management');
        Route::get('service-request','admin\SubcriptionController@serviceRequest')->middleware('haspermission:booking management');
    });
});


Route::group(["prefix"=>"organization"],function(){
    Route::match(['get','post'],'login','organization\AccountController@login');
    Route::get('logout','organization\AccountController@logout');
    Route::get('home','organization\AccountController@home');
    Route::match(['get','post'],'forgetpassword','organization\AccountController@forgetpassword');
    Route::match(['get','post'],'resetpassword/{token}','organization\AccountController@resetpassword');
    Route::match(['get','post'],'editprofile','organization\AccountController@editprofile');
    Route::match(['get','post'],'changepassword','organization\AccountController@changepassword');
    Route::get('profile','organization\AccountController@profile');


    Route::get('user_management/{type}','organization\AccountController@usermanagement');
    Route::get('deleteuser/{id}','organization\AccountController@delete_user');
    Route::get('blockuser/{id}','organization\AccountController@block_user');

    Route::get('verify_driver/{id}','organization\AccountController@verifydriver');
    Route::get('set_url_value','organization\ManagementController@seturlvalue');
    Route::post('send_reason_to_driver','organization\AccountController@sendreasontodriver');
    Route::post('document_verified','organization\AccountController@document_verified');
    Route::post('verify_document/{type}','organization\AccountController@verifydocument');



    Route::get('service_type','organization\ManagementController@servicetype');
    Route::post('add_service_type','organization\ManagementController@addservicetype');
    Route::post('edit_service_type','organization\ManagementController@editservicetype');
    Route::get('delete_service_type/{id}','organization\ManagementController@deleteservicetype');

    Route::get('service-request','organization\SubcriptionController@serviceRequest');

});



Route::post('/complete','user\DriverController@complete');
Route::post('/cancel','user\DriverController@cancel');
Route::post('/notify','user\DriverController@notify');
