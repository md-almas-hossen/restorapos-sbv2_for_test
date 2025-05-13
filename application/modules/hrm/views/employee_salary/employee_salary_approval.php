<div class="row">
	
    <div class="col-sm-12"> 
	  
        <div id="printArea">

            <div class="salary_approval">
				<?php if(empty($salary_sheet_generate_info->approved_by)){ $approve=0;}else{ $approve=1;} ?>
                <div class="row mb-10">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center fs-20">

                                    Payroll posting sheet for <?php echo $salary_sheet_generate_info->name;?>
                                    <br>(<span style="<?php echo $approve == 1?'color:#05f305;':'color:#ef1ea5;';?>"><?php echo $approve == 1?'Approved':'Not Approved';?></span>)
                                    
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="title_firsttwo">
                                <td colspan="">Description</td>
                                <td colspan="2">Amounts</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="title_fourth">Debit</td>
                                <td class="title_fifth">Credit</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="center-item">
                                <th class="center-item">Gross Salary</th>
                                <td><?php echo $currency->curr_icon.' '.numbershow($gross_salary,$settinginfo->showdecimal);?></td>
                                <td></td>
                            </tr>
                            <tr class="center-item">
                                <th class="center-item">Net Salary</th>
                                <td></td>
                                <td><?php echo $currency->curr_icon.' '.numbershow($net_salary,$settinginfo->showdecimal);?></td>
                            </tr>
                            <tr class="center-item">
                                <th class="center-item">Loans</th>
                                <td></td>
                                <td><?php echo $currency->curr_icon.' '.numbershow($loans,$settinginfo->showdecimal);?></td>
                            </tr>
                            <tr class="center-item">
                                <th class="center-item">Salary Advance</th>
                                <td></td>
                                <td><?php echo $currency->curr_icon.' '.numbershow($salary_advance,$settinginfo->showdecimal);?></td>
                            </tr>
                            <?php foreach($deducthead as $deduct){?>
                            <tr class="center-item">
                                <th class="center-item"><?php echo $deduct->sal_name;?></th>
                                <td></td>
                                <td><?php  echo $currency->curr_icon.' '.numbershow($deduct->netdeductamount,$settinginfo->showdecimal);?></td>
                            </tr>
                            <?php } ?>
                            <tr class="center-item">
                                <th class="center-item">LWP</th>
                                <td></td>
                                <td><?php echo $currency->curr_icon.' '.numbershow($lwp,$settinginfo->showdecimal);?></td>
                            </tr>
                            
                        </tbody>
                    </table>

                    <div class="form-group form-group-margin text-right">
                        <button type="button" id="post_button" class="btn btn-success w-md m-b-5" style="<?php echo $approve == 1?'display: none;':'';?>"><?php echo display('post') ?></button>
                    </div>

                </div>

                <?php echo  form_open('hrm/Payroll/payconfirm') ?>

                <div class="row" id="payment_area">

                    <!-- Payment for Net Renumeration -->

                    <div class="col-sm-12">
                        <h3>Note: Sum of all payment amount should be equal to Net Salary.</h3>
                    </div>

                    <table class="table table-bordered" id="request_table">
                        <thead>
                            <tr>
                                <th width="50%">Payment Nature</th>
                                <th class="amnt_net_salary" width="40%">Amount(Net Salary <?php echo $currency->curr_icon.' ';?><span><b><?php echo ' '.numbershow($net_salary,$settinginfo->showdecimal);?></b></span>)</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="payment_request_item">
                            <tr>
                                <td>
                                    <select name="payment_nature[]" class="form-control payment-nature-select"  required="">
                                            <option value=""> Select Payment Nature</option>
                                        <?php if ($payment_natures) { ?>
                                          <?php foreach($payment_natures as $key => $value){?>
                                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                            
                                        <?php }} ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" id="amount" name="amount[]" class="form-control payment-amount" required step=".01">
                                </td>
                                <td>
                                    <a  id="add_payment_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="add_key_payment_item('payment_request_item')"  tabindex="9"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                    <a class="btn btn-danger btn-sm"  value="<?php echo display('delete') ?>" onclick="deleteRow(this)" tabindex="3"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>

                                <input type="hidden" id="payment_list" value='<?php if ($payment_natures) { ?><?php foreach($payment_natures as $key => $value){?><option value="<?php echo $key;?>"><?php echo $value;?></option><?php }}?>' name="">

                                <input type="hidden" id="net_renumeration" value="<?php echo $net_salary;?>" name="net_renumeration">
                                

                                <input type="hidden" id="month_year" value="<?php echo $salary_sheet_generate_info->name;?>" name="month_year">
                                <input type="hidden" id="ssg_id" value="<?php echo $ssg_id;?>" name="ssg_id">


                            </tr>
                        </tbody>
                    </table>

                    

                    <div class="form-group form-group-margin text-right">
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>

                </div>
                <input type="hidden" id="advance" name="advance" value="<?php echo $salary_advance;?>">
                <input type="hidden" id="salaryloan" name="salaryloan" value="<?php echo $loans;?>">
				<input type="hidden" id="paymonth" name="paymonth" value="<?php echo $salary_sheet_generate_info->name;?>">
                <input type="hidden" id="net_salary_hidden" name="net_salary_hidden" value="<?php echo $net_salary;?>">
                

                <?php echo form_close() ?>

            </div>

        </div>

        </div>

</div>

<script src="<?php echo base_url('application/modules/hrm/assets/js/emp_salary_approval.js'); ?>" type="text/javascript"></script>

