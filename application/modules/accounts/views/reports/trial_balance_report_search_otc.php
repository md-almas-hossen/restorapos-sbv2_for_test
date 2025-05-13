<link rel="stylesheet" href="<?php echo base_url('application/modules/accounts/assets/reports/trial_balance.css'); ?>">
<div class="row">
     <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd">
            <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
                    <div class="panel-heading">
                        <div >
                            <h4><?php echo "Trial Balance With Filter"; ?></h4>
                        </div>
                    </div>

                    <div class="panel-body"> 
                        <?php echo form_open('accounts/AccReportController/trial_balance_report_search',array('class' => 'form-inline','method'=>'post'))?>
                        <div class="form-group form-group-new">
                            <label for="dtpFromDate"><?php echo display('financial_year') ?> :</label>
                        </div>
                            <div class="form-group form-group-new empdropdown">
                                <select id="financial_year" class="form-control" name="dtpYear">
                                    <option value="">Select Financial Year</option>
                                    <?php foreach ($financial_years as $year): ?>
                                        <option 
                                            value="<?php echo $year['title']; ?>" 
                                            data-start_date="<?php echo $year['start_date']; ?>" 
                                            data-end_date="<?php echo $year['end_date']; ?>"
                                            <?php echo (isset($dtpYear) && $dtpYear == $year['title']) ? 'selected' : ''; ?>>
                                            <?php echo $year['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group form-group-new">
                                <label for="dtpFromDate"><?php echo display('from_date')?> :</label>
                                <input type="text" name="dtpFromDate"  value="<?php echo isset($dtpFromDate)? $dtpFromDate : ''; ?>" class="form-control" id="from_date" readonly/>
                            </div> 
                          <div class="form-group form-group-new">
                                <label for="dtpToDate"><?php echo display('to_date')?> :</label>
                                <input type="text" class="form-control" name="dtpToDate" value="<?php echo  isset($dtpToDate)? $dtpToDate : ''; ?>"  id="to_date" readonly/>
                            </div> 
                            <div class="form-group form-group-new">
                            <input type="checkbox" id="withDetails" name="withDetails" <?php echo  ($withDetails==1) ? 'checked' : ''; ?>  value="1"><label for="withDetails">With Details</label>
                            </div>
                            <div class="form-group form-group-new">
                                <input type="checkbox" id="withOTC" name="withOTC" <?php echo  ($withOTC==1) ? 'checked' : ''; ?> value="1"><label for="withOTC">With Opening Balnace</label>
                            </div>
                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button> 
                       <?php echo form_close()?>
                    </div>
             </div>
       </div>
 </div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div >
                    <h4><?php echo display('trial_balance')?>
                    <div class="btn-group pull-right form-inline">
                        <button id="hideUnhideButton" type="button" class="btn btn-success">Hide/Unhide Zero Value Rows</button>
                    </div></h4>
                </div>
            </div>
            <?php
				$path = base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png'));
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				$newformDate = date("d-M-Y", strtotime($dtpFromDate));  
 				$newToDate = date("d-M-Y", strtotime($dtpToDate));
				?>
            <div id="printArea">
                <div class="panel-body">
                	<div class="text-center">
                    	<img src="<?php echo  $path; ?>" alt="logo">
                        <h2 class="mb-0"><?php echo $setting->title;?></h2>
                        <h3 class="mt-10"><?php echo display('trial_balance').' '.display('report') .' with Opening Balance'?></h3>
                        <h5>As on <?php echo (!empty($newformDate)?$newformDate:''); ?> To <?php echo (!empty($newToDate)?$newToDate:''); ?></h5>
                    </div>
                    <div class="table-responsive">
                    <table width="99%" align="center" class="table table-bordered table-hover" title="TriaBalanceReport<?php echo $dtpFromDate; ?><?php echo display('to_date');?><?php echo $dtpToDate;?>" id="trial-balance-report">
						
                        <tr class="voucherList header-row"> 
                             <th style="background: #9febd3!important; text-align:center;"><?php echo display('account_name');?></th>

                             <th style="background: #9febd3!important; text-align:center;" colspan="2"><?php echo display('opening_balance');?></th>

                             <th style="background: #9febd3!important; text-align:center;" colspan="2"><?php echo 'Transactional Balance';?></th>

                             <th style="background: #9febd3!important; text-align:center;" colspan="2"><?php echo display('closing_balance');?></th>
                        </tr>
                        <tr class="header-row"> 
                             <th style="text-align:center;"></th>
                             
                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('debit');?></th>
                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('credit');?></th>

                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('debit');?></th>
                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('credit');?></th>

                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('debit');?></th>
                             <th style="text-align:right;">(<?php echo $currency;?>)<?php echo display('credit');?></th>
                        </tr>
                        <tbody>
                             <?php 
                             
                            // dd($trial_balance_data);

                            $total_o_debit = 0;
                            $total_o_credit = 0;
                            
                            $total_t_debit = 0;
                            $total_t_credit = 0;
                            
                            $total_c_debit = 0;
                            $total_c_credit = 0;

                             foreach ($trial_balance_data as $arr_key => $t_b_data) {
                                  





                                  if($arr_key){

                                  ?>
                                 <tr class="header-row">
                                    <th colspan="7" style="background: #55e3b7!important"><strong>
                                        <?php 
                                        echo $arr_key;
                                        // echo $arr_key . " ( Total ".$arr_key.": ";
                                        
                                        // foreach ($sum as $s) {
                                            // if (strpos((string)$s['nature_name'], (string)$arr_key) !== false) {
                                                // echo $s['total_amount']?$s['total_amount']:'0.00';
                                            // }
                                        // }
                                        
                                        // echo ")"; 
                                        ?>
                                    </strong></th>
                            
                                    
                                </tr>

                                   <?php 
                                  
                                   foreach ($t_b_data as $d_key => $t_b_detail_data) {
                                    



                                $total_o_debit  += $t_b_detail_data['o_debit'];
                                $total_o_credit += $t_b_detail_data['o_credit'];
                                
                                $total_t_debit  += $t_b_detail_data['t_debit'];
                                $total_t_credit += $t_b_detail_data['t_credit'];
                                
                                $total_c_debit  += $t_b_detail_data['c_debit'];
                                $total_c_credit += $t_b_detail_data['c_credit'];

                                    
                                    
                                    
                                    
                                    ?>
                                        <tr>
                                             <td>
                                                

                                                <?php echo form_open('accounts/AccReportController/general_ledger_report_search_from_trial', ['method' => 'POST', 'target' => '_blank']); ?>
                                                    <!-- CSRF Token -->
                                                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                                          
                                                    <!-- Hidden Inputs -->
                                                    <?php echo form_hidden('cmbCode', $t_b_detail_data['ledger_id']); ?>
                                                    <?php echo form_hidden('dtpFromDate', $dtpFromDate); ?>
                                                    <?php echo form_hidden('dtpToDate', $dtpToDate); ?>
                                                    <?php echo form_hidden('dtpYear', $dtpYear); ?>
                                                    <?php echo form_hidden('row', 10); ?>
                                                    <?php echo form_hidden('page', 1); ?>
                                                    
                                                    <!-- Submit Button -->
                                                    <button type="submit" style="border: none;background: transparent; width: 100%; text-align:left">
                                                        <?php echo $t_b_detail_data['ledger_name']; ?>
                                                    </button>
                                                <?php echo form_close(); ?>

        
                                            </td>
                                             <td style="text-align:right;"><?php echo $t_b_detail_data['o_debit'];?></td>
                                             <td style="text-align:right;"><?php echo $t_b_detail_data['o_credit'];?></td>

                                             <td style="text-align:right;"><?php echo $t_b_detail_data['t_debit'];?></td>
                                             <td style="text-align:right;"><?php echo $t_b_detail_data['t_credit'];?></td>

                                             <td style="text-align:right;"><?php echo $t_b_detail_data['c_debit'];?></td>
                                             <td style="text-align:right;"><?php echo $t_b_detail_data['c_credit'];?></td>
                                        </tr>
                                   
                                   <?php }?>   




                                   
                                  
                              <?php 
                                   }else{ 
                              ?>
                                    <tr>
                                        <td style="background: #55e3b7!important"><strong><?php echo $t_b_data[0]['ledger_name'];?></strong></td>
                                        
                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['o_debit'];?></td>
                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['o_credit'];?></td>

                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['t_debit'];?></td>
                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['t_credit'];?></td>

                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['c_debit'];?></td>
                                        <td style="background: #55e3b7!important;text-align:right;"><?php echo $t_b_data[0]['c_credit'];?></td>
                                   </tr>
                              <?php    
                                   }
                              }
                              ?>



                                <tr style="font-weight:bold; text-align:right; background: #55e3b7!important" >
                                    <td>Total</td>

                                    <td><?php echo $total_o_debit;?></td>
                                    <td><?php echo $total_o_credit;?></td>
                                    
                                    <td><?php echo $total_t_debit;?></td>
                                    <td><?php echo $total_t_credit;?></td>
                                    
                                    <td><?php echo $total_c_debit;?></td>
                                    <td><?php echo $total_c_credit;?></td>

                                </tr>
                         </tbody>
                    </table>
                    </div>

                </div>
            </div>
          <div class="text-center trial_balance_with_opening_btn" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv();"/>
                <input type="button" class="btn btn-success"  value="PDF" onclick="getPDF('printArea');"/>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/accounts/assets/reports/trial_balance.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/jspdf.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/html2canvas.js'); ?>" type="text/javascript"></script>