<?php

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

Route::group(['prefix' => 'v1'], function () {

    /* get_country */
    Route::get('get_country/{code?}','RESTAPIs\v1\country\CountryServiceController@get_country');


    /* CUSTOMER */
    /* customer login */
    Route::post('login_cust', 'RESTAPIs\v1\customer\LoginRegisterServiceController@login');
    /* customer social login */
    Route::post('login_with_social_cust','RESTAPIs\v1\customer\LoginRegisterServiceController@login_with_social');
    /* customer register */
    Route::post('signup_cust', 'RESTAPIs\v1\customer\LoginRegisterServiceController@signup');
    /* customer verify user */
    Route::post('verifyotp_cust', 'RESTAPIs\v1\customer\LoginRegisterServiceController@verify');
    /* customer forgot password */
    Route::post('forgot_cust','RESTAPIs\v1\customer\LoginRegisterServiceController@forgot');
    /* customer logout */
    Route::post('logout','RESTAPIs\v1\customer\LoginRegisterServiceController@logout');


    /* ======= IMAGES ======= */
    /* get customer profile image */
    Route::get('images/user/{folder}/profile/{img_name}','RESTAPIs\v1\customer\LoginRegisterServiceController@getImage');



    /* Driver */
    /* vehicle type */
    Route::get('vehicle_type','RESTAPIs\v1\driver\LoginRegisterServiceController@vehicle_type');
    Route::get('vehicle_category/{id?}','RESTAPIs\v1\driver\LoginRegisterServiceController@vehicle_category');
    /* compnay list */
    Route::get('company_list','RESTAPIs\v1\driver\LoginRegisterServiceController@company_list');
    /* driver register */
    Route::post('signup_driver','RESTAPIs\v1\driver\LoginRegisterServiceController@signup');
    /* verify driver */
    Route::post('verifyotp_driver','RESTAPIs\v1\driver\LoginRegisterServiceController@verify');
    /* driver login */
    Route::post('login_driver','RESTAPIs\v1\driver\LoginRegisterServiceController@login');
    /* customer forgot password */
    Route::post('forgot_driver','RESTAPIs\v1\driver\LoginRegisterServiceController@forgot');


    /* ======= IMAGES ======= */
    /* get driver profile image */
    Route::get('images/driver/{folder}/profile/{img_name}','RESTAPIs\v1\driver\LoginRegisterServiceController@getImage');
    /* get driver document */
    Route::get('driver/{folder}/document/{doc_name}','RESTAPIs\v1\driver\LoginRegisterServiceController@getDocument');
    /* get trip detail map image */
    Route::get('trip/{trip_id}/images/{map_image}','RESTAPIs\v1\driver\TripServiceController@getMapImage');


    /*  Authorized CUSTOMER route  */
    Route::group(['middleware' => 'auth:user_api'], function() {
        Route::get('customer_auth', function() {
            return "done";
        });

        /* customer nearby cab */
        Route::post('nearbycab_cust','RESTAPIs\v1\customer\NearServiceController@find_near_me');
        /* customer nearby vehicle types */
        Route::post('nearby_vehicle_type','RESTAPIs\v1\customer\NearServiceController@find_nearby_vehicle_type');
        /* customer travel with friend */
        Route::post('trip_with_friend','RESTAPIs\v1\customer\NearServiceController@trip_with_friend');
        /* customer nearby vehicle category */
        Route::post('nearby_vehicle_category','RESTAPIs\v1\customer\NearServiceController@find_nearby_vehicle_category');
        /* customer search/request for cab ride */
        Route::post('book_cab_cust','RESTAPIs\v1\customer\NearServiceController@book_cab_cust');
        /* customer profile */
        Route::post('profile_cust','RESTAPIs\v1\customer\ProfileController@get_profile');
        /* customer past trip */
        Route::post('past_trip_cust','RESTAPIs\v1\customer\TripServiceController@past_trip_details');
        /* customer on going trip */
        Route::post('ongoing_trip_cust','RESTAPIs\v1\customer\TripServiceController@ongoing_trip');
        /* customer feedback to driver trip */
        Route::post('feedback_cust','RESTAPIs\v1\customer\FeedbackServiceController@feedback_save');
        /* customer cancel trip */
        Route::post('cancel_trip_cust','RESTAPIs\v1\customer\TripServiceController@cancel_trip');

        /* customer sos create */
        Route::post('create_sos','RESTAPIs\v1\customer\SosController@create_sos');
        /* customer sos list get */
        Route::post('get_sos','RESTAPIs\v1\customer\SosController@get_sos');
        /* customer delete sos */
        Route::post('delete_sos','RESTAPIs\v1\customer\SosController@delete_sos');    
        /* customer edit sos */
        Route::post('edit_sos','RESTAPIs\v1\customer\SosController@edit_sos');
        /* customer send sos */
        Route::post('send_sos','RESTAPIs\v1\customer\SosController@send_sos');


        /* customer make payment */
        Route::post('make_payment','RESTAPIs\v1\customer\PaymentController@make_payment');
        /* customer update */
        Route::post('update_cust','RESTAPIs\v1\customer\ProfileController@update');
        /* customer update contact number */
        Route::post('change_contact_no','RESTAPIs\v1\customer\ProfileController@change_contact_no');
        /* customer update contact number verify */
        Route::post('contact_no_verify','RESTAPIs\v1\customer\ProfileController@verify_new_contact');
        /* customer change password */
        Route::post('change_password_cust','RESTAPIs\v1\customer\ProfileController@change_password');
        /* customer notification log */
        Route::post('notification_log','RESTAPIs\v1\customer\NotificationController@get_notification');
        /* customer previous trip */
        Route::post('previous_trip','RESTAPIs\v1\customer\CurrentController@get_current');
    });

    /*  After Authorized driver route  */
    Route::group(['middleware' => 'auth:driver_api'], function() {
        Route::get('driver_auth', function(){
            return "done";
        });
        Route::post('available_driver','RESTAPIs\v1\driver\AvailabledriverServiceController@available_drive');
        /* driver profile */
        Route::post('profile_driver','RESTAPIs\v1\driver\ProfileController@get_profile');
        /* driver past trip */
        Route::post('past_trip_driver','RESTAPIs\v1\driver\TripServiceController@past_trip_details');
        /* driver decline trip */
        Route::post('trip_decline_driver','RESTAPIs\v1\driver\TripServiceController@trip_decline_driver');
        /* driver missed trip */
        Route::post('missed_trip','RESTAPIs\v1\driver\TripServiceController@missed_trip_list');
        /* driver pickedup customer */
        Route::post('pickup_customer','RESTAPIs\v1\driver\TripServiceController@pickup_customer');
        /* driver trip complate */
        Route::post('trip_complate','RESTAPIs\v1\driver\TripServiceController@trip_complate');
        /* driver trip report */
        Route::post('rides_report_driver','RESTAPIs\v1\driver\TripServiceController@report');
        /* driver feedback to customer trip */
        Route::post('feedback_driver','RESTAPIs\v1\driver\FeedbackServiceController@feedback_save');
        /* driver cancel trip */
        Route::post('cancel_trip_driver','RESTAPIs\v1\driver\TripServiceController@cancel_trip');
        /* driver profile update */
        Route::post('update_driver','RESTAPIs\v1\driver\ProfileController@update');
        /* driver update contact number */
        Route::post('change_contact_no_driver','RESTAPIs\v1\driver\ProfileController@change_contact_no');
        /* driver update contact number verify */
        Route::post('contact_no_verify_driver','RESTAPIs\v1\driver\ProfileController@verify_new_contact');
        /* driver change password */
        Route::post('change_password_driver','RESTAPIs\v1\driver\ProfileController@change_password');
        /* driver change email */
        Route::post('change_email_driver','RESTAPIs\v1\driver\ProfileController@change_email');
        /* driver verify email */
        Route::post('email_verify_driver','RESTAPIs\v1\driver\ProfileController@verify_new_email');
        /* driver notification log */
        Route::post('notification_log_driver','RESTAPIs\v1\driver\NotificationController@get_notification');
    });

});
