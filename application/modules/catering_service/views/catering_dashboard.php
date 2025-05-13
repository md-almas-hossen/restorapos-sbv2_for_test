<!-- fullcalendar css -->
<link href="<?php echo base_url('application/modules/catering_service/assets/fullcalendar/fullcalendar.min.css')?>"
    rel="stylesheet" type="text/css" />
<!-- fullcalendar print css -->
<link
    href="<?php echo base_url('application/modules/catering_service/assets/fullcalendar/fullcalendar.print.min.css');?>"
    rel="stylesheet" media="print" type="text/css" />
<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_service.css')?>"
    rel="stylesheet" type="text/css" />

<script src="<?php echo base_url('application/modules/catering_service/assets/fullcalendar/lib/moment.min.js');?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/catering_service/assets/fullcalendar/fullcalendar.min.js');?>"
    type="text/javascript"></script>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-bd rounded-34">
                <div class="panel-px c-header">
                    <h3 class="c-header m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 39 38" fill="none">
                            <path
                                d="M23.2 37H15.8C8.82321 37 5.33483 37 3.1674 34.787C1 32.5741 1 29.0123 1 21.8889V18.1111C1 10.9877 1 7.42595 3.1674 5.21297C5.33483 3 8.82321 3 15.8 3H23.2C30.1767 3 33.6653 3 35.8325 5.21297C38 7.42595 38 10.9877 38 18.1111V21.8889C38 29.0123 38 32.5741 35.8325 34.787C34.6241 36.0208 33.0052 36.5667 30.6 36.8083"
                                stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M10 3V1" stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M29 3V1" stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M37 13H28H17.1538M1 13H8.15385" stroke="#019868" stroke-width="2"
                                stroke-linecap="round" />
                            <path
                                d="M31 28C31 29.1046 29.8807 30 28.5 30C27.1193 30 26 29.1046 26 28C26 26.8954 27.1193 26 28.5 26C29.8807 26 31 26.8954 31 28Z"
                                fill="#019868" />
                            <path
                                d="M31 20C31 21.1046 29.8807 22 28.5 22C27.1193 22 26 21.1046 26 20C26 18.8954 27.1193 18 28.5 18C29.8807 18 31 18.8954 31 20Z"
                                fill="#019868" />
                            <path
                                d="M22 28C22 29.1046 20.8807 30 19.5 30C18.1193 30 17 29.1046 17 28C17 26.8954 18.1193 26 19.5 26C20.8807 26 22 26.8954 22 28Z"
                                fill="#019868" />
                            <path
                                d="M22 20C22 21.1046 20.8807 22 19.5 22C18.1193 22 17 21.1046 17 20C17 18.8954 18.1193 18 19.5 18C20.8807 18 22 18.8954 22 20Z"
                                fill="#019868" />
                            <path
                                d="M12 28C12 29.1046 11.1046 30 10 30C8.89544 30 8 29.1046 8 28C8 26.8954 8.89544 26 10 26C11.1046 26 12 26.8954 12 28Z"
                                fill="#019868" />
                            <path
                                d="M12 20C12 21.1046 11.1046 22 10 22C8.89544 22 8 21.1046 8 20C8 18.8954 8.89544 18 10 18C11.1046 18 12 18.8954 12 20Z"
                                fill="#019868" />
                        </svg><span class="ps-10"> Order Calender</span>
                    </h3>

                    <button class="btn btn-success" onclick="add_order()">
                        <i class="ti-plus"></i><span>Add Order</span>
                    </button>

                </div>
                <div class="panel-body panel-px">
                    <!-- calender -->
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Modal -->
            <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog modal-inner modal_orderAdd" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 43 43" fill="none">
                        <path d="M25.547 25.5471L17.4527 17.4528M17.4526 25.5471L25.547 17.4528" stroke="#FA5B14"
                          stroke-width="3" stroke-linecap="round" />
                        <path
                          d="M41 21.5C41 12.3077 41 7.71136 38.1442 4.85577C35.2886 2 30.6923 2 21.5 2C12.3076 2 7.71141 2 4.85572 4.85577C2 7.71136 2 12.3077 2 21.5C2 30.6924 2 35.2886 4.85572 38.1443C7.71141 41 12.3076 41 21.5 41C30.6923 41 35.2886 41 38.1442 38.1443C40.0431 36.2455 40.6794 33.5772 40.8926 29.3"
                          stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
                      </svg>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Order</h4>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col-md-4 col-sm-6">
                          <div class="form-group position-relative">
                            <label for="customerName" class="fs_16 fw_500 mb_7">Customer Name</label>
                            <input type="email" class="form-control inputOrder" id="customerName"
                              placeholder="Jonathan Smith" />
                            <a href="#" class="addCust">
                              <i class="ti-plus"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                          <div class="form-group">
                            <label for="deliveryDate" class="fs_16 fw_500 mb_7">Delivery Date and Time</label>
                            <input type="text" class="form-control inputOrder" id="deliveryDate"
                              placeholder="12-07-2023 , 10:35 AM" />
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                          <div class="form-group">
                            <label for="deliveryDate" class="fs_16 fw_500 mb_7">Delivery Location</label>
                            <input type="text" class="form-control inputOrder" id="deliveryDate"
                              placeholder="Dhaka, Uttara" />
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                          <div class="form-group">
                            <label for="deliveryDate" class="fs_16 fw_500 mb_7">Order Type</label>
                            <input type="text" class="form-control inputOrder" id="deliveryDate" placeholder="" />
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                          <div class="form-group">
                            <label for="deliveryDate" class="fs_16 fw_500 mb_7">Serving Person</label>
                            <input type="text" class="form-control inputOrder" id="deliveryDate"
                              placeholder="20 Person" />
                          </div>
                        </div>
                      </div>
                    </form>

                    <div class="row mt_15">
                      <div class="col-md-12">
                        <div class="table-responsive table-scroll">
                          <table class="table table-bordered table-responsive orderTable mb_12">
                            <thead>
                              <tr>
                                <th>Category</th>
                                <th>Items Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr id="addRow">
                                <td class="col-sm-2">
                                  <input class="form-control minW-sm-150 addMain" type="text" value="Salad" />
                                </td>

                                <td class="col-sm-3">
                                  <input class="form-control minW-sm-150 addPrefer" type="text"
                                    value="Buffet Chicken Teheri" />
                                </td>
                                <td class="col-sm-2">
                                  <input class="form-control minW-sm-100 addCommon" type="text" value="10" />
                                </td>
                                <td class="col-sm-2">
                                  <input class="form-control minW-sm-100 addCommon" type="text" value="100" />
                                </td>
                                <td class="col-sm-2">
                                  <input class="form-control minW-sm-100 addCommon" type="text" value="1000" />
                                </td>
                                <td class="col-sm-1 text-center">
                                  <a href="#" class="d-flex deleteBtn crosser" onClick="deleteRow(this)"><i
                                      class="ti-trash d-block" aria-hidden="true">
                                    </i>
                                  </a>
                                </td>
                              </tr>

                            </tbody>
                          </table>
                        </div>
                        <div class="col-md-1 pull-right pe_0">
                          <button class="btn btn-primary pull-right addBtn">
                            <i class="ti-plus"></i>
                            <span class="">Add Items</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-body border_top">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="d-flex">
                          <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                            fill="none">
                            <path
                              d="M20 12.875V4.5625C20 2.59499 18.4051 1 16.4375 1H4.5625C2.59499 1 1 2.59499 1 4.5625V16.4375C1 18.4051 2.59499 20 4.5625 20H12.2812M20 12.875L12.2812 20M20 12.875H14.6562C13.3445 12.875 12.2812 13.9383 12.2812 15.25V20"
                              stroke="#019868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.92578 5.92603H15.0739" stroke="#019868" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" />
                            <path d="M5.92578 10.8518H10.8517" stroke="#019868" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" />
                          </svg>
                          <span class="fs_16 fw_500 text_note">Notes</span>
                        </div>
                        <textarea class="form-control form-note" rows="3"></textarea>
                      </div>
                      <div class="col-md-6 col-lg-4 col-lg-offset-2">
                        <div class="check-order">
                          <div class="">
                            <div>
                              <div class="calc_sub">
                                <div class="sub_totalable">Sub-Total</div>
                                <div class="fs_16 fw_500 product-total text-right">
                                  10,000.00
                                </div>
                              </div>
                              <div class="calc_sub">
                                <div class="sub_totalable">
                                  Delivery Charge
                                </div>
                                <div class="product-total text-right">
                                  <input type="text" class="form-control subtotal_input" id="customerName"
                                    value="100" />
                                </div>
                              </div>
                              <div class="calc_sub">
                                <div class="sub_totalable">
                                  Service Charge
                                </div>
                                <div class="product-total text-right">
                                  <input type="text" class="form-control subtotal_input" id="customerName"
                                    value="100" />
                                </div>
                              </div>
                              <div class="calc_sub">
                                <div class="sub_totalable">Others Charge</div>
                                <div class="product-total text-right">
                                  <input type="text" class="form-control subtotal_input" id="customerName"
                                    value="100" />
                                </div>
                              </div>
                              <div class="calc_sub">
                                <div class="sub_totalable">Tax</div>
                                <div class="product-total text-right">
                                  <input type="text" class="form-control subtotal_input" id="customerName"
                                    value="100" />
                                </div>
                              </div>
                              <div class="calc_sub">
                                <div class="sub_totalable">Grand-Total</div>
                                <div class="fs_16 fw_500 product-total text-right">
                                  10,000.00
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="d-flex flex-wrap align-items-end gap-4">
                      <div class="form-group mb-0 text-left neo_wd">
                        <label for="customerName" class="fs_16 fw_500 mb_7">Discount</label>
                        <input type="email" class="form-control inputOrder" id="customerName" value="20" />
                      </div>
                      <div class="form-group mb-0 text-left neo_wd">
                        <label for="customerName" class="fs_16 fw_500 mb_7">Payment Type</label>
                        <input type="email" class="form-control inputOrder" id="customerName" value="Card" />
                      </div>
                      <div class="form-group mb-0 text-left neo_wd">
                        <label for="customerName" class="fs_16 fw_500 mb_7">Paid Amount</label>
                        <input type="email" class="form-control inputOrder" id="customerName" value="200" />
                      </div>
                      <div class="form-group mb-0 text-left neo_wd">
                        <label for="customerName" class="fs_16 fw_500 mb_7">Due Amount</label>
                        <input type="email" class="form-control inputOrder" id="customerName" value="50" />
                      </div>
                      <button type="button" class="btn btn-print fw_500 btn_footer neo_wd" data-dismiss="modal">
                        Submit & Print
                      </button>
                      <button type="button" class="btn btn-submit fw_500 btn_footer neo_wd">
                        Submit Order
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="modal fade" id="OderInfo" tabindex="-1" role="dialog" aria-labelledby="OderInfo">
                <div class="modal-dialog modal-inner modal_orderAdd" role="document">
                    <div class="modal-content OderInfo_content">

                    </div>
                </div>
            </div>



        </div>
    </div>
