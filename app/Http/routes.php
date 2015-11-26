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

    Route::get('/student/{studentId}/delete', ['middleware' => 'nursery_admin','uses'=>'StudentsController@delete']);
    Route::get('/student/{studentId}/restore',['middleware' => 'nursery_admin','uses'=>'StudentsController@restore']);

    Route::get('/student/{studentId}/edit', ['middleware' => 'nursery_admin','uses'=>'StudentsController@editForm']);
    Route::post('/student/{studentId}/edit', ['middleware' => 'nursery_admin','uses'=>'StudentsController@editSave']); 
    Route::get('/studentEditCheck', 'StudentsController@studentEditCheck');

//---------------------------------------SubscriptionController---------------------------------------
    Route::get('/student/subscription/add/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@add']);
    Route::get('/subsCheck', 'SubscriptionController@subsCheck');
    Route::post('/student/subscription/add/{studentId}/{standard}', 'SubscriptionController@store');

    Route::get('/student/subscription/addHours/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@addHours']);
    Route::post('/student/subscription/addHours/{studentId}/{standard}', 'SubscriptionController@saveHours');

    Route::post('/subscriptionDelete/{studentId}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@delete']);
    Route::get('/subsLockUnlock', 'SubscriptionController@lockUnlock');

    Route::get('/student/subscription/refund/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@refund']);
    Route::get('/subsCheckRefund', 'SubscriptionController@subsCheckRefund');
    Route::post('/student/subscription/refund/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@refundPost']);

    Route::post('/student/subscription/refundTicket/{studentId}/{standard}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@ticketSave']);

    Route::get('/student/subscription/refund/excess/{studentId}/{amount}', ['middleware' => 'nursery_admin','uses'=>'SubscriptionController@refundExcess']);

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

    Route::post('/profile/student/{studentId}', ['middleware' => 'nursery_admin','uses'=>'InvoiceController@delete']);

    Route::get('/invoice/exchange/{customId}','InvoiceController@exchangeForm');
    Route::post('/invoice/exchange/{customId}','InvoiceController@exchangeSave');



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

//-------------------------------CallCenterController-----------------------------------------------
    Route::get('/refunds/tickets/{viewer}', ['middleware' => 'CallCenterManager','uses'=>'CallCenterController@unassigned']);
    Route::post('/refunds/tickets/{viewer}', ['middleware' => 'CallCenterManager','uses'=>'CallCenterController@assignRefundAgents']);

    Route::get('/refunds/agents/tickets/{viewer}', ['middleware' => 'CallCenterAgent','uses'=>'CallCenterController@feedbacks']);
    Route::get('/refunds/agent/feedback/{studentId}/{ticketId}', ['middleware' => 'CallCenterAgent','uses'=>'CallCenterController@feedbackForm']);
    Route::post('/refunds/agent/feedback/{studentId}/{ticketId}', ['middleware' => 'CallCenterAgent','uses'=>'CallCenterController@saveFeedback']);

