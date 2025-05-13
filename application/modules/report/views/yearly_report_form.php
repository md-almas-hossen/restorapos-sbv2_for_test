<?php
$currentYear = date("Y");
$startYear = $currentYear - 5;
$endYear = $currentYear + 1;
?>

<link href="<?php echo base_url('application/modules/report/assets/css/schargeReport.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('report/reports/getYearlyReport', array('class' => 'form-inline')) ?>


                        <?php date_default_timezone_set("Asia/Dhaka");
								$today = date('d-m-Y'); ?>
                        <div class="form-group">

                            <label class="" for="from_date"><?php echo display('year'); ?></label>

                            <select name="year" id="year" class="form-control">
                                <?php

										for ($year = $startYear; $year <= $endYear; $year++) {
											echo "<option " . ($year == $currentYear ? 'selected' : '') . " value=\"$year\">$year</option>";
										}

										?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="" for="from_date"><?php echo display('status'); ?></label>
                            <select name="status" class="postform resizeselect form-control" id="status">
                                <option value="" selected="selected">Select Status</option>
                                <option value="1">Paid</option>
                                <option value="2">Unpaid</option>
                            </select>
                        </div>

                        <div class="form-group" style="position: relative; top: 12px; left: 15px;">
                            <a class="btn btn-success" onclick="getYearlyReport()"><?php echo display('search') ?></a>
                            <a class="btn btn-warning" href="#"
                                onclick="printDiv('purchase_div')"><?php echo display('print'); ?></a>
                        </div>


                    </div>


                    <?php echo form_close() ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo $title;?></h4>
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

                            <!-- Invoice Results  -->
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

<input type="hidden" id="today" value="<?php echo date('d-m-Y'); ?>">
<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    $("#respritbl_filter").hide();
    $(".dt-buttons").hide();
    $(".dataTables_info").hide();
    $("#respritbl_paginate").hide();
    $("#respritbl_length").hide();
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

function getYearlyReport() {

    year = $('#year').val();
    var billstatus = $('#status').val();

    var myurl = baseurl + 'report/reports/getYearlyReport/' + year + '/' + billstatus;

    $.ajax({
        type: "GET",
        url: myurl,
        data: {
            year: year,
            billstatus: billstatus

        },
        success: function(data) {

            $('#getresult2').html(data);
            //$("#sdate").text($("#")from_date+' - '+to_date);


        }
    });

}
</script>