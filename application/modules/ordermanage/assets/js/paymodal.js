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
 function changedue(){
		var main=$("#totalamount_marge").val();
		var paid=$("#paidamount_marge").val();
		var change=main-paid;
		$("#change").val(Math.round(change));
	}
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

	
	
   var oldprice= $("#oldprice").val();
    $("#ordertotal").val(oldprice);
    $("#orderdue").val(oldprice);
    $("#grandtotal").val(oldprice);
    $("#ordertotale").val(oldprice);
    $("#discountttch").val(oldprice);

	$("#chty").text(thistype);
	$("#discounttype").val(distypech);
	$("#discount").val('');
	$(".clearamount").val('');
	$(".emptysl").remove();
	$("#discount" ).trigger("change");
	}


	$("body").on("change", "#discount", function (e) {
		//$(document).on('change','#discount', function(){
		var subtotal = $("#main_subtotal").val();
		var discount = $("#discount").val();
		var distype = $("#discounttype").val();
		var total = $("#ordertotal").val();
		var due = $("#orderdue").val();
		var oldprice = $("#oldprice").val();
	  
		if (discount === "" || discount === 0) {
		  var tdis = parseFloat(discount);
		  var ttot = parseFloat(oldprice);
		  if (tdis > ttot) {
			$("#discount").val(0);
			$(".clearamount").trigger("keyup");
			alert("Discount Amount Is Greater Than Total Amount!!");
			return false;
		  }
		  $("#totalamount_marge").text(oldprice);
		  $("#due-amount").text(due);
		  $("#grandtotal").val(oldprice);
		  $("#granddiscount").val(0);
		  $(".paidamnt").html(oldprice);
		  $("#pay-amount").text(due.toFixed(basicinfo.showdecimal));
		  $(".clearamount").trigger("keyup");
		  //$(".firstpay").val(total);
		  //alert("fg");
		} else {
		  // new code by MKar starts here...
		  var recalculate_vat = $("#recalculate_vat").val();
	  
		  if (recalculate_vat == 1) {
	  
	  
			// old code to change
	  
			  if (distype == 1) {
	  
				  var oldprice = $("#oldprice").val() - $("#totalvat").val() // price without vat
				  console.log(oldprice); //416.813
				  if (discount > 100) {
					  $("#discount").val(0);
					  $("#totalamount_marge").text(oldprice.toFixed(basicinfo.showdecimal));
					  $("#paidamount_marge").val(oldprice);
					  $("#grandtotal").val(oldprice);
					  $("#due-amount").text(oldprice);
					  $("#granddiscount").val(0);
					  $(".paidamnt").html(oldprice.toFixed(basicinfo.showdecimal));
					  $("#pay-amount").text(oldprice.toFixed(basicinfo.showdecimal));
					  alert("Discount Amount Is Greater Than Total Amount!!");
					  return false;
				  }
				  var totaldis = (discount * oldprice) / 100; // 8.336
				  
				  var maxdiscount = $("#maxdiscount_amount").val();
				  var maxdiscount_amount = (oldprice * maxdiscount) / 100;
	  
				  if (parseFloat(maxdiscount_amount) != "") {
					  if (maxdiscount_amount >= totaldis) {
						  totaldis = totaldis;
					  } else {
						  alert("Discount Amount Is Greater Than Max Discount Amount!!");
						  totaldis = 0;
						  $("#discount").val("0.00");
					  }
				  } else {
					  var totaldis = (discount * oldprice) / 100;
				  }
	  
			  } else {
	  
				  // here i will work later...
					  var tdis = parseFloat(discount);
					  var ttot = parseFloat(oldprice);
	  
					  if (tdis > ttot) {
						  $("#discount").val(0);
						  $("#totalamount_marge").text(oldprice.toFixed(basicinfo.showdecimal));
						  $("#paidamount_marge").val(oldprice);
						  $("#grandtotal").val(oldprice);
						  $("#due-amount").text(oldprice);
						  $("#granddiscount").val(0);
						  $(".paidamnt").html(oldprice.toFixed(basicinfo.showdecimal));
						  $("#pay-amount").text(oldprice.toFixed(basicinfo.showdecimal));
						  alert("Discount Amount Is Greater Than Total Amount!!");
						  return false;
					  }
	  
					  var totaldis = discount;
					  var maxdiscount = $("#maxdiscount_amount").val();
					  var maxdiscount_amount = (oldprice * maxdiscount) / 100;
	  
					  if (parseFloat(maxdiscount_amount) != "") {
	  
						  if (maxdiscount_amount >= totaldis) {
							  totaldis = discount;
						  } else {
							  alert("Discount Amount Is Greater Than Max Discount Amount!!");
							  totaldis = 0;
							  $("#discount").val("0.00");
						  }
	  
					  } else {
						  var totaldis = discount;
					  }
				  // here i will work later...
	  
			  }
	  
			  vat = parseFloat($("#totalvat").val());
			  var afterdiscount = parseFloat(oldprice - totaldis + vat); // 408.477
	  
			  var newtotal = afterdiscount.toFixed(basicinfo.showdecimal);
			  var granddiscount = parseFloat(totaldis);
	  
			  $("#totalamount_marge").text(newtotal);
			  $("#paidamount_marge").val(newtotal);
			  $("#grandtotal").val(newtotal);
			  $("#due-amount").text(newtotal);
			  $("#granddiscount").val(granddiscount);
			  $(".paidamnt").html(newtotal);
			  $("#pay-amount").text(newtotal);
			  $(".clearamount").trigger("keyup");
			// old code to change
	  
	  
	  
		  } else {
			// new code by MKar ends here...
	  
			if (distype == 1) {
			  var oldprice = $("#oldprice").val();
			  if (discount > 100) {
				$("#discount").val(0);
				$("#totalamount_marge").text(oldprice.toFixed(basicinfo.showdecimal));
				$("#paidamount_marge").val(oldprice);
				$("#grandtotal").val(oldprice);
				$("#due-amount").text(oldprice);
				$("#granddiscount").val(0);
				$(".paidamnt").html(oldprice.toFixed(basicinfo.showdecimal));
				$("#pay-amount").text(oldprice.toFixed(basicinfo.showdecimal));
				alert("Discount Amount Is Greater Than Total Amount!!");
				return false;
			  }
			  var totaldis = (discount * oldprice) / 100;
			  var maxdiscount = $("#maxdiscount_amount").val();
			  var maxdiscount_amount = (oldprice * maxdiscount) / 100;
			  if (parseFloat(maxdiscount_amount) != "") {
				if (maxdiscount_amount >= totaldis) {
				  totaldis = totaldis;
				} else {
				  alert("Discount Amount Is Greater Than Max Discount Amount!!");
				  totaldis = 0;
				  $("#discount").val("0.00");
				}
			  } else {
				var totaldis = (discount * oldprice) / 100;
			  }
			} else {
			  var tdis = parseFloat(discount);
			  var ttot = parseFloat(oldprice);
	  
			  if (tdis > ttot) {
				$("#discount").val(0);
				$("#totalamount_marge").text(oldprice.toFixed(basicinfo.showdecimal));
				$("#paidamount_marge").val(oldprice);
				$("#grandtotal").val(oldprice);
				$("#due-amount").text(oldprice);
				$("#granddiscount").val(0);
				$(".paidamnt").html(oldprice.toFixed(basicinfo.showdecimal));
				$("#pay-amount").text(oldprice.toFixed(basicinfo.showdecimal));
				alert("Discount Amount Is Greater Than Total Amount!!");
				return false;
			  }
	  
			  var totaldis = discount;
			  var maxdiscount = $("#maxdiscount_amount").val();
			  var maxdiscount_amount = (oldprice * maxdiscount) / 100;
			  if (parseFloat(maxdiscount_amount) != "") {
				if (maxdiscount_amount >= totaldis) {
				  totaldis = discount;
				} else {
				  alert("Discount Amount Is Greater Than Max Discount Amount!!");
				  totaldis = 0;
				  $("#discount").val("0.00");
				}
			  } else {
				var totaldis = discount;
			  }
			  // alert(oldprice);
			  // alert(totaldis);
			}
	  
			var afterdiscount = parseFloat(oldprice - totaldis);
			var newtotal = afterdiscount.toFixed(basicinfo.showdecimal);
			var granddiscount = parseFloat(totaldis);
	  
			$("#totalamount_marge").text(newtotal);
			$("#paidamount_marge").val(newtotal);
			$("#grandtotal").val(newtotal);
			$("#due-amount").text(newtotal);
			$("#granddiscount").val(granddiscount);
			$(".paidamnt").html(newtotal);
			$("#pay-amount").text(newtotal);
			$(".clearamount").trigger("keyup");
	  
			// new code by MKar starts here...
		  }
		  // new code by MKar ends here...
		}
	  
		//$("#adddiscount").hide();
		$("#adddiscount").addClass("display-none");
		$("#add_new_payment").empty();
	  });
	
