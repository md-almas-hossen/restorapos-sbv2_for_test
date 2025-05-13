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
                </h4>
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
                        <h3 class="mt-10"><?php echo display('trial_balance').' '.display('report')?></h3>
                        <h5>As on <?php echo (!empty($newformDate)?$newformDate:''); ?> To <?php echo (!empty($newToDate)?$newToDate:''); ?></h5>
                    </div>
                    <div class="table-responsive">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="trial-balance-report">
                        <thead>
                            <tr>
                                <th width="30%"><strong>Particulars</strong></th>
                                <th class="text-right"><strong>Ledger Amount (<?php echo $currency; ?>)</strong></th>
                                <th class="text-right"><strong>Sub Ledger Amount (<?php echo $currency; ?>)</strong></th>
                                <th class="text-right"><strong>Group Ledger Amount (<?php echo $currency; ?>)</strong></th>
                                <th class="text-right"><strong>Nature Amount (<?php echo $currency; ?>)</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_debit = 0;
                            $total_credit = 0;

                            if (isset($trial_balance_data) && !empty($trial_balance_data)): 
                            ?>
                                <?php foreach ($trial_balance_data as $nature_name => $nature_data): ?>
                                    <!-- Nature level display -->
                                    <tr class="voucherList">
                                        <td style="background: #008d4b9e!important;">
                                            <strong>
                                                <?php echo $nature_name; ?>
                                    
                                                <?php 
                                        echo  " ( Total ".$nature_name.": ";
                                        
                                        foreach ($sum as $s) {
                                            if (strpos((string)$s['nature_name'], (string)$nature_name) !== false) {
                                                echo $s['total_amount']?$s['total_amount']:'0.00';
                                            }
                                        }
                                        
                                        echo ")"; 
                                        ?>
                                    
                                    
                                    </strong></td>




                                        
                                        <td style="background: #008d4b9e!important;" class="text-right"></td>
                                        <td style="background: #008d4b9e!important;" class="text-right"></td>
                                        <td style="background: #008d4b9e!important;" class="text-right"></td>
                                        <td style="background: #008d4b9e!important;" class="text-right">
                                            <strong>
                                                <?php if ($nature_name == 'Assets' || $nature_name == 'Expenses'): ?>
                                                    <?php echo number_format($nature_data['nature_amount_debit'], 2); ?>
                                                <?php else: ?>
                                                    <?php echo number_format($nature_data['nature_amount_credit'], 2); ?>
                                                <?php endif; ?>
                                            </strong>
                                        </td>
                                    </tr>

                                    <?php foreach ($nature_data['groups'] as $group_name => $group_data): ?>
                                        <!-- Group level display -->
                                        <tr class="voucherList">
                                            <td style="padding-left:40px;"><strong><?php echo $group_name; ?></strong></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">
                                                <strong>
                                                    <?php if ($nature_name == 'Assets' || $nature_name == 'Expenses'): ?>
                                                        <?php echo number_format($group_data['group_amount_debit'], 2); ?>
                                                    <?php else: ?>
                                                        <?php echo number_format($group_data['group_amount_credit'], 2); ?>
                                                    <?php endif; ?>
                                                </strong>
                                            </td>
                                            <td class="text-right"></td>
                                        </tr>

                                        <?php foreach ($group_data['sub_groups'] as $sub_group_name => $sub_group_data): ?>
                                            <!-- Sub-group level display -->
                                            <tr class="voucherList">
                                                <td style="padding-left:80px;"><strong><?php echo $sub_group_name; ?></strong></td>
                                                <td class="text-right"></td>
                                                <td class="text-right">
                                                    <strong>
                                                        <?php if ($nature_name == 'Assets' || $nature_name == 'Expenses'): ?>
                                                            <?php echo number_format($sub_group_data['sub_group_amount_debit'], 2); ?>
                                                        <?php else: ?>
                                                            <?php echo number_format($sub_group_data['sub_group_amount_credit'], 2); ?>
                                                        <?php endif; ?>
                                                    </strong>
                                                </td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            </tr>

                                            <?php foreach ($sub_group_data['ledgers'] as $ledger): ?>
                                                <!-- Ledger level display -->
                                                <tr class="voucherList">
                                                    <td style="padding-left:120px;"><?php echo $ledger['ledger_name']; ?></td>
                                                    <td class="text-right">
                                                        <strong>
                                                            <?php if ($nature_name == 'Assets' || $nature_name == 'Expenses'): ?>
                                                                <?php echo number_format($ledger['debit'], 2); ?>
                                                                <?php $total_debit += $ledger['debit']; ?>
                                                            <?php else: ?>
                                                                <?php echo number_format($ledger['credit'], 2); ?>
                                                                <?php $total_credit += $ledger['credit']; ?>
                                                            <?php endif; ?>
                                                        </strong>
                                                    </td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td class="text-right"><strong><?php echo number_format($total_debit, 2); ?></strong></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"><strong><?php echo number_format($total_credit, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>

                </div>
            </div>
          <div class="text-center trial_balance_with_opening_btn" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv();"/>
                <input type="button" class="btn btn-success"  value="PDF" onclick="getPDF('printArea');"/>
                <!-- <a href="<?php //echo base_url($pdf)?>"download>
                    <input type="button" class="btn btn-success" name="btnPdf" id="btnPdf" value="PDF"/>
                </a> -->
                <!-- <a href="<?php //echo base_url()?>accounts/accounts/trialbalance_report_excel/<?php echo $dtpFromDate; ?>/<?php echo $dtpToDate;?>/<?php if(isset($withzero)){ echo 0;}else{ echo 1;} ?>" target="_blank">
                <button class="btn btn-primary btn-md" name="excel" id="excel"><i class="fa fa-file-excel-o"></i> Excel</button>
                </a> -->
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/accounts/assets/reports/trial_balance.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/jspdf.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/html2canvas.js'); ?>" type="text/javascript"></script>