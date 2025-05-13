var row = $("#purchaseTable tbody tr").length;
var count = row + 1;
var limits = 500;
function product_types(sl) {
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
    data: { product_type: product_type, csrf_test_name: csrf },
    cache: false,
    success: function (data) {
      $("#product_id_" + sl).html(data);
    },
  });
}
function product_list(sl) {
  var supplier_id = $("#suplierid").val();
  var product_id = $("#product_id_" + sl).val();
  var storage = ("box_" + sl);
  var conversionvalue = ("conversion_value_" + sl);
  var geturl = $("#url").val();
  var csrf_token = $("#setcsrf").val();
  var csrfname = $("#csrfname").val();
  var csrf = $("#csrfhashresarvation").val();
  var available_quantity = "available_quantity_" + sl;
  if (supplier_id == 0 || supplier_id == "") {
    alert("Please select Supplier !");
    return false;
  }

  $.ajax({
    type: "POST",
    url: baseurl + "purchase/purchase/purchasequantity",
    data: { product_id: product_id, csrf_test_name: csrf },
    cache: false,
    success: function (data) {
      console.log(data);
      obj = JSON.parse(data);
      $("#" + available_quantity).val(obj.total_purchase);
      $("#" + conversionvalue).val(obj.conversion_unit);
      $("#" + storage).val(obj.storageunit);
    },
  });
}

function addmore(divName) {
  var credit = $("#cntra").html();
  var types = $("#types").html();
  var vatypes = $("#vattypes").html();
  var nowDate = new Date();
  var nowDay =
    nowDate.getDate().toString().length == 1
      ? "0" + nowDate.getDate()
      : nowDate.getDate();
  var nowMonth =
    nowDate.getMonth().toString().length == 1
      ? "0" + (nowDate.getMonth() + 1)
      : nowDate.getMonth() + 1;
  var nowYear = nowDate.getFullYear();
  var assigndate = nowYear + "-" + nowMonth + "-" + nowDay;
  var row = $("#purchaseTable tbody tr").length;
  var count = row + 1;
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
      '" class="postform resizeselect form-control" onchange="product_types(' +
      count +
      ')">' +
      types +
      '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' +
      count +
      '" class="postform resizeselect form-control" onchange="product_list(' +
      count +
      ')">' +
      credit +
      '</select></td><td><input type="text" name="expriredate[]" id="expriredate_' +
      count +
      '" class="form-control expriredate_' +
      count +
      ' datepicker5" value="' +
      assigndate +
      '" tabindex="7" required="" readonly=""></td><td class="wt"> <input type="text" id="available_quantity_' +
      count +
      '" class="form-control text-right stock_ctn_' +
      count +
      '" placeholder="0.00" readonly/> </td><td class="text-right"><input type="number" step="0.0001" name="storage_quantity[]" tabindex="' +
      tab2 +
      '" required=""  id="box_' +
      count +
      '" class="form-control text-right storage_cal_' +
      count +
      '" onkeyup="calculate_singleqty(' +
      count +
      ');" onchange="calculate_singleqty(' +
      count +
      ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/><input type="hidden" name="conversion_value[]" id="conversion_value_'+count+'">  </td><td class="text-right"><input type="number" step="0.0001" name="product_quantity[]" tabindex="' +
      tab2 +
      '" required=""  id="cartoon_' +
      count +
      '" class="form-control text-right product_quantity store_cal_' +
      count +
      '" onkeyup="calculate_store('+count+');calculate_storageqty(' +
      count +
      ');" onchange="calculate_store('+count+');calculate_storageqty(' +
      count +
      ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/>  </td><td class="test"><input type="number" step="0.0001" name="product_rate[]" onkeyup="calculate_store(' +
      count +
      ');" onchange="calculate_store(' +
      count +
      ');" id="product_rate_' +
      count +
      '" class="form-control product_rate_' +
      count +
      ' text-right" placeholder="0.00" required="" min="0" oninput="validity.valid||(value=\'\');" value="" tabindex="' +
      tab3 +
      '"/></td><td class="text-right"><input type="number" step="0.00001" onkeyup="calculate_store('+ count +');" onchange="calculate_store('+ count +');" name="purchase_price[]" id="purchase_price_'+ count +'" class="form-control purchase_price_'+ count +' text-right" placeholder="0.00" value="" min="0.00" tabindex="7" oninput="validity.valid||(value=\'\');"></td><td><input type="number" step="0.0001" name="product_vat[]" onkeyup="calculate_store(' +
      count +
      ');" onchange="calculate_store(' +
      count +
      ');" id="product_vat_' +
      count +
      '" class="form-control product_vat_' +
      count +
      ' text-right" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');" tabindex="8"></td><td><select name="vat_type[]" id="vat_type_' +
      count +
      '" class="form-control vattype" onkeyup="calculate_store(' +
      count +
      ');" onchange="calculate_store(' +
      count +
      ');" >' +
      vatypes +
      '</select></td><td class="text-right"><input class="form-control total_price text-right total_price_' +
      count +
      '" type="text" name="total_price[]" id="total_price_' +
      count +
      '" value="0.00" readonly="readonly" /> </td><td> <input type="hidden" id="total_discount_1" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8"><svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button></td>';
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
function calculate_singleqty(sl) {
  var conversionvalue = $("#conversion_value_" + sl).val();
  var storageqty = $("#box_" + sl).val();  
  if(storageqty>0){  
      var totalqty=conversionvalue*storageqty;
      var unitprice=$("#product_rate_"+sl).val();
      if(unitprice=='' || unitprice==0 ||  unitprice==0.00){
        unitprice=0;
      }
      $("#cartoon_"+sl).val(totalqty);
      $("#purchase_price_" + sl).val(unitprice*totalqty);
      $("#total_price_" + sl).val(unitprice*totalqty);
  }else{
      $("#cartoon_"+sl).val('');
  }

  calculate_store(sl);
  
}

