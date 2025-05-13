<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_service.css') ?>"
    rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/postop.js'); ?>" type="text/javascript">
</script>
<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js">
</script>

<script src="<?php echo base_url("ordermanage/order/showljslang"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('ordermanage/order/basicjs'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('ordermanage/order/possettingjs'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('ordermanage/order/quickorderjs'); ?>" type="text/javascript"></script>

<style>
ul.ui-menu.ui-widget.ui-widget-content.ui-autocomplete.ui-front {
    z-index: 9999;
}
</style>
<section class="catering_head_section d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="33" viewBox="0 0 25 33" fill="none">
            <path
                d="M15.4975 24.6963H7.39595C6.65023 24.6963 6.04572 25.3009 6.04572 26.0465C6.04572 26.7922 6.65029 27.3968 7.39595 27.3968H15.4975C16.2432 27.3968 16.8477 26.7922 16.8477 26.0465C16.8478 25.3009 16.2432 24.6963 15.4975 24.6963Z"
                fill="#019868" />
            <path
                d="M18.198 19.2954H7.39595C6.65023 19.2954 6.04572 19.9 6.04572 20.6456C6.04572 21.3913 6.65029 21.9959 7.39595 21.9959H18.198C18.9437 21.9959 19.5482 21.3913 19.5482 20.6456C19.5482 19.9 18.9437 19.2954 18.198 19.2954Z"
                fill="#019868" />
            <path
                d="M15.4975 13.8945H7.39595C6.65023 13.8945 6.04572 14.4991 6.04572 15.2448C6.04572 15.9904 6.65029 16.595 7.39595 16.595H15.4975C16.2432 16.595 16.8477 15.9904 16.8477 15.2448C16.8477 14.4991 16.2432 13.8945 15.4975 13.8945Z"
                fill="#019868" />
            <path
                d="M18.198 8.49316H7.39595C6.65023 8.49316 6.04572 9.09774 6.04572 9.84339C6.04572 10.5891 6.65029 11.1936 7.39595 11.1936H18.198C18.9437 11.1936 19.5482 10.5891 19.5482 9.84339C19.5482 9.09774 18.9437 8.49316 18.198 8.49316Z"
                fill="#019868" />
            <path
                d="M24.1157 0.494626C23.6115 0.285759 23.0304 0.401206 22.6442 0.787357L20.8986 2.53304L19.1529 0.787357C18.6256 0.260062 17.7707 0.260062 17.2433 0.787357L15.4975 2.53304L13.7518 0.787357C13.2246 0.260062 12.3696 0.260062 11.8423 0.787357L10.0965 2.53304L8.35083 0.787357C7.8236 0.260062 6.96864 0.260062 6.44128 0.787357L4.69553 2.53304L2.94972 0.787357C2.56357 0.401079 1.98279 0.285759 1.47822 0.494626C0.973646 0.70362 0.644775 1.19591 0.644775 1.74207V31.4476C0.644775 32.1933 1.24935 32.7978 1.99501 32.7978H23.599C24.3447 32.7978 24.9492 32.1932 24.9492 31.4476V1.74207C24.9493 1.19591 24.6204 0.70362 24.1157 0.494626ZM22.2488 30.0974H3.34524V5.00191L3.74076 5.39737C4.26799 5.92466 5.12295 5.92466 5.65031 5.39737L7.39599 3.65168L9.1418 5.39737C9.66903 5.92466 10.524 5.92466 11.0514 5.39737L12.797 3.65168L14.5429 5.39737C15.0701 5.92466 15.925 5.92466 16.4524 5.39737L18.1981 3.65168L19.9439 5.39737C20.4711 5.92466 21.3261 5.92466 21.8535 5.39737L22.2489 5.00191V30.0974H22.2488Z"
                fill="#019868" />
        </svg>

        <div class="">
            <h3 class="m-0">Order List</h3>
            <p class="mb-0">Here Your order List Data</p>
        </div>
    </div>
    <button class="btn btn-success" onclick="add_order()">
        <i class="ti-plus"></i><span>Add Order</span>
    </button>



