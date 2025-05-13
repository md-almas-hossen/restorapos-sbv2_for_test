<style>
.modal-backdrop {
   z-index: -999;
}
</style>
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
<table class="table custom-table_two table-striped table-condensed bg-white wpr_100" id="thirdpartyorderlist">
    <thead>
        <tr>
            <th class="text-center"><?php echo display('sl');?>. </th>  
            <th class="text-center"><?php echo display('invoice');?> </th>
            <th class="text-center"><?php echo display('customer_name');?> </th>
            <th class="text-center"><?php echo display('thirdpartycustomer_list'); //echo display('customer_type');?></th>
            <!-- <th class="text-center"><?php echo display('waiter');?></th> -->
            <!-- <th class="text-center"><?php echo display('tabltno');?></th> -->
            <th class="text-center"><?php echo display('ordate');?></th>
            <th class="text-right"><?php echo display('amount');?></th>
            <th class="text-center"><?php echo display('action');?></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-right"><?php echo display('total');?>:</th>
            <th  class="text-right"></th>
            <th  class="text-right"></th>

       
        </tr>
    </tfoot>
</table>

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/thirdparty_order.js?v=1.6'); ?>" type="text/javascript">
</script>