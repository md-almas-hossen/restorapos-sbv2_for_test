  // JavaScript Document
$( window ).load(function() {
// Run code
"use strict";
$(".sidebar-mini").addClass('sidebar-collapse');
});
$(document).ready(function () {
"use strict";
// select 2 dropdown
$("select.form-control:not(.dont-select-me)").select2({
placeholder: "Select option",
allowClear: true
});
//form validate
$("#validate").validate();
$("#add_category").validate();
$("#customer_name").validate();
$('.productclist').slimScroll({
size: '3px',
height: '345px',
allowPageScroll: true,
railVisible: true
});
$('.product-grid').slimScroll({
size: '3px',
height: '720px',
allowPageScroll: true,
railVisible: true
});
});
//Customer select
$('.search-field-customersr').select2({
    //placeholder: "Select Customer",
    minimumInputLength: 1,
    ajax: {
		url: basicinfo.baseurl+'ordermanage/order/getcustomertdroup',
        dataType: 'json',
        delay: 250,
        //data:{csrf_test_name:basicinfo.csrftokeng},
        processResults: function(data) {
            return {
                results: $.map(data, function(item) {
                    return {
                        text: item.text,
                        id: item.id
                    }
                })
            };
        },
        cache: true
    }
  });
$('body').on('keyup', '#update_product_name', function() {
var product_name = $(this).val();
var category_id = $('#category_id').val();
var myurl= $('#posurl_update').val();
var csrf = $('#csrfhashresarvation').val();
$.ajax({
type: "post",
async: false,
url: myurl,
data: {product_name: product_name,category_id:category_id,csrf_test_name:csrf},
success: function(data) {
if (data == '420') {
$("#product_search_update").html(lang.Product_not_found);
}else{
$("#product_search_update").html(data);
}
},
error: function() {
alert(lang.req_failed);
}
});
});
$(document).on('change', '#customer_name', function() {
    var tid = $(this).children("option:selected").val();
    var idvid = tid.split('-');
    var id = idvid[0];
    var vid = idvid[1];
    console.log(id)
    $('#customer_namet option[value='+id+']').attr('selected','selected');  
  });
function getslcategory_update(carid){
var product_name = $('#update_product_name').val();
var category_id = carid;
var myurl= $('#posurl_update').val();
var csrf = $('#csrfhashresarvation').val();
$.ajax({
type: "post",
async: false,
url: myurl,
data: {product_name: product_name,category_id:category_id,isuptade:1,csrf_test_name:csrf},
success: function(data) {
if (data == '420') {
$("#product_search_update").html(lang.Product_not_found);
}else{
$("#product_search_update").html(data);
}
},
error: function() {
alert(lang.req_failed);
}
});
}
//Product search button js
$('body').on('click', '#search_button', function() {
var product_name = $('#update_product_name').val();
var category_id = $('#category_id').val();
var myurl= $('#posurl_update').val();
var csrf = $('#csrfhashresarvation').val();
$.ajax({
type: "post",
async: false,
url: myurl,
data: {product_name: product_name,category_id:category_id,csrf_test_name:csrf},
success: function(data) {
if (data == '420') {
$("#product_search_update").html(lang.Product_not_found);
}else{
$("#product_search_update").html(data);
}
},
error: function() {
alert(lang.req_failed);
}
});
});

//Payment method toggle
$(document).ready(function(){
if(orderinfo.isthirdparty>0){
$("#nonthirdparty_update").hide();
$("#thirdparty_update").show();
$("#delivercom_update").prop('disabled', false);
$("#waiter_update").prop('disabled', true);
$("#tableid_update").prop('disabled', true);
$("#cardarea_update").show();
} else{
if(orderinfo.cutomertype==4){
$("#nonthirdparty_update").show();
$("#thirdparty_update").hide();
$("#tblsec_update").hide();
$("#delivercom_update").prop('disabled', true);
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', true);
$("#cardarea_update").hide();
}else if(orderinfo.cutomertype==2){
$("#nonthirdparty_update").show();
$("#thirdparty_update").hide();
$("#tblsec_update").hide();
$("#delivercom_update").prop('disabled', true);
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', true);
$("#cardarea_update").hide();
} else{
$("#nonthirdparty_update").show();
$("#tblsec_update").show();
$("#thirdparty_update").hide();
$("#delivercom_update").prop('disabled', true);
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', false);
$("#cardarea_update").hide();
} }

$(".payment_button").click(function(){
$(".payment_method").toggle();
//Select Option
$("select.form-control:not(.dont-select-me)").select2({
placeholder: "Select option",
allowClear: true
});
});

$("#card_typesl").on('change', function(){
var cardtype=$("#card_typesl").val();

$("#card_type").val(cardtype);
if(cardtype==4){
$("#isonline").val(0);
$("#cardarea").hide();
$("#assigncard_terminal").val('');
$("#assignbank").val('');
$("#assignlastdigit").val('');
}
else if(cardtype==1){
$("#isonline").val(0);
$("#cardarea").show();
}
else{
$("#isonline").val(1);
$("#cardarea").hide();
$("#assigncard_terminal").val('');
$("#assignbank").val('');
$("#assignlastdigit").val('');
}

});
$("#ctypeid_update").on('change', function(){
var customertype=$("#ctypeid_update").val();
if(customertype==3){
$("#delivercom_update").prop('disabled', false);
$("#waiter_update").prop('disabled', true);
$("#tableid_update").prop('disabled', true);
$("#nonthirdparty_update").hide();
$("#thirdparty_update").show();
}
else if(customertype==4){
$("#nonthirdparty_update").show();
$("#thirdparty_update").hide();
$("#tblsec_update").hide();
$("#delivercom_update").prop('disabled', true);
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', true);
}
else if(customertype==2){
$("#nonthirdparty_update").show();
$("#tblsec_update").hide();
$("#thirdparty_update").hide();
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', false);
$("#cookingtime_update").prop('disabled', false);
$("#delivercom_update").prop('disabled', true);
}
else{
$("#nonthirdparty_update").show();
$("#tblsec_update").show();
$("#thirdparty_update").hide();
$("#delivercom_update").prop('disabled', true);
$("#waiter_update").prop('disabled', false);
$("#tableid_update").prop('disabled', false);
}
});
$('.update_search-field').select2({
placeholder: 'Select Product',
minimumInputLength: 1,
ajax: {
url: 'getitemlistdroup',
dataType: 'json',
delay: 250,
//data:{csrf_test_name:basicinfo.csrftokeng},
processResults: function (data) {
return {
results:  $.map(data, function (item) {
return {
text: item.text+'-'+item.variantName,
id: item.id+'-'+item.variantid
}
})
};
},
cache: true
}
});
});

