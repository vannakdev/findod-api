<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */

 
/////===================Post with autho==================
$router->group(['prefix' => 'api', 'middleware' => ['access', 'auth', 'localization']], function () use ($router) {
    // User request control
    $router->put('users', ['uses' => 'UserController@updateProfile']);

    // Deactivate account
    $router->put('/users/deactivate', ['uses' => 'UserController@deactivate']);

    // Change password
    $router->put('/users/change-password', ['uses' => 'UserController@changePassword']);

    //Add favorite property
    $router->post('favorite', ['uses' => 'PropertyController@addFavorite']);
    //Remove favorite property
    $router->delete('favorite/{id}', ['uses' => 'PropertyController@removeFavorite']);


//    // Property request control
//    //============Create a property==============================//
    $router->post('property', ['uses' => 'PropertyController@hosting']);
//    //============Create a property==============================//
    $router->post('property/postByWeb', ['uses' => 'PropertyController@hostingByWeb']);

//    //============Update a property==============================//
    $router->put('property/{id}', ['uses' => 'PropertyController@update']);

    // ============Update a property==============================//
    $router->put('/property/update/byWeb/{id}', ['uses' => 'PropertyController@updateByWeb']);


    //============Update price for a property==============================//
    $router->put('property/update/price/{id}', ['uses' => 'PropertyController@updatePrice']);

    //============Update residence and amenity for a property==============================//
    $router->put('property/update/residenceAmenity/{id}', ['uses' => 'PropertyController@updateResidenceAndAmenity']);

    //============Update residence and amenity for a property==============================//
    $router->put('property/update/searchType/{id}', ['uses' => 'PropertyController@updateSearchType']);

    //============Update locaiton for a property==============================//
    $router->put('property/update/location/{id}', ['uses' => 'PropertyController@updateLocation']);

    //============Update property more info==============================//
    $router->put('property/update/moreInfo/{id}', ['uses' => 'PropertyController@updateMoreInfo']);

    //============Update property contact==============================//
    $router->put('property/update/contact/{id}', ['uses' => 'PropertyController@updateContact']);

    //============Update property video==============================//
    $router->put('property/update/video/{id}', ['uses' => 'PropertyController@updateVideo']);

    //============Delete property video==============================//
    $router->delete('property/update/video/delete/{id}', ['uses' => 'PropertyController@deleteVideo']);


    //============Update property contact==============================//
    $router->get('property/update/getUpdateData', ['uses' => 'PropertyController@getUpdateData']);


    //============Update property contact==============================//
    $router->get('property/hosting/getHostingData', ['uses' => 'PropertyController@getHostingData']);

    //============Get property project name==============================//
    $router->get('property/getProjectName', ['uses' => 'PropertyProjectNameController@showAll']);

    //============Search property project name==============================//
    $router->post('property/projectName/search', ['uses' => 'PropertyProjectNameController@searchProjectName']);


    //============SoftDelete a property==============================//
    $router->delete('property/{id}', ['uses' => 'PropertyController@softDelete']);

    //============Get one property==============================//
    $router->get('property/{id}', ['uses' => 'PropertyController@propertyDetail']);


    // Get list of property favorite by login userr==========
    $router->get('/property/byfavorite/user', ['uses' => 'PropertyController@propertyByFavorite']);

    // Get list of property favorite by login userr==========
    $router->get('/property/byfavorite/user/web', ['uses' => 'PropertyController@propertyByFavoriteWeb']);


    // Get list of property posted login userr==========
    $router->get('/property/byposted/user', ['uses' => 'PropertyController@postedPropertyByUser']);

    // Get list of property posted login userr by web==========
	$router->get('/property/byposted/user/web', ['uses' => 'PropertyController@postedPropertyByUserWeb']);

    $router->put('logout', ['uses' => 'UserController@logout']);

    // Get list of property posted login userr==========
    $router->get('/property/propertyByuser/{id}', ['uses' => 'PropertyController@propertyByUserId']);

    // Count favorite and hosting property by login userr==========
    $router->get('/users/profile/', ['uses' => 'UserController@userProfile']);

    //============Search a property history==============================//
//    $router->post('/filter/setfilter', 'PropertyController@setFilterHistroy');
//    

    //============Search a property==============================//
    $router->post('filter', 'PropertyController@filter');
    
    //============Search a property for web==============================//
    $router->post('filterByWeb', 'PropertyController@filterByWeb');

    //===============Rating property====================
    //Post rating
    $router->post('rating', ['uses' => 'RatingController@create']);

    //Get rating exist
    $router->get('/rating/check/{id}', ['uses' => 'RatingController@checkRating']);


    //Post request viewing property
    $router->post('/property/request-viewing/', ['uses' => 'RequestViewingController@create']);


    //===============Property report by user====================
    //Post report
    $router->post('/property/report/', ['uses' => 'PropertyReportController@create']);

    //Get report
    $router->get('/property/report/{id}', ['uses' => 'PropertyReportController@show']);

    //Get report type
    $router->get('/property/reportType/list', ['uses' => 'ReportTypeController@showAll']);


//    //============Get all properties==============================//
//    $router->get('property', ['uses' => 'PropertyController@showAllProperty']);
    //============Update a property photo==============================//
    $router->put('/property/update-photo/{id}', ['uses' => 'PropertyController@updatePhoto']);

    //============Update a property plan==============================//
    $router->put('/property/update-plan/{id}', ['uses' => 'PropertyController@updatePlan']);

//    $router->get('/property/nearest', ['uses' => 'PropertyController@getNearest']);
//
    //Get property rating number for each star
    $router->get('rating/{id}', ['uses' => 'RatingController@getRatingCounter']);

    //User feedback about the system
    $router->post('/users/feedback', ['uses' => 'FeedbackController@create']);

    ///Get notification
    $router->get('notify', ['uses' => 'NotificationController@showAll']);

//    $router->get('notify/{notifyId}', ['uses' => 'NotificationController@show']);

    //Author: HIA YONGKUY
    //Chat Route
    $router->get('chat/property/{property_id}', ['uses' => 'ChatController@init']);
    $router->get('chat/channel/list', ['uses' => "ChatController@list"]);
    $router->get('chat/channel/{id}', ['uses' => "ChatController@get"]);
    $router->post('chat/message', ['uses' => 'ChatController@saveMessageFromRequest']);
    $router->get('chat/unread/count', ['uses' => "ChatController@UnreadCount"]);
    $router->delete('chat/channel/{id}', ['uses' => "ChatController@deleteFromRequest"]);
    //End - Chat Route
    //Gallery Upload
    $router->post('gallery/create', ['uses' => "GalleryController@createFromRequest"]);
    $router->get('gallery/get', ['uses' => "GalleryController@get"]);
    $router->delete('gallery/image/{id}', ['uses' => "GalleryController@deleteItem"]);
    $router->put('gallery/image/{id}', ['uses' => "GalleryController@updateItemOrder"]);
    //End - Gallery Upload
    //Application Setting
    $router->put('application/setting/{key}', ['uses' => 'SettingController@update']);
    //End - Application Setting
    //End - Author
});
//
$router->group(['prefix' => 'api', 'middleware' => ['access', 'localization']], function () use ($router) {
    //$router->group(['prefix' => 'api', 'middleware' => ['access']], function () use ($router) {
    //============Get all properties==============================//
    $router->get('/', function () { return "Hello FindOD"; });
    $router->get('property', ['uses' => 'PropertyController@showAllProperty']);

//    ========================================================
    //User request control
    $router->get('users', ['uses' => 'UserController@showAllUsers']);
    //get a user infomation
    $router->get('users/{id}', ['uses' => 'UserController@showOneUser']);
    $router->post('users', ['uses' => 'UserController@userRegister']);
    $router->post('login', ['uses' => 'AuthController@authenticate']);
    $router->post('socialLogin', ["uses" => "UserController@socialLogin"]);
    // Agent register==========
    $router->post('agent', ['uses' => 'UserController@agentRegister']);


    //==================Property Modauls===========================//
//
//    $router->get('featured', ['uses' => 'PropertyController@showFeatured']);
    $router->post('featured', ['uses' => 'PropertyController@getFeatureAndNearby']);

    //============Map cluster: all near by location properties will given==============================//
    $router->post('mapcluster', ['uses' => 'PropertyController@mapCluster']);



    $router->post('db_update', 'DatabaseController@updateDatabase');
    $router->post('db_backup', 'DatabaseController@backup');
    $router->post('db_reset', 'DatabaseController@resetDatabase');

//    $router->post('search', 'SearchController@filterAdvance');
    //============Get list of amenities from a give residence id==============================//
    $router->get('amenities/{id}', 'PropertyController@getAmenities');
    $router->get('/amenities/byResidenc/{id}', 'AmenityController@showByResidence');
    $router->post('amenities', 'AmenityController@add');
    $router->put('amenities/{id}', 'AmenityController@update');
    //============Get list of amenities from a give residence id==============================//
    $router->get('/residence', 'PropertyController@getResidence');
    //============Get list of residence group by residence type==============================//
    $router->get('/residence/type', 'PropertyController@getResidenceByType');
    
    //============Get residence feature =====================================================//		
    $router->get('residence/feature/{id}', 'PropertyController@getResidenceByFeature');

    $router->post('/user/password-reset-request', ["uses" => "UserController@sendResetEmail"]);
    $router->post('/user/reset-password', ["uses" => "UserController@resetPassword"]);

    $router->post('/user/password-reset-link-request', ["uses" => "UserController@sendLinkResetPassword"]);
//    $router->get('/user/password-reset-link-auth/{link}', ["uses" => "UserController@resetLinkAuth"]);
    $router->post('/user/password-reset-link', ["uses" => "UserController@resetPassword"]);

    //============Backend upload a property photos==============================//
    $router->post('/property/upload/photos', ['uses' => 'PropertyController@backendUploadPhotos']);

    //============Backend upload a property plan==============================//
    $router->post('/property/upload/plans', ['uses' => 'PropertyController@backendUploadPlans']);

    //============Backend upload a property photos==============================//
    $router->post('/property/upload/user', ['uses' => 'UserController@backendUploadUserPhoto']);

    //============Backend upload a property plan==============================//
    $router->post('/property/upload/licence', ['uses' => 'UserController@backendUploadUserLicence']);

    //============Backend upload amenity==============================//
    $router->post('/property/upload/amenity', ['uses' => 'PropertyController@backendUploadAmenity']);

    //============Backend upload residence==============================//
    $router->post('/property/upload/residence', ['uses' => 'PropertyController@backendUploadResidence']);
    //============Backend upload advertisement=============================//
    $router->post('/property/upload/advertisement', ['uses' => 'PropertyController@backendUploadAdvertisement']);


    //============Advertisement moduel=============================//
    $router->get('advertisement', ['uses' => 'AdvertisementController@index']);

    $router->get('advertisement/list', ['uses' => 'AdvertisementController@showAll']);
    $router->get('advertisement/weblist', ['uses' => 'AdvertisementController@showAllWeb']);
    $router->get('advertisement/view/{id}', ['uses' => 'AdvertisementController@show']);




    //=========================Onesignal new player id=================================
    $router->post('/users/create/player', ['uses' => 'OneSignalController@createPlayer']);

    //Get property review list
    $router->get('review/{id}', ['uses' => 'RatingController@getReviews']);
    $router->post('upload/watermark', ['uses' => 'PropertyController@uploadWatermark']);
    //===================Backend Youtube video upload======================
    $router->post('youtube', ['uses' => 'YoutubeController@backendVideoUpload']);
    //===================Notification test======================
    $router->post('/notify/email', ['uses' => 'NotificationController@notifyEmail']);
    $router->post('/notify/push', ['uses' => 'NotificationController@notifyPush']);
    $router->post('/notify/sms', ['uses' => 'NotificationController@notifySMS']);

    $router->post('/notify/passwordChangeConfirm', ['uses' => 'NotificationController@notifyPasswordChangeConfirm']);
    $router->post('/notify/DeactivateAccount', ['uses' => 'NotificationController@notifyDeactivateAccount']);


    //================Test notification==================================
    $router->post('/notify/nearbyPost', ['uses' => 'NotificationController@nearbyPost']);
    //=========================Test Cronjob =================================
    $router->get('scheduler', ['uses' => 'SchedulerController@runScheduler']);
    //=========================Test Cronjob =================================
    $router->post('FakProperties', ['uses' => 'PropertyController@insertFakProperties']);
    //==============Reset password link validation ===================
    $router->get('/user/password-reset-link-auth/{link}', ["uses" => "UserController@resetLinkAuth"]);
    ////////===================Project Administrator================================
    $router->post('/projetAdministrator/userResetPasswords', ['uses' => 'DatabaseController@userResetPassword']);
    //Author : HIA YONGKUY
    $router->get('gallery/get', ['uses' => "GalleryController@get"]);
    $router->get('posts/{param}', ['uses' => "PostController@show"]);
    $router->get('application/setting', ['uses' => 'SettingController@getByKeys']);
    $router->get('application/setting/{key}', ['uses' => 'SettingController@get']);
    //END - Author : HIA YONGKUY

    $router->post('google/get/json', ['uses' => "GoogleServiceController@submitRequest"]);

    //Author : PONRAJ
	
	$router->post('application/setting/videoImage',['uses' => 'SettingController@uploadvideoImage']);
	
	//END - Author : PONRAJ
    $router->get('setting/residence', ['uses' => 'SettingController@show']);
});
