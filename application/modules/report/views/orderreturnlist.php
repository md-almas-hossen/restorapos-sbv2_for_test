<?php 

// dd($returnitem);
?>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('report/index',array('class' => 'form-inline'))?>
                        <?php $today = date('d-m-Y'); ?>
                        <div class="form-group">
                            <label class="" for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="from_date" class="form-control datepicker" id="from_date"
                                value="<?php //echo date('d-m-Y');?>" placeholder="<?php echo display('start_date') ?>"
                                readonly="readonly">
                        </div>

                        <div class="form-group">
                            <label class="" for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>"
                                readonly="readonly">
                        </div>

                        <div class="form-group">

                            <input type="text" name="invoice_no" class="form-control" id="invoie_no"
                                placeholder="<?php echo display('invoice_no'); ?>">
                        </div>
                        <div class="form-group">
                            <select id="pay_type" class="form-control">
                                <!-- <option value="0">Pay Status</option> -->
                                <option value="1">Adjustment</option>
                                <option value="2">Cash Payment</option>
                            </select>
                        </div>
                        <a class="btn btn-success" onclick="getReturnReport()"><?php echo display('search') ?></a>

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
                            <h4><?php echo $title?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="purchase_div">
                            <!-- Invoice Title Start -->
                            <div style="margin-bottom: 20px;" class="text-center">
                                <h3> <?php echo $setting->storename;?> </h3>
                                <h4 style="margin-bottom: 0px;"><?php echo $setting->address;?> </h4>
                                <h4 style="margin: 2px;"><?php echo $title; ?></h4>
                                <span id="hsdate" style="display:none;"><?php echo display('date'); ?>: <span
                                        id="sdate"></span> </span>

                            </div>
                            <!-- Invoice Title End-->
                            <div style='padding-left:6px; padding-right:6px' class="table-responsive" id="getresult2">
                            </div>
                            <!-- Table Footer start -->
                            <div style='padding-left:6px; padding-right:6px'
                                class="d-flex justify-content-between align-items-center">
                                <p><?php echo $setting->powerbytxt;?></p>
                                <p style="font-style: italic">
                                    <?php echo display('print_date'); ?>:
                                    <?php echo date("d/m/Y h:i:s"); ?> </p>
                            </div>
                            <!-- Table Footer End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
function getReturnReport() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var invoie_no = $('#invoie_no').val();
    var pay_type = $('#pay_type').val();


    if (from_date == '') {
        alert("Please select from date");
        return false;
    }
    if (to_date == '') {
        alert("Please select To date");
        return false;
    }
    var myurl = baseurl + 'report/reports/get_order_ReturnReport';
    var csrf = basicinfo.csrftokeng;
    var dataString = "from_date=" + from_date + '&to_date=' + to_date + '&pay_type=' + pay_type + '&invoie_no=' +
        invoie_no + '&csrf_test_name=' + csrf;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            //   console.log(data);

            $('#getresult2').html(data);
            $('#respritbl').DataTable({
                responsive: true,
                paging: true,
                dom: 'Blfrtip',
                lengthChange: true,
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