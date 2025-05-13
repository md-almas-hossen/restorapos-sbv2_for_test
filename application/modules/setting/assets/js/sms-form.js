"use strict"; 
$("form :input").attr("autocomplete", "off");

$('input.smsactive[type="checkbox"]').click(function(){
			var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
			   var ischeck=1;
			   var dataString = 'status=1&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
                var menuid=$(this).val();
				var ischeck=0;
				var dataString = 'status=0&csrf_test_name='+csrf;
            }
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"setting/smsetting/sms_enable",
				data: dataString,
				success: function(data){
					if(ischeck==1){
						swal("Enable", "SMS service is enable.", "success");
						}
						else{
						swal("Disable", "SMS service is Disable.", "warning");
						}
				    }
			    });
		});