</section>


<div id="orderdetailsp" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width:204mm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close printmodal" data-dismiss="modal">&times;</button>
                <strong>

                </strong>
            </div>
            <div class="modal-body orddetailspop"> </div>
        </div>
        <div class="modal-footer"> </div>
    </div>
</div>
<!-- canceled modal  -->
<div id="cancelord" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Order Cancel</strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label for="payments" class="col-sm-4 col-form-label">Order ID </label>
                                    <div class="col-sm-7 customesl">
                                        <span id="canordid"></span>
                                        <input name="mycanorder" id="mycanorder" type="hidden" value="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="canreason" class="col-sm-4 col-form-label">Cancel Reason</label>
                                    <div class="col-sm-7 customesl">
                                        <textarea name="canreason" id="canreason" cols="35" rows="3"
                                            class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-sm-11 pr-0">
                                        <button type="button" class="btn btn-success w-md m-b-5"
                                            id="calender_cancelreasons">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- payprint merge -->
<div id="payprint_marge" class="modal fade" role="dialog">
    <div class="modal-dialog modal-inner" id="modal-ajaxview" role="document"> </div>
</div>

<!-- <script src="assets/dist/js/dashboard.js" type="text/javascript"></script> -->
<!-- End Theme label Script
        =====================================================================-->
