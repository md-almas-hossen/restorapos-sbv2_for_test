<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo  include($this->load->view('accounts/opening-balance/opening_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo  display('add_opening_balance') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo  form_open_multipart('accounts/AccOpeningBalanceController/opening_balance') ?>
                <div class="row">
                    <!-- 
                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <input type="hidden" id="opening_balance_form" value="1">
                            <label for="year" class="col-sm-3 col-form-label ps-0"><?php echo  display('financial_year');?><span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="financial_year_id" class="select-basic-single" id="financial_year_id">
                                    <option value=""><?php echo  display('select_financial_year');?></option>
                                    <?php foreach($financial_years as $key => $year){ ?>
                                    <option value="<?php echo  $year->fiyear_id ;?>" <?php echo @$opening_balance[0]->financial_year_id == $year->fiyear_id ? 'selected' : '';?>><?php echo $year->title;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">

                            <label for="date" class="col-lg-3 col-form-label ps-0 label_date">
                                <?php echo  display('date');?>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="date" name="date" id="date" placeholder="Date" value="<?php echo @$opening_balance[0]->open_date;?>" required class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    -->
                    <table class="table table-bordered table-hover" id="debtAccVoucher">
                        <thead>
                            <tr>
                            <th width="25%" class="text-center"><?php echo  display('account_name') ;?></th>
                            <th width="25%" class="text-center"><?php echo  display('subtype') ;?></th>
                            <th width="20%" class="text-center"><?php echo  display('debit') ;?></th>
                            <th width="20%" class="text-center"><?php echo  display('credit') ;?></th>
                            <th width="10%" class="text-center"><?php echo  display('action') ;?></th>
                            </tr>
                    </thead>
                        <tbody id="debitvoucher">

                            <?php
                                if(!empty($opening_balance) && count($opening_balance)>0){
                                    $count = count($opening_balance);
                                }else{
                                    $count = 1;
                                }
                            ?>

                            <input type="hidden" id="countstart" value="<?php echo $count;?>">

                            <?php if (!empty($opening_balance) && count($opening_balance)>0){ ?>

                               <?php
                                    $sl = 1;
                                    $debit = 0;
                                    $credit = 0;
                               ?>
                                    <?php foreach ($opening_balance as $item){ ?>
                                        <tr>
                                            <td >
                                                <select name="opening_balances[<?php echo $sl;?>][coa_id]" id="cmbCode_<?php echo $sl;?>" class="select-basic-single" onchange="load_subtypeOpen(this.value,<?php echo $sl;?>)" >
                                                <option value=""><?php echo  display('select_amount') ;?></option>
                                                    <?php foreach($accounts as $account){ ?>
                                                        <option value="<?php echo $account->id;?>" <?php echo $item->acc_coa_id == $account->id ? 'selected' : '';?> ><?php echo $account->account_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        <td>
                                                <select name="opening_balances[<?php echo $sl;?>][subcode_id]" id="subtype_<?php echo $sl;?>" class="select-basic-single" >
                                                    <option value=""><?php echo  display('select_subtype') ;?></option>
                                                    
                                                </select>
                                                <input type="hidden" name="opening_balances[<?php echo $sl;?>][subtype_id]" id="stype_<?php echo $sl;?>">
                                        </td>
                                            <td>
                                                <input type="number" name="opening_balances[<?php echo $sl;?>][debit]" value="<?php echo $item->debit??0;?>" class="form-control total_dprice text-right" id="txtDebit_<?php echo $sl;?>" onkeyup="calculationDebtOpen(<?php echo $sl;?>)" autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="number" name="opening_balances[<?php echo $sl;?>][credit]" value="<?php echo $item->credit??0;?>" class="form-control total_cprice text-right" id="txtCredit_<?php echo $sl;?>" onkeyup="calculationCreditOpen(<?php echo $sl;?>)" autocomplete="off">
                                                <input type="hidden" name="opening_balances[<?php echo $sl;?>][is_subtype]" id="isSubtype_<?php echo $sl;?>" value="<?php echo $sl;?>" autocomplete="off">
                                            </td>
                                            <td>
                                                <button class="btn btn-danger" type="button" value="Delete" onclick="deleteRowDebtOpen(this)" autocomplete="off"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        $debit+=$item->debit??0;
                                        $credit+=$item->credit??0;
                                        $sl += 1;
                                    ?>
                                <?php } ?>
                            
                            
                                <?php }else{ ?>
                            
                            <tr>
                                <td >
                                    <select name="opening_balances[1][coa_id]" id="cmbCode_1" class="select-basic-single" onchange="load_subtypeOpen(this.value,1)">
                                    <option value=""><?php echo  display('select_amount') ;?></option>
                                    <?php foreach($accounts as $account) { ?>
                                        <option value="<?php echo $account->id;?>"><?php echo $account->account_name;?></option>
                                    <?php } ?>
                                    </select>
                            </td>
                            <td>
                                    <select name="opening_balances[1][subcode_id]" id="subtype_1" class="select-basic-single" ><option value=""><?php echo  display('select_subtype') ;?></option></select>
                                    <input type="hidden" name="opening_balances[1][subtype_id]" id="stype_1">
                            </td>
                                <td>
                                    <input type="number" name="opening_balances[1][debit]" value="" class="form-control total_dprice text-right" id="txtDebit_1" onkeyup="calculationDebtOpen(1)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="opening_balances[1][credit]" value="" class="form-control total_cprice text-right" id="txtCredit_1" onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                    <input type="hidden" name="opening_balances[1][is_subtype]" id="isSubtype_1" value="1" autocomplete="off">
                                </td>
                                <td>
                                    <button class="btn btn-danger" type="button" value="Delete" onclick="deleteRowDebtOpen(this)" autocomplete="off"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php } ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                            <td>

                            <?php if($ended_year):?>
                            <?php else:?>
                                <input type="button" id="add_more" class="btn btn-success" name="add_more" onclick="addaccountOpen('debitvoucher');" value="Add More" autocomplete="off">
                            <?php endif;?>
                                
                            
                                </td>
                                <td colspan="1" class="text-right"><label for="reason" class="  col-form-label"><?php echo  display('total') ;?></label>
                                </td>

                                <td class="text-right">
                                        <input type="text" id="grandTotald" class="form-control text-right " name="grand_totald" value="<?php echo @$debit;?>" readonly="readonly" autocomplete="off">
                                </td>
                                <td class="text-right">
                                        <input type="text" id="grandTotalc" class="form-control text-right " name="grand_totalc" value="<?php echo @$credit;?>" readonly="readonly" autocomplete="off">
                                </td>
                                </tr>
                            </tfoot>
                        </table>


                    <div class="modal-footer">


                    <?php if($ended_year):?>
                    <?php else:?>
                        <button type="submit" class="btn btn-primary submit_button" id="create_submit"><?php echo  display('save');?></button>
                    <?php endif;?>
                       
                       
                        <input type="hidden" name="" id="headoption" value="<option value=''> Please select</option><?php foreach ($accounts as $acc2) {?><option value='<?php echo  $acc2->id;?>'><?php echo  $acc2->account_name;?></option><?php }?>">
                    </div>
                    </div>
                <?php echo  form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/accounts/assets/openingBalance/account-ledger.js?v=4'); ?>" type="text/javascript"></script>
<script src="<?php echo  base_url('application/modules/accounts/assets/openingBalance/opb-row-more.js'); ?>" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('.select-basic-single').select2();
    function load_subtypeAndSetSelected(id, sl, selectedSubcodeId) {
        // var baseurl = $("#base_url").val();
        get_subtypeCode(id, sl);
        $.ajax({
            url : basicinfo.baseurl + "accounts/AccOpeningBalanceController/getSubtypeByCode/" + id,
            type: "GET",
            dataType: "json",
            success: function(data) {
                if(data != '') {
                    $('#subtype_'+sl).html(data);
                    $('#subtype_'+sl).removeAttr("disabled");
                    if (selectedSubcodeId) {
                        $('#subtype_'+sl).val(selectedSubcodeId).trigger('change');
                    }
                } else {
                    $('#subtype_'+sl).attr("disabled","disabled");
                    $('#subtype_'+sl).find('option').remove();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    // Initialize the dropdowns with the pre-selected values
    <?php foreach($opening_balance as $index => $item){ ?>
        load_subtypeAndSetSelected(<?php echo  $item->acc_coa_id;?>, <?php echo  $index + 1;?>, <?php echo  $item->acc_subcode_id ?? 'null';?>);
    <?php } ?>
});
</script>