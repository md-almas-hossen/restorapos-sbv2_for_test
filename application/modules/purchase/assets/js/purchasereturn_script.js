function getinvoice(){
	 var geturl=$("#invoiceurl").val();
	 var suplierid=$("#supplier_id").val();
	 var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+suplierid+'&status=1&csrf_test_name='+csrf;

		 $.ajax({
		 type: "POST",
		 url: geturl,
		 data: dataString,
		 success: function(data) {
			 $('#invoicelist').html(data);
			 $('select#invoice').val(null).trigger('change');
			 $("select.form-control:not(.dont-select-me)").select2({
				  placeholder: "Select option",
				  allowClear: true
				});
		 } 
	});
	
	}
	
function showinvoice(){
	 var geturl=$("#serachurl").val();
	 var invoice=$("#invoice").val();
	 var csrf = $('#csrfhashresarvation').val();
	 var myurl= geturl;
	    var dataString = "invoice="+invoice+'&status=1&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#itemlist').html(data);
			  $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd"
    }); 
		 } 
	});
	
	}

  $("body").on("click", "#full-invoice-return", function () {
        
    if ($("#full-invoice-return").is(":checked")) {
      var length= $('tr', '#addinvoiceItem').length;
      $("#full-invoice-return").attr("value", "1");
          for(var i=1;i<=length;i++){
              var prev=$("#orderqty_"+i).val();
             $("#quantity_"+i).val(prev);
              calculate_store(i);
          }
    } else {
        $("#full-invoice-return").attr("value", "0");
        var today= $('tr', '#addinvoiceItem').length;
        for(var i=1;i<=today;i++){
            calculate_store(i);
          //    var prev=$("#orderqty_"+i).val();
             $("#quantity_"+i).val("0");
             $("#total_price_" + i).val("0.00");
        }
        // var servicecharge = $("#servicecharge_id").val();
        // var grandTotal = $("#grandTotal").val();
        // if(servicecharge<grandTotal){
        //  var servicecharge=0;
        // }
        // $('#servicecharge_id').val('0');
        // var servicecharge = $("#servicecharge_id").val();
        // var grandTotalddd = grandTotal - servicecharge;
        // $("#grandTotal").val(grandTotalddd.toFixed(3));

        

    }
  });


