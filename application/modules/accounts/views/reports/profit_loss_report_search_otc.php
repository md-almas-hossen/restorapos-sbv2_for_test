<link rel="stylesheet" href="<?php echo base_url('application/modules/accounts/assets/reports/profit_loss_report_search.css'); ?>">


<style>
    .baby-bold{
        font-weight: 900;
    }
</style>

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

                <div class="form-group form-group-new">
                    <input type="checkbox" id="withOTC" name="withOTC" <?php echo  ($withOTC==1) ? 'checked' : ''; ?> value="1"><label for="withOTC">With Opening Balnace</label>
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
            <h3 class="mt-10"><?php echo "Income Expense" . ' ' . display('report') ?></h3>
            <h5><?php echo display('as_on') ?> <?php echo $date; ?></h5>
        </div>

        <div class="table-responsive">
            <table width="100%" class="table table-striped table-bordered table-hover" id="profit-loss-report">
                <thead>
                    <tr>
                        <th style=" background: #008d4b9e!important; justify-content: center; align-items: center;" rowspan="2"><strong>Particulers</strong></th>

                        <td style="text-align:center; background: #008d4b9e!important;" colspan="2"><b>Opening</b></td>
                        <td style="text-align:center; background: #008d4b9e!important;" colspan="2"><b>Transactional</b></td>
                        <td style="text-align:center; background: #008d4b9e!important;" colspan="2"><b>Closing</b></td>
                    </tr>
                    <tr>

                        <td style="background: #008d4b9e!important;" class="text-right">Debit</td>
                        <td style="background: #008d4b9e!important;"  class="text-right">Credit</td>

                        <td style="background: #008d4b9e!important;"  class="text-right">Debit</td>
                        <td style="background: #008d4b9e!important;"  class="text-right">Credit</td>

                        <td style="background: #008d4b9e!important;"  class="text-right">Debit</td>
                        <td style="background: #008d4b9e!important;"  class="text-right">Credit</td>

                    </tr>
                </thead>
                <tbody>
                <?php //dd($getProfitLossNature_3_Balances);?>

                    <?php if (isset($getProfitLossNature_3_Balances) && !empty($getProfitLossNature_3_Balances)): ?>

                        <?php 
                            $total_o_debit1 = 0;
                            $total_o_credit1 = 0;

                            $total_t_debit1 = 0;
                            $total_t_credit1 = 0;

                            $total_c_debit1 = 0;
                            $total_c_credit1 = 0;
                        ?>

                        <?php foreach ($getProfitLossNature_3_Balances as $key1 => $data): ?>

                            <?php if ($data->level == 1): ?>
                                <tr class="voucherList">
                                    <td colspan="7" style="/*background: #008d4b9e!important;*/"><strong><?php echo $data->name; ?></strong></td>
                                </tr>
                            <?php elseif ($data->level == 4): ?>

                                <?php 
                                    $total_o_debit1  += $data->o_debit;
                                    $total_o_credit1 += $data->o_credit;

                                    $total_t_debit1  += $data->t_debit;
                                    $total_t_credit1 += $data->t_credit;

                                    $total_c_debit1  += $data->c_debit;
                                    $total_c_credit1 += $data->c_credit;
                                ?>


                                <tr class="voucherList">
                                    <td style="padding-left:120px;"><?php echo $data->name; ?></td>
                                    
                                    <td class="text-right"><?php echo number_format($data->o_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->o_credit, 2); ?></td>

                                    <td class="text-right"><?php echo number_format($data->t_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->t_credit, 2); ?></td>

                                    <td class="text-right"><?php echo number_format($data->c_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->c_credit, 2); ?></td>

                                </tr>
                            <?php elseif ($data->level == 0 && property_exists($data, 'class')): ?>
                                <tr class="voucherList <?php echo $data->class; ?>">
                                    <td><strong><?php echo $data->name; ?></strong></td>
                                    <td class="text-right"><strong><?php echo number_format($data->credit, 2); ?></strong></td>
                                </tr>
                            <?php elseif ($data->level == 0): ?>
                                <tr class="voucherList">
                                    <td><strong><?php echo $data->name; ?></strong></td>
                                    <td class="text-right"><strong><?php echo number_format($data->credit, 2); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr class="baby-bold" style="text-align: right; background: #008d4b9e!important;">
                        <td>Total Income</td>
                        <td><?php echo $total_o_debit1;?></td>
                        <td><?php echo $total_o_credit1;?></td>

                        <td><?php echo $total_t_debit1;?></td>
                        <td><?php echo $total_t_credit1;?></td>

                        <td><?php echo $total_c_debit1;?></td>
                        <td><?php echo $total_c_credit1;?></td>
                    </tr>













                    <?php if (isset($getProfitLossNature_4_Balances) && !empty($getProfitLossNature_4_Balances)): ?>

                        <?php 
                            $total_o_debit2 = 0;
                            $total_o_credit2 = 0;

                            $total_t_debit2 = 0;
                            $total_t_credit2 = 0;

                            $total_c_debit2 = 0;
                            $total_c_credit2 = 0;
                        ?>

                        <?php foreach ($getProfitLossNature_4_Balances as $key1 => $data): ?>

                            <?php if ($data->level == 1): ?>

                                <tr class="voucherList">
                                    <td colspan="7" style="/*background: #008d4b9e!important;*/"><strong><?php echo $data->name; ?></strong></td>
                                </tr>

                            <?php elseif ($data->level == 4): ?>

                                <?php 
                                    $total_o_debit2  += $data->o_debit;
                                    $total_o_credit2 += $data->o_credit;

                                    $total_t_debit2  += $data->t_debit;
                                    $total_t_credit2 += $data->t_credit;

                                    $total_c_debit2  += $data->c_debit;
                                    $total_c_credit2 += $data->c_credit;
                                ?>

                                <tr class="voucherList">
                                    <td style="padding-left:120px;"><?php echo $data->name; ?></td>

                                    <td class="text-right"><?php echo number_format($data->o_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->o_credit, 2); ?></td>

                                    <td class="text-right"><?php echo number_format($data->t_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->t_credit, 2); ?></td>

                                    <td class="text-right"><?php echo number_format($data->c_debit, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($data->c_credit, 2); ?></td>

                                </tr>

                               


                            <?php elseif ($data->level == 0 && property_exists($data, 'class')): ?>

                                <tr class="voucherList <?php echo $data->class; ?>">
                                    <td><strong><?php echo $data->name; ?></strong></td>
                                    <td class="text-right"><strong><?php echo number_format($data->debit, 2); ?></strong></td>
                                </tr>

                            <?php elseif ($data->level == 0): ?>

                                <tr class="voucherList">
                                    <td><strong><?php echo $data->name; ?></strong></td>
                                    <td class="text-right"><strong><?php echo number_format($data->debit, 2); ?></strong></td>
                                </tr>

                            <?php endif; ?>



                        <?php endforeach; ?>

                        


                    <?php endif; ?>

                    <tr class="baby-bold" style="text-align: right; background: #008d4b9e!important;">
                        <td>Total Expense</td>
                        <td><?php echo $total_o_debit2;?></td>
                        <td><?php echo $total_o_credit2;?></td>

                        <td><?php echo $total_t_debit2;?></td>
                        <td><?php echo $total_t_credit2;?></td>

                        <td><?php echo $total_c_debit2;?></td>
                        <td><?php echo $total_c_credit2;?></td>
                    </tr>

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