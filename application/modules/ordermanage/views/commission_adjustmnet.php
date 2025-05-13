<script src="<?php echo base_url('application/modules/ordermanage/assets/js/commission_adjustment.js?v=3'); ?>"
    type="text/javascript"></script>
<link href="<?php echo base_url('application/modules/report/assets/css/prechasereport.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">
                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo $title; ?></legend>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php date_default_timezone_set("Asia/Dhaka"); $today = date('d-m-Y'); ?>
                                <div class="form-group">
                                    <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                    <input type="text" name="from_date" class="form-control datepicker" id="from_date"
                                        placeholder="<?php echo display('start_date') ?>" readonly="readonly">
                                </div>

                                <div class="form-group">
                                    <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                    <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                        placeholder="<?php echo "To"; ?>" value="<?php echo $today?>"
                                        readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <select name="commission_status" class="form-control" id="commission_status">
                                        <option value=""><?php echo 'Payment Status' ?></option>
                                        <option value="1">Paid</option>
                                        <option value="0">Unpaid</option>
                                        <?php //} ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="third_party" class="form-control" id="thirdparty">
                                        <option value=""><?php echo 'Third Parties' ?></option>
                                        <?php foreach($third_parties as $party){?>
                                        <option value="<?php echo $party['companyId'] ;?>">
                                            <?php echo $party['company_name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <a class="btn btn-success" onclick="getreport()"><?php echo display('search') ?></a>
                                <a class="btn btn-warning" href="#"
                                    onclick="printDiv('purchase_div')"><?php echo display('print'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-bd">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4>Commission Adjustment</h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="purchase_div">

                                    <div class="table-responsive" id="getcommissionresult">
                                        <div class="text-center">
                                            <h3> <?php echo $setting->storename;?> </h3>
                                            <h4><?php echo $setting->address;?> </h4>
                                            <h4> <?php echo display('print_date'); ?>:
                                                <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                            <h4 id="hsdate" style="display:none;">Date: <span id="sdate"></span> </h4>
                                        </div>
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr class="voucherList">
                                                    <th style="background: #abbff9!important; text-align:right"> </th>
                                                    <th style="background: #abbff9!important; text-align:right">
                                                        <?php echo display('invoice_no'); ?></th>
                                                    <th style="background: #abbff9!important; text-align:right">
                                                        <?php echo display('status'); ?></th>
                                                    <th style="background: #abbff9!important; text-align:right;">
                                                        <?php echo display('total_ammount') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="prechasereport">

                                            </tbody>












                                            <tfoot class="prechasereport-footer">
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td class="total_purchase" align="right">
                                                        &nbsp;<b><?php echo display('total') ?> </b></td>
                                                    <td class="totalprice" align="right"><b><?php echo $totalqty;?></b>
                                                    <td class="totalprice" align="right">
                                                        <b><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                            <?php echo numbershow($totalprice, $setting->showdecimal); ?>
                                                            <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                        </b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>





                                </div>
                                <div class="text-right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>