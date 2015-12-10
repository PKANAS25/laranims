<link href="/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
<div id="sidebar" class="sidebar">
            <!-- begin sidebar scrollbar -->
            <div data-scrollbar="true" data-height="100%">
                <!-- begin sidebar user -->
                <ul class="nav">
                    <li class="nav-profile">
                         
                        <div class="info">
                           <a @if (Auth::user()->admin_type>1) 
                            href="/branching"  
                            @endif 
                            style="text-decoration:none; color:#FFF !important;"> {!! Auth::user()->branch_name; !!}</a>
                            <small></small>
                        </div>
                    </li>
                </ul>
                <!-- end sidebar user -->
                <!-- begin sidebar nav -->
                <ul class="nav">
                    <li class="nav-header">Navigation</li>
<!-- **************************************************************************************************************************************** -->                    
                    <li class="@if(session('title') == 'Home')   active @endif"><a href="/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
<!-- ***********************************************************Payments***************************************************************************** -->                                         
                    <li class="has-sub">
                        <a href="javascript:;">
                             <b class="caret pull-right"></b>
                            <i class="fa fa-credit-card"></i> 
                            <span>Payments</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="email_inbox.html">Balance</a></li>
                            <li><a href="email_inbox_v2.html">Receipt Book</a></li>
                            <li><a href="email_compose.html">Paments Lock</a></li>
                            <li><a href="email_detail.html">Payments Deposit</a></li>
                            <li><a href="email_detail.html">Bonus</a></li>
                            <li><a href="email_detail.html">Refund Tickets</a></li>
                            <li><a href="email_detail.html">Deposit Slips</a></li>
                            <li><a href="email_detail.html">Non Cash Statement</a></li>
                            <li><a href="email_detail.html">No Deposits</a></li>
                            <li><a href="email_detail.html">Cash in Hand Report</a></li>
                            <li><a href="email_detail.html">Trash Bin  <i class="fa fa-trash text-theme m-l-5"></i></a></li>
                            
                        </ul>
                    </li>

<!-- ************************************************************Students**************************************************************************** -->                                        
                    <li class="has-sub @if(session('title') == 'Students')   active @endif">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-mortar-board"></i>
                            <span>Students</span> 
                        </a>
                        <ul class="sub-menu">
                            <li class="@if(session('subtitle') == 'grades')   active @endif"><a href="/students/grades">Grades</a></li>
                            <li class="@if(session('subtitle') == 'search')   active @endif"><a href="/students/search">Search <i class="fa fa-binoculars text-theme m-l-5"></i></a></li>
                            @if(Auth::user()->hasRole('nursery_admin')) 
                            <li class="@if(session('subtitle') == 'enroll')   active @endif"><a href="/enroll">Enroll</a></li> 
                            @endif
                            <li class="@if(session('subtitle') == 'attendance')   active @endif"><a href="/students/reports/attendance">Attendance Report</a></li> 
                        </ul>
                    </li>

<!-- *******************************************************************Store********************************************************************* -->                    
                    @if(Auth::user()->hasRole('BranchStore') || Auth::user()->hasRole('StoreManager') || Auth::user()->hasRole('StoreView'))                    
                    <li class="has-sub @if(session('title') == 'Store')   active @endif">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="ion-ios-cart"></i>
                            <span>Store</span></span> 
                        </a>
                        <ul class="sub-menu">
                            @if(Auth::user()->hasRole('StoreManager') || Auth::user()->hasRole('StoreView'))
                            <li class="@if(session('subtitle') == 'main')   active @endif"><a href="/store/main">Main Store</a></li>
                               
                                @if(Auth::user()->hasRole('StoreManager') )
                                <li class="@if(session('subtitle') == 'addItem')   active @endif"><a href="/store/add/item/new">Add Item</a></li> 
                                
                                <li class="has-sub @if(session('subtitle') == 'returns' || session('subtitle') == 'rejections' || session('subtitle') == 'storeRequestsMain' || session('subtitle') == 'RequestsNTransfers')   active @endif">
                                <a href="javascript:;"><b class="caret pull-right"></b> Reports</a>
                                <ul class="sub-menu">                                
                                <li class="@if(session('subtitle') == 'returns')   active @endif"><a href="/store/main/returns/pending">Store Returns</a></li>
                                <li class="@if(session('subtitle') == 'rejections') active @endif"><a href="/store/main/rejections/unread">Transfer Rejections</a></li>
                                <li class="@if(session('subtitle') == 'storeRequestsMain') active @endif"><a href="/store/main/requests/unread">Store Requests</a></li> 
                                <li class="@if(session('subtitle') == 'RequestsNTransfers') active @endif"><a href="/store/main/report/requests/transfers">Requests & Transfers</a></li>
                                </ul>
                                </li>
                                
                                @endif

                            <li class="@if(session('subtitle') == 'categories') active @endif"><a href="/store/categories">Categories</a></li>
                            <li class="@if(session('subtitle') == 'suppliers') active @endif"><a href="/store/suppliers">Suppliers</a></li>
                            @endif                           
                            
                            <li class="@if(session('subtitle') == 'branchStore') active @endif"><a href="/store/branch/items">Branch Store</a></li> 
                            @if(Auth::user()->hasRole('BranchStore') )
                             <li class="@if(session('subtitle') == 'returnRejections') active @endif"><a href="/store/branch/returns/rejections/unread">Return Rejections</a></li>
                             <li class="@if(session('subtitle') == 'storeRequests') active @endif"><a href="/store/branch/requests">Store Requests</a></li>
                            @endif
                             <li class="@if(session('subtitle') == 'nonReceived') active @endif"><a href="/store/students/nonreceived/waiting">Non-Received Items</a></li>
                            <li class="@if(session('subtitle') == 'exchanged') active @endif"><a href="/store/receipts/exchanged/items">Exchanged Items</a></li>
                        </ul>
                    </li>
                    @endif
