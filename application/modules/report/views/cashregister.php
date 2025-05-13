<link href="<?php echo base_url('application/modules/report/assets/css/cashregister.css'); ?>" rel="stylesheet"
    type="text/css" />

<div id="orderdetailsp" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>

                </strong>
            </div>
            <div class="modal-body orddetailspop"> </div>
        </div>
        <div class="modal-footer"> </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo form_open('report/index',array('class' => 'form-inline'))?>
                            <?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); 
		               $statdate = date('Y-m-d', strtotime('first day of this month'));?>
                            <div class="form-group">
                                <label class="" for="from_date"><?php echo display('start_date') ?></label>

                                <input type="text" name="from_date" value="<?php //echo $statdate?>"
                                    class="form-control datepicker5" id="from_date"
                                    placeholder="<?php echo display('start_date') ?>" readonly="readonly">
                            </div>

                            <div class="form-group">
                                <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker5" id="to_date"
                                    placeholder="<?php echo "To"; ?>" value="<?php echo $today?>" readonly="readonly">
                            </div>
                            <?php if(!empty($alluser)){?>
                            <div class="form-group">
                                <?php echo form_dropdown('user',$alluser,'','class="postform resizeselect form-control " id="user"') ?>
                            </div>
                            <?php } ?>
                            <?php if(!empty($allcounter)){?>
                            <div class="form-group">
                                <?php echo form_dropdown('counterno',$allcounter,'','class="postform resizeselect form-control " id="counterno"') ?>
                            </div>
                            <?php } ?>


                            <a class="btn btn-success" onclick="getreportcash()"><?php echo display('search') ?></a>

                            <a class="btn btn-warning" href="#"
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
                                <h4><?php echo $title; ?></h4>
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
                                <div style='padding-left:6px; padding-right:6px' class="table-responsive"
                                    id="getresult2">
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
</div>


<script src="<?php echo base_url('application/modules/report/assets/js/html2canvas.min.js'); ?>" type="text/javascript">
</script>
<script src="<?php echo base_url('application/modules/report/assets/js/jspdf.min.js'); ?>" type="text/javascript">
</script>
<script src="<?php echo base_url('application/modules/report/assets/js/cashregister.js?v=5'); ?>"
    type="text/javascript"></script>