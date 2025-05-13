<?php 

// dd($returnitem);
?>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="form-group text-right">
            <a href="<?php echo base_url('purchase/purchase/return_form');?>" class="btn btn-success btn-md"><i
                    class="fa fa-plus-circle" aria-hidden="true"></i>
                <?php echo display('add_return'); ?>
            </a>
        </div>
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <div class="panel-title">
                    <h4><?php echo display('purchase_return_list'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('sl') ?></th>
                            <th><?php echo display('supplier_name') ?> </th>
                            <th><?php echo display('purchase_no');?> </th>
                            <th style="text-align: center;"><?php echo display('date'); ?></th>
                            <th style="text-align: right;"><?php echo display('total_ammount'); ?> </th>
                            <th style="text-align: center;"><?php echo display('action'); ?> </th>

                        </tr>
                    </thead>

                    <tbody id="addinvoiceItem">
                        <?php 
                        $sl=1;
                        foreach ($getPurchaseReturnList as $details) {
                        //  d($details);
                        // echo $details->adjustment_status;
                        ?>

                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $details->supName; ?></td>
                            <td><?php echo getPrefixSetting()->purchase_return. '-' .$details->po_no; ?></td>
                            <td style="text-align: center;"><?php echo $details->return_date; ?></td>
                            <td style="text-align: right;"><?php echo number_format($details->totalamount, 2); ?></td>
                            <td style="text-align: center;">
                                <a href="<?php echo base_url("purchase/purchase/purchase_return_edit/$details->preturn_id") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                <a href="<?php echo base_url("purchase/purchase/returnview/$details->preturn_id") ?>"
                                    class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>

                            </td>
                        </tr>
                        <?php
                     $sl++;
                     } 
                     ?>


                </table>
                <?php echo $links;?>
            </div>
        </div>


    </div>
</div>