</section>
<!-- <section class="content"> -->
<!-- order list  -->
<div class="panel panel-bd">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="form-inline ">
                <div class="form-group">
                    <label class="" for="from_date"><?php echo display('start_date') ?></label>
                    <input type="text" name="from_date" class="form-control datepicker5" id="from_date" value=""
                        placeholder="<?php echo display('start_date') ?>" readonly="readonly">
                </div>
                <div class="form-group">
                    <label class="" for="to_date"><?php echo display('end_date') ?></label>
                    <input type="text" name="to_date" class="form-control datepicker5" id="to_date"
                        placeholder="<?php echo "To"; ?>" value="" readonly="readonly">
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="filterordlist"><?php echo display('search') ?></button>
                    <button class="btn btn-warning" id="filterordlistrst"><?php echo display('reset') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive table_list">
            <table id="tallorder" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?php echo display('orderid'); ?></th>
                        <th><?php echo display('customer'); ?></th>
                        <th><?php echo display('phone'); ?></th>
                        <th><?php echo display('delivery_location'); ?></th>
                        <th><?php echo display('person'); ?></th>
                        <th><?php echo display('total'); ?> <?php echo display('amount'); ?></th>
                        <th><?php echo display('ordate'); ?></th>
                        <th><?php echo display('delv_date'); ?></th>
                        <th><?php echo display('status'); ?></th>
                        <th><?php echo display('action'); ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
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
                                            id="catering_cancelreason">Submit</button>
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
<!--order add Modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-modal-parent="#myModal">
    <div class="modal-dialog modal-inner modal_orderAdd" role="document">

        <div class="modal-content addcontent">

        </div>
    </div>
</div>
<!-- order edit modal  -->
<div class="modal fade" id="edit_myModal" role="dialog" aria-labelledby="myModalLabel"
    data-modal-parent="#edit_myModal">
    <div class="modal-dialog modal-inner modal_orderAdd" role="document">
        <div class="modal-content editcontent">

        </div>
    </div>
