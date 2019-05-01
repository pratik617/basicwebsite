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

//Route::get('/', 'MapController@index');

Route::get('/', function () {
	return view('customer.home.home');
})->name('dashboard');

Route::get('/phpinfo', function () {
	return phpinfo();
});

// CUSTOMER ROUTE
Route::get('/user/forgot/{token?}','Customer\RegisterController@forgot')->name('customer.password.reset');
Route::post('/user/password/reset','Customer\RegisterController@forgotReset')->name('customer.password.save');

// DRIVER FORGOT LINK
Route::get('/driver/forgot/{token?}','Customer\RegisterController@forgotDriver')->name('driver.password.reset');
Route::post('/driver/password/reset','Customer\RegisterController@forgotResetDriver')->name('driver.password.save');

Route::prefix('/')->middleware('user')->group(function() {
	// Route::get('register','Auth\RegisterController@showRegistrationForm')->name('register');
	// Route::post('register/contact','Auth\RegisterController@set_contact_no')->name('register.set_contact_no');
	// Route::get('register/get_contact_no/{id}',function(){
	// return view('customer.register.get_contact_no');
	// })->name('register.get_contact_no');

	// Route::post('register','Auth\RegisterController@register')->name('register');

	// Route::get('login','Auth\LoginController@showLoginForm')->name('login');
	// Route::post('login','Auth\LoginController@login');

	// Route::post('logout','Auth\LoginController@logout')->name('logout');
	// Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

	// Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	// Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');
	// Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
	// Register Route
	Route::get('register','Customer\RegisterController@index')->name('customer.register');
	Route::post('register','Customer\RegisterController@register')->name('customer.register');

	Route::post('register/verification','Customer\RegisterController@otpVerification')->name('customer.otp.verification');

	Route::get('login','Customer\RegisterController@showLoginForm')->name('customer.login');
	Route::post('login','Customer\RegisterController@login')->name('customer.login');

	Route::get('logout','Customer\RegisterController@logout')->name('customer.logout');

	// Customer Dashboard
	//Route::get('/home', 'HomeController@index')->name('customer.dashboard');
	Route::get('dashboard', 'Customer\DashboardController@index')->name('customer.dashboard');

	// Customer My Trip
	Route::get('mytrip','Customer\MytripController@index')->name('customer.mytrip');

	// Customer My wallet
	Route::get('mywallet','Customer\MywalletController@index')->name('customer.mywallet');

	// Customer Sharing Code & Invitation
	Route::get('share','Customer\SharingController@index')->name('customer.share');

	// Customer Promotion
	Route::get('promotion','Customer\PromotionController@index')->name('customer.promotion');

	// Customer Notification
	Route::get('notification','Customer\NotificationController@index')->name('customer.notification');

	// Customer Profile
	Route::get('profile','Customer\ProfileController@index')->name('customer.profile');
	Route::post('profile','Customer\ProfileController@store')->name('customer.profile.store');
	Route::post('profile/verification','Customer\ProfileController@verifyOtp')->name('customer.profile.verification');
});

// LOGIN WITH GOOGLE
Route::get('register/google', 'Customer\SocialAuthGoogleController@redirect')->name('register.google');
Route::get('redirect/google', 'Customer\SocialAuthGoogleController@callback');


// LOGIN WITH FACEBOOK
Route::get('register/facebook', 'Customer\SocialAuthFacebookController@redirect')->name('register.facebook');
Route::get('redirect/facebook', 'Customer\SocialAuthFacebookController@callback');

// LOGIN WITH LINKEDIN
Route::get('register/linkedin', 'Customer\SocialAuthLinkedinController@redirect')->name('register.linkedin');
Route::get('redirect/linkedin', 'Customer\SocialAuthLinkedinController@callback');


/*========================ADMIN PASSWORD RESET========================*/
Route::get('admin/forgot','AdminAuth\LoginController@forgot')->name('admin.forgot.password');
Route::post('admin/forgot','AdminAuth\LoginController@sendlink')->name('admin.forgot.sendlink');
Route::get('admin/reset/{token?}','AdminAuth\LoginController@forgotAdmin')->name('admin.password.reset');
Route::post('admin/password','AdminAuth\LoginController@updatePassword')->name('admin.password.save');

