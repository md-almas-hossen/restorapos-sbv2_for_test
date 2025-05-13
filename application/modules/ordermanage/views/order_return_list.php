<?php

// dd($returnitem);
?>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="form-group text-right">
            <a href="<?php echo base_url('ordermanage/orderreturn/index/'); ?>" class="btn btn-success btn-md"><i
                    class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo display('add_return') ?></a>
        </div>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('sl') ?></th>
                            <th><?php echo display('customer_name') ?> </th>
                            <th><?php echo display('orderid'); ?> </th>
                            <th><?php echo display('date'); ?></th>
                            <th><?php echo display('discount'); ?></th>
                            <th><?php echo display('vat'); ?></th>
                            <th><?php echo display('service_chrg'); ?></th>
                            <th><?php echo display ('total_ammount'); ?> </th>
                            <th><?php echo display('action'); ?> </th>

                        </tr>
                    </thead>
                    <tbody id="addinvoiceItem">
                        <?php
            $sl = 1;
            foreach ($returnitem as $details) {
              //  d($details);
              // echo $details->adjustment_status;
            ?>

                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $details->customer_name; ?></td>
                            <td><?php echo getPrefixSetting()->sales_return . '-' . $details->order_id; ?></td>
                            <td><?php echo $details->return_date; ?></td>
                            <td><?php echo $details->t_discount; ?></td>
                            <td><?php echo $details->t_vat; ?></td>
                            <td><?php echo $details->serv_charge; ?></td>
                            <td><?php echo number_format($details->tamount, 2); ?></td>
                            <td class="center">
                                <?php //echo $details->totalamount.'_'.$details->pay_amount;
                  //if($this->permission->method('hrm','update')->access()): 
                  ?>

                                <?php if (($details->totalamount != $details->pay_amount) && ($details->adjustment_status != 1)) { ?>
                                <a href="<?php echo base_url("ordermanage/orderreturn/returnedit/$details->oreturn_id") ?>"
                                    class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                <?php } ?>

                                <?php if (($details->totalamount != $details->pay_amount) && ($details->adjustment_status != 1)) { ?>
                                <!-- <a href="<?php echo base_url("ordermanage/orderreturn/make_payment/$details->oreturn_id") ?>" class="btn btn-xs btn-info"><i class="fa fa-usd" aria-hidden="true"></i></a> -->
                                <a href="javascript:;" class="btn btn-inverse btn-xs"
                                    onclick="makepayment(<?php echo $details->oreturn_id; ?>)"
                                    data-original-title="Enter Product Delivery">
                                    <i class="fa fa-usd"></i> Make Payment
                                    <!-- <i class="fa fa-usd" aria-hidden="true"></i> -->
                                </a>

                                <?php } else {
                    if ($details->adjustment_status == 1) {
                      echo "Adjusted Invoice";
                    } else { ?>
                                <a href="javascript:;" class="btn btn-inverse btn-xs"
                                    onclick="makepayment(<?php echo $details->oreturn_id; ?>)"
                                    data-original-title="Enter Product Delivery">
                                    <i class="fa fa-eye"></i> View Details
                                </a>

                                <a href="javascript:;" class="btn btn-inverse btn-xs"
                                    onclick="printreturn(<?php echo $details->oreturn_id; ?>)"
                                    data-original-title="Print">
                                    <i class="fa fa-print"></i>
                                </a>
                                <?php }
                  } ?>
                            </td>
                        </tr>
                        <?php $sl++;
            } ?>


                </table>
                <?php echo $links; ?>
            </div>
        </div>


    </div>
</div>


<div class="modal fade" id="paymentModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #019868;">
                <h5 class="modal-title text-white " style="float:left;" id="paymentModallevel">Transaction</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body paymodal">

            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary text-white" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>
function makepayment(id) {
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "ordermanage/Orderreturn/make_payment",
        type: "POST",
        data: {
            csrf_test_name: csrf,
            id: id
        },
        success: function(data) {
            $('.paymodal').html(data);
            $('#paymentModal').modal('show');
            $('#payment_method_id').select2({
                allowClear: true,
                placeholder: 'Select an option'
            });

        },

    });

}

function printreturn(id) {
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "ordermanage/Orderreturn/returnprint",
        type: "POST",
        data: {
            csrf_test_name: csrf,
            id: id
        },
        success: function(data) {
            if (basicinfo.printtype != 1) {
                printRawHtml(data);
            }
        },

    });
}

function printRawHtml(view) {
    printJS({
        printable: view,
        type: 'raw-html',

    });
}
</script>