</div>
<!-- customer modal  -->
<div class="modal fade modal-warning modal-child" id="client-info" role="dialog">
    <div class="modal-dialog modal-inner" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="customer_close close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo display('add_customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label"><?php echo display('customer_name'); ?> <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control simple-control" name="customername" id="customernames" type="text"
                            placeholder="<?php echo display('customer_name'); ?>" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label"><?php echo display('email'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="email" id="customer_email" type="email"
                            placeholder="<?php echo display('email'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('mobile'); ?><i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="mobile" id="customer_mobile" type="number"
                            placeholder="<?php echo display('mobile'); ?>" min="0">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tax_number" class="col-sm-3 col-form-label"><?php echo display('tax_number'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="tax_number" id="customer_tax_number" type="text"
                            placeholder="Tax Number" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_discount"
                        class="col-sm-3 col-form-label"><?php echo display('max_discount'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="max_discount" id="customer_max_discount" type="max_discount"
                            placeholder="Max Discount" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date_of_birth"
                        class="col-sm-3 col-form-label"><?php echo display('date_of_birth'); ?></label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="date_of_birth" id="customer_date_of_birth">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address " class="col-sm-3 col-form-label"><?php echo display('b_address'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="address" id="customer_address" rows="3"
                            placeholder="<?php echo display('b_address'); ?>"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address " class="col-sm-3 col-form-label"><?php echo display('fav_addesrr'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="favaddress" id="customer_favaddress" rows="3"
                            placeholder="<?php echo display('fav_addesrr'); ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger customer_close" style="border-radius: 4px;"
                    data-dismiss="modal"><?php echo display('close'); ?> </button>
                <button type="button" class="btn btn-success " onclick="insertcustomer()"
                    style="border-radius: 4px;"><?php echo display('submit'); ?> </button>
            </div>
        </div>

    </div>

</div>

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
<style></style>

<script src="<?php echo base_url('assets/sweetalert/sweetalert.min.js') ?>" type="text/javascript"></script>
<?php
$is_ok = $_GET['value'];
if (isset($_GET['value'])) { ?>

<script>
swal({
        title: lang.ord_places,
        text: lang.do_print_in,
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        confirmButtonText: lang.yes,
        cancelButtonText: lang.no,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        $(".clearold").empty();
        if (isConfirm) {

            createMargeorder("<?php echo $_GET['value'] ?>", 1);
            var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist";
            window.history.pushState('', '', page_url2);

        } else {
            var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist";
            window.history.pushState('', '', page_url2);


        }
    });
</script>
<?php }
if (isset($_GET['addorder'])) { ?>
<script>
var csrf = $('#csrfhashresarvation').val();
$.ajax({
    url: basicinfo.baseurl + "catering_service/cateringservice/add_catering_order",
    type: "POST",
    data: {
        csrf_test_name: csrf
    },
    success: function(data) {
        //   console.log(data);
        $("#myModal").modal('show');
        $('.addcontent').html(data);
        // commented by MKar
        // $("#myModal").on('hide.bs.modal',()=>{
        //     $('.addcontent').html("");
        // });
        // $(".categoryid").val('').trigger("change");
        $("#sub_totalable").text('0.00');
        $("#taxval").val('0.00');
        $(".grandtotal").text('0.00');
        $("#grandtotal").val('0.00');
        $('.select2').select2();
        var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist";
        window.history.pushState('', '', page_url2);
    },
    error: function(xhr) {
        alert('failed!');
    }
});
</script>
<?php
}

?>
<script src="<?php echo base_url('application/modules/catering_service/assets/js/catering_orderlist.js? v=2'); ?>"
    type="text/javascript"></script>




<script>
$("body").on('click', '.printmodal', function() {

    $("#orderdetailsp").modal('hide');
    var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist";
    window.history.pushState('', '', page_url2);
    document.location.href = page_url2;
});

$("body").on('click', '.sa-clicon', function() {
    var page_url2 = basicinfo.baseurl + "catering_service/cateringservice/catering_orderlist";
    window.history.pushState('', '', page_url2);
});


$(function() {

    $('.delivarydate').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm:ss',
        changeYear: true
    });
});

$('body').on('click', '.customer_close', function() {
    var mode = $("#mode").val();
    if (mode == 'edit') {
        $("#edit_myModal").modal('show');

    } else {

        $("#myModal").modal('show');
    }
});

function add_order() {
    // $('.po_total').val(0.00);
    $("#taxval").val('0.00');
    $("#sub_totalable").text('0.00');
    $(".grandtotal").text('0.00');
    $("#grandtotal").val('0.00');
    var csrf = $('#csrfhashresarvation').val();


    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/add_catering_order",
        type: "POST",
        data: {
            csrf_test_name: csrf
        },
        success: function(data) {
            //   console.log(data);
            $("#myModal").modal('show');
            $('.addcontent').html(data);


            // commented by MKar
            //  $("#myModal").on('hide.bs.modal',()=>{
            //     $('.addcontent').html("");
            // });

            $("#taxval").val('0.00');
            $("#sub_totalable").text('0.00');
            $(".grandtotal").text('0.00');
            $("#grandtotal").val('0.00');
            // $('.select2').select2();
        },
        error: function(xhr) {
            alert('failed!');
        }
    });
}


function editOrder(order_id) {
    var csrf = $('#csrfhashresarvation').val();
    // alert(status);
    // return false;
    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/edit_catering_order",
        type: "POST",
        data: {
            order_id: order_id,
            csrf_test_name: csrf
        },
        success: function(data) {
            $("#edit_myModal").modal('show');

            // commented by MKar : both in add and edit....#id issue...
            // $("#edit_myModal").on('hide.bs.modal',()=>{
            //     $('.editcontent').html("");
            // });
            $('.editcontent').html(data);
            $('.select2').select2();
        },
        error: function(xhr) {
            alert('failed!');
        }
    });

}

