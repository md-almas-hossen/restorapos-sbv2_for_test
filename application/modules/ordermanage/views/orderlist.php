<script src="<?php echo base_url('application/modules/ordermanage/assets/js/postop.js'); ?>" type="text/javascript">
</script>
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
                                    <label for="payments" class="col-sm-4 col-form-label">Order ID</label>
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
                                            id="cancelreason">Submit</button>
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
<div id="payprint_marge" class="modal fade" role="dialog">
    <div class="modal-dialog modal-inner" id="modal-ajaxview" role="document"> </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="btn-group pull-right form-inline">

                    <?php if (!$this->session->userdata('is_sub_branch')) {?>

                        <div style="width: 200px;" class="form-group">


                            <?php
                            // $unpostedSalesVoucher = $this->db->select('count(*) as unposted')->from('bill')->where('VoucherNumber', NULL)->get()->row();                
                            $unpostedSalesVoucher = $this->db->select('count(*) as unposted')->from('bill b')->join('customer_order co', 'co.order_id = b.order_id', 'inner')->where('co.order_status', 4)->where('b.VoucherNumber', NULL)->get()->row();                
                            $unpostedPurchaseVoucher = $this->db->select('count(*) as unposted')->from('purchaseitem')->where('VoucherNumber', NULL)->get()->row();
                            ?>

                            <!-- Button trigger modal -->
                            <button style="margin-right:5px" type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#exampleModal">
                                Voucher Sync (
                                <?php echo $unpostedSalesVoucher->unposted + $unpostedPurchaseVoucher->unposted;?> )
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="d-flex justify-content-between align-item">
                                                <h5 class="modal-title" id="exampleModalLabel">Voucher Sync</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="modal-body">
                                            <p>Total Unposted Sales Voucher Number:
                                                <b><?php echo $unpostedSalesVoucher->unposted; ?></b>
                                            </p>
                                            <p>Total Unposted Purchase Voucher Number:
                                                <b><?php echo $unpostedPurchaseVoucher->unposted; ?></b>
                                            </p>
                                            <a class="btn btn-success"
                                                href="<?php echo base_url('ordermanage/Order/voucher_sync') ?>">Sync
                                                Voucher</a>
                                            <p><i><b>Note:</b> It will take some time</i></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                        <div style="width: 200px;" class="form-group">
                            <select name="thirdparty" class="form-control js-basic-single w-100" id="thirdparty"
                                tabindex="-1">
                                <option value="">Select Third Party</option>
                                <option value="0">All Third Party</option>
                                <?php foreach($allthirdparty as $row){?>
                                <option value="<?php echo $row->companyId;?>"><?php echo $row->company_name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php $today = date('d-m-Y'); ?>
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
                            <button class="btn btn-warning"
                                id="filterordlistrst"><?php echo display('reset') ?></button>
                        </div>
                    </div>
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row"><?php //echo $_SERVER['HTTP_USER_AGENT'];?>
                    <div class="col-sm-12" id="findfood">
                        <div class="table-responsive-mobile">
                            <table class="table table-fixed table-bordered table-hover bg-white" id="tallorder">
                                <thead>
                                    <tr>
                                        <th class="text-center"><input name="checkall" type="checkbox" value=""
                                                class="allorder pull-left" /><?php echo display('sl')?> &nbsp;</th>
                                        <th class="text-center"><?php echo display('invoice_no');?></th>
                                        <th class="text-center"><?php echo display('customer_name');?></th>
                                        <th class="text-center"><?php echo display('waiter');?></th>
                                        <th class="text-center"><?php echo display('table');?></th>
                                        <th class="text-center"><?php echo display('state');?></th>
                                        <th class="text-center"><?php echo display('ordate');?></th>
                                        <th class="text-right"><?php echo display('amount');?></th>
                                        <th class="text-center"><?php echo display('action');?></th>
                                        
                                        <?php
                                        $is_sub_branch = $this->session->userdata('is_sub_branch');
                                        if($is_sub_branch == 0){
                                        ?>	
                                            <th class="text-center">Event Code</th>
                                            <th class="text-center">Voucher No</th>
                                            
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                        <div class="text-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="production_setting" value="<?php echo $possetting->productionsetting; ?>">
<input type="hidden" id="production_url" value="<?php echo base_url("production/production/ingredientcheck") ?>">
<div id="payprint_split" class="modal fade  bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg" id="modal-ajaxview-split"> </div>
</div>


<div id="pickupmodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo  "Pic-up Delivery Agent";?></strong>
            </div>
            <div class="modal-body" id="pickupmodalview">


            </div>
        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<div id="view_order" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close font-bold" data-dismiss="modal"><strong>&times;</strong></button>
                <strong><?php echo "Invoice Information"; ?></strong>
            </div>
            <div class="modal-body view_order_info">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<style>

    @media (max-width:767px) {
        .table-responsive-mobile{
            min-height: 0.01%;
            overflow-x: auto;
            width:100%;
        }
    }
</style>

<script>
"use strict";

function printModalContent(modald) {
    var divName = "vaucherPrintArea";
    document.title = '<?php echo $settinginfo->title;?>'; // Set a blank title
    var printContents = document.getElementById(modald).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
    setTimeout(function() {
        // $('#'+modald).modal().hide();;
        // $("#"+modald + " .close").click();
        location.reload();
    }, 100);
}

"use strict";

function viewOrderInfo(id) {
    var geturl = $("#url_order_" + id).val();
    // console.log(geturl);
    // return false;
    var myurl = geturl + '/' + id;
    var csrf = $('#csrfhashresarvation').val();
    var dataString = "id=" + id + "&csrf_test_name=" + csrf;

    $.ajax({
        type: "GET",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.view_order_info').html(data);
            $('#view_order').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy"
            });
        }
    });
}

function pickupmodal(id, status) {
    var csrf = $('#csrfhashresarvation').val();
    // alert(status);
    // return false;
    $.ajax({
        url: basicinfo.baseurl + "ordermanage/order/pickupmodalload",
        type: "POST",
        data: {
            id: id,
            status: status,
            customertype: 3,
            csrf_test_name: csrf
        },
        success: function(data) {

            $("#pickupmodalview").html("");
            $('#pickupmodalview').html(data);
            $('#pickupmodal').modal('show');
            $("#pagename").val("0");
        },
        error: function(xhr) {
            alert('failed!');
        }
    });
}
</script>

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/orderlist.js?v=5'); ?>"
    type="text/javascript"></script>