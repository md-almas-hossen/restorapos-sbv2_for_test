// JavaScript Document
$(document).ready(function () {
	"use strict";
	// select 2 dropdown 
	$("select.form-control:not(.dont-select-me)").select2({
		placeholder: "Select option",
		allowClear: true
	});
});
"use strict";
function changetype(){
	var distypech=$("#switch-orange").val();
	 if($("#switch-orange").prop("checked")) {
       var distypech=0;
     }else{
	   var distypech=1;
	}
	if(distypech==0){
		var thistype=basicinfo.curr_icon;
	}
	else{
		var thistype="%";
	}


	$("#chty").text(thistype);
	$("#discounttype").val(distypech);
	$("#discount").val('');
	$(".clearamount").val('');
	$(".emptysl").remove();
	$("#discount" ).trigger("change");

}
$('body').on('change', '#discount', function(e){
            var discount = $("#discount").val();
			var distype=$("#discounttype").val();
			var total=$("#ordertotal").val();
			var due=$("#orderdue").val();
			if(discount=='' || discount==0){
				 var tdis=parseFloat(discount);
				 var ttot=parseFloat(total);
				 if(tdis > ttot){
					 $("#discount").val(0);
					 $(".clearamount" ).trigger("keyup");
					 alert("Discount Amount Is Greater Than Total Amount!!");
					 return false;
				 }
				 $("#totalamount_marge").text(total);
				 $("#due-amount").text(due);
				 $("#grandtotal").val(total);
				 $("#granddiscount").val(0);
				 $(".paidamnt").html(total);
				 $("#pay-amount").text(due);
				 $(".clearamount" ).trigger("keyup");
				 //$(".firstpay").val(total);
				}
			 else{
				  if(distype==1){
					    if(discount>100){
							 $("#discount").val(0);
							 $("#totalamount_marge").text(total);
							 $("#paidamount_marge").val(total);
							 $("#grandtotal").val(total);
							 $("#due-amount").text(total);
							 $("#granddiscount").val(0);	
							 $(".paidamnt").html(total);	
				 			 $("#pay-amount").text(total);
							 
							 alert("Discount Amount Is Greater Than Total Amount!!");
							 return false;
						 }
                         
						 var totaldis=discount*total/100;
						 var maxdiscount=  $("#maxdiscount_amount").val();
						 var maxdiscount_amount=  total* maxdiscount/100;
						 if(parseFloat(maxdiscount_amount) !=''){
							if(maxdiscount_amount>=totaldis){
								totaldis= totaldis
							}else{
								alert("Discount Amount Is Greater Than Max Discount Amount!!");
								totaldis= 0;
								$("#discount").val('0.00');
							}
						}else{
							var totaldis=discount*total/100;
						}



					//  var totaldis=discount*total/100;
				  }else{
					  var tdis=parseFloat(discount);
					  var ttot=parseFloat(total);
					        if(tdis > ttot){
							 $("#discount").val(0);
							 $("#totalamount_marge").text(total);
							 $("#paidamount_marge").val(total);
							 $("#grandtotal").val(total);
							 $("#due-amount").text(total);
							 $("#granddiscount").val(0);	
							 $(".paidamnt").html(total);	
				 			 $("#pay-amount").text(total);
							 alert("Discount Amount Is Greater Than Total Amount!!");
							 return false;
						   }


					    var totaldis=discount;
						var maxdiscount=  $("#maxdiscount_amount").val();
						var maxdiscount_amount=  total* maxdiscount/100;
						if(parseFloat(maxdiscount_amount) !=''){
								if(maxdiscount_amount>=totaldis){
								totaldis= discount;
								}else{
								alert("Discount Amount Is Greater Than Max Discount Amount!!");
								totaldis= 0;
								$("#discount").val('0.00');
						
							}
						}else{
							var totaldis=discount;
						}

					  }
					 var afterdiscount=parseFloat(total-totaldis);
					 var newtotal=afterdiscount.toFixed(3);
					 var granddiscount=parseFloat(totaldis);
				 $("#totalamount_marge").text(newtotal);
				 $("#paidamount_marge").val(newtotal);
				 $("#grandtotal").val(newtotal);
				 $("#due-amount").text(newtotal);
				 $("#granddiscount").val(granddiscount.toFixed(3));	
				 $(".paidamnt").html(newtotal);		
				 $("#pay-amount").text(newtotal);
				 $(".clearamount" ).trigger("keyup");	 
				 }
		//$("#adddiscount").hide();
		$("#adddiscount").addClass('display-none');
		$("#add_new_payment").empty();
            
});
$('body').on('click','#paymentnow',function(){
		 var distotal=$("#grandtotal").val();
		 $(".firstpay").val(distotal);
         $("#adddiscount").removeClass('display-none');
        });
$('input[type="checkbox"]').click(function(){
			if($(this).is(":checked")){
				var test =$('input[name="redeemit"]:checked').val();
				$("#isredeempoint").val(test);
				}
			else{
				$("#isredeempoint").val('');
				}		
		});
