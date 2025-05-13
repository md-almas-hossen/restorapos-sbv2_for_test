<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Stock report start -->
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">
                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo "Po Information"; ?></legend>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <a class="btn btn-warning" href="#"
                                    onclick="printDiv('printableArea')"><?php echo "Print"; ?></a>
                                <span class="pull-right">
                                    <a href="<?php echo base_url("purchase/purchase/po_request_list"); ?>"
                                        class="btn btn-primary btn-md"><i class="fa fa-list" aria-hidden="true"></i>
                                        <?php echo display('po_request_list') ?></a>

                                </span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo "PO DEtails Information"; ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="printableArea" class="purchase_invoice_left">
                                    <div class="text-center">
                                        <h3> <?php echo $setting->storename;?> </h3>
                                        <h4>Supplier Name:<?php echo $supplierinfo->supName;?> </h4>
                                        <h4>Date : <?php echo date("d-M-Y", strtotime($getPOData->date));?></h4>
                                        <h4>Invoice No : <?php echo $getPOData->invoice_no;?></h4>
                                        <h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                    </div>
                                    <div class="table-responsive purchase_invoice_top" id="stockproduct">
                                        <table id="" class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><?php echo display('ingredient_name') ?>
                                                    </th>
                                                    <th class="text-center"><?php echo "Quantity"; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
									 //print_r($iteminfo);
									 $subtotal=0;
									 foreach($getPODetailsData as $item){
										$product_info = $this->purchase_model->getingredientname($item->ingredient_code);
										?>
                                                <tr>
                                                    <td><?php echo $product_info->ingredient_name;?></td>
                                                    <td class="text-center"><?php echo $item->quantity;?>
                                                        <?php echo $item->uom_short_code;?></td>
                                                </tr>
                                                <?php } ?>

                                                <tr>
                                                    <td><b>Notes</b></td>
                                                    <td colspan="4">
                                                        <p><?php echo (!empty($getPOData->note)?$getPOData->note:'');?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Terms And Condition</b></td>
                                                    <td colspan="4">
                                                        <p><?php echo (!empty($getPOData->termscondition)?$getPOData->termscondition:'');?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseinvoice_script.js'); ?>"
    type="text/javascript"></script>