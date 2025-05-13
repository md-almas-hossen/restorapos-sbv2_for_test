<table class="table custom-table_two table-striped table-condensed bg-white wpr_100" id="allqrorder">
                                    <thead>
                                         <tr>
                                               <th class="text-center"><?php echo display('sl');?>. </th>
                                                <th class="text-center"><?php echo display('orderno');?> </th>
                                                <th class="text-center"><?php echo display('customer_name');?></th>
                                                <th class="text-center"><?php echo display('waiter');?></th> 
                                                <th class="text-center"><?php echo display('payment_status');?></th>
                                                <th class="text-center"><?php echo display('payment_type');?></th>  
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
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/qrorder.js'); ?>" type="text/javascript"></script>