<script>
$('body').on('click', '#payprint_marge .modal-inner .modal-header.modal-header .close', function() {
    $(".modal_orderAdd").css("max-width", '1285px');
    $(".modal_orderAdd").css("max-width", 'width: 100%');
    $("#OderInfo").modal('show');
});

function createMargeorder(orderid, value = null) {
    $("#OderInfo").modal('hide');
    var url = basicinfo.baseurl + 'catering_service/cateringservice/showpaymentmodal/' + orderid;
    callback = function(a) {
        $("#modal-ajaxview").html(a);

        $('#get-order-flag').val('2');
    };
    if (value == null) {

        getAjaxModal(url);
    } else {
        getAjaxModal(url, callback);
    }
}


$('body').on('click', '#getdueinvoiceorder', function() {
    $("#getdueinvoiceorder").hide();
    var orderid = $("#get-order-id").val();
    var discountamount = $("#granddiscount").val();
    var discounttype = $("#discounttype").val();
    var discount = $("#discount").val();
    var discountnote = $("#discountnote").val();
    var is_duepayment = $("#is_duepayment").val();
    var url = basicinfo.baseurl + "ordermanage/order/dueinvoice2/" + orderid;
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            csrf_test_name: csrf,
            discountamount: discountamount,
            discounttype: discounttype,
            discount: discount,
            discounttext: discountnote,
            is_duepayment: is_duepayment
        },
        success: function(data) {
            // alert(data);return false;
            $('#payprint_marge').modal('hide');
            $('#lbid' + orderid).css("background", "#dbcdd2");
            var dtype = checkdevicetype();
            if (dtype == 1) {
                var url2 = "http://www.abc.com/invoice/due/" + orderid;
                window.open(url2, "_blank");
            } else {
                printRawHtml(data);
            }
            $("#getdueinvoiceorder").show();
        }
    });
});

