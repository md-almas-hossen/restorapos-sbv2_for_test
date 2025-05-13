<link rel="stylesheet" href="<?php echo  base_url('application/modules/accounts/assets/css/bootstrapClass.css'); ?>">
<div class="row">
    <div style="margin-bottom: 10px" class="col-md-12">
        <div class="btn-group pull-right form-inline">
            <?php if ($this->permission->method('accounts', 'read')->access()): ?>
            <div class="form-group">
                <a href="<?php echo base_url("accounts/AccVoucherController/voucher_list") ?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('vouchers'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <?php // echo include($this->load->view('accounts/header/voucher_header')) ?>
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo  display('create_voucher') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('exception')) { ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('exception'); ?>
                </div>
                <?php } ?>
                <?php echo  form_open_multipart('accounts/AccVoucherController/voucher_save') ?>
                <input type="hidden" id="rev_code" name="rev_code">
                <div class="row">
                    <input type="hidden" name="voucher_no" value="">
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="voucher_type"
                                class="col-sm-3 col-form-label ps-0"><?php echo display('voucher_type'); ?><span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="voucher_type" id="voucher_type" class="form-control select-basic-single"
                                    required>
                                    <option value=""><?php echo  display('select_one'); ?></option>
                                    <?php foreach ($voucher_types as $key => $type) { ?>
                                    <option value="<?php echo  $type->id; ?>"><?php echo $type->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id"
                                class="col-sm-3 col-form-label ps-0"><?php echo display('date'); ?><span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control financialyear" name="date"
                                    value="<?php echo date('Y-m-d') ?>" readonly="readonly" required>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id"
                                class="col-sm-3 col-form-label ps-0"><?php echo display('remarks'); ?></label>
                            <div class="col-lg-9">
                                <textarea class="form-control" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="voucher_type"
                                class="col-sm-3 col-form-label ps-0"><?php echo display('attachment'); ?>
                                </label>
                            <div class="col-lg-9">
                                <input type="file" name="attachment" id="attachment" class="form-control">
                                <p id="file-info"></p>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 10px">
                        <table class="table table-bordered table-hover" id="debtAccVoucher">
                            <thead>
                                <tr>
                                    <th width="15%" class="text-center"><?php echo display('account_name') ;?></th>
                                    <th width="15%" class="text-center"><?php echo display('subtype') ;?></th>
                                    <th width="20%" class="text-center"><?php echo display('ledger_comment') ;?></th>
                                    <th width="10%" class="text-center"><?php echo display('debit') ;?></th>
                                    <th width="10%" class="text-center"><?php echo display('credit') ;?></th>
                                    <th width="5%" class="text-center"><?php echo display('action') ;?></th>
                                </tr>
                            </thead>
                            <tbody id="creditvoucher">
                                <tr>
                                    <td>
                                        <select name="debits[1][coa_id]" id="cmbCode_1" required
                                            class="form-control select-basic-single account_name"
                                            onchange="load_subtypeOpen(this.value,1)">
                                            <option selected disabled><?php echo display('select_amount') ;?></option>
                                            <?php foreach($accounts as $account){ ?>
                                            <option value="<?php echo $account->id;?>">
                                                <?php echo $account->account_name;?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="debits[1][subcode_id]" id="subtype_1" disabled
                                            class="form-control select-basic-single" disabled>
                                            <option value=""><?php echo display('select_subtype') ;?></option>
                                        </select>
                                        <input type="hidden" name="debits[1][subtype_id]" id="stype_1">
                                    </td>
                                    <td>
                                        <input type="text" name="debits[1][ledger_comment]"
                                            class="form-control text-right" id="ledger_comment" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[1][debit]" value=""
                                            class="form-control total_dprice text-right" id="txtDebit_1"
                                            onkeyup="calculationDebtOpen(1)" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[1][credit]" value=""
                                            class="form-control total_cprice text-right" id="txtCredit_1"
                                            onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm" type="button" value="Delete"
                                            onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                    <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                        id="reversehead_code_1" readonly="">
                                </tr>

                                <tr>
                                    <td>
                                        <select name="debits[2][coa_id]" id="cmbCode_2" required
                                            class="form-control select-basic-single account_name"
                                            onchange="load_subtypeOpen(this.value,2)">
                                            <option value=""><?php echo display('select_amount') ;?></option>
                                            <?php foreach($accounts as $account){ ?>
                                            <option value="<?php echo $account->id;?>">
                                                <?php echo $account->account_name;?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="debits[2][subcode_id]" disabled id="subtype_2"
                                            class="form-control select-basic-single">
                                            <option value=""><?php echo display('select_subtype') ;?></option>
                                        </select>
                                        <input type="hidden" name="debits[2][subtype_id]" id="stype_2">
                                    </td>
                                    <td>
                                        <input type="text" name="debits[2][ledger_comment]"
                                            class="form-control  text-right" id="ledger_comment" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[2][debit]" value=""
                                            class="form-control total_dprice text-right" id="txtDebit_2"
                                            onkeyup="calculationDebtOpen(2)" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[2][credit]" value=""
                                            class="form-control total_cprice text-right" id="txtCredit_2"
                                            onkeyup="calculationCreditOpen(2)" autocomplete="off">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm" type="button" value="Delete"
                                            onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                    <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                        id="reversehead_code_2" readonly="">
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <input type="button" id="add_more" class="btn btn-success" name="add_more"
                                            onclick="addaccountOpen('creditvoucher');" value="Add More"
                                            autocomplete="off">
                                    </td>
                                    <td colspan="2" class="text-right">
                                        <label for="reason"
                                            class="  col-form-label"><?php echo display('total') ;?></label>
                                    </td>

                                    <td class="text-right">
                                        <input type="text" id="grandTotald" class="form-control text-right "
                                            name="grand_totald" value="" readonly="readonly" autocomplete="off">
                                    </td>
                                    <td class="text-right">
                                        <input type="text" id="grandTotalc" class="form-control text-right "
                                            name="grand_totalc" value="" readonly="readonly" autocomplete="off">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success submit_button"
                            id="create_submit"><?php echo display('save');?></button>
                        <input type="hidden" name="" id="headoption"
                            value="<option value=''> Please select</option><?php foreach ($accounts as $acc2) {?><option value='<?php echo $acc2->id;?>'><?php echo $acc2->account_name;?></option><?php }?>">
                    </div>
                </div>
                <?php echo  form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/accounts/assets/openingBalance/account-ledger.js'); ?>"
    type="text/javascript"></script>
<script src="<?php echo  base_url('application/modules/accounts/assets/vouchers/row-more.js'); ?>"
    type="text/javascript"></script>