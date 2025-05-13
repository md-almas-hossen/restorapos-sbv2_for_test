"use strict"; 
function editinfo(id){
	   var geturl=$("#url_"+id).val();
	   var myurl =geturl+'/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
			  $(".datepicker").datepicker({
				dateFormat: "dd-mm-yy"
			}); 
		 } 
	});
	}
	

function editinfoshiping(id){
	   var geturl=$("#url_"+id).val();
	   var myurl =geturl+'/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
			  $(".datepicker").datepicker({
				dateFormat: "dd-mm-yy"
			}); 
				 $("select.form-control:not(.dont-select-me)").select2({
					placeholder: "Select option",
					allowClear: true
				});
		 } 
	});
	}
function editmethod(mpname,sid,comission){
	$("#mpname").val(mpname);
	$("#commission").val(comission);
	$("#btnchnage").show();
	$("#btnchnage").text("Update");
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"setting/paymentmethod/editmethod/"+sid);
	$(window).scrollTop(0);
	}
function special_character(vtext,id) {
	 //alert(vtext);
        var specialChars = "1234567890<>@!#$%^&*()_+[]{}?:;|'\"\\/~`-="
        var check = function (string) {
            for (var i = 0; i < specialChars.length; i++) {
                if (string.indexOf(specialChars[i]) > -1) {
                    return true
                }
            }
            return false;
        }
        if (check(vtext) == false) {
            // Code that needs to execute when none of the above is in the string
        } else {
            alert(specialChars + " these character are not allows");
            $("#"+id).val('');
            $("#"+id).focus();
            //$("#category_name").focus();
        }
    }

function getingname(){
	   var myurl =basicinfo.baseurl+"setting/ingradient/getskubarcode";
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "csrf_test_name="+csrf;
		 $.ajax({
		 type: "GET",
		 url: myurl,
		 dataType: 'json',
		 success: function(data) {
			$("#sku").val(data.sku);
			$("#barcode").val(data.barcode);
			// alert(data.sku);
		 } 
	});
}