function submitmultiplepay() {
    var thisForm = $('#paymodal-multiple-form');
    var inputval = parseFloat(0);
    var maintotalamount = $('#due-amount').text();
    var noinputvalue = 0;
    $(".checkpay").each(function() {
        var found = $(this).html();
        if (found != "") {
            noinputvalue += 1;
        }
    });
    var selecttab = $("#sltab li.active a").attr("data-select");
    if (noinputvalue == 0) {
        if (selecttab == 1) {
            var bank = $("#inputBank").val();
            var terminal = $("#inputterminal").val();
            var lastdigit = $("#lastdigit").val();
            if (bank == '') {
                alert(lang.bank_select);
                return false;
            }
            if (terminal == '') {
                alert(lang.bnak_terminal);
                return false;
            }
        }
        if (selecttab == 14) {
            var paymethod = $("#inputmobname").val();
            var mobileno = $("#mobno").val();
            var transid = $("#transid").val();
            if (paymethod == '') {
                alert(lang.sl_mpay);
                return false;
            }

        }
        paidvalue = parseFloat(maintotalamount);
        var test = '<input class="emptysl" name="paytype[]" data-total="' + paidvalue + '" type="hidden" value="' +
            selecttab + '" id="ptype_' + selecttab + '" /><input type="hidden" id="paytotal_' + selecttab +
            '"  name="paidamount[]" value="' + paidvalue + '">';
        $("#addpay_" + selecttab).append(test);
    }

    $(".emptysl").each(function() {
        var inputdata = parseFloat($(this).attr('data-total'));
        inputval = inputval + inputdata;

    });
    if (inputval < parseFloat(maintotalamount)) {

        setTimeout(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000

            };
            toastr.error(lang.pay_full, lang.error);
        }, 100);
        return false;
    }
    if (noinputvalue >= 1) {
        var card = 0;
        var mobilepay = 0;
        var errorcard = '';
        var mobileerror = '';
        $('input[name="paytype[]"]').map(function() {
            var pid = $(this).val();
            var amount = $(this).attr('data-total');
            if (pid == 1) {
                var bank = $("#inputBank").val();
                var terminal = $("#inputterminal").val();
                var lastdigit = $("#lastdigit").val();
                if (bank == '') {
                    errorcard += lang.bank_select;
                    card = 1;
                }
                if (terminal == '') {
                    errorcard += lang.bnak_terminal;
                    card = 1;
                }

            }
            if (pid == 14) {
                var paymethod = $("#inputmobname").val();
                var mobileno = $("#mobno").val();
                var transid = $("#transid").val();
                if (paymethod == '') {
                    mobileerror += lang.sl_mpay;
                    mobilepay = 14;
                }
            }

        });

        if (card == 1) {
            alert(errorcard);
            return false;
        }
        if (mobilepay == 14) {
            alert(mobileerror);
            return false;
        }
    }
    var formdata = new FormData(thisForm[0]);


    var orderid = formdata.get('orderid');

    $.ajax({
        type: "POST",
        url: basicinfo.baseurl + "catering_service/cateringservice/paymultiple",
        data: formdata,
        processData: false,
        contentType: false,
        success: function(data) {

            var value = $('#get-order-flag').val();
            if (value == 1) {
                setTimeout(function() {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'slideDown',
                        timeOut: 4000

                    };
                    toastr.success(lang.payment_taken_successfully, lang.success);
                    $('#payprint_marge').modal('hide');
                    $('#modal-ajaxview').empty();
                    if (prevsltab[0].id == "tallorder") {
                        $('#' + prevsltab[0].id).DataTable().ajax.reload();
                    } else {
                        prevsltab.trigger('click');
                    }
                }, 100);

            } else {
                $('#payprint_marge').modal('hide');
                $('#modal-ajaxview').empty();
                if (basicinfo.printtype != 1) {
                    printRawHtml(data);
                }
                if (prevsltab[0].id == "tallorder") {
                    $('#' + prevsltab[0].id).DataTable().ajax.reload();
                } else {
                    prevsltab.trigger('click');
                }





            }
        },

    });
}


