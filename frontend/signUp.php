<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://d3oia8etllorh5.cloudfront.net/20201215211355/css/bootstrap.min.css" rel="stylesheet"
        media="screen" />
    <link href="https://d3oia8etllorh5.cloudfront.net/20201215211355/css/cognito-login.css" rel="stylesheet"
        media="screen" />
    
    <title>Signin</title>

    <script src="https://d3oia8etllorh5.cloudfront.net/20201215211355/js/amazon-cognito-advanced-security-data.min.js" ></script>
    <script>
    function getAdvancedSecurityData(formReference) {
        if (typeof AmazonCognitoAdvancedSecurityData === "undefined") {
            return true;
        }

        // UserpoolId is not available on frontend for springboard. We do not use userPoolId
        // anyway other than put in context data. 
        var userPoolId = "us-east-1_lGQzJzb8q";
        var clientId = getUrlParameter("client_id");

        var username = "";
        var usernameInput = document.getElementsByName("username")[0];
        if (usernameInput && usernameInput.value) {
            username = usernameInput.value;
        }

        var asfData = AmazonCognitoAdvancedSecurityData.getData(username, userPoolId, clientId);
        if (typeof asfData === "undefined") {
            return true;
        }

        if (formReference && formReference.cognitoAsfData) {
            formReference.cognitoAsfData.value = asfData
        }

        return true;
    }

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function onSubmit(evt, formRef) {
        formRef.querySelector('button[type="submit"]').disabled = true;
        if (!!formRef.submitted) {
            evt.preventDefault();
            return false;
        } else {
            formRef.submitted = true;
            return getAdvancedSecurityData(formRef);
        }
    }
</script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <div class="modal-dialog">
            
            <div class="modal-content background-customizable modal-content-mobile visible-md visible-lg">
                <div><div>
                    <div class="banner-customizable">
                        <center>
                            
                        </center>
                    </div>
                </div></div>
                <div class="modal-body">
                    <div>
                        <div><div>
    
</div></div>
                        <div><div>
    
</div></div>
                    </div>
                    <div>
                        <div ><div>
    
        <Span class="textDescription-customizable ">Sign up with a new account</Span>
        <form name="signupform" action="/signup?client_id=dtqh7o342i2pfe2iad62e811v&amp;response_type=code&amp;scope=email+openid+profile&amp;redirect_uri=https://qlpvexadf5.execute-api.us-east-1.amazonaws.com/testing/login" method="post"
            class="cognito-asf" onsubmit="onSubmit(event, this, 'signUpButton')"><input type="hidden" name="_csrf" value="360f95e6-5128-4be2-bf05-9c8b83649e7e"/>

            
            <label class="label-customizable" >Username</label>
            <div>
                
                
                <input name="username" onkeyup="checkPasswordMatch();" type="text" value="" autocapitalize="none"
                    class="form-control inputField-customizable" placeholder="Username" required aria-label="Username">
            </div>
            <div>
                <label class="label-customizable">Given name</label>
                <div>
                    

                    

                    <input type="text" name="requiredAttributes[given_name]" class="form-control inputField-customizable"
                           required aria-label="Given name">
                </div>
            </div>
            <div>
                <label class="label-customizable">Email</label>
                <div>
                    <input type="email" name="requiredAttributes[email]" class="form-control inputField-customizable"
                           placeholder="name@host.com" required aria-label="Email">

                    

                    
                </div>
            </div>
            <label class="label-customizable">Password</label>
            <br/>
            <input name="password" onkeyup="checkPasswordMatch();" type="password" value="" class="form-control inputField-customizable"
                placeholder="Password" required aria-label="Password">
            <br/>

            <div><div>
    
        <div class="checkPassword-lowerletter">
            <span aria-hidden="true" class="check-lowerletter"></span>
            <span class="checkPasswordText-lowerletter"></span>
        </div>
        <div class="checkPassword-upperletter">
            <span aria-hidden="true" class="check-upperletter"></span>
            <span class="checkPasswordText-upperletter"></span>
        </div>
        
        <div class="checkPassword-numbers">
            <span aria-hidden="true" class="check-numbers"></span>
            <span class="checkPasswordText-numbers"></span>
        </div>
        <div class="checkPassword-length">
            <span aria-hidden="true" class="check-length"></span>
            <span class="checkPasswordText-length"></span>
        </div>
    
