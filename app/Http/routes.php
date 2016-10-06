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
    Route::get('/excelHome', 'ExcelController@home');

//--------------------------------------StudentsController----------------------------------------
    Route::get('/enroll', ['middleware' => 'nursery_admin','uses'=>'StudentsController@enroll']); 
    Route::get('/enrollCheck', 'StudentsController@enrollCheck');
    Route::post('/enroll', 'StudentsController@store');

    Route::get('/students/search', 'StudentsController@searchView');
    Route::get('/searchBind', 'StudentsController@searchBind');

    Route::get('/profile/student/{studentId}', 'StudentsController@profile');

    Route::post('/student/{studentId}/delete', ['middleware' => 'nursery_admin','uses'=>'StudentsController@delete']);
    Route::post('/student/{studentId}/restore',['middleware' => 'nursery_admin','uses'=>'StudentsController@restore']);

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

    Route::get('/invLockUnlock', 'InvoiceController@lockUnlock');

    Route::post('/profile/student/{studentId}', ['middleware' => 'nursery_admin','uses'=>'InvoiceController@delete']);

    Route::get('/invoice/exchange/{customId}','InvoiceController@exchangeForm');
    Route::post('/invoice/exchange/{customId}','InvoiceController@exchangeSave');

 //---------------------------------------GradesController-------------------------------------------------------------
    Route::get('/students/grades', 'GradesController@index');
    Route::get('/students/grade/{classId}/students/{filter}', 'GradesController@students');   
    Route::get('/excelStudentsList/{classId}/students/{filter}', 'ExcelController@students');   
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

    Route::post('/store/delete/stock/{stockId}', ['middleware' => 'StoreManager','uses'=>'StoreController@deleteStock']);

    Route::get('/store/upload/invoice/{stockId}', ['middleware' => 'StoreManager',function () {return view('store.uploadStockInvoice');}]);
    Route::post('/store/upload/invoice/{stockId}', ['middleware' => 'StoreManager','uses'=>'StoreController@uploadInvoice']);

    Route::get('/store/transfer/item/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransfer']);
    Route::post('/store/transfer/item/{itemId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransferSave']);
    Route::post('/store/transfer/callback/{transferId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemTransferCallback']);

    Route::get('/store/branch/transfers/pending', ['middleware' => 'BranchStore','uses'=>'StoreController@pendingTransfers']);
    Route::post('/store/branch/transfers/approve/{transferId}', ['middleware' => 'BranchStore','uses'=>'StoreController@approveTransfer']);
    Route::post('/store/branch/transfers/reject/{transferId}', ['middleware' => 'BranchStore','uses'=>'StoreController@rejectTransfer']);

    Route::get('/store/branch/items', ['middleware' => 'StoreAnyRole','uses'=>'StoreController@branchStore']); 
    Route::get('/store/branch/items/{itemId}', ['middleware' => 'StoreAnyRole','uses'=>'StoreController@itemViewBranch']); 

    Route::get('/store/return/item/{itemId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturn']);
    Route::post('/store/return/item/{itemId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturnSave']);
    Route::post('/store/return/callback/{returnId}', ['middleware' => 'BranchStore','uses'=>'StoreController@itemReturnCallback']);

    Route::get('/store/main/rejections/{viewer}', ['middleware' => 'StoreManager','uses'=>'StoreController@transferRejections']);
    Route::get('/transferRejectRead', ['middleware' => 'StoreManager','uses'=>'StoreController@transferRejectRead']);

    Route::get('/store/main/returns/{viewer}', ['middleware' => 'StoreManager','uses'=>'StoreController@storeReturns']);
    Route::post('/store/main/returns/approve/{returnId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemReturnApprove']);
    Route::post('/store/main/returns/reject/{returnId}', ['middleware' => 'StoreManager','uses'=>'StoreController@itemReturnReject']);

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
    Route::post('/employees/{employeeId}/salary/add', ['middleware' => 'SalaryEditor','uses'=>'EmployeesController@saveSalary']);

    Route::get('/employees/{employeeId}/salary/edit', ['middleware' => 'SalaryEditor','uses'=>'EmployeesController@editSalary']);
    Route::post('/employees/{employeeId}/salary/edit', ['middleware' => 'SalaryEditor','uses'=>'EmployeesController@editSaveSalary']);

    Route::get('/employees/branch/search',function () {return view('employees.search');});
    Route::get('/employeeSearchBind', 'EmployeesController@searchBind');