//Calculate storageqty
function calculate_storageqty(sl) {
  var conversionvalue = $("#conversion_value_" + sl).val();
  var singleqty = $("#cartoon_" + sl).val();  
  if(singleqty>0){  
      var totalstorageqty=singleqty/conversionvalue;
      $("#box_"+sl).val(totalstorageqty);
  }
  else{
      $("#box_"+sl).val('');
  }

  calculate_store(sl);
}

//Calculate store product
function calculate_store(sl) {
  var gr_tot = 0;
  var item_ctn_qty = $("#cartoon_" + sl).val();
  var vendor_rate = $("#product_rate_" + sl).val();
  var itemvat = $("#product_vat_" + sl).val();
  var vattype = $("#vat_type_" + sl).val();
  var purchase_price = $("#purchase_price_" + sl).val();

  if (itemvat == "") {
    itemvat = 0;
  }
  if (vattype == "") {
    vattype = 1;
  }


  // new codes by MKar starts...
    $("#product_rate_"+sl).on("click", function () {
        $("#purchase_price_"+sl).val(null);
    });

    if ((!(purchase_price) > 0) || (purchase_price == 0.00) ) {
  // new codes by MKar ends...



    var total_price = item_ctn_qty * vendor_rate;

    if (vattype == 0) {
      if (total_price > 0) {
        var vatwithprice = parseFloat(itemvat) + parseFloat(total_price);
        var netvat = itemvat;
      } else {
        var vatwithprice = 0;
        var netvat = 0;
      }
    } else {
      var netvat = (parseFloat(total_price) * parseFloat(itemvat)) / 100;
      var vatwithprice = parseFloat(netvat) + parseFloat(total_price);
    }

    $("#total_price_" + sl).val(vatwithprice.toFixed(2));
  } else {
    var rate_price = purchase_price / item_ctn_qty;

    if (vattype == 0) {
      if (rate_price > 0) {
        var vatwithprice = parseFloat(rate_price);
        var netvat = itemvat;
        var total_price = parseFloat(netvat) + parseFloat(purchase_price);
      } else {
        var vatwithprice = 0;
        var netvat = 0;
      }
    } else {
      var netvat = (parseFloat(purchase_price) * parseFloat(itemvat)) / 100;
      var vatwithprice = parseFloat(rate_price);
      var total_price = parseFloat(netvat) + parseFloat(purchase_price);
    }

    $("#product_rate_" + sl).val(vatwithprice.toFixed(2));
    $("#total_price_" + sl).val(total_price.toFixed(2));
  }

  //Total Price
  var vatamount = 0;
  $(".total_price").each(function () {
    var priceid = $(this).attr("id");
    var idstring = priceid.split("_");
    var allitemvat = $("#product_vat_" + idstring[2]).val();
    var allvatype = $("#vat_type_" + idstring[2]).val();
    var eatchqty = $("#cartoon_" + idstring[2]).val();
    var eatchrate = $("#product_rate_" + idstring[2]).val();
    if (allitemvat == "") {
      allitemvat = 0;
    }
    if (allvatype == "") {
      allvatype = 1;
    }
    var calceachprice = eatchqty * eatchrate;
    if (allvatype == 0) {
      var allnetvat = allitemvat;
    } else {
      var allnetvat =
        (parseFloat(calceachprice) * parseFloat(allitemvat)) / 100;
    }
    vatamount += parseFloat(allnetvat);
    isNaN(this.value) ||
      0 == this.value.length ||
      (gr_tot += parseFloat(this.value));
  });
  console;
  $("#vatamount").val(vatamount);

  $("#subtotal").val(gr_tot.toFixed(2, 2));
  var subtotal = $("#subtotal").val();
  var vattotal = $("#vatamount").val();
  var discount = $("#discount").val();
  var labourcost = $("#labourcost").val();
  var transpostcost = $("#transpostcost").val();
  var othercost = $("#othercost").val();

  if (vattotal == "") {
    vattotal = 0;
  }
  if (discount == "") {
    discount = 0;
  }
  if (labourcost == "") {
    labourcost = 0;
  }
  if (transpostcost == "") {
    transpostcost = 0;
  }
  if (othercost == "") {
    othercost = 0;
  }
  var total =
    parseFloat(gr_tot) +
    parseFloat(labourcost) +
    parseFloat(transpostcost) +
    parseFloat(othercost);
  var grandtotal = parseFloat(total) - parseFloat(discount);
  //var grandtotal=totalexdis.toFixed(2);
  $("#grandTotal").val(grandtotal.toFixed(2, 2));
}
function purchasetdeleteRow(e) {
  var t = $("#purchaseTable > tbody > tr").length;
  if (1 == t) alert(lang.cantdel);
  else {
    var a = e.parentNode.parentNode;
    a.parentNode.removeChild(a);
  }
  calculate_store();
}
function bank_paymet(id) {
  var csrf = $("#csrfhashresarvation").val();
  var dataString = "bankid=" + id + "&status=1&csrf_test_name=" + csrf;
  if (id == 2) {
    $("#showbank").show();
    $("#bankid").attr("required", true);
    $.ajax({
      url: baseurl + "purchase/purchase/banklist",
      dataType: "json",
      type: "POST",
      data: dataString,
      async: true,
      success: function (data) {
        var options = data.map(function (val, ind) {
          return $("<option></option>").val(val.bankid).html(val.bank_name);
        });
        $("#bankid").append(options);
      },
    });
  } else {
    $("#showbank").hide();
    $("#bankid").attr("required", false);
  }

  // If Payment Type is not Due which is 3
  if(id == 3){
    
      // $("#paidamount").prop("readonly", true);
      // var grandTotal = parseFloat($("#grandTotal").val());
      // if(grandTotal > 0){
      //   $("#paidamount").val(0);
      //   $("#dueTotal").val(grandTotal);
        
      // }

      $("#paidamount").val(0).trigger('keyup');
      $("#paidamount").prop("readonly", true);
      $("#discount").val(0).trigger('keyup');
      $("#discount").prop("readonly", true);

  }else{
     $("#paidamount").prop("readonly", false);
     $("#discount").prop("readonly", false);
  }

}
$(document).on(
  "keyup",
  "#vatamount,#discount,#labourcost,#transpostcost,#othercost,#paidamount",
  function () {
    var subtotal = $("#subtotal").val();
    var vattotal = $("#vatamount").val();
    var discount = $("#discount").val();
    var labourcost = $("#labourcost").val();
    var transpostcost = $("#transpostcost").val();
    var othercost = $("#othercost").val();
    var paid = $("#paidamount").val();

    if (vattotal == "") {
      vattotal = 0;
    }
    if (discount == "") {
      discount = 0;
    }
    if (labourcost == "") {
      labourcost = 0;
    }
    if (transpostcost == "") {
      transpostcost = 0;
    }
    if (othercost == "") {
      othercost = 0;
    }
    if (paid == "") {
      paid = 0;
    }
    var total =
      parseFloat(subtotal) +
      parseFloat(labourcost) +
      parseFloat(transpostcost) +
      parseFloat(othercost);
    var totalexdis = parseFloat(total) - parseFloat(discount);
    var grandtotal = totalexdis.toFixed(2);
    if (parseFloat(paid) >= parseFloat(grandtotal)) {
      var due = 0;
    } else {
      var due = grandtotal - parseFloat(paid).toFixed(2);
    }
    $("#grandTotal").val(grandtotal);
    $("#dueTotal").val(due);
  }
);
function verifyinvoice() {
  var invoiceno = $("#invoice_no").val();
  var purchaseid = $("#purID").val();
  var csrf = $("#csrfhashresarvation").val();
  var url = basicinfo.baseurl + "purchase/purchase/checkinvoiceno";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      csrf_test_name: csrf,
      invoiceno: invoiceno,
      purchaseid: purchaseid,
    },
    success: function (data) {
      if (data == 1) {
        //swal("Success", "Successfully Deleted!!", "success");
      } else {
        var oldinv = $("#oldinvoice").val();
        $("#invoice_no").val(oldinv);
        swal("Invalid", "Invoice No. Already Exists!!", "warning");
      }
    },
  });
}

// As per new disscussion for accounting integration... will not allow to submit for cash, bank paymwnt if not fully paid 
$("#add_purchase").click(function(event) {

  var payment_type = parseInt($("#payment_type").val());
  if(payment_type != 3){

      var paidamount = parseFloat($("#paidamount").val());
      var grand_total = parseFloat($("#grandTotal").val());
      // console.log(grand_total);
      
      if(grand_total <= 0){
          event.preventDefault(); // Stop form submission
          swal("Invalid", "Please select product first !", "warning");
          return false;
      }
      if(paidamount != grand_total){
          event.preventDefault(); // Stop form submission
          // swal("Invalid", "Paid amount is not equal to grand total !", "warning");

          swal({
            title: "Invalid",
            text: "Paid amount is not equal to grand total !",
            type: "warning",
            // showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: lang.cancel,
            closeOnConfirm: true
        },
        function(isConfirm) {
            if (isConfirm) {
                setTimeout(() => {
                    $("#paidamount").focus();
                }, 3); // Short delay ensures the element is focusable
            }
        });
        
      }
  }
});