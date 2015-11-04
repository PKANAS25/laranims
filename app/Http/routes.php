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

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');





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

    Route::get('/student/{studentId}/delete', 'StudentsController@delete');
    Route::get('/student/{studentId}/restore', 'StudentsController@restore');

//---------------------------------------SubscriptionController---------------------------------------
    Route::get('/student/subscription/add/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@add']);
    Route::get('/subsCheck', 'SubscriptionController@subsCheck');
    Route::post('/student/subscription/add/{studentId}/{standard}', 'SubscriptionController@store');

    Route::get('/student/subscription/addHours/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@addHours']);
    Route::post('/student/subscription/addHours/{studentId}/{standard}', 'SubscriptionController@saveHours');

    Route::post('/subscriptionDelete/{studentId}', 'SubscriptionController@delete');
    Route::get('/subsLockUnlock', 'SubscriptionController@lockUnlock');

    Route::get('/student/subscription/refund/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@refund']);
    Route::get('/subsCheckRefund', 'SubscriptionController@subsCheckRefund');
    Route::post('/student/subscription/refund/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@refundPost']);

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
    Route::post('/students/grade/{classId}/students/{filter}', 'GradesController@gradeTransfer');

    Route::get('/students/grade/{classId}/attendance', 'GradesController@editAttendance');
    Route::post('/students/grade/{classId}/attendance', 'GradesController@saveAttendance');

    Route::get('/students/reports/attendance', 'GradesController@attendanceReportView');
    Route::post('/students/reports/attendance', 'GradesController@attendanceReport');

//---------------------------------UsersController--------------------------------------------------------------------
    Route::get('users',['middleware' => 'OfficeStaff','uses'=>'UsersController@index']);
	Route::get('users/{id?}/edit', ['middleware' => 'OfficeStaff','middleware' => 'Superman','uses'=>'UsersController@edit']);    
	Route::post('users/{id?}/edit',['middleware' => 'OfficeStaff','middleware' => 'Superman','uses'=>'UsersController@update']);

    Route::get('users/register',['middleware' => 'OfficeStaff','middleware' => 'UserAdd', function () {return view('users.register');}]);
    Route::get('/duplicateCheck', ['middleware' => 'OfficeStaff','uses'=>'UsersController@duplicateCheck']);
    Route::get('/branchLoader', ['middleware' => 'OfficeStaff','uses'=>'UsersController@branchLoader']); 
    Route::post('users/register',['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@add']);

    Route::get('users/{id?}/typeEdit', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@typeEdit']);
    Route::post('users/{id?}/typeEdit', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@typeUpdate']);

    Route::get('/users/{id?}/disable', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@disable']);

    Route::get('users/disabled',['middleware' => 'OfficeStaff','uses'=>'UsersController@usersDisabled']);
    Route::get('/users/{id?}/restore', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@restore']);

    Route::get('/users/{id?}/password', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@passwordChangeView']);
    Route::post('/users/{id?}/password', ['middleware' => 'OfficeStaff','middleware' => 'UserAdd','uses'=>'UsersController@passwordChange']);


    Route::get('users/password/edit/self',function () {return view('users.passwordSelfEdit');});
    Route::post('users/password/edit/self', 'UsersController@passwordSelfUpdate');
//-------------------------------RolesController-----------------------------------------------
	Route::get('roles', ['middleware' => 'OfficeStaff','middleware' => 'Superman','uses'=>'RolesController@index']);
	Route::get('roles/create', ['middleware' => 'OfficeStaff','middleware' => 'Superman','uses'=>'RolesController@create']);
	Route::post('roles/create', ['middleware' => 'OfficeStaff','uses'=>'RolesController@store']);
});

 



 

 