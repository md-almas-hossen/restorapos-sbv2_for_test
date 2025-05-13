//all js 

$('input.qrpayments[type="checkbox"]').click(function(){
	var csrf = $('#csrfhashresarvation').val();
	var valuesArray = $('input:checkbox:checked').map( function() {
    return this.value;
}).get().join(",");
console.log(valuesArray);
var dataString = 'payments='+valuesArray+'&csrf_test_name='+csrf;
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"qrapp/qrmodule/savepayment",
				data: dataString,
				success: function(data){
					if(data==1){
						swal("Enable", "This payments are added to your QR module page", "success");
						}
						else{
						swal("No Option", "You can't select any payment method for QR module", "warning");
						}
				    }
			    });
        })
		
	
