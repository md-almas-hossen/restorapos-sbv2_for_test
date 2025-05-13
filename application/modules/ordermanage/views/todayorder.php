<table class="table custom-table_two table-striped table-condensed bg-white wpr_100" id="onprocessing">
    <thead>
        <tr>
            <th class="text-center"><?php echo display('sl');?>. </th>
            <th class="text-center"><?php echo display('invoice');?> </th>
            <th class="text-center"><?php echo display('customer_name');?> </th>
            <th class="text-center"><?php echo display('customer_type');?></th>
            <th class="text-center"><?php echo display('waiter');?></th>
            <th class="text-center"><?php echo display('tabltno');?></th>
            <th class="text-center"><?php echo display('ordate');?></th>
            <th class="text-right"><?php echo display('amount');?></th>
            <th class="text-center"><?php echo display('action');?></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-right"><?php echo display('total');?>:</th>
            <th colspan="2" class="text-center"></th>
        </tr>
    </tfoot>
</table>
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

<script>
        function todaypickupmodal(id,status){
        var csrf = $('#csrfhashresarvation').val();
        // alert(status);
        // return false;
        $.ajax({
            url: basicinfo.baseurl + "ordermanage/order/pickupmodalload",
            type: "POST",
            data: {
                id:id,
                status:status,
                csrf_test_name: csrf
            },
            success: function(data) {

                $("#pickupmodalview").html("");
                $('#pickupmodalview').html(data);
                $('#pickupmodal').modal('show');

            },
            error: function(xhr) {
                alert('failed!');
            }
        });
    }
</script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/todayorder.js?v=1'); ?>" type="text/javascript">
</script>