//-------------------------------StoreController---------------------------------
    Route::get('/store/main', ['middleware' => 'StoreManagerOrView','uses'=>'StoreController@mainStore']);
    Route::get('/store/main/item/{itemId}', ['middleware' => 'StoreManagerOrView','uses'=>'StoreController@itemView']);

    Route::get('/store/add/item/new', ['middleware' => 'StoreManager','uses'=>'StoreController@addNewItem']);
    Route::post('/store/add/item/new', ['middleware' => 'StoreManager','uses'=>'StoreController@saveNewItem']);
    Route::get('/itemAddCheck', 'StoreControllerExtra@itemAddCheck');

    Route::get('/store/edit/{itemId}/item', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editItem']);
    Route::post('/store/edit/{itemId}/item', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editItemSave']);
    Route::get('/itemEditCheck', 'StoreControllerExtra@itemEditCheck');

    Route::get('/store/add/stock/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@addStock']);
    Route::post('/store/add/stock/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@saveStock']);

    Route::get('/store/delete/stock/{stockId}', ['middleware' => 'StoreManager','uses'=>'StoreController@deleteStock']);

    Route::get('/store/upload/invoice/{stockId}', ['middleware' => 'StoreManager',function () {return view('store.uploadStockInvoice');}]);
    Route::post('/store/upload/invoice/{stockId}', ['middleware' => 'StoreManager','uses'=>'StoreController@uploadInvoice']);

    Route::get('/store/transfer/item/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransfer']);
    Route::post('/store/transfer/item/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransferSave']);
    Route::get('/store/transfer/callback/{transferId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransferCallback']);

    Route::get('/store/branch/transfers/pending', ['middleware' => 'BranchStore','uses'=>'StoreController@pendingTransfers']);
    Route::get('/store/branch/transfers/approve/{transferId}', ['middleware' => 'BranchStore','uses'=>'StoreController@approveTransfer']);
    Route::get('/store/branch/transfers/reject/{transferId}', ['middleware' => 'BranchStore','uses'=>'StoreController@rejectTransfer']);

    Route::get('/store/branch/items', ['middleware' => 'StoreAnyRole','uses'=>'StoreController@branchStore']); 
    Route::get('/store/branch/items/{itemId}', ['middleware' => 'StoreAnyRole','uses'=>'StoreController@itemViewBranch']); 

    Route::get('/store/return/item/{itemId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturn']);
    Route::post('/store/return/item/{itemId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturnSave']);
    Route::get('/store/return/callback/{returnId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturnCallback']);

    Route::get('/store/main/rejections/{viewer}', ['middleware' => 'StoreManager','uses'=>'StoreController@transferRejections']);
    Route::get('/transferRejectRead', ['middleware' => 'StoreManager','uses'=>'StoreController@transferRejectRead']);

    Route::get('/store/main/returns/{viewer}', ['middleware' => 'StoreManager','uses'=>'StoreController@storeReturns']);
    Route::get('/store/main/returns/approve/{returnId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemReturnApprove']);
    Route::get('/store/main/returns/reject/{returnId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemReturnReject']);

    Route::get('/store/branch/returns/rejections/{viewer}', ['middleware' => 'BranchStore','uses'=>'StoreController@returnRejections']);
    Route::get('/returnRejectRead', ['middleware' => 'BranchStore','uses'=>'StoreController@returnRejectRead']);

    Route::get('/store/branch/requests', ['middleware' => 'BranchStore','uses'=>'StoreController@storeRequestsBranch']);

    Route::get('/store/branch/requests/add', ['middleware' => 'BranchStore','uses'=>'StoreControllerExtra@addStoreRequest']);
    Route::get('/storeItemLoader', 'StoreControllerExtra@itemLoader');
    Route::get('/storeItemAdd', 'StoreControllerExtra@itemAdd');
    Route::get('/storeItemRemove', 'StoreControllerExtra@itemRemove');
    Route::post('/store/branch/requests/add', ['middleware' => 'BranchStore','uses'=>'StoreControllerExtra@saveStoreRequest']);


    Route::get('/store/main/requests/{viewer}', ['middleware' => 'StoreManager','uses'=>'StoreController@storeRequestsMain']);
    Route::get('/store/requestReader', ['middleware' => 'StoreManager','uses'=>'StoreController@storeRequestRead']);
    Route::get('/store/main/report/requests/transfers', ['middleware' => 'StoreManager','uses'=>'StoreController@storeRequestsTransfers']);
    Route::post('/store/main/report/requests/transfers', ['middleware' => 'StoreManager','uses'=>'StoreController@RequestsTransfersReport']);

    Route::get('/store/categories', ['middleware' => 'StoreManagerOrView','uses'=>'StoreControllerExtra@categories']);
    Route::get('/store/categories/add/new', ['middleware' => 'StoreManager',function () {return view('store.addCategory');}]);
    Route::post('/store/categories/add/new', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@addCategory']);
    Route::get('/categoryAddCheck', 'StoreControllerExtra@categoryAddCheck');

    Route::get('/store/categories/{categoryId}/edit', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editCategory']);
    Route::post('/store/categories/{categoryId}/edit', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editSaveCategory']);
    Route::get('/categoryEditCheck', 'StoreControllerExtra@categoryEditCheck');

    Route::get('/store/suppliers', ['middleware' => 'StoreManagerOrView','uses'=>'StoreControllerExtra@suppliers']);
    Route::get('/store/suppliers/add/new', ['middleware' => 'StoreManager',function () {return view('store.addSupplier');}]);
    Route::post('/store/suppliers/add/new', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@addSupplier']);
    Route::get('/supplierAddCheck', 'StoreControllerExtra@supplierAddCheck');

    Route::get('/store/suppliers/{supplierId}/edit', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editSupplier']);
    Route::post('/store/suppliers/{supplierId}/edit', ['middleware' => 'StoreManager','uses'=>'StoreControllerExtra@editSaveSupplier']);
    Route::get('/supplierEditCheck', 'StoreControllerExtra@supplierEditCheck');

    Route::get('/store/students/nonreceived/{viewer}', ['middleware' => 'StoreAnyRole','uses'=>'StoreControllerExtra@nonReceivedItems']);
    Route::get('/store/issueReceiveLetter/{customId}', ['middleware' => 'BranchStore','uses'=>'StoreControllerExtra@issueReceiveLetter']);
    Route::get('/store/ReceiveLetter/{trackId}', ['middleware' => 'StoreAnyRole','uses'=>'StoreControllerExtra@ReceiveLetter']);

    Route::get('/store/receipts/exchanged/items', ['middleware' => 'StoreAnyRole','uses'=>'StoreControllerExtra@exchangedItems']);

//--------------------------------------------------------EmployeesController------------------------------------------ 

    Route::get('/employees/branch', 'EmployeesController@index'); 
    Route::get('/employees/{employeeId}/profile','EmployeesController@profile');

    Route::get('/employees/add/new', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@add']);
    Route::post('/employees/add/new', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@save']);
    Route::get('/employeeAddCheck', 'EmployeesController@employeeAddCheck');

    Route::get('/employees/{employeeId}/edit', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@edit']);
    Route::post('/employees/{employeeId}/edit', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@editSave']);
    Route::get('/employeeEditCheck', 'EmployeesController@employeeEditCheck');

    Route::get('/employees/{employeeId}/special/workings', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@specialDays']);
    Route::post('/employees/{employeeId}/special/workings', ['middleware' => 'HRAdmin','uses'=>'EmployeesController@specialDaysSave']);

    Route::get('/employees/{employeeId}/salary/add', ['middleware' => 'SalaryEditor','uses'=>'EmployeesController@addSalary']);
});

 



 

 