function positemupdate(itemid,existqty,orderid,varientid,isgroup,auid,status){
$('#uvchange').val(1);
$(".maindashboard").addClass("disabled");
 $("#fhome").addClass("disabled");
 $("#kitchenorder").addClass("disabled");
 $("#todayqrorder").addClass("disabled");
 $("#todayonlieorder").addClass("disabled");
 $("#todayorder").addClass("disabled");
 $("#ongoingorder").addClass("disabled");
 var original_previousqty = $("#original_previousqty_"+auid).val();
if(status == 'del' && existqty==1){
 return false;
}
if(status == 'add'){
/*check production*/
var checkqty = existqty+1;
var checkstock = checkstockvalidity(itemid);
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
				 
var productionsetting = $('#production_setting').val();
if(productionsetting == 1){

var checkvalue = checkproduction(itemid,varientid,checkqty);
if(checkvalue == false){
return false;
}

}
/*end checking*/
}
var csrf = $('#csrfhashresarvation').val();
var dataString = "itemid="+itemid+"&existqty="+existqty+"&orderid="+orderid+"&varientid="+varientid+"&auid="+auid+"&status="+status+"&isgroup="+isgroup+'&original_previousqty='+original_previousqty+'&csrf_test_name='+csrf;
var myurl=basicinfo.baseurl+"ordermanage/order/itemqtyupdate";
$.ajax({
type: "POST",
url: myurl,
data: dataString,
success: function(data) {
$('#updatefoodlist').html(data);
//alert(original_previousqty);
$("#original_previousqty_"+auid).val(original_previousqty);
var total=$('#grtotal').val();
var totalitem=$('#totalitem').val();
$('#item-number').text(totalitem);
$('#getitemp').val(totalitem);
var tax=$('#tvat').val();
var floatax=parseFloat(tax);
var gettax=floatax.toFixed(2);

var discount=$('#tdiscount').val();
var tgtotal=$('#tgtotal').val();
$('#calvatup').text(tax);
$('#invoice_discount').val(discount);
var sc=$('#sc').val();

//$('#service_charge_update').val(sc);
if(basicinfo.isvatinclusive==1){
	var getvalue=parseFloat(tgtotal) - parseFloat(gettax);
	$('#gtotal_update').text(getvalue);  
  }else{
	  var getvalue=parseFloat(tgtotal).toFixed(2);
	$('#gtotal_update').text(getvalue);
  }
$('#grandtotal').val(getvalue);
$('#orggrandTotal').val(getvalue);
$('#orginattotal').val(getvalue);
//alert(sc);
var stotal=$('#subtotal_update').val();
var discount2=$('#invoice_discount_update').val();
/*if(basicinfo.service_chargeType==1){
	
}*/
//var sc2=$('#service_charge_update').val();
var tax2=$('#vat_update').val();

var allnumbv=tax2+'|'+discount2+'|'+sc+'|'+stotal+'|'+getvalue;
var encsting= window.btoa( allnumbv );
$('#udenc').val(encsting);
}
});
}
function updatebarcodeaddtocart(pid,sizeid,totalvarient,customqty,isgroup,catid,itemname,varientname,price,hasaddons,iswithoutproduction,isstockavail){
      var qty = 1;
      var csrf = $('#csrfhashresarvation').val();
		if(iswithoutproduction==1 && isstockavail==1){
		   			var isselected = $('#productionsetting-update-'+pid+'-'+sizeid).length;
                    if(isselected ==1 ){
                        var checkqty = parseInt($('#productionsetting-update-'+pid+'-'+sizeid).text())+qty;
                    }
                    else{
                        var checkqty = qty;
                    }
		  var checkstock = checkstockvalidity(pid);
		  if(checkstock>=0){
			  if(checkqty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
	      }
	   }
        var updateid=$("#saleinvoice").val();
        if(hasaddons==0 && totalvarient==1 && customqty==0){
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
        var mysound=baseurl+"assets/";
        var audio =["beep-08b.mp3"];
        new Audio(mysound + audio[0]).play();
        var myurl= $('#updatecarturl').val();
        var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+checkqty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&isgroup='+isgroup+'&orderid='+updateid+'&totalvarient='+totalvarient+'&customqty='+customqty+'&csrf_test_name='+csrf;
		
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
            
			 var geturl=$("#addonexsurl").val();
             var myurl =geturl+'/'+pid;
             var dataString = "pid="+pid+"&sid="+sizeid+"&id="+catid+"&totalvarient="+totalvarient+"&customqty="+customqty+'&csrf_test_name='+csrf;
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
  }
 $('body').on('change', '#updatecheckbarcode', function(e) {
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
         
      });
