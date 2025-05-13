function setOpeningStockDate() {

    var csrf = $("#csrfhashresarvation").val();
    var fiyear_id = $('#fiyear_id').find("option:selected").val();
    console.log(fiyear_id);
    url = baseurl + "purchase/purchase/setOpeningStockDate";

    $.ajax({
        type: "POST",
        url: url,
        data: {
            fiyear_id: fiyear_id,
            csrf_test_name: csrf
        },
        cache: false,
        success: function(data) {
            obj = JSON.parse(data);
            $("#opening_stock_date").val(obj);
        },
    });

}



function product_types_openstock(sl) {
    var supplier_id = $("#suplierid").val();
    var csrf_token = $("#setcsrf").val();
    var csrfname = $("#csrfname").val();
    var csrf = $("#csrfhashresarvation").val();
    var product_type = $("#product_type_" + sl).val();
    if (supplier_id == 0 || supplier_id == "") {
        $("#product_type_" + sl).val("");
        alert("Please select Supplier !");
        return false;
    }
    $.ajax({
        type: "POST",
        url: baseurl + "purchase/purchase/purchaseitembytype",
        data: {
            product_type: product_type,
            csrf_test_name: csrf
        },
        cache: false,
        success: function(data) {
            $("#product_id_" + sl).html(data);
        },
    });
  }



  function getQtyStorage(sl){

    var conversion_value = $("#conversion_value_" + sl).val();
    var cartoon = $("#cartoon_"+sl).val();
    var box = $("#box_"+sl).val(cartoon/conversion_value);

   var total_price = $('#product_rate_'+sl).val()* $('#cartoon_'+sl).val();
    $('#total_product_rate_'+sl).val(total_price);

    getTotalPrice(sl);

    
  }


  function getTotalPrice(sl){
    var total_price = $('#product_rate_'+sl).val()* $('#cartoon_'+sl).val();
    $('#total_product_rate_'+sl).val(total_price);
  }



  var count = $('#actual_count').val();
  var limits = 500;
  
  function addmore(divName) {

   


    var credit = $("#cntra").html();
    var types = $("#types").html();
    var vatypes = $("#vattypes").html();
    var nowDate = new Date();
    var nowDay =
        nowDate.getDate().toString().length == 1 ?
        "0" + nowDate.getDate() :
        nowDate.getDate();
    var nowMonth =
        nowDate.getMonth().toString().length == 1 ?
        "0" + (nowDate.getMonth() + 1) :
        nowDate.getMonth() + 1;
    var nowYear = nowDate.getFullYear();
    var assigndate = nowYear + "-" + nowMonth + "-" + nowDay;
    if (count == limits) {
        alert("You have reached the limit of adding " + count + " inputs");
    } else {
        var newdiv = document.createElement("tr");
        var tabin = "product_id_" + count;
        (tabindex = count * 4), (newdiv = document.createElement("tr"));
        tab1 = tabindex + 1;
  
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tab5 + 1;
        tab7 = tab6 + 1;
  
        newdiv.innerHTML =
        '<td><select name="product_type[]" id="product_type_' +
        count +
        '" class="postform resizeselect form-control" onchange="product_types_openstock(' +
        count +
        ')">' +
        types +
        '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' +
        count +
        '" class="postform resizeselect form-control" onchange="product_item_select(' +
        count +
        ')" required>' +
        credit +
        '</select></td><td class="text-right"><input type="number" step="0.0001" name="storage_quantity[]" tabindex="' +
        tab2 +
        '" required=""  id="box_' +
        count +
        '" class="form-control text-right storage_cal_' +
        count +
        '" onkeyup="canculatevaluestorage(' +
        count +
        ');" onchange="canculatevaluestorage(' +
        count +
        ');" placeholder="0.00" value="" min="0" ><input type="hidden" name="conversion_value[]" id="conversion_value_'+count+'">  </td><td class="text-right"><input type="number" step="0.0001" name="product_quantity[]" tabindex="' +
        tab2 +
        '" required=""  id="cartoon_' +
        count +
        '" class="form-control text-right product_quantity store_cal_' +
        count +
        '" placeholder="0.00" value="" min="0" onkeyup="getQtyStorage('+
        count +')" onchnage="getQtyStorage('+
        count +')">  </td><td class="test"><input type="number" step="0.0001" name="product_rate[]" id="product_rate_' +
        count +
        '" class="form-control product_rate_' +
        count +
        ' text-right" placeholder="0.00" min="0" required="" value="0" tabindex="' +
        tab3 +
        '" onkeyup="getTotalPrice('+
        count +')"/></td><td class="test"><input type="number" step="0.0001" readonly name="total_product_rate[]" id="total_product_rate_' +
        count +
        '" class="form-control total_product_rate_' +
        count +
        ' text-right" placeholder="0.00" min="0" required="" value="" tabindex="' +
        tab3 +
        '" autocomplete="off"/></td>'+
        '<td>'+
        '<button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8">Delete</button></td>';
        
        document.getElementById(divName).appendChild(newdiv);
        document.getElementById(tabin).focus();
        document.getElementById("add_invoice_item").setAttribute("tabindex", tab5);
        document.getElementById("add_purchase").setAttribute("tabindex", tab6);
  
        count++;
        $(".datepicker5").datepicker({
            dateFormat: "yy-mm-dd",
        });
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true,
        });
    }
  }

	function canculatevaluestorage(sl){

		var totalpack= $("#conversion_value_"+sl).val();
		var storageqty= $("#box_"+sl).val();

		var totalbox=totalpack*storageqty;

		$("#cartoon_"+sl).val(totalbox);

        getTotalPrice(sl);
	}
  
    
    function product_item_select(sl) {

		var myurl =basicinfo.baseurl+"purchase/purchase/getconversion";
		var foodid=$("#product_id_"+sl).val();
		var csrf = $('#csrfhashresarvation').val();
		 var dataString = "csrf_test_name="+csrf+'&foodid='+foodid;
		  $.ajax({
		  type: "POST",
		  url: myurl,
		  data: dataString,
		  dataType: 'json',
		  success: function(data) {

            $("#box_"+sl).val(0);
            $("#cartoon_"+sl).val(0);

			 $("#conversion_value_"+sl).val(data.conversion);
		  } 
	 	});
	}
  
  function purchasetdeleteRow(e,id) {

    var t = $("#purchaseTable > tbody > tr").length;
    if (1 == t) alert(lang.cantdel);
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a);
    }


        var myurl = baseurl + 'purchase/Purchase/opening_stock_item_delete/'+id;

    
        $.ajax({
            type: "get",
            url: myurl,
            
            success: function(data) {
                
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });


    // calculate_store();
  }
  

