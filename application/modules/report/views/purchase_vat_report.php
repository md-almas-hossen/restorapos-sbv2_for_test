<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('report/index',array('class' => 'form-inline'))?>
                        <?php date_default_timezone_set("Asia/Dhaka"); $today = date('d-m-Y'); ?>
                        <div class="form-group">
                            <label class="" for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="from_date" value="<?php //echo $today?>"
                                class="form-control datepicker" id="from_date"
                                placeholder="<?php echo display('start_date') ?>" readonly="readonly">

                        </div>

                        <div class="form-group">
                            <label class="" for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                placeholder="<?php echo "To"; ?>" value="<?php echo $today?>" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label class="" for="orderid"><?php echo display('invoice_no'); ?></label>
                            <input type="text" name="orderid" class="form-control" id="orderid"
                                placeholder="<?php echo display('invoice_no'); ?>" value="">
                        </div>
                        <a class="btn btn-success" onclick="getreport()"><?php echo display('search') ?></a>
                        <!-- <a class="btn btn-warning" href="#"
                                    onclick="printDiv('purchase_div')"><?php echo "Print"; ?></a> -->
                    </div>


                    <?php echo form_close()?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo (!empty($title)?$title:''); ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea">
                            <div class="text-center">
                                <h3> <?php echo $setting->storename;?> </h3>
                                <h4><?php echo $setting->address;?> </h4>
                                <!-- <h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4> -->
                            </div>
                            <div class="table-responsive" id="getresult2">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" id="today" value="<?php echo date('d-m-Y'); ?>">
<input type="hidden" id="todayEnd" value="<?php echo date('d-m-Y'); ?>">
<script>
var today = $("#today").val();
var todayEnd = $("#todayEnd").val();
$(document).ready(function() {
    "use strict";
    var csrf = $('#csrfhashresarvation').val();
    var myurl = baseurl + 'report/reports/getPurchasevatReport';
    var csrf = $('#csrfhashresarvation').val();
    var dataString = "from_date=" + today + '&to_date=' + todayEnd + '&csrf_test_name=' + csrf + '&invoice_id=';

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('#getresult2').html(data);
            $('#respritbl').DataTable({
                responsive: true,
                paging: true,
                "language": {
                    "sProcessing": lang.Processingod,
                    "sSearch": lang.search,
                    "sLengthMenu": lang.sLengthMenu,
                    "sInfo": lang.sInfo,
                    "sInfoEmpty": lang.sInfoEmpty,
                    "sInfoFiltered": "",
                    "sInfoPostFix": "",
                    "sLoadingRecords": lang.sLoadingRecords,
                    "sZeroRecords": lang.sZeroRecords,
                    "sEmptyTable": lang.sEmptyTable,
                    "paginate": {
                        "first": lang.sFirst,
                        "last": lang.sLast,
                        "next": lang.sNext,
                        "previous": lang.sPrevious
                    },
                    "oAria": {
                        "sSortAscending": ": " + lang.sSortAscending,
                        "sSortDescending": ": " + lang.sSortDescending
                    },
                    "select": {
                        "rows": {
                            "_": lang._sign,
                            "0": lang._0sign,
                            "1": lang._1sign
                        }
                    },
                    buttons: {
                        copy: lang.copy,
                        csv: lang.csv,
                        excel: lang.excel,
                        pdf: lang.pdf,
                        print: lang.print,
                        colvis: lang.colvis
                    }
                },
                dom: 'Bfrtip',
                "lengthMenu": [
                    [25, 50, 100, 150, 200, 500, -1],
                    [25, 50, 100, 150, 200, 500, "All"]
                ],
                buttons: [{
                        extend: 'copy',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'csv',
                        title: 'Report',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'excel',
                        title: 'Report',
                        className: 'btn-sm',
                        title: 'exportTitle',
                        footer: true
                    },
                    {
                        extend: 'pdf',
                        title: 'Report',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-sm',
                        footer: true
                    }
                ],
                "searching": true,
                "processing": true,

            });
        }
    });
});


function getreport() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var orderid = $('#orderid').val();
    if (from_date == '') {
        alert("Please select from date");
        return false;
    }
    if (to_date == '') {
        alert("Please select To date");
        return false;
    }

    var myurl = baseurl + 'report/reports/getPurchasevatReport';
    var csrf = $('#csrfhashresarvation').val();
    var dataString = "from_date=" + from_date + '&to_date=' + to_date + '&csrf_test_name=' + csrf + '&invoice_id=' +
        orderid;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('#getresult2').html(data);
            $('#respritbl').DataTable({
                responsive: true,
                paging: true,
                dom: 'Bfrtip',
                "lengthMenu": [
                    [25, 50, 100, 150, 200, 500, -1],
                    [25, 50, 100, 150, 200, 500, "All"]
                ],
                buttons: [{
                        extend: 'copy',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'csv',
                        title: 'Report',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'excel',
                        title: 'Report',
                        className: 'btn-sm',
                        title: 'exportTitle',
                        footer: true
                    },
                    {
                        extend: 'pdf',
                        title: 'Report',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm',
                        footer: true
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-sm',
                        footer: true
                    }
                ],
                "searching": true,
                "processing": true,

            });
        }
    });
}
</script>