<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Auth\AuthController@getLogin');
Route::post('/', 'Auth\AuthController@authenticate');
Route::get('/logout', 'Auth\AuthController@getLogout');
Route::get('/errorLogout', 'Auth\AuthController@errorLogout');

Route::group(array('middleware' => 'auth'), function () {
Route::get('/branching', 'BranchingController@branching');
Route::post('/branching', 'BranchingController@updateCurrentBranch');
});

Route::group(array('middleware' => 'critical','middleware' => 'auth' ), function () {
   
         
    Route::get('/home', 'HomeController@home');
//--------------------------------------StudentsController----------------------------------------
    Route::get('/enroll', ['middleware' => 'nursery_admin','uses'=>'StudentsController@enroll']); 
    Route::get('/enrollCheck', 'StudentsController@enrollCheck');
    Route::post('/enroll', 'StudentsController@store');

    Route::get('/students/search', 'StudentsController@searchView');
    Route::get('/searchBind', 'StudentsController@searchBind');

    Route::get('/profile/student/{studentId}', 'StudentsController@profile');
//---------------------------------------SubscriptionController---------------------------------------
    Route::get('/student/subscription/add/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@add']);
    Route::get('/subsCheck', 'SubscriptionController@subsCheck');
    Route::post('/student/subscription/add/{studentId}/{standard}', 'SubscriptionController@store');

    Route::get('/student/subscription/addHours/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@addHours']);
    Route::post('/student/subscription/addHours/{studentId}/{standard}', 'SubscriptionController@saveHours');

    Route::post('/subscriptionDelete/{studentId}', 'SubscriptionController@delete');
    Route::get('/subsLockUnlock', 'SubscriptionController@lockUnlock');
//-------------------------------------InvoiceController-----------------------------------------
    Route::get('/student/invoice/add/{studentId}', ['middleware' => 'nursery_admin','uses'=>'InvoiceController@add']); 
    Route::get('/invEventAdd', 'InvoiceController@eventAdd'); 
    Route::get('/invEventRemove', 'InvoiceController@eventRemove'); 
    Route::get('/invEventLoader', 'InvoiceController@eventLoader'); 

    Route::get('/invItemLoader', 'InvoiceController@itemLoader');
    Route::get('/invItemAdd', 'InvoiceController@itemAdd');
    Route::get('/invItemRemove', 'InvoiceController@itemRemove');
 
    Route::post('/student/invoice/add/{studentId}', 'InvoiceController@save');
    Route::get('/invoice/{invoiceId}', 'InvoiceController@view');

    Route::post('/profile/student/{studentId}', 'InvoiceController@delete');
 //---------------------------------------GradesController-------------------------------------------------------------
    Route::get('/students/grades', 'GradesController@index');
    Route::get('/students/grade/{classId}/students/{filter}', 'GradesController@students');    
//---------------------------------UsersController--------------------------------------------------------------------
    Route::get('users','UsersController@index');
	Route::get('users/{id?}/edit', 'UsersController@edit');
	Route::post('users/{id?}/edit','UsersController@update');
//-------------------------------RolesController-----------------------------------------------
	Route::get('roles', 'RolesController@index');
	Route::get('roles/create', 'RolesController@create');
	Route::post('roles/create', 'RolesController@store');
});

 



 

 