$('body').on('click','#paymentnow',function(){
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

		function checkdevicetype(){
			if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
		  return 1;
		  }else{
		   return 0
		  }
		}
		// $('body').on('click', '#getdueinvoiceorder_v2', function() {
		$('body').off('click', '#getdueinvoiceorder_v2').on('click', '#getdueinvoiceorder_v2', function() {
			$("#getdueinvoiceorder_v2").hide();
			var orderid=$("#get-order-id").val();
			var discountamount=$("#granddiscount").val();
			var discounttype=$("#discounttype").val();
			var discount=$("#discount").val();
			var discountnote=$("#discountnote").val();
			var is_duepayment = $("#is_duepayment").val();


			var url = basicinfo.baseurl + "ordermanage/order/dueinvoice2/"+orderid;
			var csrf = $('#csrfhashresarvation').val();

			// console.log(url);
			// return false;

			 $.ajax({
				 type: "POST",
				 url: url,
				 data: { csrf_test_name : csrf, discountamount : discountamount, discounttype : discounttype, discount : discount, discounttext : discountnote, is_duepayment : is_duepayment},
				 success: function(data) {
				   // alert(data);return false;
					 $('#payprint_marge').modal('hide');
					 $('#lbid'+orderid).css("background", "#dbcdd2");
					 var dtype=checkdevicetype();
					   if(dtype==1){
						   var url2 = "http://www.abc.com/invoice/due/"+orderid;
						   window.open(url2, "_blank");
					   }else{
						printRawHtmlDueInvoice(data);
					   }
					 $("#getdueinvoiceorder_v2").show();
				 }
			 });
	   });

	"use strict";
 	function printRawHtmlDueInvoice(view) {
      printJS({
        printable: view,
        type: 'raw-html',
        
      });
    }