<!-- ***********************************************************Payroll***************************************************************************** -->                                        
                    
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-suitcase"></i>
                            <span>Payroll</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="table_basic.html">Generate Payroll</a></li>
                            <li><a href="table_basic.html">History</a></li> 
                            <li><a href="table_basic.html">Salary Verification</a></li> 
                            <li><a href="table_basic.html">Approvals</a></li> 
                            <li><a href="table_basic.html">Salary Contents Report</a></li> 
                            <li><a href="table_basic.html">Bank Rejections</a></li> 
                        </ul>
                    </li>
                    
<!-- *************************************************************HR*************************************************************************** -->                    

                    @if(Auth::user()->admin_type>1)
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="ion-person-stalker"></i>
                            <span>HR</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="table_basic.html">Employees Search</a></li> 
                            <li><a href="table_basic.html">Customized Search</a></li> 
                                                        
                            <li class="has-sub">
                                <a href="javascript:;"><b class="caret pull-right"></b> Reports</a>
                                <ul class="sub-menu"> 
                                <li><a href="table_basic.html">IBAN Check</a></li> 
                                <li><a href="table_basic.html">Labour Card Check</a></li> 
                                <li><a href="table_basic.html">Boiometris Report</a></li>
                                </ul>
                            </li>

                            <li><a href="table_basic.html">Import Attendance</a></li>
                            <li><a href="table_basic.html">Saturday Overtimes</a></li> 
                            <li><a href="table_basic.html">Public Holidyas</a></li> 
                        </ul>
                    </li>
                    @endif