function calculate_store(sl) {
       
        var gr_tot = 0;
        var item_ctn_qty    = $("#quantity_"+sl).val();
        var vendor_rate = $("#product_rate_"+sl).val();
        var discount    = $("#discount_"+sl).val();
        // console.log(discount);
        var price     = (item_ctn_qty * vendor_rate) - discount;
        var vat= $('#vat_'+sl).val();
        var vattype= $('#vattype_'+sl).val();
        // var total_price =  price+ +vat;
        // alert(vattype);
        if(vattype==1){
          var tv=  (item_ctn_qty*vendor_rate)*vat/100;
        }else{
          var tv=  vat;
        }
        var   total_price =parseFloat(price)+parseFloat(tv);
        

        // alert(total_price);
        
        // return false;
        //  var total_price
        $("#vat_amount_"+sl).val(tv);
        $("#total_price_"+sl).val(total_price.toFixed(2));
        var vat_amount=0;
        $(".vat_amount").each(function () {
          isNaN(this.value) || 0 == this.value.length || (vat_amount += parseFloat(this.value))
        });

        $("#total_vat").val(vat_amount.toFixed(2,2));

        var discount_amount=0;
          $(".discount_amount").each(function () {
          isNaN(this.value) || 0 == this.value.length || (discount_amount += parseFloat(this.value))
        });
        $("#total_discount").val(discount_amount.toFixed(2,2));
        //Total Price
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#grandTotal").val(gr_tot.toFixed(2,2));
    }
 function checkboxcheck(sl){
        var check_id    ='check_id_'+sl;
        var total_qntt  ='quantity_'+sl;
        var product_rate ='product_rate_'+sl;
        var store_id ='store_id_'+sl;
        var product_id ='product_id_'+sl;
       var grandTotal ='grandTotal';
        if($('#'+check_id).prop("checked") == true){
            document.getElementById(total_qntt).setAttribute("required","required");
            document.getElementById(total_qntt).setAttribute("name","total_qntt[]");
            document.getElementById(product_rate).setAttribute("name","product_rate[]");
            document.getElementById(product_id).setAttribute("name","product_id[]");
            document.getElementById(grandTotal).setAttribute("name","grand_total_price");
        }
        else if($('#'+check_id).prop("checked") == false){
            document.getElementById(total_qntt).removeAttribute("required");
            document.getElementById(total_qntt).removeAttribute("name");
            document.getElementById(product_id).removeAttribute("name");
            document.getElementById(product_rate).removeAttribute("name");
             document.getElementById(grandTotal).removeAttribute("name");
        }
    };
 function checkrequird(sl) {
   var  quantity=$('#total_qntt_'+sl).val();
   var check_id    ='check_id_'+sl;
    if (quantity == null || quantity == 0 || quantity == ''){
    document.getElementById(check_id).removeAttribute("required");
    }else{
       
         document.getElementById(check_id).setAttribute("required","required");
    }
}
 function checkqty(sl)
{
   var order_qty = $('#orderqty_'+sl).val();
  var quant=$('#quantity_'+sl).val();
  var vendor_rate =$("#product_rate_"+sl).val();
  var discount    = $("#discount_"+sl).val();
  var total_price     = (quant * vendor_rate)-discount;
  var diductprice= total_price.toFixed(2);  
  var grtotal=$("#grandTotal").val();
  
  if (isNaN(quant)) 
  {
    alert("Must input numbers");
    document.getElementById("quantity_"+sl).value = '';
    document.getElementById("total_price_"+sl).value = '';
    return false;
  }
  if (parseFloat(quant) <= 0) 
  {
    alert("You Can Not Return Less than 0");
      document.getElementById("quantity_"+sl).value = '';
        document.getElementById("total_price_"+sl).value = '';
    return false;
  }
  if (parseFloat(quant) > parseFloat(order_qty)) 
  {
	   var diductprice= total_price.toFixed(2);  
       var grtotal=$("#grandTotal").val();
	   if(grtotal>0){
	   var restprice=parseFloat(grtotal)-parseFloat(diductprice);
	   $("#grandTotal").val(restprice.toFixed(2));
	   }
     
	 setTimeout(function(){
	   alert("You Can Not return More than Order quantity");
	   document.getElementById("quantity_"+sl).value = '';
	   document.getElementById("total_price_"+sl).value = '';
      }, 500);
    return false;
  }
  
}
function bank_paymet(id){
			var csrf = $('#csrfhashresarvation').val();
		    var dataString = 'bankid='+id+'&status=1&csrf_test_name='+csrf;
		if(id==2){
			$("#showbank").show();
			$('#bankid').attr('required', true);   
        	$.ajax({
			 url: baseurl+"purchase/purchase/banklist",
			 dataType:'json',
			  type: "POST",
			  data: dataString,
			  async:true,
			  success: function(data) {
					var options = data.map(function(val, ind){
    					return $("<option></option>").val(val.bankid).html(val.bank_name);
					});
					$('#bankid').append(options);
				  }

		   });
		}
		else{
			$("#showbank").hide();
			$('#bankid').attr('required', false);  
			}
	}	

  	//Calculate singleqty
function calculate_singleqty(sl) {
  var conversionvalue = $("#conversion_id_" + sl).val();
  var storageqty = $("#storagequantity_" + sl).val();  
  if(storageqty>0){  
      var totalqty=conversionvalue*storageqty;
      $("#quantity_"+sl).val(totalqty);
  }else{
      $("#quantity_"+sl).val('');
  }
calculate_store(sl);
}

//Calculate storageqty
function calculate_storageqty(sl) {
  var conversionvalue = $("#conversion_id_" + sl).val();
  var singleqty = $("#quantity_" + sl).val();  
  if(singleqty>0){  
      var totalstorageqty=singleqty/conversionvalue;
      $("#storagequantity_"+sl).val(totalstorageqty);
  }
  else{
      $("#storagequantity_"+sl).val('');
  }
}