$(document).on('click', '.aceptorcancels', function() {

    var ordid = $(this).attr("data-id");
    var paytype = $(this).attr("data-type");
    var csrf = $('#csrfhashresarvation').val();
    var dataovalue = 'orderid=' + ordid + '&csrf_test_name=' + csrf;
    var productionsetting = $('#production_setting').val();
    var message = "Are You Accept Or Reject this Order??";
    if (productionsetting == 1) {
        var check = true;
        $.ajax({
            type: "POST",
            url: basicinfo.baseurl + "ordermanage/order/checkstock",
            data: dataovalue,
            async: false,
            global: false,
            success: function(data) {
                if (data != 1) {
                    message = data;
                    return false;
                }
            }
        });
    }
    if (message != 'Are You Accept Or Reject this Order??') {
        $("#cancelord").modal({
            backdrop: 'static',
            keyboard: false
        }, 'show');
        $("#canordid").html(ordid);
        $("#mycanorder").val(ordid);
        $("#canreason").val(message);
        return false;
    }
    swal({
            title: "Order Confirmation",
            text: message,
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Accept",
            cancelButtonText: "Reject",
            closeOnConfirm: true,
            closeOnCancel: true,
            showCloseButton: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                // var dataString = 'status=1&acceptreject=1&reason=&orderid='+ordid+'&csrf_test_name='+csrf;
                //     $.ajax({
                //       type: "POST",
                //       url: basicinfo.baseurl+"ordermanage/order/acceptnotify",
                //       data: dataString,
                //       success: function(data){
                //         swal("Accepted", "Your Order is Accepted", "success");
                //         if(prevsltab[0].id=="tallorder"){
                //         $('#'+prevsltab[0].id).DataTable().ajax.reload();	
                //         }else{
                //             prevsltab.trigger('click');
                //         load_unseen_notification();
                //         } 
                //       }
                //     });
                swal("Accepted", "Your Order is Accepted", "success");
            } else {
                $("#cancelord").modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
                $("#canordid").html(ordid);
                $("#mycanorder").val(ordid);

            }
        });
});