//--------------------------------------------------------EmployeesControllerExtra------------------------------------------  

    Route::get('/employees/{employeeId}/{stuff}/paymentHistory', 'EmployeesControllerExtra@payContentHistory');   
    Route::get('/payrollContentUnapprove', ['middleware' => 'Superman','uses'=>'EmployeesControllerExtra@payrollContentUnapprove']);
    Route::post('/payrollContentDelete/{Id}/{stuff}/{employeeId}', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@payrollContentDelete']);

    Route::get('/employee/upload/hrx/{doc}/{Id}/{employeeId}', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@uploadFormHRXDoc']);
    Route::post('/employee/upload/hrx/{doc}/{Id}/{employeeId}', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@uploadHRXDoc']);
 
    Route::get('/employee/{employeeId}/add/bonus', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addBonus']);
    Route::post('/employee/{employeeId}/add/bonus', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveBonus']);  
    

    Route::get('/employee/{employeeId}/add/deduction', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addDeduction']);
    Route::post('/employee/{employeeId}/add/deduction', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveDeduction']);

    Route::get('/employee/{employeeId}/add/loan', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addLoan']);
    Route::post('/employee/{employeeId}/add/loan', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveLoan']);

    Route::get('/employee/{employeeId}/add/benefit', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addBenefit']);
    Route::post('/employee/{employeeId}/add/benefit', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveBenefit']);

    Route::get('/employee/{employeeId}/add/overtime', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addOvertime']);
    Route::post('/employee/{employeeId}/add/overtime', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveOvertime']);

    Route::get('/employee/{employeeId}/add/vacation', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@addVacation']);
    Route::post('/employee/{employeeId}/add/vacation', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@saveVacation']);
    Route::get('/employeeVacationCheck', 'EmployeesControllerExtra@employeeVacationCheck');

    Route::get('/employee/{employeeId}/add/sicks', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@addSicks']);
    Route::post('/employee/{employeeId}/add/sicks', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@saveSicks']);

    Route::get('/employee/{employeeId}/add/maternity', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@addMaternity']);
    Route::post('/employee/{employeeId}/check/maternity', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@checkMaternity']); 
    Route::post('/employee/{employeeId}/add/maternity', ['middleware' => 'HRAdmin','uses'=>'EmployeesControllerExtra@saveMaternity']);

    Route::get('/employee/{employeeId}/absentCorrection', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@absentCorrection']);
    Route::get('/absentCorrectionCheck', 'EmployeesControllerExtra@absentCorrectionCheck');
    Route::post('/employee/{employeeId}/absentCorrection', ['middleware' => 'AttendanceManager','uses'=>'EmployeesControllerExtra@absentCorrectionSave']);

    Route::get('/employee/{employeeId}/proPayment', ['middleware' => 'ProPayments','uses'=>'EmployeesControllerExtra@proPayment']);
    Route::get('/employeeExpenseCheck', 'EmployeesControllerExtra@employeeExpenseCheck');
    Route::post('/employee/{employeeId}/proPayment', ['middleware' => 'ProPayments','uses'=>'EmployeesControllerExtra@proPaymentSave']);

//--------------------------------------------------------EmployeesControllerHR------------------------------------------  
    Route::get('/employees/hr/search',['middleware' => 'OfficeStaff', function () {return view('employees.searchAll');}]);
    Route::get('/employeeSearchBindAll', 'EmployeesControllerHR@searchBind');

    Route::post('/employees/hr/resign/{employeeId}', ['middleware' => 'HROfficer','uses'=>'EmployeesControllerHR@resignation']);
    Route::post('/employees/hr/restore/{employeeId}', ['middleware' => 'HROfficer','uses'=>'EmployeesControllerHR@restore']);
    Route::post('/employees/hr/terminate/{employeeId}', ['middleware' => 'HROfficer','uses'=>'EmployeesControllerHR@terminate']);
    Route::post('/employees/hr/remove/{employeeId}', ['middleware' => 'HROfficer','uses'=>'EmployeesControllerHR@remove']);

    Route::get('/employees/hr/transfer/{employeeId}/{branch}', ['middleware' => 'HROfficer','uses'=>'EmployeesControllerHR@transfer']);

//--------------------------------------------------------PayrollController------------------------------------------------ 

    Route::get('/payroll/salary/verification', ['middleware' => 'OfficeStaff','middleware' => 'PayrollReports','uses'=>'PayrollController@salaryVerification']);
    Route::get('/verifySalary', ['middleware' => 'OfficeStaff','middleware' => 'PayrollReports','uses'=>'PayrollController@verifySalary']);

    Route::get('/payroll/salary/approvals', ['middleware' => 'OfficeStaff','middleware' => 'PayrollApprovals','uses'=>'PayrollController@pendingApprovals']);
    Route::post('/approvePayrollContent', ['middleware' => 'OfficeStaff','middleware' => 'PayrollApprovals','uses'=>'PayrollController@approvePayrollContent']);

    Route::get('/payroll/content/rejections/unread', ['middleware' => 'HRAdmin','uses'=>'PayrollController@approvePayrollContent']);

//--------------------------------------------------------PayrollControllerMain------------------------------------------------ 

    Route::get('/payroll/generate/initialize',['middleware' => 'Payroll', function () {return view('payroll.initialize');}]);
    Route::get('/payrollBranches', ['middleware' => 'Payroll','uses'=>'PayrollControllerMain@branchFilter']);
    Route::post('/payroll/generate/middle',['middleware' => 'Payroll', 'uses'=>'PayrollControllerMain@step2']);
    Route::post('/payroll/generate/final',['middleware' => 'Payroll', 'uses'=>'PayrollControllerMain@step3']);
     
    Route::get('/payroll/{employeeId}/{stuff}/{start}/{end}/individualContents', 'PayrollController@individualContents');  

    Route::get('/payroll/{id}/view', ['middleware' => 'OfficeStaff','middleware' => 'PayrollReports','uses'=>'PayrollControllerMain@payrollView']);    
    
    Route::get('/payroll/history',['middleware' => 'PayrollReports', function () {return view('payroll.history');}]);

//--------------------------------------------------------DocumentsController------------------------------------------------------------------ 


    Route::get('/staffDocExpiry', ['middleware' => 'HRAdmin','uses'=>'DocumentsController@staffDocExpiryChange']);
    Route::post('/staffDocUpload', ['middleware' => 'HRAdmin','uses'=>'DocumentsController@staffDocUpload']);

    Route::get('/{docId}/{employee}/{number}/staff/show/Document', ['uses'=>'DocumentsController@staffDocShow']);
    Route::get('/{docId}/{employee}/{number}/staffDocDownload', ['uses'=>'DocumentsController@staffDocDownload']);

//----------------------------------------------------PaymentsController--------------------------------------------------------------------------

    Route::get('/payments/balance',['uses'=>'PaymentsController@balance']);
    Route::get('/payments/{classId}/{filter}/balance',['uses'=>'PaymentsController@balanceGrades']);
    Route::get('/excelStudentsBalance/{classId}/{filter}', 'ExcelController@studentsBalance');

    Route::get('/payments/receipts',['uses'=>'PaymentsController@receiptBook']);
    Route::post('/payments/receipts',['uses'=>'PaymentsController@receiptBookGenerate']);

});

 


 

 