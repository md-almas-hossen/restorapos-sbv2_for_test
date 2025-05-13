<script src="<?php echo base_url('application/modules/report/assets/js/salereportfrm.js'); ?>" type="text/javascript">
</script>
<link href="<?php echo base_url('application/modules/report/assets/css/salereportfrm.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="row">
    <div class="col-sm-12 col-md-12">
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
                                placeholder="<?php echo "To"; ?>" value="<?php echo $today?>" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <?php echo form_dropdown('paytype',$paymentmethod,(!empty($card_type)?$card_type:null),'class="postform resizeselect form-control " id="paytype"') ?>
                        </div>
                        <div class="form-group">
                            <input type="text" name="invoice_no" class="form-control" id="invoie_no"
                                placeholder="<?php echo "Invoice No"; ?>">
                        </div>
                        <div class="form-group">
                            <select name="status" class="postform resizeselect form-control" id="status">
                                <option value="" selected="selected">Select Method</option>
                                <option value="1">Paid</option>
                                <option value="2">Unpaid</option>
                            </select>
                        </div>
                        <a class="btn btn-success" href="javascript:void(0);"
                            onclick="getreport()"><?php echo display('search') ?></a>

                        <a class="btn btn-warning" href="javascript:void(0);"
                            onclick="printDiv('purchase_div')"><?php echo display('print'); ?></a>
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
                            <h4><?php echo display('sell_report') ?></h4>
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

<input type="hidden" id="today" value="<?php echo date('d-m-Y'); ?>">
<script src="<?php echo base_url('application/modules/report/assets/js/salereportfrm_script.js'); ?>"
    type="text/javascript"></script>