</div></div>

            <button type="submit" name="signUpButton" class="btn btn-primary signUpButton submitButton-customizable">Sign up</button>
            <br/>
            <p class="redirect-customizable"><span>Already have an account?</span>&nbsp;<a href="/login?client_id=dtqh7o342i2pfe2iad62e811v&amp;response_type=code&amp;scope=email+openid+profile&amp;redirect_uri=https://qlpvexadf5.execute-api.us-east-1.amazonaws.com/testing/login">Sign in</a></p>
        </form>
    
</div></div>
                    </div>
                </div>
            </div>

            <div class="modal-content background-customizable modal-content-mobile visible-xs visible-sm">
                <div><div>
                    <div class="banner-customizable">
                        <center>
                            
                        </center>
                    </div>
                </div></div>
                <div class="modal-body">
                    <div><div>
    
</div></div>
                    <div><div>
    
</div></div>
                    <div>
                        
                        <div><div>
    
        <Span class="textDescription-customizable ">Sign up with a new account</Span>
        <form name="signupform" action="/signup?client_id=dtqh7o342i2pfe2iad62e811v&amp;response_type=code&amp;scope=email+openid+profile&amp;redirect_uri=https://qlpvexadf5.execute-api.us-east-1.amazonaws.com/testing/login" method="post"
            class="cognito-asf" onsubmit="onSubmit(event, this, 'signUpButton')"><input type="hidden" name="_csrf" value="360f95e6-5128-4be2-bf05-9c8b83649e7e"/>

            
            <label class="label-customizable" >Username</label>
            <div>
                
                
                <input name="username" onkeyup="checkPasswordMatch();" type="text" value="" autocapitalize="none"
                    class="form-control inputField-customizable" placeholder="Username" required aria-label="Username">
            </div>
            <div>
                <label class="label-customizable">Given name</label>
                <div>
                    

                    

                    <input type="text" name="requiredAttributes[given_name]" class="form-control inputField-customizable"
                           required aria-label="Given name">
                </div>
            </div>
            <div>
                <label class="label-customizable">Email</label>
                <div>
                    <input type="email" name="requiredAttributes[email]" class="form-control inputField-customizable"
                           placeholder="name@host.com" required aria-label="Email">

                    

                    
                </div>
            </div>
            <label class="label-customizable">Password</label>
            <br/>
            <input name="password" onkeyup="checkPasswordMatch();" type="password" value="" class="form-control inputField-customizable"
                placeholder="Password" required aria-label="Password">
            <br/>

            <div><div>
    
        <div class="checkPassword-lowerletter">
            <span aria-hidden="true" class="check-lowerletter"></span>
            <span class="checkPasswordText-lowerletter"></span>
        </div>
        <div class="checkPassword-upperletter">
            <span aria-hidden="true" class="check-upperletter"></span>
            <span class="checkPasswordText-upperletter"></span>
        </div>
        
        <div class="checkPassword-numbers">
            <span aria-hidden="true" class="check-numbers"></span>
            <span class="checkPasswordText-numbers"></span>
        </div>
        <div class="checkPassword-length">
            <span aria-hidden="true" class="check-length"></span>
            <span class="checkPasswordText-length"></span>
        </div>
    
</div></div>

            <button type="submit" name="signUpButton" class="btn btn-primary signUpButton submitButton-customizable">Sign up</button>
            <br/>
            <p class="redirect-customizable"><span>Already have an account?</span>&nbsp;<a href="index.php">Sign in</a></p>
        </form>
    
</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://d3oia8etllorh5.cloudfront.net/20201215211355/js/jquery-3.4.1.min.js" ></script>
    <script>
    var $inputs = $(":input");
    $inputs.on('input', function () {
        var self = this;
        var matches = $('input[name="' + this.name + '"]');
        var selfIndex = matches.index($(self));
        matches.each(function (index, element) {
            if (selfIndex !== index) {
                $(element).val($(self).val());
            }
        });
    });