// ordermanage/order/insert_customer

function insertcustomer() {

    var fd = new FormData();
    var customername = $('#customernames').val();
    var customer_email = $('#customer_email').val();
    var customer_mobile = $('#customer_mobile').val();
    var customer_tax_number = $('#customer_tax_number').val();
    var customer_max_discount = $('#customer_max_discount').val();
    var customer_date_of_birth = $('#customer_date_of_birth').val();
    var customer_address = $('#customer_address').val();
    var customer_favaddress = $('#customer_favaddress').val();
    var csrf = $('#csrfhashresarvation').val();

    if (customername == '') {
        errormessage = '<span>Customer Name Is Required.</span>';
        toastr.error(errormessage, 'Error');
        $("#customername").focus();
        return false;
    }
    if (customer_mobile == '') {
        errormessage = '<span>Customer Mobile Is Required.</span>';
        toastr.error(errormessage, 'Error');
        $("#customername").focus();
        return false;
    }

    fd.append("customername", customername);
    fd.append("customer_email", customer_email);
    fd.append("customer_mobile", customer_mobile);
    fd.append("customer_tax_number", customer_tax_number);
    fd.append("customer_max_discount", customer_max_discount);
    fd.append("customer_date_of_birth", customer_date_of_birth);
    fd.append("customer_address", customer_address);
    fd.append("customer_favaddress", customer_favaddress);
    fd.append("csrf_test_name", csrf);

    $.ajax({
        type: "POST",
        url: basicinfo.baseurl + "catering_service/cateringservice/insertCateringCustomer",
        data: fd,
        contentType: false,
        cache: false,
        processData: false,
        error: function() {

        },
        success: function(resp) {
            // console.log(resp);
            // return false;
            var mode = $("#mode").val();
            if (mode == 'edit') {
                $("#edit_myModal").modal('show');

            } else {

                $("#myModal").modal('show');
            }


            var obj = JSON.parse(resp);
            if (obj.status == true) {
                toastr.success(obj.message, 'Success');

                customerlist();

                $("#client-info").modal('hide');
                $('#customername').val('');
                $('#customer_email').val('');
                $('#customer_mobile').val('');
                $('#customer_tax_number').val('');
                $('#customer_max_discount').val('');
                $('#customer_date_of_birth').val('');
                $('#customer_address').val('');
                $('#customer_favaddress').val('');
            } else {
                toastr.error(obj.message, 'Something Wrong');
            }


        },
    });

}

function customerlist(sl) {
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/customerlist",
        type: "POST",
        data: {
            csrf_test_name: csrf
        },
        success: function(data) {
            $("#customer_name").html(data);
            $(".select2").select2();

        },
        error: function(xhr) {
            alert('failed!');
        }
    });
}
var ary = [];

function pushToAry(name, val) {
    var obj = {};
    obj[name] = val;
    ary.push(obj);
}

function category_wise_foods(sl) {

    var category_id = $("#categoryid_" + sl).val();
    var package_check = $("#package_wise_category_" + sl).val();
    var package_id = $('#package_wise_category_' + sl).val();

    if (package_check != 'none_package') {

        $('#category_' + sl).attr('class', 'samecategory_' + package_id + '_' + category_id);
    } else {
        $('#category_' + sl).attr('class', 'samecategory');
    }
    var csrf = $('#csrfhashresarvation').val();



    var totalcat = $('.samecategory_' + package_id + '_' + category_id).length;
    var totalpack = $('.samepackage' + package_id).length;

    // alert(totalcat);


    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/category_wise_foods",
        type: "POST",
        data: {
            package_id: package_id,
            category_id: category_id,
            csrf_test_name: csrf
        },
        success: function(data) {
            // console.log(data);
            var obj = JSON.parse(data);
            if (package_check != 'none_package') {


                if (totalcat > obj.max_item) {
                    // alert('limit ses');

                    var errormessage = '<span>Package Limit is over .</span>';
                    toastr.error(errormessage, 'Error');
                    $("#categoryid_" + sl).focus();
                    $("#categoryid_" + sl).val('').trigger('change')
                    return false;
                }
            }
            // var max_limit=$('#category_max_item_'+sl).val();
            //alert(obj.max_item);


            $('#productsid_' + sl).html(obj.categorylist);
            $('.select2').select2();

        },
        error: function(xhr) {
            alert('failed!');
        }
    });
}


