<link rel="stylesheet" href="<?php echo  base_url('application/modules/accounts/assets/css/bootstrapClass.css'); ?>">
<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php // echo include($this->load->view('accounts/header/voucher_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo  display('edit_voucher') ?>
                    <div class="btn-group pull-right form-inline">
                            <?php if ($this->permission->method('accounts', 'read')->access()): ?>
                                <div class="form-group">
                                    <a href="<?php echo base_url("accounts/AccVoucherController/voucher_list") ?>" class="btn btn-primary btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                                        <?php echo display('vouchers'); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                </h4>
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
                    <input type="hidden" name="id" value="<?php echo $voucher_master->id?>">
                    <input type="hidden" name="voucher_no" value="">
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="voucher_type" class="col-sm-3 col-form-label ps-0"><?php echo display('voucher_type'); ?><span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="voucher_type" id="voucher_type" class="form-control select-basic-single" required>
                                    <option value=""><?php echo  display('select_one'); ?></option>
                                    <?php foreach ($voucher_types as $key => $type) { ?>
                                        <option value="<?php echo  $type->id; ?>" <?php echo ($type->id==$voucher_master->VoucharTypeId?'selected':'');?> ><?php echo $type->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id" class="col-sm-3 col-form-label ps-0"><?php echo display('date'); ?><span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control financialyear" name="date" value="<?php echo ($voucher_master->VoucherDate?$voucher_master->VoucherDate:date('Y-m-d')) ?>" readonly="readonly" required>
                            </div>
                        </div>

                        


                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id" class="col-sm-3 col-form-label ps-0"><?php echo display('remarks'); ?></label>
                            <div class="col-lg-9">
                                <textarea class="form-control" name="remarks"><?php echo $voucher_master->Remarks??'';?></textarea>
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


                        <div class="form-group mb-2 mx-0 row">
                            <label for="voucher_type"
                                class="col-sm-3 col-form-label ps-0"> Old Document
                            </label>
                            <div class="col-lg-9">

                                <input type="hidden" name="old_image_path" value="<?php echo $voucher_master->documentURL;?>">

                                <a style="margin-top: 1%;" class="btn btn-xs btn-danger" style="margin-right:10px" target="_blank"
                                               href="<?php echo base_url().$voucher_master->documentURL;?>"> View Document</a>

                                </a>
                            </div>
                        </div>

                    </div>

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
                            <?php 
                             $i = 1;
                             $reverse_code = '';
                             $debit = 0;
                             $credit = 0;
                             foreach($voucher_details as $key => $voucher){
                                $i++;
                                //$reverse_code = $voucher->reverse_code;
                                $debit += $voucher->Dr_Amount;
                                $credit += $voucher->Cr_Amount;
                            ?>
                            <tr>
                                <td >
                                    <select name="debits[<?php echo $key+1;?>][coa_id]" id="cmbCode_<?php echo $key+1;?>" required class="form-control select-basic-single account_name" onchange="load_subtypeOpen(this.value,<?php echo $key+1;?>)">
                                     <option selected disabled><?php echo display('select_amount') ;?></option>
                                      <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->id;?>" <?php echo ($account->id == $voucher->acc_coa_id ? 'selected' : '');?>><?php echo $account->account_name;?></option>
                                      <?php } ?>
                                    </select>
                               </td>
                               <td>
                                    <select name="debits[<?php echo $key+1;?>][subcode_id]" id="subtype_<?php echo $key+1;?>" class="form-control select-basic-single" <?php echo (empty($voucher->subtype_id)?'disabled':'') ;?>>
                                        <option value=""><?php echo display('select_subtype') ;?></option>
                                            <?php if($voucher->subtype_id){?>
                                                <option value="<?php echo $voucher->subcode_id?>" selected><?php echo $voucher->name??''?></option>
                                            <?php } ?>
                                    </select>
                                    <input type="hidden" name="debits[<?php echo $key+1;?>][subtype_id]" id="stype_<?php echo $key+1;?>" value="<?php echo $voucher->subtype_id;?>">
                               </td>
                                <td>
                                    <input type="text" name="debits[<?php echo $key+1;?>][ledger_comment]" class="form-control text-right" id="ledger_comment" autocomplete="off" value="<?php echo $voucher->LaserComments;?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[<?php echo $key+1;?>][debit]" value="<?php echo $voucher->Dr_Amount?>" class="form-control total_dprice text-right" id="txtDebit_<?php echo $key+1;?>" onkeyup="calculationDebtOpen(<?php echo $key+1;?>)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[<?php echo $key+1;?>][credit]" value="<?php echo $voucher->Cr_Amount?>" class="form-control total_cprice text-right" id="txtCredit_<?php echo $key+1;?>" onkeyup="calculationCreditOpen(<?php echo $key+1;?>)" autocomplete="off">
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm" type="button" value="Delete" onclick="deleteRowDebtOpen(this)" autocomplete="off"><i class="fa fa-trash"></i></button>
                                </td>
                                    <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code" id="reversehead_code_<?php echo $key+1;?>" readonly="">
                            </tr>
                            <?php } ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <input type="button" id="add_more" class="btn btn-success" name="add_more" onclick="addaccountOpen('creditvoucher');" value="Add More" autocomplete="off">
                                </td>
                                <td colspan="2" class="text-right">
                                    <label for="reason" class="  col-form-label"><?php echo display('total') ;?></label>
                                </td>

                                <td class="text-right">
                                    <input type="text" id="grandTotald" class="form-control text-right " name="grand_totald" value="<?php echo $debit;?>" readonly="readonly" autocomplete="off">
                                </td>
                                <td class="text-right">
                                    <input type="text" id="grandTotalc" class="form-control text-right " name="grand_totalc" value="<?php echo $credit;?>" readonly="readonly" autocomplete="off">
                                </td>
                            </tr>
                            
                        </tfoot>
                    </table>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success submit_button" id="create_submit"><?php echo display('update');?></button>
                        <input type="hidden" name="" id="headoption" value="<option value=''> Please select</option><?php foreach ($accounts as $acc2) {?><option value='<?php echo $acc2->id;?>'><?php echo $acc2->account_name;?></option><?php }?>">
                    </div>
                </div>
                <?php echo  form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/accounts/assets/openingBalance/account-ledger.js'); ?>" type="text/javascript"></script>
<script src="<?php echo  base_url('application/modules/accounts/assets/vouchers/row-more.js'); ?>" type="text/javascript"></script>