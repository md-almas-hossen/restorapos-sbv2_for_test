<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('trial_balance') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo  form_open_multipart('accounts/AccReportController/trial_balance_report_search', array('class' => 'form-inline', 'method' => 'post')) ?>
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
                                <?php echo (isset($financialyears) && $financialyears->title == $year['title']) ? 'selected' : ''; ?>>
                                <?php echo $year['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-group-new">
                    <label for="dtpFromDate"><?php echo display('from_date') ?> :</label>
                    <input type="text" name="dtpFromDate" value="<?php echo isset($financialyears) ? $financialyears->start_date : ''; ?>" class="form-control" id="from_date" readonly/>
                </div>
                <div class="form-group form-group-new">
                    <label for="dtpToDate"><?php echo display('to_date') ?> :</label>
                    <input type="text" class="form-control" name="dtpToDate" value="<?php echo isset($financialyears) ? $financialyears->end_date : ''; ?>" id="to_date" readonly/>
                </div>
                <div class="form-group form-group-new">
                    <input type="checkbox" id="withDetails" name="withDetails" value="1"><label for="withDetails">With Details</label>
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