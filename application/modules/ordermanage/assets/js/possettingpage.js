// JavaScript Document
  "use strict";
$('input.placeord[type="checkbox"]').click(function(){
			var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
               var menuid=$(this).val();
			   var ischeck=1;
			   var dataString = 'menuid='+menuid+'&status=1&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
                var menuid=$(this).val();
				var ischeck=0;
				var dataString = 'menuid='+menuid+'&status=0&csrf_test_name='+csrf;
            }
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/settingenable",
				data: dataString,
				success: function(data){
					if(ischeck==1){
						swal(lang.enable, lang.Enable_This_Option_to_show_on_Pos_Invoice, "success");
						}
						else{
						swal(lang.disable, lang.Make_This_Field_Is_Optional_On_Pos_Page, "warning");
						}
				    }
			    });
        });
		$('input.placeord[type="radio"]').click(function(){
			var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
			   var fieldname=$(this).attr('name');
               var menuid=$(this).val();
			   var ischeck=1;
			   var dataString = 'menuid='+fieldname+'&status='+menuid+'&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
				var fieldname=$(this).attr('name');
                var menuid=$(this).val();
				var ischeck=0;
				var dataString = 'menuid='+fieldname+'&status='+menuid+'&csrf_test_name='+csrf;
            }
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/settingenable",
				data: dataString,
				success: function(data){
					if(ischeck==1){
						swal(lang.enable, lang.Enable_This_Option_to_show_on_Pos_Invoice, "success");
						}
						else{
						swal(lang.disable, lang.Make_This_Field_Is_Optional_On_Pos_Page, "warning");
						}
				    }
			    });
        });
$('input.quick[type="checkbox"]').click(function(){
	var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
               var menuid=$(this).val();
			   var ischeck=1;
			   var dataString = 'menuid='+menuid+'&status=1&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
                var menuid=$(this).val();
				var ischeck=0;
				var dataString = 'menuid='+menuid+'&status=0&csrf_test_name='+csrf;
            }
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/quicksetting",
				data: dataString,
				success: function(data){
					if(ischeck==1){
						swal(lang.enable, lang.Enable_This_Option_to_show_on_Pos_Invoice, "success");
						}
						else{
						swal(lang.disable, lang.Make_This_Field_Is_Optional_On_Pos_Page, "warning");
						}
				    }
			    });
        })
$('input.inv[type="checkbox"]').click(function(){
	var csrf = $('#csrfhashresarvation').val();
	var printlaypou=$('#printerlayout').val();
            if($(this).is(":checked")){
               var menuid=$(this).val();
			   var ischeck=1;
			   var dataString = 'menuid='+menuid+'&printlayout='+printlaypou+'&status=1&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
                var menuid=$(this).val();
				var ischeck=0;
				var dataString = 'menuid='+menuid+'&printlayout='+printlaypou+'&status=0&csrf_test_name='+csrf;
            }
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/invsetting",
				data: dataString,
				success: function(data){
					if(ischeck==1){
						swal(lang.enable, lang.Enable_This_Option_to_show_on_Pos_Invoice, "success");
						}
						else{
						swal(lang.disable, lang.Make_This_Field_Is_Optional_On_Pos_Page, "warning");
						}
				    }
			    });
        })
	
