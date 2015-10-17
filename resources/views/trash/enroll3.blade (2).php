@extends('master2') 
@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Enrole</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Enroll <small> new student</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Enroll</h4>
                        </div>
                        <div class="panel-body">
                            <form id="defaultForm" method="post" class="form-horizontal" action="target.php">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Full name</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="firstName" placeholder="First name" />
                        </div>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="lastName" placeholder="Last name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Username</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="username" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Email address</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="email" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-5">
                            <input type="password" class="form-control" name="password" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Retype password</label>
                        <div class="col-lg-5">
                            <input type="password" class="form-control" name="confirmPassword" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Gender</label>
                        <div class="col-lg-5">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="male" /> Male
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="female" /> Female
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="other" /> Other
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Age</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="age" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Website</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="website" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Phone number</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="phoneNumber" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Languages</label>
                        <div class="col-lg-5">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="english" /> English
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="french" /> French
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="german" /> German
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="russian" /> Russian
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="other" /> Other
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Programming Languages</label>
                        <div class="col-lg-5">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="net" /> .Net
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="java" /> Java
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="c" /> C/C++
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="php" /> PHP
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="perl" /> Perl
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="ruby" /> Ruby
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="python" /> Python
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="programs[]" value="javascript" /> Javascript
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                            <button type="submit" class="btn btn-primary" name="signup2" value="Sign up 2">Sign up 2</button>
                        </div>
                    </div>
                </form>

                             <script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            firstName: {
                validators: {
                    notEmpty: {},
                    stringCase: {
                        'case': 'upper'
                    }
                }
            },
            lastName: {
                validators: {
                    notEmpty: {},
                    stringCase: {
                        'case': 'upper'
                    }
                }
            },
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {},
                    stringLength: {
                        min: 6,
                        max: 20
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/
                    },
                    remote: {
                        url: 'remote.php',
                        message: 'The username is not available'
                    }
                }
            },
            email: {
                validators: {
                    emailAddress: {}
                }
            },
            password: {
                validators: {
                    notEmpty: {},
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    }
                }
            },
            confirmPassword: {
                validators: {
                    notEmpty: {},
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
            age: {
                validators: {
                    notEmpty: {},
                    digits: {},
                    greaterThan: {
                        value: 18
                    },
                    lessThan: {
                        value: 100
                    }
                }
            },
            website: {
                validators: {
                    notEmpty: {},
                    uri: {}
                }
            },
            phoneNumber: {
                validators: {
                    notEmpty: {},
                    digits: {},
                    phone: {
                        country: 'US'
                    }
                }
            },
            gender: {
                validators: {
                    notEmpty: {}
                }
            },
            'languages[]': {
                validators: {
                    notEmpty: {}
                }
            },
            'programs[]': {
                validators: {
                    choice: {
                        min: 2,
                        max: 4
                    }
                }
            }
        }
    });
});
</script>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
        @endsection