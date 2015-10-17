<div id="sidebar" class="sidebar">
            <!-- begin sidebar scrollbar -->
            <div data-scrollbar="true" data-height="100%">
                <!-- begin sidebar user -->
                <ul class="nav">
                    <li class="nav-profile">
                         
                        <div class="info">
                           <a @if (Auth::user()->admin_type>1) href="/branching"  @endif style="text-decoration:none; color:#FFF !important;"> {!! Auth::user()->branch_name; !!}</a>
                            <small></small>
                        </div>
                    </li>
                </ul>
                <!-- end sidebar user -->
                <!-- begin sidebar nav -->
                <ul class="nav">
                    <li class="nav-header">Navigation</li>
                    <li class="@if(session('title') == 'Home')   active @endif"><a href="/home"><i class="fa fa-laptop"></i> <span>Dashboard</span></a></li>
                     
                    <li class="has-sub">
                        <a href="javascript:;">
                             <b class="caret pull-right"></b>
                            <i class="fa fa-money"></i> 
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
                    <li class="has-sub @if(session('title') == 'Students')   active @endif">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-mortar-board"></i>
                            <span>Students</span> 
                        </a>
                        <ul class="sub-menu">
                            <li><a href="ui_general.html">Grades</a></li>
                            <li><a href="ui_typography.html">Search <i class="fa fa-binoculars text-theme m-l-5"></i></a></li>
                             <li class="@if(session('subtitle') == 'enroll')   active @endif"><a href="/enroll">Enroll</a></li>
                            <li><a href="ui_unlimited_tabs.html">Attendance</a></li> 
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-shopping-cart"></i>
                            <span>Store</span></span> 
                        </a>
                        <ul class="sub-menu">
                            <li><a href="form_elements.html">Main Store</a></li>
                            <li><a href="form_plugins.html">Add Item</a></li>
                            <li><a href="form_slider_switcher.html">Categories</a></li>
                            <li><a href="form_validation.html">Suppliers</a></li>
                            <li><a href="form_wizards.html">Branch Store</a></li>
                            <li><a href="form_wizards_validation.html">Non-Received Items</a></li>
                            <li><a href="form_wysiwyg.html">Exchanged Items</a></li>
                            
                        </ul>
                    </li>
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
                            <li><a href="table_basic.html">Selected Fields</a></li> 
                            <li><a href="table_basic.html">Employees Search</a></li> 
                            <li><a href="table_basic.html">Import Attendance</a></li> 
                            <li><a href="table_basic.html">IBAN Check</a></li> 
                            <li><a href="table_basic.html">Labour Card Check</a></li> 
                            <li><a href="table_basic.html">Boiometris Report</a></li> 
                             
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-user"></i> 
                            <span>Employees</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="#" >Employee List</a></li>
                            <li><a href="#" >Search <i class="fa fa-binoculars text-theme m-l-5"></i></a></li>
                            <li><a href="#" >Bonus Hold</a></li>
                            <li><a href="#" >Attendance Report</a></li>
                            <li><a href="#" >Exit Permits</a></li>
                            <li><a href="#" >Expiring Documents</a></li>
                            <li><a href="#" >Missing Documents</a></li>
                            <li><a href="#" >Petty Cash</a></li>
                        </ul>
                    </li>
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
                     
                    
                    <li class="has-sub @if(session('title') == 'Administrator')   active @endif" >
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-gear (alias)"></i>
                            <span>Administrator</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="@if(session('subtitle') == 'users')   active @endif"><a href="/users">Users</a></li>
                            <li class="@if(session('subtitle') == 'Roles')   active @endif"><a href="/roles">Roles</a></li>
                            <li class="@if(session('subtitle') == 'addRoles')   active @endif"><a href="/roles/create">Add Roles</a></li>
                             
                        </ul>
                    </li>
                    
                    
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