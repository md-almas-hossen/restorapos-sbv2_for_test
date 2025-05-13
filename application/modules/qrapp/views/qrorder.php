<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo (!empty($title) ? $title : null) ?>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-fixed table-bordered table-hover bg-white" id="myqrorder" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo display('sl');?>. </th>
                            <th class="text-center"><?php echo display('invoice');?> </th>
                            <th class="text-center"><?php echo display('customer_name');?></th>
                            <th class="text-center"><?php echo display('customer_type');?></th>
                            <th class="text-center"><?php echo display('waiter');?></th>
                            <th class="text-center"><?php echo display('tabltno');?></th>
                            <th class="text-center"><?php echo display('payment_status');?></th>
                            <th class="text-center"><?php echo display('ordate');?></th>
                            <th class="text-right"><?php echo display('amount');?></th>
                            <th class="text-center"><?php echo display('action');?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" style="text-align:right"><?php echo display('total');?>:</th>
                            <th colspan="2" style="text-align:center"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-warning" id="posprint" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="kotenpr">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="orderdetailsp" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php //echo display('unit_update');?></strong>
            </div>
            <div class="modal-body orddetailspop">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>


<script>
function detailspop(orderid) {
    var csrf = $('#csrfhashresarvation').val();
    var myurl = '<?php echo base_url();?>ordermanage/order/orderdetailspop/' + orderid;
    var dataString = "orderid=" + orderid + '&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.orddetailspop').html(data);
            $('#orderdetailsp').modal('show');
        }
    });

}

function pospageprint(orderid) {
    var csrf = $('#csrfhashresarvation').val();
    var datavalue = 'customer_name=fgd&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: "<?php echo base_url()?>ordermanage/order/posprintview/" + orderid,
        data: datavalue,
        success: function(printdata) {
            $("#kotenpr").html(printdata);
            //alert(printdata);
            const style = '@page { margin:0px;font-size:18px; }';
            printJS({
                printable: 'kotenpr',
                onPrintDialogClose: printJobComplete,
                type: 'html',
                font_size: '25px',
                style: style,
                scanStyles: false
            })
        }
    });
}

function printJobComplete() {
    //alert("print job complete");
    $("#kotenpr").empty();
}
</script>