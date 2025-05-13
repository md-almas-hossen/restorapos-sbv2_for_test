<link rel="stylesheet" href="<?php echo base_url('application/modules/accounts/assets/reports/profit_loss_report_search.css'); ?>">
<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('profit_loss') ?>
                    <div class="btn-group pull-right form-inline">
                        <button id="hideUnhideButton" type="button" class="btn btn-success">Hide/Unhide Zero Value Rows</button>
                    </div>
                </h4>
                </div>
            </div>

            <div class="panel-body">
                <?php echo form_open('accounts/AccReportController/profit_loss_report_search', array('class' => 'form-inline', 'method' => 'post')) ?>
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
                <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                <?php echo form_close() ?>
                
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">

    <?php
    $path = base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png'));
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>
    <div id="printArea">
        <div class="text-center">
            <img src="<?php echo  $path; ?>" alt="logo">
            <h2 class="mb-0"><?php echo $setting->title; ?></h2>
            <h3 class="mt-10"><?php echo display('profit_loss') . ' ' . display('report') ?></h3>
            <h5><?php echo display('as_on') ?> <?php echo $date; ?></h5>
        </div>

        <div class="table-responsive">
            <table width="100%" class="table table-striped table-bordered table-hover" id="profit-loss-report">
                <thead>
                    <tr>
                        <th>Name</th>

                        <th>Opening D</th>
                        <th>Opening C</th>

                        <th>Transactional D</th>
                        <th>Transactional C</th>

                        <th>Closing D</th>
                        <th>Closing C</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($otc as $o){ ?>
                        <tr>
                        <td><?php echo $o->ledger_name;?></td>

                        <td><?php echo $o->o_debit;?></td>
                        <td><?php echo $o->o_credit;?></td>

                        <td><?php echo $o->t_debit;?></td>
                        <td><?php echo $o->t_credit;?></td>

                        <td><?php echo $o->c_debit;?></td>
                        <td><?php echo $o->c_credit;?></td>
                        </tr>
                    <?php } ?>   

                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center" id="print">
        <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="<?php echo display('print') ?>" onclick="printDiv();" />
        <input type="button" class="btn btn-success"  value="PDF" onclick="getPDF('printArea');"/>
        <!-- <a href="<?php // echo base_url($pdf) 
                        ?>" download>
                <input type="button" class="btn btn-success" name="btnPdf" id="btnPdf" value="<?php // echo display('pdf') ?>" />
            </a> -->
    </div>

</div>
<script src="<?php echo base_url('application/modules/accounts/assets/reports/profit_loss_report_search_script.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/jspdf.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/html2canvas.js'); ?>" type="text/javascript"></script>