function productinfo(id, sl) {

    var csrf = $('#csrfhashresarvation').val();
    var variantid = $("#productsid_" + sl).find(":selected").data("variantid");
    var package_id = $('#package_wise_category_' + sl).val();


    $("#variantid_" + sl).val(variantid);
    $("#product_id" + sl).val(id);
    $("#sub_totalable").val('0.00');
    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/productinfo",
        type: "POST",
        data: {
            id: id,
            package_id: package_id,
            variantid: variantid,
            csrf_test_name: csrf
        },
        success: function(data) {


            var package_check = $("#package_wise_category_" + sl).val();
            var obj = JSON.parse(data);

            if (package_check == 'none_package') {

                $("#taxval").val('0.00');
                $("#sub_totalable").text('0.00');
                $(".grandtotal").text('0.00');
                $("#grandtotal").val('0.00');


                $('#product_discount_' + sl).val(obj.product_discount.toFixed(2, 2));
                $('#product_single_discount_' + sl).val(obj.product_discount.toFixed(2, 2));

                $('#product_vat_' + sl).val(obj.pvat.toFixed(2, 2));

                $('#pvat_' + sl).val(obj.pvat.toFixed(2, 2));
                $("#price_" + sl).val(obj.productinfo.price);
                var qty = $('#qty_' + sl).val();
                var total = (obj.total_price);
                $('#total_price_' + sl).val(total.toFixed(2));
                $("#isgroup_" + sl).val(obj.productinfo.isgroup);
                // console.log(total);
                po_calculation(sl);

            } else {
                var package_check = $("#package_wise_category_" + sl).val();


                var pkid = 'pakageid' + package_id;
                $("#productsid_" + sl).addClass(pkid);

                $('#deleteid' + sl).attr('onclick', 'deleteRows(this,' + sl + ',' + package_id + ')');

                // price_7

                $('.pkid_' + sl).addClass('pk' + package_id);

                $('#price_' + sl).attr('data-packageid', package_id);


                $('#price_' + sl).attr('data-price', obj.packageinfo.price);



                $(".pkvatid_" + sl).addClass('pkvat' + package_id);
                $('#pvat_' + sl).attr('data-vat', obj.package_vat.toFixed(2, 2));
                $('#pvat_' + sl).attr('data-vat2', obj.package_vat.toFixed(2, 2));


                //var package_check = $(".product_id ").val();


                if (package_check != 'none_package') {

                    var totalpack = $('.samepackage' + package_id).length;

                    // $('#product_discount_' + sl).val('0.00');
                    // $('#product_single_discount_' + sl).val('0.00');
                    // $('#product_vat_' + sl).val('0.00');
                    // $('#pvat_' + sl).val('0.00');

                    var totalslfood = $(".pakageid" + package_check).length;


                    // alert(totalslfood);
                    if (totalslfood <= 1) {
                        var price = obj.packageinfo.price;
                        $("#price_" + sl).val(price);
                        $("#total_price_" + sl).val(price);

                        // $('#product_discount_' + sl).val(obj.product_discount.toFixed(2, 2));
                        // $('#product_single_discount_' + sl).val(obj.product_discount.toFixed(2, 2));
                        $('#product_vat_' + sl).val(obj.package_vat);
                        $('#pvat_' + sl).val(obj.package_vat.toFixed(2, 2));
                        po_calculation(sl);
                    } else {

                        var d = $('.samepackage_sl' + package_id + sl).val();
                        if (d == 1) {
                            var prices = $("#total_price_" + sl).val();

                            var package_vats = $('#product_vat_' + sl).val();
                            var pvats = $('#pvat_' + sl).val();

                            $("#price_" + sl).val(prices);
                            $("#total_price_" + sl).val(prices);

                            $('#product_vat_' + sl).val(package_vats);
                            $('#pvat_' + sl).val(pvats);
                        } else {
                            $("#price_" + sl).val('0.00');
                            $("#total_price_" + sl).val("0.00");
                        }

                        po_calculation(sl);
                    }

                    $("#package_wise_category_" + sl).prop("disabled", true);

                }

            }

        },
        error: function(xhr) {
            alert('failed!');
        }
    });
}


