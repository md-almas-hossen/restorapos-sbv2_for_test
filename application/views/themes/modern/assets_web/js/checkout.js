// JavaScript Document
$(document).ready(function() {
           "use strict";
     	var ckbox = $('#creat_ac');
        $('input').on('click', function() {
            if (ckbox.is(':checked')) {
                $('#ac_pass').attr("required", true);
            } else {
                $('#ac_pass').attr("required", false);
            }
        });
        var ckbox2 = $('#shipping_address2');
        $('input').on('click', function() {
            if (ckbox2.is(':checked')) {
                $('#shipping_address2').attr("value", 1);
                $('#f_name3').attr("required", true);
                $('#l_name2').attr("required", true);
                $('#email2').attr("required", true);
                $('#phone2').attr("required", true);
            } else {
                $('#shipping_address2').attr("value", '');
                $('#f_name3').attr("required", false);
                $('#l_name2').attr("required", false);
                $('#email2').attr("required", false);
                $('#phone2').attr("required", false);
            }
        });
    });
       "use strict";
    function logincustomer() {
    var email = $('#user_email').val();
    var pass = $('#u_pass').val();
    var errormessage = '';
    if (email == '') {
    errormessage = errormessage + '<span>'+lang.enter_your_phone_or_email+'</span>';
    alert(lang.enter_your_phone_or_email);
    return false;
    }
    if (pass == '') {
    errormessage = errormessage + '<span>'+lang.password_not_empty+'</span>';
    alert(lang.password_not_empty);
    return false;
    }
    var dataString = 'email=' + email + '&pass1=' + pass+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
    type: "POST",
    url: basicinfo.baseurl+'hungry/userlogin',
    data: dataString,
    success: function(data) {
    var err = data;
    if (err == '404') {
    alert(lang.failed_login_msg);
    } else {
    window.location.href = basicinfo.baseurl+'checkout';
    }
    }
    });
    }
    function lostpassword() {
    var email = $('#user_email2').val();
    var errormessage = '';
    if (email == '') {
    errormessage = errormessage + '<span>'+lang.please_enter_your_email+'</span>';
    alert(lang.please_enter_your_email);
    return false;
    }
    var dataString = 'email=' + email+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
    type: "POST",
    url: basicinfo.baseurl+'hungry/passwordrecovery',
    data: dataString,
    success: function(data) {
    var err = data;
    if (err == '404') {
    alert(lang.email_not_registered_msg);
    } else {
    alert(lang.have_been_sent_email+" " + email + " "+lang.check_your_new_password);
    window.location.href = basicinfo.baseurl+'checkout';
    }
    }
    });
    }
    $(document).on('change', '#country', function() {
    var id = $('#country option:selected').data('id');
    var url = 'hungry/getstate' + '/' + id;
    $.ajax({
    type: "GET",
    url: url,
	data:{csrf_test_name:basicinfo.csrftokeng},
    success: function(data) {
    $('#district').html(data);
    }
    });
    });
    $(document).on('change', '#district', function() {
        var id = $('#district option:selected').data('stateid');
        var url = 'hungry/getcity' + '/' + id;
    $.ajax({
    type: "GET",
    url: url,
	data:{csrf_test_name:basicinfo.csrftokeng},
    success: function(data) {
    $('#town').html(data);
    }
    });
    });
    $(document).on('change', '#country2', function() {
    var id = $('#country2 option:selected').data('id');
    var url = 'hungry/getstate' + '/' + id;
    $.ajax({
    type: "GET",
    url: url,
	data:{csrf_test_name:basicinfo.csrftokeng},
    success: function(data) {
    $('#district2').html(data);
    }
    });
    });
    $(document).on('change', '#district2', function() {
    var id = $('#district2 option:selected').data('stateid');
    var url = 'hungry/getcity' + '/' + id;
    $.ajax({
    type: "GET",
    url: url,
	data:{csrf_test_name:basicinfo.csrftokeng},
    success: function(data) {
    $('#town2').html(data);
    }
    });
    });
	function signupcustomer() {
    var uname = $('#user_name').val();
	var email = $('#user_email2').val();
	var phone = $('#phone').val();
	var countrycode = $('#countrycode').val();
    var pass = $('#u_pass2').val();
	var address = $('#billing_address_1').val();
    var errormessage = '';
	if (uname == '') {
    errormessage = errormessage + '<span>Enter Your Name</span>';
    alert("Enter Your Name");
    return false;
    }
    
	if (phone == '') {
    errormessage = errormessage + '<span>Enter Your Phone</span>';
    alert("Enter Your Phone");
    return false;
    }
	if (countrycode == '') {
    errormessage = errormessage + '<span>Enter Country Code</span>';
    alert("Enter Country Code");
    return false;
    }
	if (address == '') {
    errormessage = errormessage + '<span>Enter Your Address</span>';
    alert("Enter Your Address");
    return false;
    }
    if (pass == '') {
    errormessage = errormessage + '<span>'+lang.password_not_empty+'</span>';
    alert(lang.password_not_empty);
    return false;
    }
    var dataString = 'user_name=' + uname +'&email=' + email + '&u_pass2=' + pass+'&countrycode='+ countrycode+'&phone=' + phone + '&address=' + address+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
    type: "POST",
    url: basicinfo.baseurl+'hungry/userregister',
    data: dataString,
    success: function(data) {
    var err = data;
    var err = data.split("|");
    if (err[0] == '404') {
    alert(lang.ooops_something_went_wrong);
    }else if(err[0] == '200'){
		$("#cususer").val(err[1]);
		$("#screctkey").val(err[2]);
		if(err[2]==2){
		window.location.href = basicinfo.baseurl+'checkout';	
		}else{
		$("#checkotp").modal('show');
			var timeleft = 40;
			var downloadTimer = setInterval(function(){
			  if(timeleft <= 0){
				clearInterval(downloadTimer);
				document.getElementById("otptimer").innerHTML = "<a href='javascript:void(0)' onclick=resendagain("+err[1]+") class='btn btn-primary'>Resend</a>";
			  } else {
				document.getElementById("otptimer").innerHTML = timeleft + " seconds remaining";
			  }
			  timeleft -= 1;
			}, 1000);	
		}
		 //window.location.href = basicinfo.baseurl+'checkout';
	}
	else {
   	alert(err);
    }
    }
    });
    }
function verifyotp(){
	  var code=$('#otpcode').val();
	  var uid=$('#cususer').val();
	  var secret=$('#screctkey').val();
	  if(code == ''){ 
			alert("Enter OTP code");
			return false;
		}
				var dataString = 'code='+code+'&secret='+secret+'&uid='+uid+'&csrf_test_name='+basicinfo.csrftokeng;
				    $.ajax({
					type: "POST",
					dataType:'json',
					async:true,
					url: basicinfo.baseurl+"hungry/checkotp",
					data: dataString,
					success: function(data){
						if(data==404){
							swal("Invalid", "Wrong OTP Code. Please Enter Correct Otp!!", "warning");
							return false;
							}
						else{
							$('#otpcode').val('');
						//swal("Success", "OTP Verified Successfully!!!", "success");
						alert("OTP Verified Successfully!!!");
						$("#checkotp").modal('hide');
						window.location.href = basicinfo.baseurl+'checkout';
						}
					}
				});
		}
