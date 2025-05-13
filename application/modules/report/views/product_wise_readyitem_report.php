<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Product js php -->
<script src="<?php echo base_url()?>assets/js/product.js.php"></script>

<!-- Stock report start -->
<script src="<?php echo base_url('application/modules/report/assets/js/product_wise_readyitemreport.js?v=1.1'); ?>"
    type="text/javascript"></script>
<link href="<?php echo base_url('application/modules/report/assets/css/product_wise_report.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('',array('class' => 'form-vertical','id' => 'validate' ));?>
                        <?php date_default_timezone_set("Asia/Dhaka"); $today = date('m-d-Y'); ?>

                        <div class="col-md-6 col-lg-3 mb-2 mb-lg-0">

                            <label for="product_id"><?php echo display('item_name')?>:</label>
                            <select name="product_name" class="form-control" id="product_name">
                                <option value="" selected=""><?php echo display('select') ?></option>
                                <option value="-1">All</option>
                                <?php foreach($allproduct as $item){?>
                                <option value="<?php echo $item->ProductsID;?>">
                                    <?php echo $item->ProductName;?></option>
                                <?php } ?>
                            </select>

                        </div>

                        <div class="col-md-6 col-lg-2 mb-2 mb-lg-0">

                            <label for="from_date"><?php echo display('from'); ?>:
                                <i class="text-danger">*</i></label>

                            <input type="text" id="from_date" name="from_date" value="" placeholder="<?php echo display('from_date');?>"

                                class="form-control datepicker" required />


                        </div>

                        <div class="col-md-6 col-lg-2 mb-2 mb-lg-0">
                            <label for="to_date"><?php echo display('to'); ?>:
                                <i class="text-danger">*</i></label>

                            <input type="text" id="to_date" name="to_date" value="<?php echo date('d-m-Y')?>" placeholder="<?php echo display('to_date');?>"
                                class="form-control datepicker" required />
                        </div>


                        <div class="col-md-6 col-lg-2 mb-2 mb-lg-0">

                            <label for="product_id"><?php echo display('stock');?>:</label>

                            <select name="stock" class="form-control" id="stock">
                                <option value="" selected=""><?php echo display('select') ?></option>
                                <option value="1">Available</option>
                                <option value="2">Stock Out</option>
                            </select>

                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-2 d-flex align-items-center" style="margin-top:24px">
                            <button style="margin-right:10px" type="button" class="btn btn-success"
                                onclick="getreport()"><?php echo display('search') ?></button>
                            <a class="btn btn-warning" href="#"
                                onclick="printDiv('printableArea')"><?php echo display('print'); ?></a>
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
                            <h4><?php echo display('stock_report_product_wise_ready_item') ?></h4>
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
                                <h4 id="hsdate" style="display:none;"><?php echo display('date'); ?>: <span
                                        id="sdate"></span> </h4>
                                <h4><?php echo display('stock_report_product_wise_ready_item') ?></h4>
                            </div>
                            <div class="table-responsive" id="stockproduct">
                                <table id="respritbl" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('item_name') ?></th>
                                            <th class="text-center"><?php echo display('varient_name') ?></th>
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
                                </table>
                            </div>
                            <div class="text-center">
                                <h4><?php echo $setting->powerbytxt;?></h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>