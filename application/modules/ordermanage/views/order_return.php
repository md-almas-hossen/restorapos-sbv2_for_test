<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php //d($allorderlist);?>
<div class="row">
    <div class="col-sm-12 col-md-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                    <!-- <h4><?php echo display('sale_return');$title; ?></h4> -->
                </div>
            </div>

            <div class="panel-body">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-body">
                                <?php echo form_open('ordermanage/orderreturn/order_return_entry',array('class' => 'form-vertical','id'=>'purchase_return' ))?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier"
                                            class="col-sm-2"><?php echo display('customer_name') ?>:</label>
                                        <div class="col-sm-3">
                                            <?php ///echo form_dropdown('supplier_id',$supplier,(!empty($supplier->supplier_id)?$supplier->supplier_id:null), 'class="form-control" id="supplier_id" onchange="getinvoice()" tabindex="1" required') ?>
                                            <?php echo form_dropdown('customer_id',$customerlist,'','class="postform js-basic-single form-control" id="customer_id" required tabindex="-1"  onchange="getinvoice()" required') ?>
                                        </div>
                                        <label for="supplier" class="col-sm-1"><?php echo display('invoice') ?>:</label>
                                        <div class="col-sm-3" id="invoicelist">
                                            <?php echo form_dropdown('invoice',$allorderlist,'','class="postform js-basic-single form-control" id="invoice" required tabindex="-1"  o required') ?>
                                            <!-- <select name="invoice" id="invoice" class="form-control" >
												
												<option value=""  selected="selected">Select Option</option>
											</select> -->
                                        </div>
                                        <input name="invoiceurl" type="hidden"
                                            value="<?php echo base_url("ordermanage/orderreturn/getinvoice") ?>"
                                            id="invoiceurl" />
                                        <input name="serachurl" type="hidden"
                                            value="<?php echo base_url("ordermanage/orderreturn/order_return_item") ?>"
                                            id="serachurl" />
                                        <button type="button" class="btn btn-success"
                                            onclick="showinvoice()"><?php echo display('search') ?></button>
                                        <!-- <div style=" float: right;">
												<a href="<?php echo base_url("ordermanage/orderreturn/returntbllist") ?>" class="btn btn-info float-right">Return List</a>
											</div> -->
                                    </div>

                                </div>
                                <div id="itemlist">


                                </div>
                                <?php echo form_close()?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getinvoice() {
    var geturl = $("#invoiceurl").val();
    var customer_id = $("#customer_id").val();
    var csrf = $('#csrfhashresarvation').val();
    var dataString = "customer_id=" + customer_id + '&status=1&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
            $('#invoicelist').html(data);
            $('select#invoice').val(null).trigger('change');
            $('#invoice').select2({
                allowClear: true,
                placeholder: 'Select an option'
            });
        }
    });

}

function showinvoice() {
    var geturl = $("#serachurl").val();
    var invoice = $("#invoice").val();

    var csrf = $('#csrfhashresarvation').val();
    var customer_id = $("#customer_id").val();

    var myurl = geturl + "/" + invoice;
    //  alert(myurl);
    //  return false;
    var dataString = "customer_id=" + customer_id + "&invoice=" + invoice + '&status=1&csrf_test_name=' + csrf;

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            // console.log(data);

            $('#itemlist').html(data);



        }
    });

}
</script>
<!-- <script src="<?php echo base_url('application/modules/purchase/assets/js/purchasereturn_script.js'); ?>" type="text/javascript"></script> -->