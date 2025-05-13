<div class="col-md-12" id="vaucherPrintArea">
    <div class="row">
        <div class="col-md-3">
            <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
        </div>
        <div class="col-md-6 text-center">
            <h2><?php echo $settings_info->title; ?></h2>
            <?php if($voucharhead->IsApprove==0){ ?>
                <h4 class="text-danger">Pending Voucher</h4>
            <?php }else{ ?>
                <h4 class="text-success">Approved Voucher</h4>
            <?php } ?>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-12">
            <div class="pull-right" style="margin-right:20px;">
                <label class="font-weight-600 mb-0"><?php echo display('voucher_type') ?></label> : <?php echo $voucharhead->VoucherType; ?><br>
                <label class="font-weight-600 mb-0"><?php echo display('financial_year') ?></label> : <?php echo $voucharhead->Fiyear_name; ?><br>
                <label class="font-weight-600 mb-0"><?php echo display('voucher_no') ?></label> : <?php echo $voucharhead->VoucherNumber; ?><br>
                <label class="font-weight-600 mb-0"><?php echo display('date') ?></label> : <?php echo date('d/m/Y', strtotime($voucharhead->VoucherDate)); ?>
            </div>
        </div>
    </div>

    <table class="datatable table table-bordered table-hover">

        <thead>
            <tr>
                <th class="text-center"><?php echo display('particulars'); ?></th>
                <th class="text-center">Comments</th>
                <th class="text-center"><?php echo display('debit') ?></th>
                <th class="text-center"><?php echo display('credit') ?></th>

            </tr>


        </thead>
        <tbody>
            <?php
            $Debit = 0;
            $Credit = 0;
            if (!empty($results)) {
                foreach ($results as $row) {
                    //print_r($row);
                    if ($row->Dr_Amount != 0) {
                        $Debit += $row->Dr_Amount;
                    }
                    if ($row->Cr_Amount != 0) {
                        $Credit += $row->Cr_Amount;
                    }
                    // $subtypeinfo = $this->db->select('*')->from('acc_subcode')->where('id', $row->subcode_id)->where('subTypeID', $row->subtype_id)->get()->row();
            ?>
                    <tr>
                        <td><strong style="font-size: 15px;;"><?php echo $row->account_name; ?></strong><br>

                        </td>
                        <td><?php echo $row->LaserComments ?></td>
                        <td class="text-right"><?php echo $row->Dr_Amount; ?></td>
                        <td class="text-right"><?php echo $row->Cr_Amount; ?></td>
                    </tr>
                <?php } ?>
                
            <?php } else { ?>
                <tr>
                    <td colspan="5" class="text-center text-danger"><?php echo display('data_is_not_available'); ?></td>
                </tr>
            <?php } ?>

        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="2"><?php echo display('total'); ?></th>
                <th class="text-right"><?php echo number_format($Debit, 2); ?></th>
                <th class="text-right"><?php echo number_format($Credit, 2); ?></th>
            </tr>
            <tr>

                <th class="" colspan="4"><?php echo display('iword'); ?> : <?php
                                                                            echo ucwords(numberToWord($voucharhead->TranAmount)) . " Only."; ?></th>

            </tr>
            <tr>
                <th class="" colspan="4"><?php echo display('remark') ?> : <?php echo $voucharhead->Remarks; ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="form-group row mt-100-50">
        <label for="name" class="col-lg-3 col-md-3 col-sm-3  col-form-label text-center">
            <div class="border-top"><?php echo display('prepared_by') ?></div>
        </label>
        <label for="name" class="col-lg-3 col-md-3 col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo display('checked_by') ?></div>
        </label>
        <label for="name" class="col-lg-3 col-md-3 col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo display('authorized_signature') ?></div>
        </label>
        <label for="name" class="col-lg-3 col-md-3 col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo "Pay By" ?></div>
        </label>
    </div>
</div>