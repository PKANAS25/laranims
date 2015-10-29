<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Mirrored from seantheme.com/color-admin-v1.7/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 24 Apr 2015 11:01:45 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Al Dana NMS | Forgot Password </title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="SHORTCUT ICON"   href="/img/favicon.ico">
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/css/animate.min.css" rel="stylesheet" />
    <link href="/css/style.min.css" rel="stylesheet" />
    <link href="/css/style-responsive.min.css" rel="stylesheet" />
    <link href="/css/theme/default.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <div class="login-cover">
        <div class="login-cover-image"><img src="/img/login-bg/bg-2.jpg" data-id="login-cover-image" alt="" /></div>
        <div class="login-cover-bg"></div>
    </div>
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand">
                    <span class="logo"></span> Al Dana NMS
                    <small>V3.0</small>
                </div>
                <div class="icon">
                    <i class="fa fa-sign-in"></i>
                </div>
            </div>
            <!-- end brand -->
            <div class="login-content">
                  @if (session('status')) <div class="alert alert-success"> {{ session('status') }}. Please check your inbox </div> @endif
                <form method="POST" action="/password/email">

                {!! csrf_field() !!}

                @if (count($errors) > 0)
                    
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                    </ul>
                @endif

                <div class="form-group m-b-20">
                    
                    <input type="email" name="email" class="form-control input-lg" placeholder="Registered Email" value="{{ old('email') }}">
                </div>

                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg"> Send Password Reset Link </button>
                </div>
                </form>
            </div>
        </div>
        <!-- end login -->
        
        <ul class="login-bg-list">
            <li ><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-1.jpg" alt="" /></a></li>
            <li class="active"><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-2.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-3.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-4.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-5.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="/img/login-bg/bg-6.jpg" alt="" /></a></li>
        </ul>
        
        <!-- begin theme-panel -->
        
        <!-- end theme-panel -->
    </div>
    <!-- end page container -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="/crossbrowserjs/html5shiv.js"></script>
        <script src="/crossbrowserjs/respond.min.js"></script>
        <script src="/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/js/login-v2.demo.min.js"></script>
    <script src="/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
            LoginV2.init();
        });
    </script>
    
</body>

 </html>