function deleteRows(trash, sl, pkid) {


    $("#taxval").val('0.00');
    $("#sub_totalable").text('0.00');
    $(".grandtotal").text('0.00');
    $("#grandtotal").val('0.00');

    //  var pkvat='';
    // var setvatid='';
    // var pkvatmain=$('#pvat_'+sl).attr('data-vat');
    // var singelprice= $('#price_'+sl).attr('data-price');
    // $('.pkvat'+pkid).attr('data-vat',pkvat);



    $(trash).closest("tr").remove();


    if (pkid != 'none_package') {


        var p = $('.pakageid' + pkid).length;
        var pkprice = '';
        var setid = '';
        var pkvat = '';
        $('.pk' + pkid).each(function() {
            pkprice = $(this).attr('data-price');
            setid = $(this).attr('id');
            pkvat = $('.pkvat' + pkid).attr('data-vat2');
            const myArray2 = setid.split("_");
            $("#total_price_" + myArray2[1]).val(0);

            $("#pvat_" + myArray2[1]).val(0);
            $("#product_vat_" + myArray2[1]).val(0);

        });


        $(".pk" + pkid).val(0);
        const myArray = setid.split("_");
        $("#" + setid).val(parseFloat(pkprice));
        $("#total_price_" + myArray[1]).val(parseFloat(pkprice));
        $("#pvat_" + myArray[1]).val(parseFloat(pkvat));
        $("#product_vat_" + myArray[1]).val(parseFloat(pkvat));



    }

    invoice_calculateSum();
    po_calculation();

}





function po_calculation(sl) {

    var prod_amount = 0;
    var po_qty = $("#qty_" + sl).val();
    var po_price = $("#price_" + sl).val();
    var package_check = $("#package_wise_category_" + sl).val();

    if (package_check == 'none_package') {

        var pvat = po_qty * $('#pvat_' + sl).val();
        $('#product_vat_' + sl).val(pvat.toFixed(2, 2));

        // var product_discount = po_qty*$("#product_discount_" + sl).val();

        var product_discount = po_qty * $("#product_single_discount_" + sl).val();
        $("#product_discount_" + sl).val(product_discount.toFixed(2, 2));

        prod_amount = (po_qty * po_price) - product_discount;

        $("#total_price_" + sl).val(prod_amount.toFixed(2, 2));
    }

    invoice_calculateSum();


}

//Calculate Sum
"use strict";

