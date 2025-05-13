  // JavaScript Document
  "use strict";
$('input.printerst[type="radio"]').click(function(){
			var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
               var ptype=$(this).val();	
			   if(ptype==1){
				var type="Autometic Print";   
			  }
			  if(ptype==2){
				var type="Manual Print";   
			  }
			  if(ptype==3){
				var type="Both Manual & Autometic Print";   
			  }				  
			   var dataString = 'printtype='+ptype+'&csrf_test_name='+csrf;
				   $.ajax({
					type: "POST",
					url: basicinfo.baseurl+"printershare/printer/changesetting",
					data: dataString,
					success: function(data){
							swal("Enable", "Enable "+type+" Option for Printing Your Invoice", "success");
						}
					});
            }
        });