</script>
    <script>

    function checkPasswordHelper(password) {
        var passwordPolicy = [];
        passwordPolicy.lowercase = "Password must contain a lower case letter";
        passwordPolicy.uppercase = "Password must contain an upper case letter";
        passwordPolicy.number = "Password must contain a number";
        passwordPolicy.special = "Password must contain a special character";
        var passwordLength = 8;
        passwordPolicy.lengthCheck = "Password must contain at least 8 characters";


        var requireLowerletter = false;
        var requireUpperletter = false;
        var requireNumber = false;
        var requireSymbol = false;
        var requireLength = false;

        if (password) {
            if (true) {
                if (/[a-z]/.test(password)) {
                    $(".check-lowerletter").html("&#10003;");
                    $(".checkPasswordText-lowerletter").html(passwordPolicy.lowercase);
                    $(".checkPassword-lowerletter").addClass("passwordCheck-valid-customizable").removeClass(
                        "passwordCheck-notValid-customizable");
                    requireLowerletter = true;
                } else {
                    $(".check-lowerletter").html("&#10006;");
                    $(".checkPasswordText-lowerletter").html(passwordPolicy.lowercase);
                    $(".checkPassword-lowerletter").addClass("passwordCheck-notValid-customizable").removeClass(
                        "passwordCheck-valid-customizable");
                    requireLowerletter = false;
                }
            } else {
                requireLowerletter = true;
            }
            if (true) {
                if (/[A-Z]/.test(password)) {
                    $(".check-upperletter").html("&#10003;");
                    $(".checkPasswordText-upperletter").html(passwordPolicy.uppercase);
                    $(".checkPassword-upperletter").addClass("passwordCheck-valid-customizable").removeClass(
                        "passwordCheck-notValid-customizable");
                    requireUpperletter = true;
                } else {
                    $(".check-upperletter").html("&#10006;");
                    $(".checkPasswordText-upperletter").html(passwordPolicy.uppercase);
                    $(".checkPassword-upperletter").addClass("passwordCheck-notValid-customizable").removeClass(
                        "passwordCheck-valid-customizable");
                    requireUpperletter = false;
                }
            } else {
                requireUpperletter = true;
            }
            if (false) {
                if (/[-+=!$%^&*()_|~`{}\[\]:\/;<>?,.@#'"]/.test(password) || password.indexOf('\\') >= 0) {
                    $(".check-symbols").html("&#10003;");
                    $(".checkPasswordText-symbols").html(passwordPolicy.special);
                    $(".checkPassword-symbols").addClass("passwordCheck-valid-customizable").removeClass(
                        "passwordCheck-notValid-customizable");
                    requireSymbol = true;
                } else {
                    $(".check-symbols").html("&#10006;");
                    $(".checkPasswordText-symbols").html(passwordPolicy.special);
                    $(".checkPassword-symbols").addClass("passwordCheck-notValid-customizable").removeClass(
                        "passwordCheck-valid-customizable");
                    requireSymbol = false;
                }
            } else {
                requireSymbol = true;
            }
            if (true) {
                if (/[0-9]/.test(password)) {
                    $(".check-numbers").html("&#10003;");
                    $(".checkPasswordText-numbers").html(passwordPolicy.number);
                    $(".checkPassword-numbers").addClass("passwordCheck-valid-customizable").removeClass(
                        "passwordCheck-notValid-customizable")
                    requireNumber = true;
                } else {
                    $(".check-numbers").html("&#10006;");
                    $(".checkPasswordText-numbers").html(passwordPolicy.number);
                    $(".checkPassword-numbers").addClass("passwordCheck-notValid-customizable").removeClass(
                        "passwordCheck-valid-customizable");
                    requireNumber = false;
                }
            } else {
                requireNumber = true;
            }

            if (password.length < passwordLength) {
                $(".check-length").html("&#10006;");
                $(".checkPasswordText-length").html(passwordPolicy.lengthCheck);
                $(".checkPassword-length").addClass("passwordCheck-notValid-customizable").removeClass(
                    "passwordCheck-valid-customizable");
                requireLength = false;
            } else {
                $(".check-length").html("&#10003;");
                $(".checkPasswordText-length").html(passwordPolicy.lengthCheck);
                $(".checkPassword-length").addClass("passwordCheck-valid-customizable").removeClass(
                    "passwordCheck-notValid-customizable");
                requireLength = true;
            }
        }

        return requireLowerletter && requireUpperletter && requireNumber && requireSymbol && requireLength;
    }

    function checkPasswordMatch() {
        var hasUsername = $('input[name="username"]').val() != "";
        var password = $('input[name="password"]').val();
        var hasValidPassword = checkPasswordHelper(password);

        var formSubmitted = false;
        var nodes = document.getElementsByName('signupform');
        for (var i = 0; i < nodes.length; i++) {
            formSubmitted = !!nodes[i].submitted || formSubmitted;
        }
        var canSubmit = hasUsername && hasValidPassword && !formSubmitted;

        $('button[name="signUpButton"]').prop("disabled", !canSubmit);
    }

    function checkConfirmForgotPasswordMatch() {
        checkResetPasswordMatch();
    }

    function checkResetPasswordMatch() {
        var password = $('#new_password').val()
        $('button[name="reset_password"]').prop("disabled",!(checkPasswordHelper(password) && password === $('#confirm_password').val()));
    }
</script>
</body>

</html>