$(document).on('click', '#calender_cancelreasons', function() {

    $("#cancelord").modal('hide');
    var ordid = $("#mycanorder").val();
    var reason = $("#canreason").val();
    var csrf = $('#csrfhashresarvation').val();
    var dataString = 'status=1&onprocesstab=1&acceptreject=0&reason=' + reason + '&orderid=' + ordid +
        '&csrf_test_name=' + csrf;

    $.ajax({
        type: "POST",
        url: basicinfo.baseurl + "catering_service/cateringservice/acceptnotify",
        data: dataString,
        success: function(data) {
            $("#onprocesslist").html(data);
            // if(prevsltab[0].id=="tallorder"){
            //   $('#'+prevsltab[0].id).DataTable().ajax.reload();	
            // }else{
            // //conlose.log(prevsltab);
            // prevsltab.trigger('click');
            // load_unseen_notification();
            // }

            swal("Rejected", "Your Order is Cancel", "success");
            var page_url2 = basicinfo.baseurl +
                "catering_service/cateringservice/catering_dashboard";
            // window.history.pushState('','',page_url2);
            document.location.href = page_url2;

        }
    });
});

function clander_cateringdetailspop(orderid) {

    var csrf = $('#csrfhashresarvation').val();
    var myurl = basicinfo.baseurl + 'catering_service/cateringservice/catering_orderdetails/' + orderid;
    var dataString = "orderid=" + orderid + '&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            // console.log(data);
            // $('.orddetailspop').html(data);
            // $('#orderdetailsp').modal('show');
            var dtype = checkdevicetype();
            if (dtype == 1) {
                var url2 = "http://www.abc.com/token/" + id;
                window.open(url2, "_blank");
            } else {
                printRawHtml(data);

            }
            // {backdrop: 'static', keyboard: true}
        }
    });

}


function add_order() {
    var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist?addorder=1";
    document.location.href = page_url2;
}


$(document).ready(function() {
    "use strict"; // Start of use strict

    /* initialize the calendar */

    $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "agendaDay,agendaWeek,month,agendaYears",
        },
        views: {
            agendaWeek: {
                type: "timeline",
                duration: {
                    weeks: 1
                },
                slotDuration: {
                    days: 7
                },
                buttonText: "Week",
            },
            agendaDay: {
                type: "timeline",
                slotDuration: {
                    days: 1
                },
                buttonText: "Day",
            },
            agendaYears: {
                type: "timeline",
                duration: {
                    years: 1
                },
                buttonText: "Year",
                slotDuration: {
                    months: 1
                },
            },
        },
        contentHeight: "auto",
        defaultDate: "<?php echo date("Y-m-d");?>",
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        events: '<?php echo base_url('catering_service/cateringservice/showcategringorderjs') ?>',
        selectable: true,
        editable: true,
        selectHelper: true,
        eventClick: function(calEvent, jsEvent, view) {
            // alert('Event: ' + calEvent.mydate);
            var csrf = $('#csrfhashresarvation').val();
            $.ajax({
                url: '<?php echo base_url('catering_service/cateringservice/catering_show_order') ?>',
                type: "POST",
                data: {
                    delivery_date: calEvent.mydate,
                    csrf_test_name: csrf
                },
                success: function(data) {
                    // console.log(data);
                    $('.OderInfo_content').html(data);
                    $("#OderInfo").modal('show');
                    //   $('.select2').select2();
                },
                error: function(xhr) {
                    alert('failed!');
                }
            });
        },
    });
});
</script>
<script>
jQuery("th.fc-agenda-axis").hide();
</script>
<script>
$('.dropdown-option li').on('click', function() {
    var getValue = $(this).text();
    $('.dropdown-select').text(getValue);
});
</script>