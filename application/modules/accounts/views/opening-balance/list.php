<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <?php echo include($this->load->view('accounts/opening-balance/opening_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading panel-aligner">
                <div class="panel-title">
                    <h4><?php echo display('opening_balance') ?>
                        <div class="btn-group pull-right form-inline">
                            <?php if ($this->permission->method('accounts', 'create')->access()): ?>
                            <div class="form-group">
                                <a href="<?php echo base_url("accounts/AccOpeningBalanceController/opening_balanceform") ?>"
                                    class="btn btn-success btn-md pull-right"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i>
                                    <?php echo display('add_opening_balance'); ?></a>
                            </div>
                            <?php endif; ?>
                            <div class="form-group">

                                <select name="fiyear_id" class="form-control js-basic-single" id="fiyear_id"
                                    tabindex="-1">
                                    <option value="">Select Financial Year</option>
                                    <?php foreach ($financialyear as $index => $row) { ?>
                                    <option <?php echo $index === count($financialyear) - 1 ? 'selected' : ''; ?>
                                        value="<?php echo $row->fiyear_id; ?>"><?php echo $row->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group form-group-new empdropdown">
                                <select id="row" class="form-control" name="row">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                    <option value="-1">All</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success"
                                    onclick="getOpeningBalance()"><?php echo display('search') ?></button>
                                <button class="btn btn-warning"
                                    id="filterordlistrst"><?php echo display('reset') ?></button>
                            </div>
                        </div>
                    </h4>
                </div>


            </div>

            <style>
            #opb_list td:nth-child(7),
            #opb_list td:nth-child(8) {
                text-align: right;
            }
            </style>


            <div class="panel-body">
                <div class="row" id="getOpeningBalance"></div>
            </div>
        </div>
    </div>
</div>

<script>
function getOpeningBalance() {


    var fiyear_id = $('#fiyear_id').find(":selected").val();
    var row = $('#row').find(":selected").val();
    var page = 1;

    var csrf = $('#csrfhashresarvation').val();
    var myurl = baseurl + 'accounts/AccOpeningBalanceController/getOpeningBalance';

    var dataString = {
        fiyear_id: fiyear_id,
        row: row,
        page: page,
        csrf_test_name: csrf
    };

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('#getOpeningBalance').html(data);
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}


$(document).ready(function() {
    getOpeningBalance(true); // Auto-load data
});
</script>