function invoice_calculateSum() {
    var calvat = 0;
    var sub_total = 0;
    var grandtotal = 0;
    var deliverycharge = 0;
    var othercharge = 0;
    var discount = 0;
    var paid_amount = 0;
    var due_amount = 0;
    var due_amount = 0;
    var product_vat = 0;
    var deliverycharge_vat = 0;
    var distype = $('#distype').val();
    var service_chargetype = $('#service_chargetype').val();
    var servicecharge = $('#servicecharge').val();

    deliverycharge = $('#deliverycharge').val();
    othercharge = $('#othercharge').val();
    discount = $('#discount').val();
    paid_amount = $('#paid_amount').val();
    var globaltaxt = $("#globaltaxt").val();


    var default_vat = $("#default_vat").val();
    var taxt_type = $("#taxt_type").val();

    //    due_amount= $('#due_amount').val();


    //Total Discount
    $(".po_total").each(function() {
            isNaN(this.value) || 0 == this.value.length || (sub_total += parseFloat(this.value))
            //alert(this.value);
        }),
        $(".product_vat").each(function() {
            isNaN(this.value) || 0 == this.value.length || (product_vat += parseFloat(this.value))

        }),


        // alert(sub_total);
        $("#sub_totalable").text(sub_total.toFixed(2, 2));


    if (service_chargetype == 1) {
        var servicetotal = servicecharge * sub_total / 100;
    } else {
        // var servicetotal = service_chargetype;
        var servicetotal = servicecharge;
    }

    // alert(servicetotal);


    var grandtotal = parseFloat(sub_total) + parseFloat(deliverycharge) + parseFloat(
        servicetotal); //+parseFloat(othercharge);




    // if(distype==1){
    //     var totaldis=discount*sub_total/100;
    // }else{
    //     var totaldis=discount;
    // }


    if (globaltaxt == 1) {
        //  var calvat=(sub_total-totaldis)*default_vat/100;
        var calvat = (sub_total) * default_vat / 100;

    } else {
        var calvat = product_vat;
    }

    var deliverycharge_vat = $("#delivaryvat").val();

    if (deliverycharge_vat == "") {
        var deliverycharge_vat = 0;
    } else {
        var deliverycharge_vat = deliverycharge_vat;
    }

    var calvat = parseFloat(calvat) + parseFloat(deliverycharge_vat);



    $('#taxval').val(calvat.toFixed(2, 2));


    if (taxt_type == 1) {
        grandtotal = parseFloat(grandtotal); //- parseFloat(totaldis);
    } else {
        grandtotal = parseFloat(grandtotal) + parseFloat(calvat) //- parseFloat(totaldis);
    }
    // var  due_amount = grandtotal - paid_amount;

    // if(grandtotal<paid_amount){
    //    alert("Please check paid amount is gater then grandtotal");
    //    $("#due_amount").val("0.00");
    //    $('#paid_amount').val("0.00")
    // }else{
    //     $("#due_amount").val(due_amount.toFixed(2, 2));
    // }
    $('#grandtotal').val(grandtotal.toFixed(2, 2));
    $(".grandtotal").text(grandtotal.toFixed(2, 2));
}


function deliverylocation() {
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/deliveryLocationPrice",
        type: "POST",
        data: {
            csrf_test_name: csrf
        },
        success: function(data) {
            var obj = JSON.parse(data);
            $('#deliverycharge').val(obj.shippingrate);

            // invoice_calculateSum();
        },
        error: function(xhr) {
            alert('failed!');
        }
    });

}




