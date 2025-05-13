<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Product js php -->
<script src="<?php echo base_url('application/modules/report/assets/js/ingredient_wise_report.js'); ?>"
    type="text/javascript"></script>
<link href="<?php echo base_url('application/modules/report/assets/css/ingredient_wise_report.css'); ?>"
    rel="stylesheet" type="text/css" />
<!-- Stock report start -->
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/modules/report/assets/daterangepicker/daterangepicker.css'); ?>"
    rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo form_open('',array('class' => 'form-vertical','id' => 'validate' ));?>
                <?php date_default_timezone_set("Asia/Dhaka"); $today = date('m-d-Y'); ?>
                <div class="col-sm-12 col-md-6 col-lg-2 mb-2 mb-lg-0">

                    <label for="producttype"><?php echo display('product_type'); ?></label>

                    <select name="producttype" class="form-control" id="producttype">
                        <option value="">All</option>
                        <option value="1">Raw</option>
                        <!--<option value="3">Add-ons</option>-->
                    </select>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-2 mb-2 mb-lg-0">

                    <label for="from_date"><?php echo display('from');?></label>

                    <input class="form-control" type="text" id="from_date" name="from_date"
                        value="01/01/2022 - 01/15/2022">

                </div>
                <div class="col-sm-12 col-md-6 col-lg-2 mb-2 mb-lg-0">

                    <label for="stock"><?php echo display('stock');?></label>

                    <select class="form-control" id="stock">
                        <option value="">select Stock</option>
                        <option value="1">Available</option>
                        <option value="2">Stock Out</option>
                    </select>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-2">

                    <label for="ingredient_name"><?php echo display('ingredients');?></label>

                    <select class="form-control" id="ingredient_name" name="ingredient_name">
                        <option value="">select Ingredient</option>
                        <?php foreach($allingredient as $ingredients){?>
                        <option value="<?php echo $ingredients->id;?>">
                            <?php echo $ingredients->ingredient_name;?></option>
                        <?php } ?>
                    </select>

                </div>


                <div class="col-sm-12 col-md-6 col-lg-2">
                    <div style="margin-top:24px" class="form-group text-left row">
                        <div class="col-12 ml-10">
                            <button type="button" class="btn btn-success mb-10"
                                onclick="getreport()"><?php echo display('search') ?></button>
                            <a class="btn btn-warning mb-10" href="#" onclick="printDiv('printableArea')">
                                <?php echo display('print'); ?>
                            </a>
                        </div>
                    </div>
                </div>
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
                    <h4><?php echo display('purchase_report_ingredient') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div id="printableArea">
                    <div class="text-center">
                        <h3> <?php echo $setting->storename;?> </h3>
                        <h4><?php echo $setting->address;?> </h4>
                        <h4><?php echo $setting->phone;?> </h4>
                        <h4> <?php echo display('print_date'); ?>: <?php echo date("d/m/Y h:i:s"); ?>
                        </h4>
                        <h4 id="hsdate" style="display:none;">Date: <span id="sdate"></span> </h4>
                        <h4><?php echo display('purchase_report_ingredient') ?></h4>
                    </div>
                    <div class="table-responsive" id="stockproduct">
                        <!--<table id="respritbl" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('product_type'); ?></th>
                                            <th class="text-center"><?php echo display('ingredient_name') ?></th>
                                            <th class="text-center"><?php echo display('price') ?></th>
											<th class="text-center"><?php echo display('open_qty') ?></th>
											<th class="text-center"><?php echo display('in_quantity') ?></th>
											<th class="text-center"><?php echo display('out_quantity') ?></th>
                                            <th class="text-center"><?php echo display('expireqty') ?></th>
                                            <th class="text-center"><?php echo display('damageqty') ?></th>
											<th class="text-center"><?php echo display('closingqty') ?></th>
                                            <th class="text-center"><?php echo display('valuation') ?></th>
										</tr>
									</thead>
									<tbody>
                                     
									</tbody>
									<tfoot>
                                        
                                    </tfoot>
			                    </table>-->

                    </div>

                    <div class="text-center">
                        <h4><?php echo $setting->powerbytxt;?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/report/assets/daterangepicker/moment.min.js'); ?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/report/assets/daterangepicker/daterangepicker.js'); ?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/report/assets/daterangepicker/daterangepicker.active.js'); ?>"
    type="text/javascript"></script>