/*========================ADMIN ROUTE========================*/
Route::prefix('admin')->middleware('admin')->group(function(){
	Route::get('/','AdminAuth\LoginController@showLoginForm')->name('admin.login');
	Route::post('/','AdminAuth\LoginController@login')->name('admin');

	// Dashboard
	Route::get('dashboard',function(){
		return view('admin.dashboard.dashboard');
	})->name('admin.dashboard');


	// Companies
	Route::get('companies','Admin\CompanyController@index')->name('admin.companies');
	Route::get('companies/add-company','Admin\CompanyController@create')->name('admin.add.company');
	Route::post('companies/add-company','Admin\CompanyController@store')->name('admin.store.company');
	Route::get('companies/edit-company/{id}','Admin\CompanyController@edit')->name('admin.edit.company');
	Route::get('companies/admin','Admin\CompanyController@company_admin')->name('admin.company.admin');

	Route::get('companies/admin/add','Admin\CompanyController@create_company_admin')->name('admin.add.company_admin');
	Route::post('companies/admin/add','Admin\CompanyController@store_company_admin')->name('admin.store.company_admin');
	Route::get('companies/admin/edit/{id}','Admin\CompanyController@edit_company_admin')->name('admin.edit.company_admin');

     //Profile Controller
     Route::get('profile','Admin\ProfileController@index')->name('admin.profile');
     Route::post('add-profile','Admin\ProfileController@store')->name('admin.add.profile');


     //RidePrice Controller
      Route::get('ride_price','Admin\RidepriceController@index')->name('admin.ride_price');
      Route::get('add-ride_price/{id}','Admin\RidepriceController@edit')->name('admin.edit.ride_price');
      Route::get('delete-ride_price/{id}','Admin\RidepriceController@delete')->name('admin.delete.ride_price');
      Route::post('store-ride_price','Admin\RidepriceController@store')->name('admin.store.rideprice');
      Route::get('add_ride_price','Admin\RidepriceController@create')->name('admin.add.rideprice');

      //Taxes Controller
      Route::get('taxes','Admin\TaxesController@index')->name('admin.taxes');
      Route::get('delete-taxes/{id}','Admin\TaxesController@delete')->name('taxes.delete');
      Route::get('edit-taxes/{id}','Admin\TaxesController@edit')->name('admin.edit.taxes');
      Route::post('store-taxes','Admin\TaxesController@store')->name('admin.store.taxes');
      Route::get('add-taxes','Admin\TaxesController@create')->name('admin.add.taxes');

	// Individual Drivers
	Route::get('individual-driver','Admin\IndividualDriverController@index')->name('admin.individual_driver');
	Route::get('driver-delete','Admin\IndividualDriverController@delete')->name('admin.driver.delete');
    Route::get('edit-driver/{id}','Admin\IndividualDriverController@edit')->name('admin.edit.driver');
    Route::post('store-driver','Admin\IndividualDriverController@store')->name('admin.store.driver');
    Route::get('add_driver','Admin\IndividualDriverController@create')->name('admin.add.driver');
    Route::get('vehicle-driver/{id}','Admin\IndividualDriverController@get_vehicle');

  	//CouponCode Controller
    Route::get('coupon-code','Admin\CouponCodeController@index')->name('admin.coupon-code');

	//vehicleType
	Route::get('vehicletype','Admin\VehicletypeController@index')->name('admin.vehicle_type');
	Route::get('vehicletype-delete','Admin\VehicletypeController@delete')->name('vehicle.delete');
	Route::get('add-vehicle_type/{id}','Admin\VehicletypeController@edit')->name('admin.edit.vehicletypes');
	Route::post('store-vehicle_type','Admin\VehicletypeController@store')->name('admin.store.vehicletype');
	Route::get('add_vehicle_type','Admin\VehicletypeController@create')->name('admin.add.vehicle_type');

	//vehicleCategory
	Route::get('vehiclecategory','Admin\VehicleCategoryController@index')->name('admin.vehicle_category');
	Route::get('vehicleCategory-delete','Admin\VehicleCategoryController@delete')->name('vehicle_categorys.delete');
	  Route::get('add-vehicle_category/{id}','Admin\VehicleCategoryController@edit')->name('admin.edit.vehicle_category');
    Route::post('store-vehicle_category','Admin\VehicleCategoryController@store')->name('admin.store.vehiclecategory');
    Route::get('add_vehicle_category','Admin\VehicleCategoryController@create')->name('admin.add.vehicle_category');


	// Customer
	Route::get('customer','Admin\CustomerController@index')->name('admin.customer');
	Route::get('customer-delete','Admin\CustomerController@delete')->name('admin.customer.delete');
	Route::get('customer-status/{status}/{id}','Admin\CustomerController@getstatus')->name('customer.status');
	Route::get('customer-delete-soft','Admin\CustomerController@deleteindex')->name('admin.customer-delete-soft');

	// Customer Feedback
	Route::get('customer-feedback','Admin\CustomerFeedbackController@index')->name('admin.customer_feedback');

	// Territory
	Route::get('territory','Admin\TerritoryController@index')->name('admin.territory');

	// Promotions
	Route::get('promotions','Admin\PromotionsController@index')->name('admin.promotions');

	// Reports
	Route::get('reports','Admin\ReportsController@index')->name('admin.reports');

	//Country-settings
	Route::get('/country-setting','Admin\CountrySettingController@index')->name('admin.country_setting');
	Route::get('/country-setting/edit-country/{id}','Admin\CountrySettingController@edit')->name('admin.edit.country-setting');
	Route::post('/country-setting/edit-country','Admin\CountrySettingController@update')->name('admin.edit.country');

	//trip-details
	Route::get('/trip-details','Admin\TripDetailsController@index')->name('admin.trip_details');
});
// ADMIN LOGOUT
Route::get('admin/logout','AdminAuth\LoginController@logout')->name('admin.logout');

/*========================Company Admin Route========================*/
Route::prefix('company')->middleware('company_admin')->group(function(){
	// Company Dashboard
	Route::get('/','Company\DashboardController@index')->name('company.dashboard');
	Route::get('/dashboard','Company\DashboardController@index')->name('company.dashboard');

	// Company Driver
	Route::get('/driver','Company\DriverController@index')->name('company.driver');

	// Company Profile
	Route::get('/company-profile','Company\CompanyProfileController@index')->name('company.company-profile');

	// Company Customer Feedback
	Route::get('/customer-feedback','Company\CustomerFeedbackController@index')->name('company.customer_feedback');

	// Company Cabs
	Route::get('/cabs','Company\CabsController@index')->name('company.cabs');

	// Company Reports
	Route::get('/reports','Company\ReportsController@index')->name('company.reports');
});

Route::get('company/logout','AdminAuth\LoginController@companylogout')->name('company.logout');

Route::get('403', function(){
return "403 error.";
})->name('403');

Route::get('404', function(){
return "Page not found.";
})->name('404');

Route::get('500', function(){
return "500 error.";
})->name('500');

Route::get('503', function(){
return "503 error.";
})->name('503');

/*device linkedin*/
Route::get('/linkedin/device/success',function(){
	return "success";
});
