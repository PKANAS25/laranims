<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="#" class="navbar-brand"><span class="navbar-logo"></span> NMS V3.0</a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					 
					<li class="dropdown">
						<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
							<i class="fa fa-bell-o"></i>
							@if($TotalNotifications) <span class="label">{{$TotalNotifications}}</span> @endif
						</a>
						<ul class="dropdown-menu media-list pull-right animated fadeInDown">
                            <li class="dropdown-header">Notifications</li>
                            
                            @if(Auth::user()->hasRole('CallCenterManager') && $CallCenterManagerCallUnassigns)
                            <li class="media">
                                <a href="/refunds/tickets/unassigned">
                                    <div class="media-left"><i class="fa fa-phone media-object bg-blue"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$CallCenterManagerCallUnassigns}} Unassigned Refund Tickets</h6>
                                        <div class="text-muted f-s-11">Assign call center agents</div>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('CallCenterAgent') && $CallCenterAgentCallAssigns)
                            <li class="media">
                                <a href="/refunds/agents/tickets/noreview">
                                    <div class="media-left"><i class="fa fa-comments media-object bg-orange"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$CallCenterAgentCallAssigns}}  Refund Tickets</h6>
                                        <div class="text-muted f-s-11">Get parents feedback</div>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('PaymentsDeposit') && $NotDepositedCount)
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><i class="fa fa-money media-object bg-red"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$NotDepositedCount}} Uncashed in all branches</h6>
                                        <div class="text-muted f-s-11">{{$NotDepositedChequeCount}} Cheques</div>
                                    </div>
                                </a>
                            </li>
                            @endif


                            @if(Auth::user()->hasRole('StoreManager'))
                           
                            @if($StoreRequestsCount)
                            <li class="media">
                                <a href="/store/main/requests/unread">
                                    <div class="media-left"><i class="fa fa-shopping-cart media-object bg-green"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$StoreRequestsCount}} New Item Requests</h6>
                                        <div class="text-muted f-s-11">Please verify and transfer</div>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if($StoreReturnsCount)
                            <li class="media">
                                <a href="/store/main/returns/pending">
                                    <div class="media-left"><i class="fa fa-shopping-cart media-object bg-red"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$StoreReturnsCount}} Store returns</h6>
                                        <div class="text-muted f-s-11">Take immediate action</div>
                                    </div>
                                </a>
                            </li>
                            @endif

                            @if($StoreRejectionsCount)
                            <li class="media">
                                <a href="/store/main/rejections/unread">
                                    <div class="media-left"><i class="fa fa-shopping-cart media-object bg-red"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$StoreRejectionsCount}} Store Rejections</h6>
                                        <div class="text-muted f-s-11">Please View & Proceed</div>
                                    </div>
                                </a>
                            </li>
                            @endif
                            @endif

                            

                            @if(Auth::user()->hasRole('BranchStore'))                           
                            @if($StoreTransferCount)
                            <li class="media">
                                <a href="/store/branch/transfers/pending">
                                    <div class="media-left"><i class="fa fa-shopping-cart media-object bg-purple"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$StoreTransferCount}} New Item Transfers</h6>
                                        <div class="text-muted f-s-11">Please verify and accept</div>
                                    </div>
                                </a>
                            </li>
                            @endif
                            @if($ReturnRejectionsCount)
                            <li class="media">
                                <a href="/store/branch/returns/rejections/unread">
                                    <div class="media-left"><i class="fa fa-shopping-cart media-object bg-red"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$ReturnRejectionsCount}} Returns Rejected</h6>
                                        <div class="text-muted f-s-11">Please View & Proceed</div>
                                    </div>
                                </a>
                            </li>
                            @endif
                            @endif

                            @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $payrollContentRejectionsCount)
                            <li class="media">
                                <a href="/payroll/content/rejections/unread">
                                    <div class="media-left"><i class="fa fa-suitcase media-object bg-purple-lighter"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">{{$payrollContentRejectionsCount}} Payroll Rejections</h6>
                                        <div class="text-muted f-s-11">verify & Re-issue</div>
                                    </div>
                                </a>
                            </li>
                            @endif

                            
						</ul>
					</li>
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="/img/user-13.jpg" alt="" /> 
							<span class="hidden-xs">{!! Auth::user()->name; !!}</span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="javascript:;">Edit Profile</a></li>
							<li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>							 
							<li><a href="javascript:;">Setting</a></li>
                            <li><a href="/users/password/edit/self">Change Password</a></li>
							<li class="divider"></li>
							<li><a href="/logout">Logout</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>