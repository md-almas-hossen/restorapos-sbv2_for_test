// $("#adjusted_type").on("change", function() {
//     if ($(this).val()) {
//         $(this).prop("disabled", true);
//     }
// });


function calculate_reset(sl) {
    var adjtype = $("#adjusted_type").val();
    var currentstock= $("#current_stock_" + sl).val();
    var adjstock= $("#adjusted_stock_" + sl).val();
    var price= $("#price_" + sl).val();

    if(adjtype=='added'){
        var finalstock= parseFloat(currentstock)+parseFloat(adjstock);
    }
    if(adjtype=='reduce'){
        var finalstock= parseFloat(currentstock)-parseFloat(adjstock);
    }
    $('#final_stock_'+sl).val(finalstock);   
    $("#total_price_" + sl).val(adjstock*price);

}


function product_typesad(sl) {
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
      url: baseurl + "purchase/purchase/typewiseproducts",
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




























function product_listad(sl) {
  var supplier_id = $("#suplierid").val();
  var product_type = $("#product_type_" + sl).val();
  var product_id = $("#product_id_" + sl).val();
  var storage = ("box_" + sl);
  var conversionvalue = ("conversion_value_" + sl);
  var csrf = $("#csrfhashresarvation").val();
  var available_quantity = "current_stock_" + sl;
  var price = "price_" + sl;

  if (supplier_id == 0 || supplier_id == "") {
      alert("Please select Supplier !");
      return false;
  }

  url = baseurl + "purchase/purchase/stock_price";


  $.ajax({
      type: "POST",
      url: url,
      data: {
          product_type: product_type,
          product_id: product_id,
          csrf_test_name: csrf
      },
      cache: false,
      success: function(data) {

          obj = JSON.parse(data);

          $("#" + available_quantity).val(obj.total_purchase);
          $("#" + price).val(obj.price);

      },
  });
}
var count = 2;
var limits = 500;

/*
function addmore(divName) {
    var credit = $("#cntra").html();
    var types = $("#types").html();
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
            '" class="postform resizeselect form-control" onchange="product_typesad(' +
            count +
            ')">' +
            types +
            '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' +
            count +
            '" class="postform resizeselect form-control" onchange="product_listad(' +
            count +
            ')">' +
            credit +
            '</select></td><td class="wt"> <input type="text" id="current_stock_' +
            count +
            '" class="form-control text-right stock_ctn_' +
            count +
            '" placeholder="0.00" readonly/> </td><td class="text-right"><input type="number" step="0.0001" name="adjusted_stock[]" tabindex="' +
            tab2 +
            '" required="" id="adjusted_stock_' +
            count +
            '" class="form-control text-right adjusted_stock_' +
            count +
            '" onkeyup="calculate_reset(' +
            count +
            ');" onchange="calculate_reset(' +
            count +
            ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/><input type="hidden" name="conversion_value[]" id="conversion_value_'+count+'">  </td><td class="wt"> <input type="text" name="price[]" id="price_' +
            count +
            '" class="form-control text-right price_' +
            count +
            '" placeholder="0.00" readonly/> </td><td class="wt"> <input type="text" name="total_price[]" id="total_price_' +
            count +
            '" class="form-control text-right tp total_price_' +
            count +
            '" placeholder="0.00" /> </td><td class="test"><input type="number" step="0.0001" name="final_stock[]" readonly id="final_stock_' +
            count +
            '" class="form-control product_rate_' +
            count +
            ' text-right" placeholder="0.00" min="0" oninput="validity.valid||(value=\'\');" required="" value="" tabindex="' +
            tab3 +
            '"/></td><td> <input type="hidden" id="total_discount_1" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="adjustmentdeleteRow(this)" tabindex="8">Delete</button></td>';
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
  */

  function calculateTotal() {
    let total = 0;
    
    $('.tp').each(function() {
        const value = parseFloat($(this).val()) || 0; 
        total += value; 
    });
    
    console.log(total);
    $('#final_price').text(total.toFixed(2)); 
    $('#final_amount').val(total.toFixed(2)); 
}
setInterval(calculateTotal, 1000);

  function addmore(divName) {



    var credit = $("#cntra").html();
    var types = $("#types").html();
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
            '" class="postform resizeselect form-control" onchange="product_typesad(' +
            count +
            ')">' +
            types +
            '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' +
            count +
            '" class="postform resizeselect form-control" onchange="product_listad(' +
            count +
            ')">' +
            credit +
            '</select></td><td class="wt"> <input type="text" id="current_stock_' +
            count +
            '" class="form-control text-right stock_ctn_' +
            count +
            '" placeholder="0.00" readonly/> </td><td class="text-right"><input type="number" step="0.0001" name="adjusted_stock[]" tabindex="' +
            tab2 +
            '" required="" id="adjusted_stock_' +
            count +
            '" class="form-control text-right adjusted_stock_' +
            count +
            '" onkeyup="calculate_reset(' +
            count +
            ');" onchange="calculate_reset(' +
            count +
            ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/><input type="hidden" name="conversion_value[]" id="conversion_value_'+count+'">  </td><td class="wt"> <input type="text" name="price[]" id="price_' +
            count +
            '" class="form-control text-right price_' +
            count +
            '" placeholder="0.00" readonly/> </td><td class="wt"> <input type="text" name="total_price[]" id="total_price_' +
            count +
            '" class="form-control text-right tp total_price_' +
            count +
            '" placeholder="0.00" readonly/> </td><td class="test"><input type="number" step="0.0001" name="final_stock[]" readonly id="final_stock_' +
            count +
            '" class="form-control product_rate_' +
            count +
            ' text-right" placeholder="0.00" min="0" oninput="validity.valid||(value=\'\');" required="" value="" tabindex="' +
            tab3 +
            '"/></td><td> <input type="hidden" id="total_discount_1" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="adjustmentdeleteRow(this)" tabindex="8">Delete</button></td>';
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


//Calculate singleqty


/*
function adjusttype(sl){
    var adjtype = $("#adjusted_type").val();
    var currentstock= $("#current_stock_" + sl).val();
    var adjstock= $("#adjusted_stock_" + sl).val();
    if(adjtype=='added'){
        var finalstock= parseFloat(currentstock)+parseFloat(adjstock);
    }
    if(adjtype=='reduce'){
        var finalstock= parseFloat(currentstock)-parseFloat(adjstock);
    }
    $('#final_stock_'+sl).val(finalstock);
}
*/




function adjustmentdeleteRow(e) {
  var t = $("#purchaseTable > tbody > tr").length;
  if (1 == t) alert(lang.cantdel);
  else {
      var a = e.parentNode.parentNode;
      a.parentNode.removeChild(a);
  }
}

function verifyinvoice() {
  var invoiceno = $("#invoice_no").val();
  var csrf = $("#csrfhashresarvation").val();
  var url = basicinfo.baseurl + "purchase/purchase/checkinvoiceno";
  $.ajax({
      type: "POST",
      url: url,
      data: {
          csrf_test_name: csrf,
          invoiceno: invoiceno
      },
      success: function(data) {
          if (data == 1) {
              //swal("Success", "Successfully Deleted!!", "success");
          } else {
              $("#invoice_no").val("");
              swal("Invalid", "Invoice No. Already Exists!!", "warning");
          }
      },
  });



//   $(document).ready(function() {

//     function calculateTotal() {
//         let total = 0;
        
//         $('.tp').each(function() {
//             const value = parseFloat($(this).val()) || 0; 
//             total += value; 
//         });
        
//         console.log(total);
//         $('#final_price').text(total.toFixed(2)); 
//     }

//     // Initial calculation
//     calculateTotal();

//     // Recalculate every second
//     setInterval(calculateTotal, 1000);
// });

}