$(document).on('click', '.aceptorcancels', function() {

    var ordid = $(this).attr("data-id");
    var paytype = $(this).attr("data-type");
    var csrf = $('#csrfhashresarvation').val();
    var dataovalue = 'orderid=' + ordid + '&csrf_test_name=' + csrf;
    var productionsetting = $('#production_setting').val();
    var message = "Are You Accept Or Reject this Order??";
    // if(productionsetting == 1){
    //     var check =true;
    //    $.ajax({
    //             type: "POST",
    //             url: basicinfo.baseurl+"catering_service/cateringservice/checkstock",
    //             data: dataovalue,
    //             async: false,
    //             global: false,
    //              success: function(data){
    //                  if(data !=1){
    //                     message=data;
    //                     return false;
    //                     }
    //             }
    //         });
    // }
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
                //    $.ajax({
                //       type: "POST",
                //       url: basicinfo.baseurl+"ordermanage/order/acceptnotify",
                //       data: dataString,
                //       success: function(data){
                //         swal("Accepted", "Your Order is Accepted", "success");
                // 		if(prevsltab[0].id=="tallorder"){
                // 		$('#'+prevsltab[0].id).DataTable().ajax.reload();	
                // 		}else{
                //         prevsltab.trigger('click');
                // 		load_unseen_notification();
                // 		}
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

$(document).on('click', '#catering_cancelreason', function() {
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
            $('#tallorder').DataTable().ajax.reload();
            // if (prevsltab[0].id == "tallorder") {
            //     $('#' + prevsltab[0].id).DataTable().ajax.reload();
            // } else {
            //     //conlose.log(prevsltab);
            //     prevsltab.trigger('click');
            //     load_unseen_notification();
            // }
            swal("Rejected", "Your Order is Cancel", "success");



        }
    });
});

function cateringdetailspop(orderid) {

    var csrf = $('#csrfhashresarvation').val();
    var myurl = basicinfo.baseurl + 'catering_service/cateringservice/catering_orderdetails/' + orderid;
    var dataString = "orderid=" + orderid + '&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {

            //   $('.orddetailspop').html(data);
            // $('#orderdetailsp').modal('show');
            var dtype = checkdevicetype();
            if (dtype == 1) {
                var url2 = "http://www.abc.com/token/" + id;
                window.open(url2, "_blank");
            } else {
                setTimeout(function() {
                    printRawHtml(data);
                }, 1000);

            }
            // {backdrop: 'static', keyboard: true}
        }
    });

}


// kot print start
function catering_postokenprint(id) {
    var csrf = $('#csrfhashresarvation').val();
    var url = basicinfo.baseurl + 'catering_service/cateringservice/catering_paidtoken' + '/' + id + '/';
    $.ajax({
        type: "POST",
        url: url,
        data: {
            csrf_test_name: csrf
        },
        success: function(data) {

            var dtype = checkdevicetype();
            if (dtype == 1) {
                var url2 = "http://www.abc.com/token/" + id;
                window.open(url2, "_blank");
            } else {
                printRawHtml(data);
            }
            //printRawHtml(data);
        }
    });
}

function checkdevicetype() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return 1;
    } else {
        return 0
    }
}



function packagewisecategory(id, sl, selecttype) {

    var csrf = $('#csrfhashresarvation').val();
    $("#productsid_" + sl).val('');


    if (id != 'none_package') {

        $.ajax({
            url: '<?php echo base_url('catering_service/cateringservice/packagewisecategory') ?>',
            type: "POST",
            data: {
                id: id,
                csrf_test_name: csrf
            },
            success: function(data) {

                $('#package_' + sl).attr('class', 'samepackage' + id);




                $('#samepackage_sl_' + sl).attr('class', 'samepackage_sl' + id + sl);

                var totalpack = $('.samepackage' + id).length;
                $('.samepackage_sl' + id + sl).val(totalpack);



                $("#price_" + sl).prop('type', 'hidden');
                $("#total_price_" + sl).prop('type', 'hidden');

                $(".categoryid").select2();
                //    if(selecttype =="" ){
                //     $('.categoryid').html(data);
                //    }else{
                $('#categoryid_' + sl).html(data);
                // }
                // $(".categoryid").select2({
                //     placeholder: 'select service name',
                //     id:data.CategoryID,
                //     text:data.Name
                // });    

            },
            error: function(xhr) {
                alert('failed!');
            }
        });
    } else {

        // $("#price_"+sl).show();
        // $("#total_price_"+sl).show();
        $("#price_" + sl).prop('type', 'text');
        $("#total_price_" + sl).prop('type', 'text');
        $('#package_' + sl).attr('class', 'samepackage');

        categorylist(sl);
    }
}

$(document).ready(function() {
    $('.select2').select2();
});
</script>