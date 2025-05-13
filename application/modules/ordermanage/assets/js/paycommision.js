function paycommision (payid) {
      var csrf = $('#csrfhashresarvation').val();
      var myurl = basicinfo.baseurl + 'ordermanage/order/paycommision';
      var dataString = "payid=" + payid + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: myurl,
          data: dataString,
          success: function(data) {
              $('#showvfrom').html(data);
			  $("select.form-control:not(.dont-select-me)").select2({
				  placeholder: "Select option",
				  allowClear: true
				});
              $('#modalcommision').modal({backdrop: 'static', keyboard: false},'show');
          }
      });

  }
  
  function submitpaycommision(){
	  	var paymentmethod=$("#cmbDebit").val();
		var amount=$("#amount").val();
		var payto=$("#payto").val();
		if(paymentmethod==''){
			swal("Oops...", "Please Select Payment Method!!!", "error");
				 return false;
			}
		 if(amount=='' || amount==''){
			swal("Oops...", "Amount Can not Empty or Zero!!!", "error");
				 return false;
			}
			
		var csrf = $('#csrfhashresarvation').val();
		var myurl = basicinfo.baseurl + 'ordermanage/order/paycommisionsubmit';
        var dataString = "payto=" + payto + '&paymentmethod=' + paymentmethod+ '&amount=' + amount+ '&csrf_test_name=' + csrf;
			$.ajax({
			  type: "POST",
			  url: myurl,
			  data: dataString,
			  success: function(data) {
				  swal("Done...", "Payment Submitted Successfully!!!", "success");
				  var url2 =  basicinfo.baseurl + 'ordermanage/order/paydelivarycommision';
				  window.location.href =url2;
			  }
		  });
	  }