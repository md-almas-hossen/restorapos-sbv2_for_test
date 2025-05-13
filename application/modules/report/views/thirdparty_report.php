<link href="<?php echo base_url('application/modules/report/assets/css/salereportfrm2.css'); ?>" rel="stylesheet"
    type="text/css" />

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
                            <input type="text" name="from_date" class="form-control datepicker" id="from_date"
                                value="<?php //echo date('01-m-Y');?>" placeholder="<?php echo display('start_date') ?>"
                                readonly="readonly">
                        </div>

                        <div class="form-group">
                            <label class="" for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                placeholder="<?php echo "To"; ?>" value="<?php echo $today?>" readonly="readonly">
                        </div>
                        <!-- <div class="form-group"> -->
                        <!-- <label class="" for="discount"><?php //echo "Type"; ?></label> -->
                        <!-- <?php echo form_dropdown('ctypeoption',$ctypeoption,'','class="postform resizeselect form-control" id="ctypeoption"') ?> -->
                        <!-- </div> -->

                        <a id="thirdpartysreport" class="btn btn-success"><?php echo display('search') ?></a>
                        <a class="btn btn-warning print-salerpform2"
                            onclick="printDiv('purchase_div')"><?php echo display('print'); ?></a>
                        <?php echo form_close()?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('thirdparty_report') ?></h4>
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
                                <table class="table table-bordered table-striped table-hover" id="thirdslreportsf">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sale_date'); ?></th>
                                            <th><?php echo display('invoice_no'); ?></th>
                                            <th><?php echo display('customer_id'); ?></th>
                                            <!-- <th><?php echo "Waiter"; ?></th> -->
                                            <th><?php echo display('sales_type'); ?></th>
                                            <th><?php echo display('app_total_discount'); ?></th>
                                            <th><?php echo display('hird_party_commission'); ?></th>
                                            <th><?php echo display('total_ammount'); ?></th>
                                            <th><?php echo display('status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot class="salereportfrm2-foot">
                                        <tr>
                                            <th class="center-cell"></th>
                                            <th class="center-cell"></th>
                                            <th class="center-cell"></th>
                                            <!-- <th class="center-cell"></th> -->
                                            <th class="right-cell"><?php echo display('total'); ?>:</th>
                                            <th class="left-cell"></th>
                                            <th class="left-cell"></th>
                                            <th class="center-cell"></th>
                                            <th class="center-cell"></th>
                                        </tr>
                                    </tfoot>
                                </table>

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
<script src="<?php echo base_url('application/modules/report/assets/js/salereport_thirdparty.js'); ?>"
    type="text/javascript"></script>