<!-- **********************************************************Employees****************************************************************************** -->                    
                    <li class="has-sub @if(session('title') == 'Employees')   active @endif">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="ion-person-add"></i> 
                            <span>Employees</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="@if(session('subtitle') == 'employeeList') active @endif"><a href="/employees/branch">Employee List</a></li>
                            @if(Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer'))
                            <li class="@if(session('subtitle') == 'addEmp') active @endif"><a href="/employees/add/new">Add Employee</a></li>
                            @endif
                            <li class="@if(session('subtitle') == 'EmpSearch') active @endif"><a href="/employees/branch/search" >Search <i class="fa fa-binoculars text-theme m-l-5"></i></a></li>
                            <li><a href="#" >Bonus Hold</a></li>
                            <li><a href="#" >Attendance Report</a></li>
                            <li><a href="#" >Exit Permits</a></li>
                            <li><a href="#" >Expiring Documents</a></li>
                            <li><a href="#" >Missing Documents</a></li>
                            <li><a href="#" >Petty Cash</a></li>
                        </ul>
                    </li>


<!-- *************************************************************Call Center*************************************************************************** -->                                         
                     @if (Auth::user()->hasRole('CallCenterManager') || Auth::user()->hasRole('CallCenterAgent') || Auth::user()->hasRole('Superman'))   
                     <li class="has-sub @if(session('title') == 'CallCenter')   active @endif" >
                        <a href="javascript:;">
                             <b class="caret pull-right"></b>
                            <i class="fa fa-phone-square"></i> 
                            <span>Call Center</span>
                        </a>
                        <ul class="sub-menu">
                            @if(Auth::user()->hasRole('CallCenterManager'))
                            <li class="@if(session('subtitle') == 'unassigned')   active @endif"><a href="/refunds/tickets/unassigned">Unassigned Refund Tickets</a></li>
                            @endif
                            @if(Auth::user()->hasRole('CallCenterAgent'))
                            <li class="@if(session('subtitle') == 'feedbacksPending')   active @endif"><a href="/refunds/agents/tickets/noreview">Refund Pending Reviews</a></li>
                            @endif
                            <li><a href="email_inbox_v2.html">Receipt Book</a></li>
                            <li><a href="email_compose.html">Paments Lock</a></li>                            
                        </ul>
                    </li>
                    @endif

<!-- **********************************************************Administrator****************************************************************************** -->                    
                    @if(Auth::user()->hasRole('user_view') || Auth::user()->hasRole('user_add') || Auth::user()->hasRole('Superman'))
                    <li class="has-sub @if(session('title') == 'Administrator')   active @endif" >
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="ion-star"></i>
                            <span>Administrator</span>
                        </a>
                        <ul class="sub-menu">
                            @if(Auth::user()->hasRole('user_view'))
                            <li class="@if(session('subtitle') == 'users')   active @endif"><a href="/users">Users</a></li>@endif
                            @if(Auth::user()->hasRole('user_add'))
                            <li class="@if(session('subtitle') == 'register')   active @endif"><a href="/users/register">Add Users</a></li>@endif
                            @if(Auth::user()->hasRole('Superman'))
                            <li class="@if(session('subtitle') == 'Roles')   active @endif"><a href="/roles">Roles</a></li>
                            <li class="@if(session('subtitle') == 'addRoles')   active @endif"><a href="/roles/create">Add Roles</a></li>@endif
                             
                        </ul>
                    </li>
                    @endif
<!-- ********************************************************Branches******************************************************************************** -->                    
                    @if(Auth::user()->admin_type>1)
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-institution alias"></i>
                            <span>Branches</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="email_system.html">Branches List</a></li>
                            <li><a href="email_newsletter.html">Add Branch</a></li>
                        </ul>
                    </li>
                    @endif

<!-- *************************************************************Settings*************************************************************************** -->                    
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cogs"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="chart-flot.html">Class Rooms</a></li>
                            <li><a href="chart-morris.html">Payments</a></li>
                            <li><a href="chart-js.html">Bonus</a></li>
                            <li><a href="chart-js.html">Offers</a></li>
                            <li><a href="chart-js.html">Branch Info</a></li>
                            <li><a href="chart-js.html">Documents</a></li>
                        </ul>
                    </li>
                     
                    
   <!-- ***********************************************************Buses***************************************************************************** -->                 

                   <!-- <li><a href="calendar.html"><i class="fa fa-calendar"></i> <span>Calendar</span></a></li> -->
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-bus"></i>
                            <span>Buses</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="map_vector.html">Buses List</a></li>
                            <li><a href="map_google.html">Add Bus</a></li>
                            <li><a href="map_google.html">Bus Report I</a></li>
                            <li><a href="map_google.html">Bus Report II</a></li>
                        </ul>
                    </li>
<!-- *************************************************************Extras*************************************************************************** -->                    


                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-gift"></i>
                            <span>Extras</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="gallery.html">Events</a></li>
                            <li><a href="gallery_v2.html">Projected Revenue</a></li>
                            <li><a href="gallery_v2.html">Mobile Numbers</a></li>
                            <li><a href="gallery_v2.html">Staff And Students</a></li>
                            <li><a href="gallery_v2.html">Contacts Report I</a></li>
                            <li><a href="gallery_v2.html">Documents</a></li>
                            <li><a href="gallery_v2.html">Enquiries</a></li>
                            <li><a href="gallery_v2.html">Waiting List</a></li>
                            <li><a href="gallery_v2.html">Wrong Numbers</a></li>
                        </ul>
                    </li>

 <!-- *************************************************************Assets*************************************************************************** -->                                      
                    
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-database"></i>
                            <span>Assets  </span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="extra_timeline.html">Assets Store</a></li>
                            <li><a href="extra_coming_soon.html">Add Item</a></li>
                            <li><a href="extra_search_results.html">Branch Assets</a></li>
                            <li><a href="extra_invoice.html">Rooms</a></li>
                             
                        </ul>
                    </li>
                     
                     
                     
                     
                    <!-- begin sidebar minify button -->
                    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
                    <!-- end sidebar minify button -->
                </ul>
                <!-- end sidebar nav -->
            </div>
            <!-- end sidebar scrollbar -->
        </div>
        <div class="sidebar-bg"></div>