                                        
<div class="table-responsive">
    <table id="respritbl" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center"><?php echo display('Sl') ?></th>
                <th class="text-center"><?php echo display('purchase_no') ?></th>
                <th class="text-center"><?php echo display('supplier') ?></th>
                <th class="text-center"><?php echo display('date') ?></th>
                <th class="text-right"><?php echo display('vat') ?></th>
        
            </tr>
        </thead>
        <tbody>
            <?php 
            $s=0;
            $totalvat=0;
            // d($allvat);
            foreach($allvat as $vatlist){

                $totalvat +=$vatlist->vat;
            ?>
            <tr>
                <td><?php echo ++$s;?></td>
                <td class="text-center"><?php echo getPrefixSetting()->purchase. '-'.(!empty($vatlist->invoiceid)? $vatlist->invoiceid:'');?></td>
                <td class="text-center"><?php echo (!empty($vatlist->supName)? $vatlist->supName:'');?></td>
                <td class="text-center"><?php echo (!empty($vatlist->purchasedate)? $vatlist->purchasedate:'');?></td>
                <td class="text-right"><?php echo (!empty($vatlist->vat)? $vatlist->vat:'');?></td>

            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td colspan=""><b>Total Vat:</b></td>
                <td class="text-right"><b><?php echo (($totalvat)? $totalvat:'');?></b></td>
            </tr>

        </tfoot>
    </table>
</div>