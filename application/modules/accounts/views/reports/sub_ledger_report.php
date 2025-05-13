<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo display('sub_ledger') ?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
               <div class="form-inline">
                <div class="form-group form-group-margin">
                    <label for="employeelist"><?php echo display('subtype') ?> <b class="text-danger">*</b>:</label>
                </div>
                <div class="form-group form-group-new empdropdown">

                    <select class="form-control" name="subtype_id" id="subtype_id">
                        <option value=""></option>
                        <?php
                        foreach ($subtypes as $subtype) {
                        ?>
                            <option value="<?php echo $subtype->id; ?>"><?php echo $subtype->name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group form-group-margin">
                    <label for="employeelist"><?php echo display('coa_head') ?> <b class="text-danger">*</b>:</label>
                </div>
                <div class="form-group form-group-new empdropdown">

                    <select class="form-control" name="acc_coa_id" id="acc_coa_id">
                    </select>
                </div>
                <div class="form-group form-group-margin">
                    <label for="employeelist"><?php echo display('subcode') ?> <b class="text-danger">*</b> :</label>
                </div>
                <div class="form-group form-group-new empdropdown">

                    <select class="form-control" name="acc_subcode_id" id="acc_subcode_id">
                        <option value=""></option>
                    </select>
                </div>
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
                    <label for="from_date"><?php echo display('from_date') ?> :</label>
                    <input type="text" value="<?php echo isset($financialyears) ? $financialyears->start_date : ''; ?>" class="form-control" id="from_date" readonly/>
                </div>
                <div class="form-group form-group-new">
                    <label for="to_date"><?php echo display('to_date') ?> :</label>
                    <input type="text" class="form-control" value="<?php echo isset($financialyears) ? $financialyears->end_date : ''; ?>" id="to_date" readonly/>
                </div>

                <div class="form-group form-group-new">
                    <label for="row">Row :</label>
                </div>
                <div class="form-group form-group-new empdropdown">
                    <select id="row" class="form-control" name="row">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="-1">All</option>
                    </select>
                </div>


                <button class="btn btn-success" onclick="getSubLedger()"><?php echo display('search') ?></button>
                        </div>
            </div>
        </div>
    </div>
</div>



<div class="row" id="getSubLedgerReport"></div>

<script src="<?php echo base_url('application/modules/accounts/assets/reports/sub_ledger_report.js?v=2'); ?>" type="text/javascript"></script>

<script>

function getSubLedger() {

    const fields = [
        { id: '#subtype_id', message: "Please select Sub Type!" },
        { id: '#acc_coa_id', message: "Please select COA Head!" },
        { id: '#acc_subcode_id', message: "Please select Sub Code!" }
    ];

    for (const field of fields) {
        if ($(field.id).find(":selected").val() == 0 || $(field.id).find(":selected").val() == "") {
            alert(field.message);
            return false;
        }
    }

    var subtype_id = $('#subtype_id').find(":selected").val();
    var acc_coa_id = $('#acc_coa_id').find(":selected").val();
    var acc_subcode_id = $('#acc_subcode_id').find(":selected").val();
    var financial_year = $('#financial_year').find(":selected").val();
    var dtpFromDate = $('#from_date').val();
    var dtpToDate = $('#to_date').val();
    var row = $('#row').find(":selected").val();
    var page = 1;

    var csrf = $('#csrfhashresarvation').val();
    var myurl =baseurl+'accounts/AccReportController/sub_ledger_report_search';

    var dataString = {
        subtype_id: subtype_id,
        acc_coa_id: acc_coa_id,
        acc_subcode_id: acc_subcode_id,
        
        dtpYear: financial_year,
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
            $('#getSubLedgerReport').html(data);
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}

</script>


