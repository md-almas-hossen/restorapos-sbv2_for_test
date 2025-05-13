<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <?php // echo include($this->load->view('accounts/header/voucher_header')) ?>
        <div class="panel panel-default thumbnail">
            <div class="panel-heading panel-aligner">
                <div class="panel-title">
                    <h4><?php echo display('vouchers') ?>
                        <div class="btn-group pull-right form-inline">
                            <?php if ($this->permission->method('accounts', 'create')->access()): ?>
                                <div class="form-group">
                                    <a href="<?php echo base_url("accounts/AccVoucherController/voucher_form") ?>" class="btn btn-primary btn-md pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        <?php echo display('create_voucher'); ?></a>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                            <select name="voucher_type" id="voucher_type" class="form-control select-basic-single" required>
                                    <option value=""><?php echo  display('select_one'); ?></option>
                                    <option value="0">All Vouchers</option>
                                    <?php foreach ($voucher_types as $key => $type) { ?>
                                        <option value="<?php echo  $type->id; ?>" ><?php echo $type->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                            <select name="status" id="status" class="form-control select-basic-single" required>
                                    <option value=""><?php echo  display('select_one'); ?></option>
                                    <option value="0" >Pending</option>
                                    <option value="1" >Approved</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" id="filterordlist"><?php echo display('search') ?></button>
                                <button class="btn btn-warning" id="filterordlistrst"><?php echo display('reset') ?></button>
                            </div>
                        </div>
                    </h4>
                </div>
            </div>
            <style>
                #voucher_list td:nth-child(5){
                    text-align: right;
                }
                #voucher_list td:nth-child(6),td:nth-child(7){
                    text-align: center;
                }
            </style>
            <!-- <input type="hidden" id="voucher_type" value="1"> -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="voucher_list">
                    <thead>
                        <tr>
                            <th><?php echo display('sl_no') ?></th>
                            <th><?php echo display('voucher_no') ?></th>
                            <th><?php echo display('date') ?></th>
                            <th><?php echo display('remark') ?></th>
                            <th class="text-right"><?php echo display('amount') ?></th>
                            <th class="text-center"><?php echo display('status') ?></th>
                            <th class="text-center"><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo include($this->load->view('accounts/modal/voucher_details')) ?>
<script src="<?php echo base_url('application/modules/accounts/assets/vouchers/voucher.js'); ?>" type="text/javascript"></script>