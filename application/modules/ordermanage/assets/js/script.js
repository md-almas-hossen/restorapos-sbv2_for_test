//all js 
function itemlist(){
	   var geturl=$("#url").val();
	    var id=$("#catid").val();
		var csrf = $('#csrfhashresarvation').val();
	   var myurl =geturl;
	    var dataString = "id="+id+'&csrf_test_name='+csrf;

		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.iteminfo').html(data);
			 $('#items').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
	


	
function addfoodtocart(pid,id){
		 var geturl=$("#carturl").val();
	     var itemname=$("#itemname_"+id).val();
		 var sizeid=$("#sizeid_"+id).val();
		 var varientname=$("#size_"+id).val();
		 var qty=$("#itemqty_"+id).val();
		 var price=$("#itemprice_"+id).val();
		 var catid=$("#catid").val();
		 var updateid=$("#updateid").val();
		 var csrf = $('#csrfhashresarvation').val();
	     var myurl =geturl;
	 if(updateid==''){
	var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&csrf_test_name='+csrf;
	 }
	 else{
		 var updateid=$("#uidupdateid").val();
	var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&orderid='+updateid+'&csrf_test_name='+csrf;
		 }
		  $.ajax({
		 	 type: "POST",
			 url: myurl,
			 data: dataString,
			 success: function(data) {
				  if(updateid==''){
				 	$('#cartlist').html(data);
					var stotal=$('#subtotal').val();
					var tax=$('#tvat').val();
					var discount=$('#tdiscount').val();
					var tgtotal=$('#tgtotal').val();
					$('#calvat').text(tax);
					$('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
					$('#grandtotal').val(tgtotal);
					$('#orggrandTotal').val(tgtotal);
					
					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
					//alert(allnumbv);
		  			var encsting= window.btoa( allnumbv );
		  			$('#denc').val(encsting);
					calculatetotal();
				  }
				  else{
					   $('#updatetlist').html(data);
					    var stotal=$('#subtotal').val();
					    var tax=$('#tvat').val();
						var discount=$('#tdiscount').val();
						var tgtotal=$('#tgtotal').val();
						$('#calvat').text(tax);
						$('#invoice_discount').val(discount);
						var sc=$('#sc').val();
						$('#service_charge').val(sc);
						if(basicinfo.isvatinclusive==1){
							$('#caltotal').text(tgtotal-tax);  
						  }else{
							$('#caltotal').text(tgtotal);
						  }
						$('#grandtotal').val(tgtotal);
						$('#orggrandTotal').val(tgtotal);
						
						var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
		  				var encsting= window.btoa( allnumbv );
		  				$('#denc').val(encsting);
						calculatetotal();
					  }
			 } 
		});
	}
	

// function updatecart(id,qty,status){
// 		if(status=="del" && qty==0){
// 			return false;
// 			}
// 		else{
// 		 var geturl=$("#cartupdateturl").val();
// 		 var csrf = $('#csrfhashresarvation').val();
// 		 var dataString = "CartID="+id+"&qty="+qty+"&Udstatus="+status+'&csrf_test_name='+csrf;
// 		  $.ajax({
// 		 	 type: "POST",
// 			 url: geturl,
// 			 data: dataString,
// 			 success: function(data) {
// 				 $('#cartlist').html(data);
// 				 	var stotal=$('#subtotal').val();
// 				    var tax=$('#tvat').val();
// 					var discount=$('#tdiscount').val();
// 					var tgtotal=$('#tgtotal').val();
// 					$('#calvat').text(tax);
// 					$('#invoice_discount').val(discount);
// 					var sc=$('#sc').val();
// 					$('#service_charge').val(sc);
// 					if(basicinfo.isvatinclusive==1){
// 						$('#caltotal').text(tgtotal-tax);  
// 					  }else{
// 					    $('#caltotal').text(tgtotal);
// 					  }
// 					$('#grandtotal').val(tgtotal);
// 					$('#orggrandTotal').val(tgtotal);
					
// 					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
// 					var encsting= window.btoa( allnumbv );
// 					$('#denc').val(encsting);
// 					//sumcalculation();
					
// 			 } 
// 		});
// 		}
// }



function posupdatecart(id,pid,vid,qty,status){
	
	

		if(status=="del" && qty==0){

			return false;

		}else{

			if(status == 'add'){

			     /*check production*/
				 var checkqty = qty+1;
				 var checkstock = checkstockvalidity(pid);
				//  console.log('checkstock',checkstock);
				//  console.log('checkqty',checkqty);
				//  console.log('pid',pid);

				/* Here previous condition was ***if(checkstock>=0)*** but for some food if not stock like for "without production" based food it should be 
				able to add more qty but for "is stock validate" based food it shoould not add more than stock which is now seems okay.. also discussed
				with touhid vai that here the food and addon primary ID was matching that also was creating issue which has been fixed by checking is_addons in 
				checkstockvalidity(pid) call*/
				 if(checkstock>0){ 

					 if(checkqty > checkstock){
						swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
						return false;
					}

				 }

				var iswithoutproduction=checkswithoutproduction(pid);
                var productionsetting = $('#production_setting').val();

				if(iswithoutproduction==1){

					  //return true

				}else{

					if(productionsetting == 1){
					
						var checkvalue = checkproduction(pid,vid,checkqty);

							if(checkvalue == false){
								return false;
							}
						
					}

				}
            /*end checking*/
        	}
			
		var csrf = $('#csrfhashresarvation').val();
		var mysound=baseurl+"assets/";
		var audio =["beep-08b.mp3"];
		new Audio(mysound + audio[0]).play();
		 var geturl=$("#cartupdateturl").val();

		 

		 var dataString = "CartID="+id+"&qty="+qty+"&Udstatus="+status+'&csrf_test_name='+csrf;

		  $.ajax({
		 	 type: "POST",
			 url: geturl,
			 data: dataString,
			 success: function(data) {

			

				 $('#addfoodlist').html(data);

				    var total=$('#grtotal').val();
					var stotal=$('#subtotal').val();
					var totalitem=$('#totalitem').val();
					$('#item-number').text(totalitem);
					$("#getitemp").val(totalitem);
					var tax=$('#tvat').val();
					var discount=$('#tdiscount').val();
					var tgtotal=$('#tgtotal').val();
					$('#calvat').text(tax);
					$('#vat').val(tax);
					$('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
					$('#grandtotal').val(tgtotal);
					$('#orggrandTotal').val(tgtotal);
					$('#orginattotal').val(tgtotal);
					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
					var encsting= window.btoa( allnumbv );
					$('#denc').val(encsting);
					calculatetotal();
			 } 
		});
		}
}



// function posupdatecartKey(id,pid,vid,qty){

// 	// console.log(qty+'_Ssss');
// 	// return false;
	
// 	qty = qty-1;

// 	/*check production*/
// 	var checkqty = qty;
// 	var checkstock = checkstockvalidity(pid);

// 	if(checkstock>=0){

// 		if(checkqty > checkstock){
// 		swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
// 		return false;
// 	}

// 	}

// 	var iswithoutproduction=checkswithoutproduction(pid);
// 	var productionsetting = $('#production_setting').val();

// 	if(iswithoutproduction==1){

// 			//return true

// 	}else{

// 		if(productionsetting == 1){
		
// 			var checkvalue = checkproduction(pid,vid,checkqty);

// 				if(checkvalue == false){
// 					return false;
// 				}
			
// 		}

// 	}
// 	/*end checking*/
		
		
// 	var csrf = $('#csrfhashresarvation').val();
// 	var mysound=baseurl+"assets/";
// 	var audio =["beep-08b.mp3"];
// 	new Audio(mysound + audio[0]).play();
// 	 var geturl=$("#cartupdateturl").val();

	 

// 	  var dataString = "CartID="+id+"&qty="+qty+"&Udstatus="+'add'+'&csrf_test_name='+csrf;

// 	  $.ajax({
// 		  type: "POST",
// 		 url: geturl,
// 		 data: dataString,
// 		 success: function(data) {

		

// 			 $('#addfoodlist').html(data);

// 				var total=$('#grtotal').val();
// 				var stotal=$('#subtotal').val();
// 				var totalitem=$('#totalitem').val();
// 				$('#item-number').text(totalitem);
// 				$("#getitemp").val(totalitem);
// 				var tax=$('#tvat').val();
// 				var discount=$('#tdiscount').val();
// 				var tgtotal=$('#tgtotal').val();
// 				$('#calvat').text(tax);
// 				$('#vat').val(tax);
// 				$('#invoice_discount').val(discount);
// 				var sc=$('#sc').val();
// 				$('#service_charge').val(sc);
// 				if(basicinfo.isvatinclusive==1){
// 					$('#caltotal').text(tgtotal-tax);  
// 				  }else{
// 					$('#caltotal').text(tgtotal);
// 				  }
// 				$('#grandtotal').val(tgtotal);
// 				$('#orggrandTotal').val(tgtotal);
// 				$('#orginattotal').val(tgtotal);
// 				var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
// 				var encsting= window.btoa( allnumbv );
// 				$('#denc').val(encsting);
// 				calculatetotal();
// 		 } 
// 	});
	
// }



function posupdatecartKey(id,pid,vid,qty, event=null){

	if (event) {
		// Check which key was pressed
		const key = event.key; // Gives the key (e.g., 'ArrowUp', 'Enter', 'Backspace', etc.)
		const keyCode = event.keyCode || event.which; // For older browsers (numeric key code)
	
		console.log(`Key pressed: ${key} (KeyCode: ${keyCode})`);
	
		// Example: Perform actions based on the key
		if (key === "Enter") {
			// Add logic for handling Enter key
		  	// console.log("Enter key pressed: Updating cart...");
		  
		} else if (key === "ArrowUp") {
			// Add logic for handling Arrow Up key
			qty = parseInt(qty) + 1;
		  	// console.log("Arrow Up key pressed: Increasing quantity...");
		} else if (key === "ArrowDown") {
			// Add logic for handling Arrow Down key
			if(qty > 0){
				qty = parseInt(qty) - 1;
			}else{
				return false;
			}
		  	// console.log("Arrow Down key pressed: Decreasing quantity...");
		}
	  }
	
	// qty = parseInt(qty) + 1;

	/*check production*/
	var checkqty = qty;
	var checkstock = checkstockvalidity(pid);

	/* Here previous condition was ***if(checkstock>=0)*** but for some food if not stock like for "without production" based food it should be 
	able to add more qty but for "is stock validate" based food it shoould not add more than stock which is now seems okay.. also discussed
	with touhid vai that here the food and addon primary ID was matching that also was creating issue which has been fixed by checking is_addons in 
	checkstockvalidity(pid) call*/
	if(checkstock>0){

		if(checkqty > checkstock){
			swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
			return false;
		}

	}

	var iswithoutproduction=checkswithoutproduction(pid);
	var productionsetting = $('#production_setting').val();

	if(iswithoutproduction==1){

			//return true

	}else{

		if(productionsetting == 1){
		
			var checkvalue = checkproduction(pid,vid,checkqty);

				if(checkvalue == false){
					return false;
				}
			
		}

	}
	/*end checking*/
		
	var csrf = $('#csrfhashresarvation').val();
	var mysound=baseurl+"assets/";
	var audio =["beep-08b.mp3"];
	new Audio(mysound + audio[0]).play();
	var geturl=$("#cartupdateturl").val();

	var dataString = "CartID="+id+"&qty="+qty+"&Udstatus="+'add_on_keyup'+'&csrf_test_name='+csrf;

	  $.ajax({
		  type: "POST",
		 url: geturl,
		 data: dataString,
		 success: function(data) {

			 $('#addfoodlist').html(data);
				// console.log('qty: '+qty);
				$("#item_pid_"+pid).focus();

				var total=$('#grtotal').val();
				var stotal=$('#subtotal').val();
				var totalitem=$('#totalitem').val();
				$('#item-number').text(totalitem);
				$("#getitemp").val(totalitem);
				var tax=$('#tvat').val();
				var discount=$('#tdiscount').val();
				var tgtotal=$('#tgtotal').val();
				$('#calvat').text(tax);
				$('#vat').val(tax);
				$('#invoice_discount').val(discount);
				var sc=$('#sc').val();
				$('#service_charge').val(sc);
				if(basicinfo.isvatinclusive==1){
					$('#caltotal').text(tgtotal-tax);  
				  }else{
					$('#caltotal').text(tgtotal);
				  }
				$('#grandtotal').val(tgtotal);
				$('#orggrandTotal').val(tgtotal);
				$('#orginattotal').val(tgtotal);
				var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
				var encsting= window.btoa( allnumbv );
				$('#denc').val(encsting);
				calculatetotal();
		 } 
	});
	
}











/* $('body').on('change', '.updatecheckbarcode', function(e) {
	alert("HI");
		var csrf = $('#csrfhashresarvation').val();
		var fullbarcode=$('#updatecheckbarcode').val();
		var codearray = fullbarcode.split(".");
		//console.log(codearray)
		var dataString = "foodid=" + codearray[0] + '&csrf_test_name=' + csrf;
		  $.ajax({
			  type: "POST",
			  url: basicinfo.baseurl + "ordermanage/order/barcodescan",
			  data: dataString,
			  dataType: 'json',
			  success: function(data) {
				  if(data.ProductsID!=''){
					  updatebarcodeaddtocart(data.ProductsID,data.variantid,data.totalvarient,data.is_customqty,data.isgroup,data.CategoryID,data.ProductName,data.variantName,data.price,data.addons,data.withoutproduction,data.isstockvalidity);
				  }
				  $('#updatecheckbarcode').val('');
			  }
		  });
         
      });*/
function removecart(rid){
		  if (confirm("Are you sure you want to delete?!") == true) {
		  var geturl=$("#removeurl").val();
		 var csrf = $('#csrfhashresarvation').val();
		 var dataString = "rowid="+rid+'&csrf_test_name='+csrf;
		var mysound=baseurl+"assets/";
		var audio =["beep-08b.mp3"];
		new Audio(mysound + audio[0]).play();
		  $.ajax({
		 	 type: "POST",
			 url: geturl,
			 data: dataString,
			 success: function(data) {
				 $('#addfoodlist').html(data);
				   var total=$('#grtotal').val();
				   var stotal=$('#subtotal').val();
					var totalitem=$('#totalitem').val();
					$('#item-number').text(totalitem);
					$("#getitemp").val(totalitem);
					var tax=$('#tvat').val();
					var discount=$('#tdiscount').val();
					var tgtotal=$('#tgtotal').val();
					$('#calvat').text(tax);
					$('#vat').val(tax);
					$('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
					$('#grandtotal').val(tgtotal);
					$('#orggrandTotal').val(tgtotal);
					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
					var encsting= window.btoa( allnumbv );
					$('#denc').val(encsting);
					calculatetotal();
			 } 
		});
		} else {
		  text = "You canceled!";
		}
		  
		
	}
function addonsitem(id,sid){
		 var geturl=$("#addonsurl").val();
		 var csrf = $('#csrfhashresarvation').val();
		 var myurl =geturl+'/'+id;
		 var dataString = "pid="+id+"&sid="+sid+'&csrf_test_name='+csrf;
		  $.ajax({
		 	 type: "POST",
			 url: myurl,
			 data: dataString,
			 success: function(data) {
				 $('.addonsinfo').html(data);
				 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
			 } 
		});
	}
function posaddonsitem(id,sid){
		 var geturl=$("#addonsurl").val();
		 var csrf = $('#csrfhashresarvation').val();
		 var myurl =geturl+'/'+id;
		 var dataString = "pid="+id+"&sid="+sid+'&csrf_test_name='+csrf;
		  $.ajax({
		 	 type: "POST",
			 url: myurl,
			 data: dataString,
			 success: function(data) {
				 $('.addonsinfo').html(data);
				 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
			 } 
		});
	}
function addonsfoodtocart(pid,id){
	var addons = [];
	var adonsqty=[];
	 var allprice = 0;
	 var adonsprice = [];
	 var adonsname=[];
				$('input[name="addons"]:checked').each(function() {
					var adnsid=$(this).val();
					var adsqty=$('#addonqty_'+adnsid).val();
					adonsqty.push(adsqty);
				  	addons.push($(this).val());
					
					allprice += parseFloat($(this).attr('role'))*parseInt(adsqty);
					adonsprice.push($(this).attr('role'));
					adonsname.push($(this).attr('title'));
				});
	var catid=$("#catid").val();
	var csrf = $('#csrfhashresarvation').val();
	var itemname=$("#itemname_"+id).val();
	 var sizeid=$("#sizeid_"+id).val();
	 var varientname=$("#size_"+id).val();
	 var qty=$("#itemqty_"+id).val();
	 var price=$("#itemprice_"+id).val();
	 var updateid=$("#updateid").val();
	 if(updateid==''){
		  var geturl=$("#carturl").val();
		  var myurl =geturl;
	var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&addonsid='+addons+'&allprice='+allprice+'&adonsunitprice='+adonsprice+'&adonsqty='+adonsqty+'&adonsname='+adonsname+'&csrf_test_name='+csrf;
	 }
	 else{
		 var updateid=$("#uidupdateid").val();
		  var geturl=$("#updatecarturl").val();
		  var myurl =geturl;
		 var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&addonsid='+addons+'&allprice='+allprice+'&adonsunitprice='+adonsprice+'&adonsqty='+adonsqty+'&adonsname='+adonsname+'&orderid='+updateid+'&csrf_test_name='+csrf;
		 }
		$.ajax({
		 	 type: "POST",
			 url: myurl,
			 data: dataString,
			 success: function(data) {
				 if(updateid==''){
					 $('#cartlist').html(data);
					 $('#edit').modal('hide');
					  var tax=$('#tvat').val();
					var discount=$('#tdiscount').val();
					var tgtotal=$('#tgtotal').val();
					$('#calvat').text(tax);
					$('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					var stotal=$('#subtotal').val();
					$('#service_charge').val(sc);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
					$('#grandtotal').val(tgtotal);
					$('#orggrandTotal').val(tgtotal);
					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
				    var encsting= window.btoa( allnumbv );
				    $('#denc').val(encsting);
					calculatetotal();
				  }
				  else{
					   $('#updatetlist').html(data);
					   $('#edit').modal('hide');
					var tax=$('#tvat').val();
					var discount=$('#tdiscount').val();
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
					var tgtotal=$('#tgtotal').val();
					$('#calvat').text(tax);
					var stotal=$('#subtotal').val();
					$('#invoice_discount').val(discount);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
					$('#grandtotal').val(tgtotal);
					$('#orggrandTotal').val(tgtotal);
					$('#orginattotal').val(tgtotal);
					
					var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
				    var encsting= window.btoa( allnumbv );
				    $('#denc').val(encsting);
					calculatetotal();
					  }
			 } 
		});

	}
/*$(document).on('change','#varientinfo',function(){
          var id    =   $("#varientinfo").val();
		  var name  = $('#varientinfo option:selected').data('title');
		  var price = $('#varientinfo option:selected').data('price'); 
		  $("#sizeid_1").val(id);
		  $("#size_1").val(name);
		  $("#itemprice_1").val(price);
		  $("#vprice").text(price);
          
     });*/


function gettotalcheck(maxitem,id,tpid){
	var total = $('input.checkbox'+id+':checkbox:checked').length;
	
	 if(maxitem == total){
		 //var count = $('input[type="checkbox"].testcheckbox').length;
        $('[type="checkbox"].checkbox'+id).not('input.checkbox'+id+':checked').prop('disabled',true);
		$('input[disabled]').parent().addClass('checkbox-disable');
    }
	else{
		 $('[type="checkbox"].checkbox'+id).not('input.checkbox'+id+':checkbox:checked').prop('disabled',false);
		 $(".check-label").removeClass('checkbox-disable');
	 }
	}
function posaddonsfoodtocart(pid,id,more=null){
	
  var addons = [];
  var adonsqty=[];
  var topping = [];
  var toppingname = [];
  var toppingassign=[];
  var toppingpos=[];
  var toppingprices=[];
  var alltoppingprice=0;
  var allprice = 0;
  var adonsprice = [];
  var adonsname=[];
  var numoftopping = $('#numoftopping').val();
  var csrf = $('#csrfhashresarvation').val();
  $('input[name="addons"]:checked').each(function() {
          var adnsid=$(this).val();
          var adsqty=$('#addonqty_'+adnsid).val();
          adonsqty.push(adsqty);
            addons.push($(this).val());
          
          allprice += parseFloat($(this).attr('role'))*parseInt(adsqty);
          adonsprice.push($(this).attr('role'));
          adonsname.push($(this).attr('title'));
        });
	for(j=1;j<=numoftopping;j++){
		$('input[name="topping_'+j+'"]:checked').each(function() {
			  var type=$(this).attr('type');
			  var pos=$(this).attr('pos');
			 
			  var toppingid=$(this).val();
			  topping.push($(this).val());
			  toppingassign.push($(this).attr('role'));
			  toppingname.push($(this).attr('title'));
			  toppingprices.push($(this).attr('lang'));
			  alltoppingprice += parseFloat($(this).attr('lang'));
			  if(pos>0){
			  toppingpos.push($(this).attr('pos'));
			  }
			  else{
				  toppingpos.push(0);
				  }
			});
	}
	
	//return false;
  var geturl=$("#carturl").val();
  var catid=$("#catid").val();
  var totalvarient=$("#totalvarient").val();
  var customqty=$("#customqty").val();
  var itemname=$("#itemname_"+id).val();
   var sizeid=$("#sizeid_"+id).val();
   var varientname=$("#size_"+id).val();
   var qty=$("#itemqty_"+id).val();
   var price=$("#itemprice_"+id).val();
    var isgroup=$("#isgroup").val();
   var updateid=$("#uidupdateid").val();
   var myurl =geturl;
   var mysound=baseurl+"assets/";
    var audio =["beep-08b.mp3"];
    new Audio(mysound + audio[0]).play();
	var checkstock = checkstockvalidity(pid);
	
		  
   if(typeof updateid == "undefined"){
		/* Here previous condition was ***if(checkstock>=0)*** but for some food if not stock like for "without production" based food it should be 
		able to add more qty but for "is stock validate" based food it shoould not add more than stock which is now seems okay.. also discussed
		with touhid vai that here the food and addon primary ID was matching that also was creating issue which has been fixed by checking is_addons in 
		checkstockvalidity(pid) call*/

	   if(checkstock>0){
			  var isselected = $('#productionsetting-' + pid + '-' + sizeid).length;
			  
			  if (isselected == 1) {

					// var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid).text()) + parseInt(qty); // old code till 11th sep 2024

					var checkqty = 1;
					if(!isNaN(parseInt($('#productionsetting-' + pid + '-' + sizeid).text()))){
						checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid).text());
					}else{
						checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid+' input').val());
					} 
					
					checkqty = checkqty + parseInt(qty);

					// End checkqty

			  } else {
				  var checkqty = parseInt(qty);
			  }

			  if(checkqty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
			

	      }
      /*check production*/
                var productionsetting = $('#production_setting').val();
				var iswithoutproduction=checkswithoutproduction(pid);
				if(iswithoutproduction==1){
					 // return true;
				}else{
                   if(productionsetting == 1){
                   
                    if(isselected ==1 ){
                        var checkqty = parseInt($('#productionsetting-'+pid+'-'+sizeid).text())+parseInt(qty);
                    }
                    else{
                        var checkqty = parseInt(qty);
                    }
				    //alert(checkqty);
                     var checkvalue = checkproduction(pid,sizeid,checkqty);

                        if(checkvalue == false){
                            return false;
                        }
                    
                }
				  }
            /*end checking*/
     var geturl=$("#carturl").val();
	 var str1='';
			 var str2='';
			 
			 if(addons!='' || topping!=''){
			//   str1=addons.replace(",", 0);
			//   str2=topping.replace(",", 0);
			 }
			 var exitrow = $('#productionsetting-update-'+pid+'-'+sizeid).length;
			 if(exitrow==1){
				var original_previousqty = $("#original_previousqty_"+pid+str1+str2+sizeid).val();
			 }else{
				var original_previousqty =0;
			 }
     var myurl =geturl;
  var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&addonsid='+addons+'&allprice='+allprice+'&adonsunitprice='+adonsprice+'&adonsqty='+adonsqty+'&adonsname='+adonsname+'&isgroup='+isgroup+'&totalvarient='+totalvarient+'&customqty='+customqty+'&csrf_test_name='+csrf+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice+'&original_previousqty='+original_previousqty;
   }
   else{

	   /* Here previous condition was ***if(checkstock>=0)*** but for some food if not stock like for "without production" based food it should be 
		able to add more qty but for "is stock validate" based food it shoould not add more than stock which is now seems okay.. also discussed
		with touhid vai that here the food and addon primary ID was matching that also was creating issue which has been fixed by checking is_addons in 
		checkstockvalidity(pid) call*/
		if(checkstock>0){ 
			  var isselected = $('#productionsetting-update-'+pid+'-'+sizeid).length;
			   
			  if (isselected == 1) {
					var checkqty = parseInt($('#productionsetting-update-'+pid+'-'+sizeid).text())+parseInt(qty);
			  } else {
				  var checkqty = parseInt(qty);
			  }
			//   console.log("checkqty: "+checkqty+" , checkstock:"+checkstock);
			  if(checkqty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
	      }
   /*check production*/
                var productionsetting = $('#production_setting').val();
                if(productionsetting == 1){
                   
                    var isselected = $('#productionsetting-update-'+pid+'-'+sizeid).length;
					
                  
                    if(isselected ==1 ){
                        var checkqty = parseInt($('#productionsetting-update-'+pid+'-'+sizeid).text())+qty;
                    }
                    else{
                        var checkqty = qty;
                    }

                     var checkvalue = checkproduction(pid,sizeid,checkqty);
                        if(checkvalue == false){
                            return false;
                        }
                    
                }
			
            /*end checking*/
     var geturl=$("#updatecarturl").val();
	 var str1='';
			 var str2='';
			 
			 if(addons!='' || topping!=''){
			//   str1=addons.replace(",", 0);
			//   str2=topping.replace(",", 0);
			 }
			 var exitrow = $('#productionsetting-update-'+pid+'-'+sizeid).length;
			 if(exitrow==1){
				var original_previousqty = $("#original_previousqty_"+pid+str1+str2+sizeid).val();
			 }else{
				var original_previousqty =0;
			 }
     var myurl =geturl;
     var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&addonsid='+addons+'&allprice='+allprice+'&adonsunitprice='+adonsprice+'&adonsqty='+adonsqty+'&adonsname='+adonsname+'&orderid='+updateid+'&isgroup='+isgroup+'&totalvarient='+totalvarient+'&customqty='+customqty+'&csrf_test_name='+csrf+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice+'&original_previousqty='+original_previousqty;
     }
    $.ajax({
       type: "POST",
       url: myurl,
       data: dataString,
       success: function(data) {
		   if(typeof updateid == "undefined"){
			   $('#addfoodlist').html(data);
		   }
		   else{
			 $('#updatefoodlist').html(data);
		   }
		 //alert(original_previousqty);
        $("#original_previousqty_"+pid+str1+str2+sizeid).val(original_previousqty);
          var total=$('#grtotal').val();
          var totalitem=$('#totalitem').val();
          $('#item-number').text(totalitem);
         
          var tax=$('#tvat').val();
          var discount=$('#tdiscount').val();
          var tgtotal=$('#tgtotal').val();
          $('#calvat').text(tax);
          $('#vat').val(tax);
          $('#invoice_discount').val(discount);
		  var sc=$('#sc').val();
		  var stotal=$('#subtotal').val();
		  $('#service_charge').val(sc);
          if(basicinfo.isvatinclusive==1){
			$('#caltotal').text(tgtotal-tax);  
		  }else{
			$('#caltotal').text(tgtotal);
		  }
          $('#grandtotal').val(tgtotal);
          $('#orggrandTotal').val(tgtotal);
          $('#orginattotal').val(tgtotal);
		  
		  var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
		  var encsting= window.btoa( allnumbv );
		  $('#denc').val(encsting);
		  calculatetotal();
          if(more!=1){
        	$('#edit').modal('hide');
		  }
       } 
    });

  }
function deletecart(id,orderid,pid,vid,qty){
	if (confirm(lang.Are_you_sure_you_want_to_delete) == true) {
	 var geturl=$("#delurl").val();
	 var csrf = $('#csrfhashresarvation').val();
		var dataString = "mid="+id+"&orderid="+orderid+"&pid="+pid+"&vid="+vid+"&qty="+qty+'&csrf_test_name='+csrf;
		$.ajax({
		 	 type: "POST",
			 url: geturl,
			 data: dataString,
			 success: function(data) {
				 alert("Deleted Successfully!!!");
				 $('#updatefoodlist').html(data);
				 $('#uvchange').val(1);
				 $(".maindashboard").addClass("disabled");
				 $("#fhome").addClass("disabled");
				 $("#kitchenorder").addClass("disabled");
				 $("#todayqrorder").addClass("disabled");
				 $("#todayonlieorder").addClass("disabled");
				 $("#todayorder").addClass("disabled");
				 $("#ongoingorder").addClass("disabled");
			 } 
		});
	} else {
	  text = "You canceled!";
	}
	
	}
function expand(id){
	var classes=$("#expandcol_"+id).attr('class').split(' ')[1];
	if ($("#expandcol_"+id).hasClass("hasaddons")) {
	$("."+classes).removeClass("hasaddons");
	}
	else{
		$("."+classes).addClass("hasaddons");
		}
	
	}

function calculatetotal() {
	var total_price = 0;
    var inv_dis = 0;
    var ser_chg = 0;
	var subtotal=0;
	var vat=0;
    total_price = $("#orggrandTotal").val();
	subtotal =    $("#subtotal").val();
    inv_dis =     $("#invoice_discount").val();
	
	if(inv_dis==''){
		inv_dis = 0;
		}
    ser_chg = $("#service_charge").val();
		if(ser_chg==''){
		ser_chg = 0;
		}
	vat = $("#vat").val();
	if(vat==''){
		vat = 0;
		}
		
	distype= $("#distype").val();
	//alert(distype);
	/*if(distype==1){
		inv_dis=parseFloat(subtotal)*parseFloat(inv_dis)/100;
		}*/
	sdtype= $("#sdtype").val();
	var newsubtotal=parseFloat(subtotal)- parseFloat(inv_dis);
	if(sdtype==1){
		ser_chg=parseFloat(newsubtotal)*parseFloat(ser_chg)/100;
		}
    var totalamount=parseFloat(newsubtotal)+parseFloat(vat);
    var sum = parseFloat(totalamount)+parseFloat(ser_chg);
	var alldecimal=sum;
	var sum=sum.toFixed(basicinfo.showdecimal);
	//alert(total_price+'_'+subtotal+'|'+inv_dis);
    $("#grandtotal").val(sum);
	$("#orginattotal").val(sum);
	$("#caltotal").text(sum);
	 var getvat=parseFloat(vat).toFixed(basicinfo.showdecimal);
	 if(basicinfo.isvatinclusive==1){
		 var getvalue=parseFloat(alldecimal) - parseFloat(vat);
		$('#caltotal').text(getvalue);
		var finalgtotal=getvalue.toFixed(basicinfo.showdecimal);
	  }else{
		  var getvalue=parseFloat(alldecimal);
		  $('#caltotal').text(sum);
		  var finalgtotal=getvalue.toFixed(basicinfo.showdecimal);
	  }
	   $('#calvat').text(getvat);
	  //alert(ser_chg);
    var allnumbv=vat+'|'+inv_dis+'|'+ser_chg+'|'+newsubtotal+'|'+finalgtotal;
	var encsting= window.btoa( allnumbv );
	$('#denc').val(encsting);

}

function sumcalculation(id=null){
	var total_price = 0;
    var inv_dis = 0;
    var ser_chg = 0;
	var subtotal=0;
	var vat=0;
	var totalamount=0;
	if(id!=''){
    total_price = $("#orginattotal_update").val();
    inv_dis = $("#invoice_discount_update").val();
	}
	else{
		total_price = $("#orginattotal").val();
    	inv_dis = $("#discount").val();
		}
	if(inv_dis==''){
		inv_dis = 0;
		}
		if(id!=''){
    		ser_chg = $("#service_charge_update").val();
		}else{
			ser_chg = $("#scharge").val();
			}
		if(ser_chg==''){
		ser_chg = 0;
		}
	if(id!=''){
	 subtotal = $("#subtotal_update").val();
	}else{
		subtotal = $("#subtotal").val();
		}
	if(subtotal==''){
		subtotal = 0;
		}
	if(id!=''){
		vat = $("#vat_update").val();
		}else{
		vat = $("#vat").val();
		}
	
	if(vat==''){
		vat = 0;
		}
	if(id!=''){
	distype= $("#distype_update").val();
	}else{
		distype= $("#distype").val();
	}
	/*if(distype==1){
		inv_dis=parseFloat(subtotal)*parseFloat(inv_dis)/100;
		}*/
	if(id!=''){
	sdtype= $("#sdtype_update").val();
	}else{
	sdtype= $("#sdtype").val();	
	}
	var newsubtotal=parseFloat(subtotal)- parseFloat(inv_dis);	
	if(sdtype==1){
		ser_chg=parseFloat(newsubtotal)*parseFloat(ser_chg)/100;
		}
	//alert(ser_chg);
    var totalamount=parseFloat(newsubtotal)+parseFloat(vat);
    var sum = parseFloat(totalamount)+parseFloat(ser_chg);
	var sum=sum.toFixed(basicinfo.showdecimal);
	if(id!=''){
    $("#grandtotal_update").val(sum);
	$("#orginattotal_update").val(sum);
	$("#sc").val(ser_chg);
	//$("#gtotal_update").text(sum);
	if(basicinfo.isvatinclusive==1){
		$('#gtotal_update').text(sum-vat);  
		var finalgtotal=sum-vat;
	  }else{
		$('#gtotal_update').text(sum);
		var finalgtotal=sum-vat;
	  }
	
	var allnumbv=vat+'|'+inv_dis+'|'+ser_chg+'|'+newsubtotal+'|'+finalgtotal;
	var encsting= window.btoa( allnumbv );
	$('#udenc').val(encsting);
	}else{
	$("#grandtotal").val(sum);
	$("#orginattotal").val(sum);
	$("#gtotal").text(sum);
	if(basicinfo.isvatinclusive==1){
		$('#gtotal').text(sum-vat);  
		var finalgtotal=sum-vat;
	  }else{
		$('#gtotal').text(sum);
		var finalgtotal=sum-vat;
	  }
	var allnumbv=vat+'|'+inv_dis+'|'+ser_chg+'|'+newsubtotal+'|'+finalgtotal;
	var encsting= window.btoa( allnumbv );
	$('#udenc').val(encsting);
	}
	

}




  function getAjaxModal(url,callback=false,ajaxclass='#modal-ajaxview',modalclass='#payprint_marge',data='',method='get')
    {
     var csrf = $('#csrfhashresarvation').val(); 
	 var fulldata=data+'&csrf_test_name='+csrf;  
    $.ajax({
        url:url,
        type:method,
        data:fulldata,
        beforeSend:function(xhr){
			//$("#payoverlay").show();
			//alert("fdsf");
          //setTimeout(function(){$("#payoverlay").fadeOut(300);},1000); 
        },
        success:function(result){ 
		    setTimeout(function(){},500);
			//$("#payoverlay").hide();
            $(modalclass).modal({backdrop: 'static', keyboard: false},'show');
			setTimeout(function(){getmytab(4);},200);			
            if(callback){
                callback(result);
                return;
            }
            $(ajaxclass).html(result); 
            $('#add_new_payment').empty();
        }
        });
    }
	function getmytab(id){
		$("#getp_"+id).trigger( "click" );
		}
     function checkproduction(foodid,vid,servingqty){
    	var myurl = $('#production_url').val();
		var csrf = $('#csrfhashresarvation').val();
        var dataString = "foodid="+foodid+'&vid='+vid+'&qty='+servingqty+'&csrf_test_name='+csrf;
  
       var check =true;
         $.ajax({
         type: "POST",
         url: myurl,
         async: false,
         global: false,
         data: dataString,
         success: function(data) {
           
            if(data !=1){
                alert(data);
                check = false;
                }
                
           
         } 
    });
        return check;
    }
	
	
	function checkstockvalidity(foodid){
    	var myurl = basicinfo.baseurl + "ordermanage/order/checkstockvalidity/"+foodid;
		var csrf = $('#csrfhashresarvation').val();
        var dataString = 'csrf_test_name='+csrf;
         var check =0;
         $.ajax({
         type: "POST",
         url: myurl,
         async: false,
         global: false,
		 dataType: 'json',
         data: dataString,
         success: function(data) {
			 if(data.withoutproduction==1 && data.isstockvalidity==1){
				 check = data.stockqty;
			 }else{
				 check ="null"
			 }
            
         } 
    	});
        return check;
    }
	
	function checkswithoutproduction(foodid){
    	var myurl = basicinfo.baseurl + "ordermanage/order/checkstockwithoutproduction/"+foodid;
		var csrf = $('#csrfhashresarvation').val();
        var dataString = 'csrf_test_name='+csrf;
        var check =0;
         $.ajax({
         type: "POST",
         url: myurl,
         async: false,
         global: false,
		 dataType: 'json',
         data: dataString,
         success: function(data) {
			 check = data.withoutproduction;
           } 
    	});
        return check;
    }

//Product search button js
    $('body').on('click', '.update_select_product', function(e) {
        e.preventDefault();
        var panel = $(this);
        var pid = panel.find('.product-card_body input[name=update_select_product_id]').val();
		var totalvarient = panel.find('.product-card_body input[name=update_select_totalvarient]').val();
		var customqty = panel.find('.product-card_body input[name=update_select_iscustomeqty]').val();
        var sizeid = panel.find('.product-card_body input[name=update_select_product_size]').val();
		var isgroup = panel.find('.product-card_body input[name=update_select_product_isgroup]').val();
        var catid = panel.find('.product-card_body input[name=update_select_product_cat]').val();
        var itemname= panel.find('.product-card_body input[name=update_select_product_name]').val();
        var varientname=panel.find('.product-card_body input[name=update_select_varient_name]').val();
        var qty=1;
        var price=panel.find('.product-card_body input[name=update_select_product_price]').val();
        var hasaddons=panel.find('.product-card_body input[name=update_select_addons]').val();
        var existqty=$('#select_qty_'+pid+'_'+sizeid).val();
		var csrf = $('#csrfhashresarvation').val();
		var iswithoutproduction=panel.find('.product-card_body input[name=update_select_withoutproduction]').val();
		var isstockavail=panel.find('.product-card_body input[name=update_select_stockvalitity]').val();
		
		
		var isavail=panel.find('.product-card_body input[name=update_select_product_avail]').val();
		  if(isavail==0){
		   toastr.warning("Food Not available at that moment!!!", 'Warning');
			return false;
			}
         if(existqty === undefined){ 
           var existqty=0;
         }
         else{
          var existqty=$('#select_qty_'+pid+'_'+sizeid).val();
        }
        var qty=parseInt(existqty)+parseInt(qty);
		if(iswithoutproduction==1 && isstockavail==1){
		  var checkstock = checkstockvalidity(pid);
		  if(checkstock>=0){
			  if(qty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
	      }
	   }
	   var updateid=$("#uidupdateid").val();
        if(hasaddons==0 && totalvarient==1 && customqty==0){
        	      /*check production*/
                var productionsetting = $('#production_setting').val();
				if(iswithoutproduction==1){
					  //return true
				  }else{
                if(productionsetting == 1){
                   
                    var isselected = $('#productionsetting-update-'+pid+'-'+sizeid).length;
                  
                    if(isselected ==1 ){

                        // var checkqty = parseInt($('#productionsetting-update-'+pid+'-'+sizeid).text())+qty;
						var checkqty = parseInt($('#productionsetting-update-' + pid + '-' + sizeid + ' input').val()) + qty;

                                               
                    }
                    else{
                        var checkqty = qty;
                    }

                     var checkvalue = checkproduction(pid,sizeid,checkqty);

                        if(checkvalue == false){
                            return false;
                        }
                    
                }
				  }
            /*end checking*/
			var auid = '';
		var original_previousqty = $("#original_previousqty_"+pid+sizeid+auid).val();
		
        var mysound=baseurl+"assets/";
        var audio =["beep-08b.mp3"];
        new Audio(mysound + audio[0]).play();
        var myurl= $('#updatecarturl').val();
        var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&isgroup='+isgroup+'&orderid='+updateid+'&totalvarient='+totalvarient+'&customqty='+customqty+'&original_previousqty='+original_previousqty+'&csrf_test_name='+csrf;
		 $.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {
                    $('#updatefoodlist').html(data);
                    var total=$('#grtotal').val();
                    var totalitem=$('#totalitem').val();
                    $('#item-number').text(totalitem);
                    $('#getitemp').val(totalitem);
                    var tax=$('#tvat').val();
                
                    var discount=$('#tdiscount').val();
                    var tgtotal=$('#tgtotal').val();
                    $('#calvat').text(tax);
                    $('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
                    if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
						$('#caltotal').text(tgtotal);
					  }
                    $('#grandtotal').val(tgtotal);
                    $('#orggrandTotal').val(tgtotal);
                    $('#orginattotal').val(tgtotal);
					$('#uvchange').val(1);
					$(".maindashboard").addClass("disabled");
					 $("#fhome").addClass("disabled");
					 $("#kitchenorder").addClass("disabled");
					 $("#todayqrorder").addClass("disabled");
					 $("#todayonlieorder").addClass("disabled");
					 $("#todayorder").addClass("disabled");
					 $("#ongoingorder").addClass("disabled");
             } 
         });
        }
        else{
            var auid = '';
			var original_previousqty = $("#original_previousqty_"+pid+sizeid+auid).val();
			 var geturl=$("#addonexsurl").val();
             var myurl =geturl+'/'+pid;
             var dataString = "pid="+pid+"&sid="+sizeid+"&id="+catid+"&totalvarient="+totalvarient+"&customqty="+customqty+'&original_previousqty='+original_previousqty+'&csrf_test_name='+csrf;
				$.ajax({
				 type: "POST",
				 url: geturl,
				 data: dataString,
				 success: function(data) {
						 $('.addonsinfo').html(data);
						 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
						 var tax=$('#tvat').val();
						var discount=$('#tdiscount').val();
						var tgtotal=$('#tgtotal').val();
						$('#vat').val(tax);
						$('#calvat').text(tax);
						$('#invoice_discount').val(discount);
						if(basicinfo.isvatinclusive==1){
							$('#caltotal').text(tgtotal-tax);  
						  }else{
							$('#caltotal').text(tgtotal);
						  }
						$('#grandtotal').val(tgtotal);
						$('#orggrandTotal').val(tgtotal);
						$('#orginattotal').val(tgtotal);
						$('#uvchange').val(1);
						$(".maindashboard").addClass("disabled");
						 $("#fhome").addClass("disabled");
						 $("#kitchenorder").addClass("disabled");
						 $("#todayqrorder").addClass("disabled");
						 $("#todayonlieorder").addClass("disabled");
						 $("#todayorder").addClass("disabled");
						 $("#ongoingorder").addClass("disabled");
				 } 
			});
         }
    });
function incrementpopaddons(e)
    {
        // use the quantity field's DOM position relative to the button that was clicked
        const qty = e.target.parentNode.parentNode.querySelector("input.popqtyincdrc");
        var value = parseInt(qty.value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        qty.value = value;
    }


    function decrementpopaddons(e)
    {
        const qty = e.target.parentNode.parentNode.querySelector("input.popqtyincdrc");
        var value = parseInt(qty.value, 10);
        value = isNaN(value) ? 1 : value;

        value--;
        if(value == 0) {
            value = 1;
        }
        qty.value = value;
    }
	function incrementpopaddonsbot(e)
    {
        // use the quantity field's DOM position relative to the button that was clicked
        const qty = e.target.parentNode.parentNode.querySelector("input.popupaddons");
        var value = parseInt(qty.value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        qty.value = value;
    }


    function decrementpopaddonsbot(e)
    {
        const qty = e.target.parentNode.parentNode.querySelector("input.popupaddons");
        var value = parseInt(qty.value, 10);
        value = isNaN(value) ? 1 : value;

        value--;
        if(value == 0) {
            value = 1;
        }
        qty.value = value;
    }