$(document).on('change', '#colormode', function() {
      var colormode = $('#colormode').val();
      var url = basicinfo.baseurl+"ordermanage/order/colorchange";
      var csrf = $('#csrfhashresarvation').val();
	  var dataString = 'mode='+colormode+'&csrf_test_name='+csrf;
      $.ajax({
          type: "POST",
          url: url,
          data: dataString,
          success: function(data) {
              swal(lang.Mode_Change, lang.Color_Mode_change_Successfully, "success");
          }
      });
	 

  });
  $(document).on('change', '#tablemapping', function() {
      var tablemapping = $('#tablemapping').val();
      var url = basicinfo.baseurl+"ordermanage/order/tablemapchange";
      var csrf = $('#csrfhashresarvation').val();
	  var dataString = 'mode='+tablemapping+'&csrf_test_name='+csrf;
      $.ajax({
          type: "POST",
          url: url,
          data: dataString,
          success: function(data) {
              swal(lang.Mode_Change, lang.Table_Mapping_Layout_change_Successfully, "success");
          }
      });
	 

  });
  function getposuserinfo(userid){
		if(userid==''){
		alert("Please select User");	
		}else{
			var csrf = $('#csrfhashresarvation').val();
			  var myurl = basicinfo.baseurl +"ordermanage/order/selectmenu";
			  $.ajax({
				  type: "post",
				  async: false,
				  url: myurl,
				  data: { userid: userid,csrf_test_name: csrf },
				  success: function(data) {
					  $("#loadmenus").html(data);
				  },
				  error: function() {
					  alert(lang.req_failed);
				  }
			  });
			}
	
	}
	$('input.pospermission[type="checkbox"]').click(function(){
			if($(this).is(":checked")){
              $(this).val(1);
            }
            else if($(this).is(":not(:checked)")){
                $(this).val(0);
            }
		});

	$(document).ready(function() {

		$(document).on('click', '#update_password', function() {
			var alert_password = $('#alert_password').val();
			if(alert_password == null || alert_password == ''){
				swal(lang.password_setting, lang.password_required, "warning");
				return false;
			}

			var url = basicinfo.baseurl+"ordermanage/order/order_modification_password_update";
			var csrf = $('#csrfhashresarvation').val();
			var dataString = 'alert_password='+alert_password+'&csrf_test_name='+csrf;
			$.ajax({
				type: "POST",
				url: url,
				data: dataString,
				success: function(data) {
					if(data == 1){
						$('#update_password').hide();
						$('#reset_up_password').show();
						$('#alert_password').val('*****').trigger('change');
						$('#alert_password').attr('readonly', true);

						swal(lang.password_setting, lang.password_set_successfully, "success");
					}else{
						swal(lang.password_setting, lang.something_went_wrong, "warning");
					}
				}
			});

		});

		$(document).on('click', '#reset_up_password', function() {

			var url = basicinfo.baseurl+"ordermanage/order/order_modification_password_reset";
			$.ajax({
				type: "GET",
				url: url,
				success: function(data) {

					if(data == 1){
						$('#reset_up_password').hide();
						$('#update_password').show();
						$('#alert_password').val('').trigger('change');
						$('#alert_password').removeAttr('readonly');

						swal(lang.password_reset, lang.password_reset_successfully, "success");
					}else{
						swal(lang.password_reset, lang.something_went_wrong, "warning");
					}
				}
			});

		});

	});

	$(document).on('change', '#pos_order_mode', function() {
		var pos_order_mode = $('#pos_order_mode').val();
		var url = basicinfo.baseurl+"ordermanage/order/pos_order_mode_update";
		var csrf = $('#csrfhashresarvation').val();
		var dataString = 'pos_order_mode='+pos_order_mode+'&csrf_test_name='+csrf;
		$.ajax({
			type: "POST",
			url: url,
			data: dataString,
			success: function(data) {
				swal(lang.order_mode_change, lang.order_mode_change_successfully, "success");
			}
		});
	   
  
	});

	$(document).on('change', '#order_number_format', function() {
		var order_number_format = $('#order_number_format').val();
		var url = basicinfo.baseurl+"ordermanage/order/order_number_format_update";
		var csrf = $('#csrfhashresarvation').val();
		var dataString = 'order_number_format='+order_number_format+'&csrf_test_name='+csrf;
		$.ajax({
			type: "POST",
			url: url,
			data: dataString,
			success: function(data) {
				swal(lang.order_number_format, lang.order_number_format_change_successfully, "success");
			}
		});
	   
  
	});

	$(document).on('change', '#items_sorting', function() {
		var items_sorting = $('#items_sorting').val();
		var url = basicinfo.baseurl+"ordermanage/order/items_sorting_update";
		var csrf = $('#csrfhashresarvation').val();
		var dataString = 'items_sorting='+items_sorting+'&csrf_test_name='+csrf;
		$.ajax({
			type: "POST",
			url: url,
			data: dataString,
			success: function(data) {
				swal(lang.items_sorting, lang.items_sorting_change_successfully, "success");
			}
		});
	   
  
	});

	// For chekcing all the permission on click Select ALL checkbox...
	function checkAllPermission() {
		if( $('.select_all').is(':checked') ){
			// console.log(111111111111+'___dfdfdf');
			$(".pospermission").val(1);
			$(".pospermission").prop('checked', true); 
		}
		else{
			// console.log(4444444+'___dfdfdf');
			$(".pospermission").val(0);
			$(".pospermission").prop('checked', false);
		}
 	}