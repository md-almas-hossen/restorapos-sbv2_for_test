<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo display('general_ledger') ?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-inline">
                    <!-- <input name="csrfhash" id="csrfhashresarvation" type="hidden" value="<?php //echo $this->security->get_csrf_hash(); ?>" /> -->
                    <div class="form-group form-group-margin">
                        <label for="employeelist"><?php echo display('transaction_head') ?> <b class="text-danger">*</b>
                            :</label>
                    </div>
                    <div class="form-group form-group-new empdropdown">

                        <select class="form-control" name="cmbCode" id="cmbCode">
                            <option value=""></option>
                            <?php
                            foreach ($general_ledger as $g_data) {
                            ?>
                            <option value="<?php echo $g_data->id; ?>"><?php echo $g_data->account_name; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group form-group-new">
                        <label for="financial_year"><?php echo display('financial_year') ?> :</label>
                    </div>
                    <div class="form-group form-group-new empdropdown">
                        <select id="financial_year" class="form-control" name="dtpYear">
                            <option value="">Select Financial Year</option>
                            <?php foreach ($financial_years as $year): ?>
                            <option value="<?php echo $year['title']; ?>"
                                data-start_date="<?php echo $year['start_date']; ?>"
                                data-end_date="<?php echo $year['end_date']; ?>"
                                <?php echo (isset($financialyears) && $financialyears->title == $year['title']) ? 'selected' : ''; ?>>
                                <?php echo $year['title']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>




                    <div class="form-group form-group-new">
                        <label for="from_date"><?php echo display('from_date') ?> :</label>
                        <input type="text"
                            value="<?php echo isset($financialyears) ? $financialyears->start_date : ''; ?>"
                            class="form-control" id="from_date" readonly />
                    </div>
                    <div class="form-group form-group-new">
                        <label for="to_date"><?php echo display('to_date') ?> :</label>
                        <input type="text" class="form-control"
                            value="<?php echo isset($financialyears) ? $financialyears->end_date : ''; ?>" id="to_date"
                            readonly />
                    </div>






                    <div class="form-group form-group-new">
                        <label for="row">Rows :</label>
                    </div>
                    <div class="form-group form-group-new empdropdown">
                        <select id="row" class="form-control" name="row">
                            <option value="">Select Row</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="-1">All</option>
                        </select>
                    </div>
                    <button class="btn btn-success"
                        onclick="getGeneralLedger()"><?php echo display('search') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" id="getgeneralLedgerreport"></div>


<script>
function getGeneralLedger() {

    var cmbCode = $('#cmbCode').find(":selected").val();

    if (cmbCode == 0 || cmbCode == "") {
        alert("Please select Transaction Head !");
        return false;
    }

    var dtpYear = $('#financial_year').find(":selected").val();
    var dtpFromDate = $('#from_date').val();
    var dtpToDate = $('#to_date').val();
    var row = $('#row').find(":selected").val();
    var page = 1;

    var csrf = $('#csrfhashresarvation').val();
    var myurl = baseurl + 'accounts/AccReportController/general_ledger_report_search';

    var dataString = {
        cmbCode: cmbCode,
        dtpYear: dtpYear,
        dtpFromDate: dtpFromDate,
        dtpToDate: dtpToDate,
        row: row,
        page: page,
        csrf_test_name: csrf
    };

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('#getgeneralLedgerreport').html(data);
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}
</script>