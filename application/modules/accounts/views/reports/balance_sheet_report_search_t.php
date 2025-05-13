<link rel="stylesheet" href="<?php echo base_url('application/modules/accounts/assets/reports/profit_loss_report_search.css'); ?>">
<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('balance_sheet') ?>
                        <div class="btn-group pull-right form-inline">
                            <button id="hideUnhideButton" type="button" class="btn btn-success">Hide/Unhide Zero Value Rows</button>
                        </div>
                    </h4>
                </div>
            </div>

            <div class="panel-body">
                <?php echo form_open('accounts/AccReportController/balance_sheet_report_search', array('class' => 'form-inline', 'method' => 'post')) ?>
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
                
                <div class="form-group form-group-new" style="margin-left:10px">
                    <input type="checkbox" id="t_shape" name="t_shape" <?php  echo ($t_shape == 1) ? 'checked' : ''; ?> value="1"><label for="t_shape">T-Shape</label>
                </div>
                
                <div class="form-group form-group-new" style="margin-left:10px">
                    <input type="checkbox" id="with_cogs" name="with_cogs" <?php  echo ($with_cogs == 1) ? 'checked' : ''; ?> value="1"><label for="with_cogs">With COGS</label>
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
            <h3 class="mt-10"><?php echo display('balance_sheet') . ' ' . display('report') ?></h3>
            <h5><?php echo display('as_on') ?> <?php echo $date; ?></h5>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Liabilities and Equity Section -->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover balance-sheet-report" >
                            <thead>
                                <tr>
                                    <th width="50%"><strong>Particulars</strong></th>
                                    <td class="text-right">Ledger <?php echo display('amount'); ?>(<?php echo $currency; ?>)</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                            <!-- Display Liabilities -->
                                <?php if (isset($getBalanceSheetLiabilities) && !empty($getBalanceSheetLiabilities)): ?>
                                    <?php foreach ($getBalanceSheetLiabilities as $liability): ?>
                                        <?php if ($liability->level == 1): ?>
                                            <tr class="voucherList">
                                                <td style="background: #28a74533;"><strong><?php echo $liability->name; ?></strong></td>
                                                <td class="text-right" style="background: #28a74533;">
                                                    <strong><?php echo number_format($liability->credit, 2); ?></strong>
                                                </td>
                                            </tr>
                                        <?php elseif ($liability->level == 4): ?>
                                            <tr class="voucherList">
                                                <td style="padding-left:120px;"><?php echo $liability->name; ?></td>
                                                <td class="text-right"><?php echo number_format($liability->credit, 2); ?></td>
                                            </tr>
                                        <?php elseif ($liability->level == 0 && property_exists($liability, 'class')): ?>
                                            <tr class="voucherList <?php echo $liability->class; ?>">
                                                <td><strong><?php echo $liability->name; ?></strong></td>
                                                <td class="text-right"><strong><?php echo number_format($liability->credit, 2); ?></strong></td>
                                            </tr>
                                        <?php elseif ($liability->level == 0): ?>
                                            <tr class="voucherList">
                                                <td><strong><?php echo $liability->name; ?></strong></td>
                                                <td class="text-right"><strong><?php echo number_format($liability->credit, 2); ?></strong></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Display Equity -->
                                <?php if (isset($getBalanceSheetEquity) && !empty($getBalanceSheetEquity)): ?>
                                    <?php foreach ($getBalanceSheetEquity as $equity): ?>
                                        <?php if ($equity->level == 1): ?>
                                            <tr class="voucherList">
                                                <td style="background: #28a74533;"><strong><?php echo $equity->name; ?></strong></td>
                                                <td class="text-right" style="background: #28a74533;">
                                                    <strong><?php echo number_format($equity->credit, 2); ?></strong>
                                                </td>
                                            </tr>
                                        <?php elseif ($equity->level == 4):
                                            $ProfitorLossText='';
                                            $ProfitorLossAmount='';
                                            if($equity->id==$CPLcode->CPLcode){
                                                if($equity->credit>0){
                                                    $ProfitorLossText='<span class="text-success"><strong>Net Profit</strong></span>';
                                                    $ProfitorLossAmount='text-success';
                                                }else{
                                                    $ProfitorLossText='<span class="text-danger"><strong>Net Loss</strong></span>';
                                                    $ProfitorLossAmount='text-danger';
                                                }
                                            }
                                            ?>
                                            <tr class="voucherList">
                                                <td style="padding-left:120px;"><?php echo $equity->name; ?><?php echo ($equity->id==$CPLcode->CPLcode?' ( '.$ProfitorLossText.' )':'') ?></td>
                                                <td class="text-right <?php echo ($equity->id==$CPLcode->CPLcode?$ProfitorLossAmount:'') ?>"><?php echo number_format($equity->credit, 2); ?></td>
                                            </tr>
                                        <?php elseif ($equity->level == 0 && property_exists($equity, 'class')): ?>
                                            <tr class="voucherList <?php echo $equity->class; ?>">
                                                <td><strong><?php echo $equity->name; ?></strong></td>
                                                <td class="text-right"><strong><?php echo number_format($equity->credit, 2); ?></strong></td>
                                            </tr>
                                        <?php elseif ($equity->level == 0): ?>
                                            <tr class="voucherList">
                                                <td><strong><?php echo $equity->name; ?></strong></td>
                                                <td class="text-right"><strong><?php echo number_format($equity->credit, 2); ?></strong></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover balance-sheet-report">
                            <thead>
                                <tr>
                                    <th width="50%"><strong>Particulars</strong></th>
                                    <td class="text-right">Ledger <?php echo display('amount'); ?>(<?php echo $currency; ?>)</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Display Assets -->
                                    <?php if (isset($getBalanceSheetAssets) && !empty($getBalanceSheetAssets)): ?>
                                        <?php foreach ($getBalanceSheetAssets as $asset): ?>
                                            <?php if ($asset->level == 1): ?>
                                                <tr class="voucherList">
                                                    <td style="background: #28a74533;"><strong><?php echo $asset->name; ?></strong></td>
                                                    <td class="text-right" style="background: #28a74533;">
                                                        <strong><?php echo number_format($asset->debit, 2); ?></strong>
                                                    </td>
                                                </tr>
                                            <?php elseif ($asset->level == 4): ?>
                                                <tr class="voucherList">
                                                    <td style="padding-left:120px;"><?php echo $asset->name; ?></td>
                                                    <td class="text-right"><?php echo number_format($asset->debit, 2); ?></td>
                                                </tr>
                                            <?php elseif ($asset->level == 0 && property_exists($asset, 'class')): ?>
                                                <tr class="voucherList <?php echo $asset->class; ?>">
                                                    <td><strong><?php echo $asset->name; ?></strong></td>
                                                    <td class="text-right"><strong><?php echo number_format($asset->debit, 2); ?></strong></td>
                                                </tr>
                                            <?php elseif ($asset->level == 0): ?>
                                                <tr class="voucherList">
                                                    <td><strong><?php echo $asset->name; ?></strong></td>
                                                    <td class="text-right"><strong><?php echo number_format($asset->debit, 2); ?></strong></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center" id="print">
        <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="<?php echo display('print') ?>" onclick="printDiv();" />
        <input type="button" class="btn btn-success"  value="PDF" onclick="getPDF('printArea');"/>
        <!-- <a href="<?php // echo base_url($pdf) 
                        ?>" download>
                <input type="button" class="btn btn-success" name="btnPdf" id="btnPdf" value="<?php // echo display('pdf') 
                                                                                                ?>" />
            </a> -->
    </div>

</div>
<script src="<?php echo base_url('application/modules/accounts/assets/reports/balance_sheet_report_search_script.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/jspdf.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/html2canvas.js'); ?>" type="text/javascript"></script>