<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/metismenujs/metismenujs.min.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/css/pos-topping.css'); ?>">

<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/css/posordernew.css'); ?>">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/postop.js'); ?>" type="text/javascript">
</script>

<input name="site_url" type="hidden" value="<?php echo $soundsetting->nofitysound; ?>" id="site_url">

<?php $subtotal = 0;
$ptdiscount = 0; ?>
<div id="payoverlay">
    <div class="pay-spinner">
        <span class="spinnerpay"></span>
    </div>
</div>
<?php if ($possetting->closingbutton == 1) { ?>
<div id="openregister" class="modal fade  bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="openclosecash">

        </div>
    </div>
</div>
<?php } ?>
<div class="modal fade" id="vieworder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="exampleModalLabel"><?php echo display('foodnote') ?></h5>

            </div>
            <div class="modal-body pd-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="user_email"><?php echo display('foodnote') ?></label>
                            <textarea cols="45" rows="3" id="foodnote" class="form-control" name="foodnote"></textarea>
                            <input name="foodqty" id="foodqty" type="hidden" />
                            <input name="foodgroup" id="foodgroup" type="hidden" />
                            <input name="foodid" id="foodid" type="hidden" />
                            <input name="foodvid" id="foodvid" type="hidden" />
                            <input name="foodcartid" id="foodcartid" type="hidden" />

                        </div>
                    </div>
                    <div class="col-md-4">
                        <a onclick="addnotetoitem()" class="btn btn-success btn-md text-white"
                            id="notesmbt"><?php echo display('addnotesi') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-none">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <div class="calcbody">
                        <form name="calc">
                            <div class="cacldisplay">
                                <input type="text" placeholder="0" name="displayResult" />
                            </div>
                            <div class="calcbuttons">
                                <div class="calcrow">
                                    <input type="button" name="c0" value="C" placeholder="0"
                                        onClick="calcNumbers(c0.value)">
                                    <button type="button" data-dismiss="modal" aria-label="Close"> <i
                                            class="fa fa-power-off" aria-hidden="true"></i> </button>
                                </div>
                                <div class="calcrow">
                                    <input type="button" name="b7" value="7" onClick="calcNumbers(b7.value)">
                                    <input type="button" name="b8" value="8" onClick="calcNumbers(b8.value)">
                                    <input type="button" name="b9" value="9" onClick="calcNumbers(b9.value)">
                                    <input type="button" name="addb" value="+" onClick="calcNumbers(addb.value)">
                                </div>
                                <div class="calcrow">
                                    <input type="button" name="b4" value="4" onClick="calcNumbers(b4.value)">
                                    <input type="button" name="b5" value="5" onClick="calcNumbers(b5.value)">
                                    <input type="button" name="b6" value="6" onClick="calcNumbers(b6.value)">
                                    <input type="button" name="subb" value="-" onClick="calcNumbers(subb.value)">
                                </div>
                                <div class="calcrow">
                                    <input type="button" name="b1" value="1" onClick="calcNumbers(b1.value)">
                                    <input type="button" name="b2" value="2" onClick="calcNumbers(b2.value)">
                                    <input type="button" name="b3" value="3" onClick="calcNumbers(b3.value)">
                                    <input type="button" name="mulb" value="*" onClick="calcNumbers(mulb.value)">
                                </div>
                                <div class="calcrow">
                                    <input type="button" name="b0" value="0" onClick="calcNumbers(b0.value)">
                                    <input type="button" name="potb" value="." onClick="calcNumbers(potb.value)">
                                    <input type="button" name="divb" value="/" onClick="calcNumbers(divb.value)">
                                    <input type="button" class="calcred" value="="
                                        onClick="displayResult.value = eval(displayResult.value)">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="payprint" class="modal fade  bd-example-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('sl_payment'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="payments"
                                            class="col-sm-4 col-form-label"><?php echo display('paymd'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <?php $card_type = 4;
                      echo form_dropdown('card_typesl', $paymentmethod, (!empty($card_type) ? $card_type : null), 'class="postform resizeselect form-control" id="card_typesl"') ?>
                                        </div>
                                    </div>
                                    <div id="cardarea wpr_100 display-none">
                                        <div class="form-group row">
                                            <label for="card_terminal"
                                                class="col-sm-4 col-form-label"><?php echo display('crd_terminal'); ?></label>
                                            <div class="col-sm-7 customesl">
                                                <?php echo form_dropdown('card_terminal', $terminalist, '', 'class="postform resizeselect form-control" id="card_terminal"') ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="bank"
                                                class="col-sm-4 col-form-label"><?php echo display('sl_bank'); ?></label>
                                            <div class="col-sm-7 customesl">
                                                <?php echo form_dropdown('bank', $banklist, '', 'class="postform resizeselect form-control" id="bank"') ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="4digit"
                                                class="col-sm-4 col-form-label"><?php echo display('lstdigit'); ?></label>
                                            <div class="col-sm-7 customesl">
                                                <input type="text" class="form-control" id="last4digit"
                                                    name="last4digit" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="4digit"
                                            class="col-sm-4 col-form-label"><?php echo display('total_amount'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <input type="hidden" id="maintotalamount" name="maintotalamount" value="" />
                                            <input type="text" class="form-control" id="totalamount" name="totalamount"
                                                readonly="readonly" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="4digit"
                                            class="col-sm-4 col-form-label"><?php echo display('cuspayment'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <input type="number" class="form-control" id="paidamount" name="paidamount"
                                                placeholder="0" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="4digit"
                                            class="col-sm-4 col-form-label"><?php echo display('cuspayment'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <input type="text" class="form-control" id="change" name="change"
                                                readonly="readonly" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <div class="col-sm-11 pr-0">
                                            <button type="button" id="paidbill" class="btn btn-success w-md m-b-5"
                                                onclick="orderconfirmorcancel()"><?php echo display('pay_print'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <form name="placenum">
                                        <div class="grid-container">
                                            <input type="button" class="grid-item" name="n1" value="1"
                                                onClick="inputNumbers(n1.value)">
                                            <input type="button" class="grid-item" name="n2" value="2"
                                                onClick="inputNumbers(n2.value)">
                                            <input type="button" class="grid-item" name="n3" value="3"
                                                onClick="inputNumbers(n3.value)">
                                            <input type="button" class="grid-item" name="n4" value="4"
                                                onClick="inputNumbers(n4.value)">
                                            <input type="button" class="grid-item" name="n5" value="5"
                                                onClick="inputNumbers(n5.value)">
                                            <input type="button" class="grid-item" name="n6" value="6"
                                                onClick="inputNumbers(n6.value)">
                                            <input type="button" class="grid-item" name="n7" value="7"
                                                onClick="inputNumbers(n7.value)">
                                            <input type="button" class="grid-item" name="n8" value="8"
                                                onClick="inputNumbers(n8.value)">
                                            <input type="button" class="grid-item" name="n9" value="9"
                                                onClick="inputNumbers(n9.value)">
                                            <input type="button" class="grid-item" name="n10" value="10"
                                                onClick="inputNumbers(n10.value)">
                                            <input type="button" class="grid-item" name="n20" value="20"
                                                onClick="inputNumbers(n20.value)">
                                            <input type="button" class="grid-item" name="n50" value="50"
                                                onClick="inputNumbers(n50.value)">
                                            <input type="button" class="grid-item" name="n100" value="100"
                                                onClick="inputNumbers(n100.value)">
                                            <input type="button" class="grid-item" name="n500" value="500"
                                                onClick="inputNumbers(n500.value)">
                                            <input type="button" class="grid-item" name="n1000" value="1000"
                                                onClick="inputNumbers(n1000.value)">
                                            <input type="button" class="grid-item" name="n0" value="0"
                                                onClick="inputNumbers(n0.value)">
                                            <input type="button" class="grid-item" name="n00" value="00"
                                                onClick="inputNumbers(n00.value)">
                                            <input type="button" class="grid-item" name="c0" value="C" placeholder="0"
                                                onClick="inputNumbers(c0.value)">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="paymentsselect" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('sl_payment'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label for="payments"
                                        class="col-sm-4 col-form-label"><?php echo display('paymd'); ?></label>
                                    <div class="col-sm-7 customesl">
                                        <?php $card_type = 4;
                    echo form_dropdown('card_typesl', $paymentmethod, (!empty($card_type) ? $card_type : null), 'class="postform resizeselect form-control" id="card_typesl"') ?>
                                    </div>
                                </div>
                                <div id="cardarea display-none wpr_100">
                                    <div class="form-group row">
                                        <label for="card_terminal"
                                            class="col-sm-4 col-form-label"><?php echo display('crd_terminal'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <?php echo form_dropdown('card_terminal', $terminalist, '', 'class="postform resizeselect form-control" id="card_terminal"') ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bank"
                                            class="col-sm-4 col-form-label"><?php echo display('sl_bank'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <?php echo form_dropdown('bank', $banklist, '', 'class="postform resizeselect form-control" id="bank"') ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="4digit"
                                            class="col-sm-4 col-form-label"><?php echo display('lstdigit'); ?></label>
                                        <div class="col-sm-7 customesl">
                                            <input type="text" class="form-control" id="last4digit" name="last4digit"
                                                value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-sm-11 pr-0">
                                        <button type="button" class="btn btn-success w-md m-b-5"
                                            onclick="onlinepay()"><?php echo display('payn'); ?></button>
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
<div id="cancelord" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('can_ord'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label for="payments"
                                        class="col-sm-4 col-form-label"><?php echo display('ordid'); ?></label>
                                    <div class="col-sm-7 customesl"> <span id="canordid"></span>
                                        <input name="mycanorder" id="mycanorder" type="hidden" value="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="canreason"
                                        class="col-sm-4 col-form-label"><?php echo display('can_reason'); ?></label>
                                    <div class="col-sm-7 customesl">
                                        <textarea name="canreason" id="canreason" cols="35" rows="3"
                                            class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-sm-11 pr-0">
                                        <button type="button" class="btn btn-success w-md m-b-5"
                                            id="cancelreason"><?php echo display('submit'); ?></button>
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
<div class="modal fade" id="openfoodmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('openfoodentry'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div style="width:100%;">
                                    <input name="openitemid" type="hidden" id="openitemid" value="" />
                                    <input name="openorderid" type="hidden" id="openorderid" value="" />
                                    <div class="form-group row">
                                        <label for="card_terminal"
                                            class="col-sm-4 col-form-label"><?php echo display('food_name'); ?></label>
                                        <div class="col-sm-7"> <input type="text" class="form-control" id="openitem"
                                                name="openitem" /></div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bank"
                                            class="col-sm-4 col-form-label"><?php echo display('quantity'); ?></label>
                                        <div class="col-sm-7"> <input type="text" class="form-control" id="openqty"
                                                name="openqty" /> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="4digit"
                                            class="col-sm-4 col-form-label"><?php echo display('price'); ?></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="openfoodprice"
                                                name="openfoodprice" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-sm-11" style="padding-right:0;">
                                        <button type="button" class="btn btn-success w-md m-b-5"
                                            onclick="addopenfood()"><?php echo display('submit'); ?></button>
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
<div id="edit" class="item-modal modal fade" tabindex="-1" role="dialog" aria-labelledby="itemInfoLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <div class="modal-body addonsinfo"> </div>
        </div>

    </div>
</div>
<!-- 22-09 -->
<div id="payprint_marge" class="modal fade" role="dialog">
    <div class="modal-dialog modal-inner" id="modal-ajaxview" role="document"> </div>
</div>
<div id="tablemodal" class="modal fade  bd-example-modal-lg <?php if ($settinginfo->tablemaping != 1) {
                                                              echo "custom-modal modal-dark";
                                                            } ?>" role="dialog">
    <div class="modal-dialog modal-inner" id="table-ajaxview"> </div>
</div>
<div id="payprint_split" class="modal fade" role="dialog">
    <div class="modal-dialog modal-inner" id="modal-ajaxview-split"> </div>
</div>

<?php //echo form_open('ordermanage/order/insert_customer','method="post" class="form-vertical" id="validate"')
?>
<div class="modal fade modal-warning" id="client-info" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><?php echo display('add_customer'); ?></h3>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label"><?php echo display('customer_name'); ?> <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control simple-control" name="customer_name" id="newcusname" type="text"
                            placeholder="<?php echo display('customer_name'); ?>" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label"><?php echo display('email'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="email" id="newcusemail" type="email"
                            placeholder="<?php echo display('email'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('mobile'); ?><i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="mobile" id="newcusmobile" type="number"
                            placeholder="<?php echo display('mobile'); ?>" min="0">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tax_number" class="col-sm-3 col-form-label"><?php echo display('tax_number'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="tax_number" id="newcustax_number" type="text"
                            placeholder="Tax Number" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_discount"
                        class="col-sm-3 col-form-label"><?php echo display('max_discount'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="max_discount" id="newcusmax_discount" type="max_discount"
                            placeholder="Max Discount" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date_of_birth"
                        class="col-sm-3 col-form-label"><?php echo display('date_of_birth'); ?></label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="date_of_birth" id="newcusdate">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address " class="col-sm-3 col-form-label"><?php echo display('b_address'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="address" id="newcusaddress" rows="3"
                            placeholder="<?php echo display('b_address'); ?>"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address " class="col-sm-3 col-form-label"><?php echo display('fav_addesrr'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="favaddress" id="newcusfavaddress" rows="3"
                            placeholder="<?php echo display('fav_addesrr'); ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo display('close'); ?>
                </button>
                <button type="button" class="btn btn-success" onclick="cusubmit();"><?php echo display('submit'); ?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</form>
<div class="modal fade modal-warning" id="myModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <form id="updateCart" action="#" method="post">
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th class="wpr_25"><?php echo display('price'); ?></th>
                                <th class="wpr_25"><span id="net_price" class="price"></span></th>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group row">
                        <label for="available_quantity"
                            class="col-sm-4 col-form-label"><?php echo display('Ava_nty'); ?></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="available_quantity"
                                placeholder="<?php echo display('Ava_nty'); ?>" name="available_quantity"
                                readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit'); ?></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="unit"
                                placeholder="<?php echo display('unit'); ?>" name="unit" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Qnty" class="col-sm-4 col-form-label"><?php echo display('qty'); ?> <span
                                class="color-red">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="Qnty" name="quantity">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Rate" class="col-sm-4 col-form-label"><?php echo display('s_rate'); ?> <span
                                class="color-red">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="Rate" name="rate">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Dis/ Pcs" class="col-sm-4 col-form-label"><?php echo display('Dis_Pcs'); ?></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="Dis/ Pcs"
                                placeholder="<?php echo display('Dis_Pcs'); ?>" name="discount">
                        </div>
                    </div>
                    <input type="hidden" name="rowID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal"><?php echo display('close'); ?></button>
                    <button type="submit" class="btn btn-success"><?php echo display('Save_Changes'); ?></button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php

$scan = scandir('application/modules/');
$qrapp = 0;
foreach ($scan as $file) {
  if ($file == "qrapp") {
    if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
      $qrapp = 1;
    }
  }
}

?>
<input name="csrfres" id="csrfresarvation" type="hidden"
    value="<?php echo $this->security->get_csrf_token_name(); ?>" />
<input name="csrfhash" id="csrfhashresarvation" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />

<?php $isAdmin = $this->session->userdata('user_type');
$loguid = $this->session->userdata('id');
$getpermision = $this->db->select('*')->where('userid', $loguid)->get('tbl_posbillsatelpermission')->row();
//print_r($getpermision);
?>
<div class="pos-content pos">
    <div class="tabsection"> <span class="display-none"><?php echo $settinginfo->language; ?></span>
        <ul class="nav nav-tabs" role="tablist">
            <li><a href="<?php echo base_url() ?>dashboard/home" class="maindashboard"><i class="fa fa-home"></i></a>
            </li>
            <li class="active"> <a href="#home" role="tab" data-toggle="tab" title="New Order" id="fhome" autofocus
                    class="home newtab" onclick="giveselecttab(this)"><i class="fa fa-plus smallview"></i> <span
                        class="responsiveview"><?php echo display('nw_order'); ?></span> </a></li>
            <li><a href="#profile" role="tab" data-toggle="tab" class="ongord newtab" id="ongoingorder"
                    onclick="giveselecttab(this)"><i class="fa fa-hourglass-start smallview"></i> <span
                        class="responsiveview"><?php echo display('ongoingorder'); ?></span> </a> </li>
            <li><a href="#kitchen" role="tab" data-toggle="tab" class="torder newtab" id="kitchenorder"
                    onclick="giveselecttab(this)"><i class="fa fa-coffee smallview"></i> <span
                        class="responsiveview"><?php echo display('kitchen_status'); ?></span> </a> </li>
            <?php if ($isAdmin == 1) {
        if ($qrapp == 1) { ?>
            <li class="seelist2"> <a href="#qrorder" role="tab" data-toggle="tab" id="todayqrorder" class="home newtab"
                    onclick="giveselecttab(this)"><i class="fa fa-qrcode smallview"></i> <span
                        class="responsiveview"><?php echo display('qr-order'); ?></span> </a> <a href=""
                    class="notif2"><span class="label label-danger count2">0</span></a> </li>
            <?php } ?>
            <li class="seelist"> <a href="#settings" role="tab" data-toggle="tab" class="comorder newtab"
                    id="todayonlieorder" onclick="giveselecttab(this)"><i class="fa fa-shopping-bag smallview"></i>
                    <span class="responsiveview"><?php echo display('onlineord'); ?></span> </a> <a href=""
                    class="notif"><span class="label label-danger count">0</span></a> </li>
            <li> <a href="#messages" role="tab" data-toggle="tab" class="torder newtab" id="todayorder"
                    onclick="giveselecttab(this)"><i class="fa fa-first-order smallview"></i> <span
                        class="responsiveview"><?php echo display('tdayorder'); ?></span> </a> </li>
            <li> <a href="#thirdpartynav" role="tab" data-toggle="tab" class="torder newtab" id="thirdparty_order"
                    onclick="giveselecttab(this)"><i class="fa fa-first-order smallview"></i> <span
                        class="responsiveview"><?php echo display('third_party'); ?></span> </a> </li>
            <?php } else {
        if ((!empty($getpermision)) && $getpermision->qrord == 1) {
          if ($qrapp == 1) { ?>
            <li class="seelist2"> <a href="#qrorder" role="tab" data-toggle="tab" id="todayqrorder" class="home newtab"
                    onclick="giveselecttab(this)"><i class="fa fa-qrcode smallview"></i> <span
                        class="responsiveview"><?php echo display('qr-order'); ?></span> </a> <a href=""
                    class="notif2"><span class="label label-danger count2">0</span></a> </li>
            <?php }
        }
        if ((!empty($getpermision)) && $getpermision->onlineord == 1) { ?>
            <li class="seelist"> <a href="#settings" role="tab" data-toggle="tab" class="comorder newtab"
                    id="todayonlieorder" onclick="giveselecttab(this)"><i class="fa fa-shopping-bag smallview"></i>
                    <span class="responsiveview"><?php echo display('onlineord'); ?></span> </a> <a href=""
                    class="notif"><span class="label label-danger count">0</span></a> </li>
            <?php }
        if ((!empty($getpermision)) && $getpermision->todayord == 1) { ?>
            <li> <a href="#messages" role="tab" data-toggle="tab" class="torder newtab" id="todayorder"
                    onclick="giveselecttab(this)"><i class="fa fa-first-order smallview"></i> <span
                        class="responsiveview"><?php echo display('tdayorder'); ?></span> </a> </li>
            <?php }
        if ((!empty($getpermision)) && $getpermision->thirdord == 1) { ?>
            <li> <a href="#thirdpartynav" role="tab" data-toggle="tab" class="torder newtab" id="thirdparty_order"
                    onclick="giveselecttab(this)"><i class="fa fa-first-order smallview"></i> <span
                        class="responsiveview"><?php echo display('third_party'); ?></span> </a> </li>
            <?php }
      } ?>
            <?php /*if ($new_version>$myversion) {
		  if($versioncheck->version!=$new_version){
		  ?><li class="mobiletag">
                <a href="<?php echo base_url("dashboard/autoupdate") ?>" class="updateanimate"><i
                        class="fa fa-warning fa-warning-bg"></i><span class="f-size-weight">Update Available</span></a>
            </li><?php } } */ ?>
            <?php if ($possetting->closingbutton == 1) { ?>
            <li class="mobiletag"><a href="javascript:;" class="btn" onclick="closeopenresister()" role="button"><i
                        class="fa fa-window-close fa-lg"></i></a></li>
            <?php } ?>
            <li class="mobiletag"><a href="#"><i class="fa fa-keyboard hover-q text-muted" aria-hidden="true"
                        data-container="body" data-toggle="popover" data-placement="bottom" data-content="<table class='table table-condensed table-striped' >
        
          <tr>
            <th>Operations</th>
            <th>Keyboard Shortcut</th>
            <th>Operations</th>
            <th>Keyboard Shortcut</th>
        </tr>
        <tr>
        <td>New Order Tab</td>
        <td>Shift+N</td>
        <td>On Going Tab</td>
        <td>Shift+G</td>
        </tr>
        <tr>
        <td>Today Order Tab</td>
        <td>Shift+T</td>
        <td>Online Order Tab</td>
        <td>Shift+O</td>
        </tr>
        <tr>
        <td>Place Order</td>
        <td>Shift+P</td>
        <td>Quick Order</td>
        <td>Shift+Q</td>
        </tr>
        <tr>
        <td>Search Product</td>
        <td>Shift+S</td>
        <td>Select Customer</td>
        <td>Shift+C</td>
        </tr>               
        <tr>
        <td>Select Customer Type</td>
        <td>Shift+Y</td>
        <td>Edit Discount:</td>
        <td>Shift+D</td></tr>
        <tr>
        <td>Edit Service Charge</td>
        <td>Shift+R</td>
        <td>Select Waiter</td>
        <td>Shift+W</td>
        </tr>          
        <tr>
        <td>Select Table</td>
        <td>Shift+B</td>
        <td>Cooking Time</td>
        <td>Alt+K</td></tr>
        <tr>
        <td>Search Table</td>
        <td>Alt+T</td>
        <td>Go Edit</td>
        <td>Shift+E</td></tr>
        <tr>
        <td>Search Today Order</td>
        <td>Shift+X</td>
        <td>Search Online Order</td>
        <td>Shift+V</td>
        </tr>  
        <tr>
        <td>Update Search Product</td>
        <td>Alt+S</td>
        <td>Update Select Customer</td>
        <td>Alt+C</td>
        </tr>               
        <tr>
        <td>Update Select Customer Type</td>
        <td>Alt+Y</td>
        <td>Update Discount:</td>
        <td>Alt+D</td></tr>
        <tr>
        <td>Update Service Charge:</td>
        <td>Alt+R</td>
        <td>Update Select Table</td>
        <td>Alt+B</td>
        </tr>          
        
        <td>Update Submit From</td>
        <td>Alt+U</td>
        <td>Select Payment Type</td>
        <td>Alt+M</td></tr>
        <tr>
        <td>Pay & Print Bill</td>
        <td>Alt+P</td>
        <td>Paid Amount Typing</td>
        <td>Alt+A</td></tr>
    </table>" data-html="true" data-trigger="hover" data-original-title="" title=""></i></a></li>
            <li class="mobiletag">
                <?php $languagenames = $this->db->field_data('language'); ?>
                <!-- for language -->
                <div class="dropdown dropdown-user">

                    <a href="#" class="btn dropdown-toggle lang_box" data-toggle="dropdown"><?php if ($this->session->has_userdata('language')) {
                                                                                    echo mb_strimwidth(strtoupper($this->session->userdata('language')), 0, 3, '');
                                                                                  } else {
                                                                                    echo mb_strimwidth(strtoupper($setting->language), 0, 3, '');
                                                                                  } ?></a>
                    <ul class="dropdown-menu lang_options">
                        <?php
            $lii = 0;
            foreach ($languagenames as $languagename) {
              if ($lii >= 2) {
            ?>
                        <li><a href="javascript:;" onclick="addlang(this)"
                                data-url="<?php echo base_url(); ?>hungry/setlangue/<?php echo $languagename->name; ?>">
                                <?php echo ucfirst($languagename->name); ?></a></li>
                        <?php
              }
              $lii++;
            } ?>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="tgbar">
            <?php if ($possetting->closingbutton == 1) { ?>
            <a href="javascript:;" class="btn" onclick="closeopenresister()" role="button"><i
                    class="fa fa-window-close fa-lg"></i></a>
            <?php } ?>
            <?php /*if ($new_version>$myversion) {
		  if($versioncheck->version!=$new_version){
		  ?>
            <a href="<?php echo base_url("dashboard/autoupdate") ?>" class="updateanimate"><i
                    class="fa fa-warning fa-warning-bg"></i><span class="f-size-weight">Update Available</span></a>
            <?php } }*/ ?>
            <a id="fullscreen" href="#"><i class="pe-7s-expand1"></i></a> <a href="#"><i
                    class="fa fa-keyboard hover-q text-muted" aria-hidden="true" data-container="body"
                    data-toggle="popover" data-placement="bottom" data-content="<table class='table table-condensed table-striped' >
        <tr>
            <th>Operations</th>
            <th>Keyboard Shortcut</th>
            <th>Operations</th>
            <th>Keyboard Shortcut</th>
        </tr>
        <tr>
        <td>New Order Tab</td>
        <td>Shift+N</td>
        <td>On Going Tab</td>
        <td>Shift+G</td>
        </tr>
        <tr>
        <td>Today Order Tab</td>
        <td>Shift+T</td>
        <td>Online Order Tab</td>
        <td>Shift+O</td>
        </tr>
        <tr>
        <td>Place Order</td>
        <td>Shift+P</td>
        <td>Quick Order</td>
        <td>Shift+Q</td>
        </tr>
        <tr>
        <td>Search Product</td>
        <td>Shift+S</td>
        <td>Select Customer</td>
        <td>Shift+C</td>
        </tr>               
        <tr>
        <td>Select Customer Type</td>
        <td>Shift+Y</td>
        <td>Edit Discount:</td>
        <td>Shift+D</td></tr>
        <tr>
        <td>Edit Service Charge</td>
        <td>Shift+R</td>
        <td>Select Waiter</td>
        <td>Shift+W</td>
        </tr>          
        <tr>
        <td>Select Table</td>
        <td>Shift+B</td>
        <td>Cooking Time</td>
        <td>Alt+K</td></tr>
        <tr>
        <td>Search Table</td>
        <td>Alt+T</td>
        <td>Go Edit</td>
        <td>Shift+E</td></tr>
        <tr>
        <td>Search Today Order</td>
        <td>Shift+X</td>
        <td>Search Online Order</td>
        <td>Shift+V</td>
        </tr>  
        <tr>
        <td>Update Search Product</td>
        <td>Alt+S</td>
        <td>Update Select Customer</td>
        <td>Alt+C</td>
        </tr>               
        <tr>
        <td>Update Select Customer Type</td>
        <td>Alt+Y</td>
        <td>Update Discount:</td>
        <td>Alt+D</td></tr>
        <tr>
        <td>Update Service Charge:</td>
        <td>Alt+R</td>
        <td>Update Select Table</td>
        <td>Alt+B</td>
        </tr>          
        
        <td>Update Submit From</td>
        <td>Alt+U</td>
        <td>Select Payment Type</td>
        <td>Alt+M</td></tr>
        <tr>
        <td>Pay & Print Bill</td>
        <td>Alt+P</td>
        <td>Paid Amount Typing</td>
        <td>Alt+A</td></tr>
    </table>" data-html="true" data-trigger="hover" data-original-title="" title=""></i></a>
            <?php $languagenames = $this->db->field_data('language'); ?>
            <div class="dropdown">
                <a class="dropdown-toggle lang_box" type="button" data-toggle="dropdown"><?php if ($this->session->has_userdata('language')) {
                                                                                    echo mb_strimwidth(strtoupper($this->session->userdata('language')), 0, 3, '');
                                                                                  } else {
                                                                                    echo mb_strimwidth(strtoupper($setting->language), 0, 3, '');
                                                                                  } ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu lang_options">
                    <?php
          $lii = 0;
          foreach ($languagenames as $languagename) {
            if ($lii >= 2) {
          ?>
                    <li><a href="javascript:;" onclick="addlang(this)"
                            data-url="<?php echo base_url(); ?>hungry/setlangue/<?php echo $languagename->name; ?>">
                            <?php echo ucfirst($languagename->name); ?></a></li>
                    <?php
            }
            $lii++;
          } ?>
                </ul>
            </div>

        </div>
    </div>
    <div class="pos-panel tab-content">
        <div class="tab-pane fade active in" id="home">
            <div class="pos-wrap">
                <!-- Pos Sidebar -->
                <div class="pos-sidebar">
                    <!-- Close Button -->
                    <div class="close-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                    <!--<div class="align-center d-flex cat-name">
              <div class=" cat-name_text">All Items</div>
              <div class="cat-shape"></div>
            </div>-->
                    <ul class="metismenu" id="menu">
                        <li>
                            <a href="javascript:void(0)" onclick="getslcategory('')">
                                All</a>
                        </li>
                        <?php $loguid = $this->session->userdata('id');
            $isAdmin = $this->session->userdata('user_type');
            foreach ($allcategorylist as $category) {
              if (!empty($category->sub)) {
                if ($isAdmin == 1) { ?>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow"
                                style="background:<?php echo $category->catcolor; ?>">
                                <div class="cat-icon">
                                    <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>"
                                        alt="">
                                </div>
                                <?php echo $category->Name; ?>
                            </a>
                            <ul>
                                <?php foreach ($category->sub as $subcat) {
                      ?>
                                <li><a href="javascript:void(0)"
                                        onclick="getslcategory(<?php echo $subcat->CategoryID; ?>)"
                                        style="background:<?php echo $subcat->catcolor; ?>"><?php echo $subcat->Name; ?></a>
                                </li>
                                <?php  } ?>
                            </ul>
                        </li>
                        <?php } else {
                  $ck_data2 = $this->db->select('*')->where('userid', $loguid)->where('catid', $category->CategoryID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();

                  //if(!empty($ck_data2)){
                ?>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow"
                                style="background:<?php echo $category->catcolor; ?>">
                                <div class="cat-icon">
                                    <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>"
                                        alt="">
                                </div>
                                <?php echo $category->Name; ?>
                            </a>
                            <ul>
                                <?php foreach ($category->sub as $subcat) {
                        $ck_data3 = $this->db->select('*')->where('userid', $loguid)->where('catid', $subcat->CategoryID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
                        if (!empty($ck_data3)) {
                      ?>
                                <li><a href="javascript:void(0)"
                                        onclick="getslcategory(<?php echo $subcat->CategoryID; ?>)"
                                        style="background:<?php echo $subcat->catcolor; ?>"><?php echo $subcat->Name; ?></a>
                                </li>
                                <?php }
                      } ?>
                            </ul>
                        </li>
                        <?php //}
                }
              } else {
                $ck_data = $this->db->select('*')->where('userid', $loguid)->where('catid', $category->CategoryID)->get('tbl_itemwiseuser')->row();
                if ($isAdmin != 1 && $ck_data->isacccess == 1) {
                ?>
                        <li>
                            <a href="javascript:void(0)" onclick="getslcategory(<?php echo $category->CategoryID; ?>)"
                                style="background:<?php echo $category->catcolor; ?>">
                                <div class="cat-icon">
                                    <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>"
                                        alt="">
                                </div>
                                <?php echo $category->Name; ?>
                            </a>
                        </li>
                        <?php  }
                if ($isAdmin == 1) { ?>
                        <li>
                            <a href="javascript:void(0)" onclick="getslcategory(<?php echo $category->CategoryID; ?>)"
                                style="background:<?php echo $category->catcolor; ?>">
                                <div class="cat-icon">
                                    <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>"
                                        alt="">
                                </div>
                                <?php echo $category->Name; ?>
                            </a>
                        </li>
                        <?php }
              }
            } ?>
                    </ul>
                </div>
                <!-- Pos Body -->
                <div class="pos-body">
                    <div class="col-md-7">
                        <input name="url" type="hidden" id="posurl"
                            value="<?php echo base_url("ordermanage/order/getitemlist") ?>" />
                        <input name="url" type="hidden" id="productdata"
                            value="<?php echo base_url("ordermanage/order/getitemdata") ?>" />
                        <input name="url" type="hidden" id="url"
                            value="<?php echo base_url("ordermanage/order/itemlistselect") ?>" />
                        <input name="url" type="hidden" id="carturl"
                            value="<?php echo base_url("ordermanage/order/posaddtocart") ?>" />
                        <input name="url" type="hidden" id="cartupdateturl"
                            value="<?php echo base_url("ordermanage/order/poscartupdate") ?>" />
                        <input name="url" type="hidden" id="addonexsurl"
                            value="<?php echo base_url("ordermanage/order/posaddonsmenu") ?>" />
                        <input name="url" type="hidden" id="removeurl"
                            value="<?php echo base_url("ordermanage/order/removetocart") ?>" />
                        <input name="updateid" type="hidden" id="updateid" value="" />
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="product-search_wrap">
                                    <div class="pos-sidebar_toggler">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-filter">
                                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                        </svg>
                                        <?php echo display('All_Items'); ?>
                                    </div>
                                    <form class="product-search" method="get"
                                        action="<?php echo base_url("ordermanage/order/pos_invoice") ?>">
                                        <select class="dont-select-me  search-field" id="product_name" dir="ltr"
                                            name="s">
                                        </select>
                                    </form>
                                    <input name="checkbarcode" id="checkbarcode" class="form-control width-auto"
                                        style="border-radius: 6px; margin-left: 10px;" type="text"
                                        placeholder="Barcode Scan" />
                                </div>
                            </div>
                        </div>
                        <!-- product-grid -->
                        <div class="product-content">
                            <div class="row" id="product_search">
                                <!--  -->
                                <?php $i = 0;
                foreach ($itemlist as $item) {
                  $item = (object)$item;
                  $i++;

                  if ($isAdmin == 1) {
                    $this->db->select('*');
                    $this->db->from('menu_add_on');
                    $this->db->where('menu_id', $item->ProductsID);
                    $query = $this->db->get();

                    $this->db->select('*');
                    $this->db->from('tbl_menutoping');
                    $this->db->where('menuid', $item->ProductsID);
                    $tquery2 = $this->db->get();

                    $getadons = "";
                    if ($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                      $getadons = 1;
                    } else {
                      $getadons =  0;
                    }
                    $this->db->select('*');
                    $this->db->from('foodvariable');
                    $this->db->where('foodid', $item->ProductsID);

                    $query2 = $this->db->get();
                    $foundintable = $query2->row();
                    $dayname = date('l');
                    $this->db->select('*');
                    $this->db->from('foodvariable');
                    $this->db->where('foodid', $item->ProductsID);
                    $this->db->where('availday', $dayname);
                    $this->db->where('is_active', 1);
                    $query = $this->db->get();
                    $avail = $query->row();

                    if (empty($foundintable)) {
                      $availavail = 1;
                    } else {
                      if (!empty($avail)) {
                        $availabletime = explode("-", $avail->availtime);
                        $deltime1 = strtotime($availabletime[0]);
                        $deltime2 = strtotime($availabletime[1]);
                        $Time1 = date("h:i:s A", $deltime1);
                        $Time2 = date("h:i:s A", $deltime2);
                        $curtime = date("h:i:s A");
                        $gettime = strtotime(date("h:i:s A"));
                        if (($gettime > $deltime1) && ($gettime < $deltime2)) {
                          $availavail = 1;
                        } else {
                          $availavail = 0;
                        }
                      } else {
                        $availavail = 0;
                      }
                    }
                ?>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                                    <div class="product-card select_product">
                                        <div class="product-card_body">
                                            <?php if ($item->OffersRate > 0) { ?>
                                            <div class="offer-small">
                                                <div class="offer-small-number"><?php echo $item->OffersRate; ?>% Off
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <img src="<?php echo base_url(!empty($item->small_thumb) ? $item->small_thumb : 'assets/img/default/default_food.png'); ?>"
                                                class="img-responsive" alt="<?php echo $item->ProductName; ?>"
                                                onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
                                            <input type="hidden" name="select_product_id" class="select_product_id"
                                                value="<?php echo $item->ProductsID; ?>">
                                            <input type="hidden" name="select_totalvarient" class="select_totalvarient"
                                                value="<?php echo $item->totalvarient; ?>">
                                            <input type="hidden" name="select_iscustomeqty" class="select_iscustomeqty"
                                                value="<?php echo $item->is_customqty; ?>">
                                            <input type="hidden" name="select_product_size" class="select_product_size"
                                                value="<?php echo $item->variantid; ?>">
                                            <input type="hidden" name="select_product_isgroup"
                                                class="select_product_isgroup" value="<?php echo $item->isgroup; ?>">
                                            <input type="hidden" name="select_product_cat" class="select_product_cat"
                                                value="<?php echo $item->CategoryID; ?>">
                                            <input type="hidden" name="select_varient_name" class="select_varient_name"
                                                value="<?php echo $item->variantName; ?>">
                                            <input type="hidden" name="select_product_name" class="select_product_name"
                                                value="<?php echo $item->ProductName;
                                                                                                              if (!empty($item->itemnotes)) {
                                                                                                                echo " -" . $item->itemnotes;
                                                                                                              } ?>">
                                            <input type="hidden" name="select_product_price"
                                                class="select_product_price" value="<?php echo $item->price; ?>">
                                            <input type="hidden" name="select_product_avail"
                                                class="select_product_avail" value="<?php echo $availavail; ?>">
                                            <input type="hidden" name="select_addons" class="select_addons"
                                                value="<?php echo $getadons; ?>">
                                            <input type="hidden" name="select_withoutproduction"
                                                class="select_withoutproduction"
                                                value="<?php echo $item->withoutproduction; ?>">
                                            <input type="hidden" name="select_stockvalitity"
                                                class="select_stockvalitity"
                                                value="<?php echo $item->isstockvalidity; ?>">
                                        </div>
                                        <div class="product-card_footer"><span><?php echo $item->ProductName; ?>
                                                (<?php echo $item->variantName; ?>)
                                                <?php if (!empty($item->itemnotes)) {
                              echo " -" . $item->itemnotes;
                            } ?></span></div>
                                    </div>
                                </div>
                                <?php } else {
                    $this->db->select('*');
                    $this->db->from('menu_add_on');
                    $this->db->where('menu_id', $item->ProductsID);
                    $query = $this->db->get();

                    $this->db->select('*');
                    $this->db->from('tbl_menutoping');
                    $this->db->where('menuid', $item->ProductsID);
                    $tquery2 = $this->db->get();

                    $getadons = "";
                    if ($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                      $getadons = 1;
                    } else {
                      $getadons =  0;
                    }
                    $this->db->select('*');
                    $this->db->from('foodvariable');
                    $this->db->where('foodid', $item->ProductsID);

                    $query2 = $this->db->get();
                    $foundintable = $query2->row();
                    $dayname = date('l');
                    $this->db->select('*');
                    $this->db->from('foodvariable');
                    $this->db->where('foodid', $item->ProductsID);
                    $this->db->where('availday', $dayname);
                    $this->db->where('is_active', 1);
                    $query = $this->db->get();
                    $avail = $query->row();

                    if (empty($foundintable)) {
                      $availavail = 1;
                    } else {
                      if (!empty($avail)) {
                        $availabletime = explode("-", $avail->availtime);
                        $deltime1 = strtotime($availabletime[0]);
                        $deltime2 = strtotime($availabletime[1]);
                        $Time1 = date("h:i:s A", $deltime1);
                        $Time2 = date("h:i:s A", $deltime2);
                        $curtime = date("h:i:s A");
                        $gettime = strtotime(date("h:i:s A"));
                        if (($gettime > $deltime1) && ($gettime < $deltime2)) {
                          $availavail = 1;
                        } else {
                          $availavail = 0;
                        }
                      } else {
                        $availavail = 0;
                      }
                    }
                    $checkitempermission = $this->db->select('*')->where('userid', $loguid)->where('menuid', $item->ProductsID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
                    if ($checkitempermission) {
                    ?>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                                    <div class="product-card select_product">
                                        <div class="product-card_body">
                                            <?php if ($item->OffersRate > 0) { ?>
                                            <div class="offer-small">
                                                <div class="offer-small-number"><?php echo $item->OffersRate; ?>% Off
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <img src="<?php echo base_url(!empty($item->small_thumb) ? $item->small_thumb : 'assets/img/icons/default.jpg'); ?>"
                                                class="img-responsive" alt="<?php echo $item->ProductName; ?>">
                                            <input type="hidden" name="select_product_id" class="select_product_id"
                                                value="<?php echo $item->ProductsID; ?>">
                                            <input type="hidden" name="select_totalvarient" class="select_totalvarient"
                                                value="<?php echo $item->totalvarient; ?>">
                                            <input type="hidden" name="select_iscustomeqty" class="select_iscustomeqty"
                                                value="<?php echo $item->is_customqty; ?>">
                                            <input type="hidden" name="select_product_size" class="select_product_size"
                                                value="<?php echo $item->variantid; ?>">
                                            <input type="hidden" name="select_product_isgroup"
                                                class="select_product_isgroup" value="<?php echo $item->isgroup; ?>">
                                            <input type="hidden" name="select_product_cat" class="select_product_cat"
                                                value="<?php echo $item->CategoryID; ?>">
                                            <input type="hidden" name="select_varient_name" class="select_varient_name"
                                                value="<?php echo $item->variantName; ?>">
                                            <input type="hidden" name="select_product_name" class="select_product_name"
                                                value="<?php echo $item->ProductName;
                                                                                                                if (!empty($item->itemnotes)) {
                                                                                                                  echo " -" . $item->itemnotes;
                                                                                                                } ?>">
                                            <input type="hidden" name="select_product_price"
                                                class="select_product_price" value="<?php echo $item->price; ?>">
                                            <input type="hidden" name="select_product_avail"
                                                class="select_product_avail" value="<?php echo $availavail; ?>">
                                            <input type="hidden" name="select_addons" class="select_addons"
                                                value="<?php echo $getadons; ?>">
                                            <input type="hidden" name="select_withoutproduction"
                                                class="select_withoutproduction"
                                                value="<?php echo $item->withoutproduction; ?>">
                                            <input type="hidden" name="select_stockvalitity"
                                                class="select_stockvalitity"
                                                value="<?php echo $item->isstockvalidity; ?>">
                                        </div>
                                        <div class="product-card_footer"><span><?php echo $item->ProductName; ?>
                                                (<?php echo $item->variantName; ?>)
                                                <?php if (!empty($item->itemnotes)) {
                                echo " -" . $item->itemnotes;
                              } ?></span></div>
                                    </div>
                                </div>
                                <?php }
                  }
                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <form action="<?php echo base_url("ordermanage/order/pos_order") ?>" class="end-content"
                            id="onlineordersubmit" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            <div class="form_content">
                                <div class="bg-holder bg-card"
                                    style="background-image:url(<?php echo base_url("application/modules/ordermanage/assets/images/") ?>corner-3.png);">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="customer_name"><?php echo display('customer_name'); ?><span
                                                class="color-red">*</span></label>
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <select class="search-field-customersr" id="customer_name" dir="ltr"
                                                    name="customer_name">
                                                    <option value="">---Select Customer---</option>
                                                    <option value="1" selected="selected">Walkin</option>
                                                </select>
                                                <?php //$cusid=1;customerinfo
                        //echo form_dropdown('customer_name',$customerlist,(!empty($cusid)?$cusid:null),'class="postform js-basic-single form-control" id="customer_name" required tabindex="-1" aria-hidden="true" novalidate="novalidate"') 
                        ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <button type="button" class="btn btn-primary wpr_100" aria-hidden="true"
                                                    data-toggle="modal" data-target="#client-info"
                                                    data-backdrop="static" data-keyboard="false"><i
                                                        class="ti-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="store_id"><?php echo display('customer_type'); ?><span
                                                class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <?php $ctype = 1;
                                            echo form_dropdown('ctypeid', $curtomertype, (!empty($ctype) ? $ctype : null), 'class="js-basic-single form-control" id="ctypeid" required') ?>
                                    </div>
                                    <div id="nonthirdparty" class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4 form-group mb-0">
                                                <label for="store_id"><?php echo display('waiter'); ?><span
                                                        class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <?php $waiterkitchen = $this->session->userdata('id');
                        echo form_dropdown('waiter', $waiterlist, (!empty($waiterkitchen) ? $waiterkitchen : null), 'class="js-basic-single" id="waiter" required') ?>
                                            </div>
                                            <?php if ($possetting->tablemaping == 1) { ?>
                                            <div class="col-md-2 form-group pl-0" id="tblsecp">
                                                <label for="store_id" class="wpr_100 person"> <span
                                                        class="color-red">&nbsp;&nbsp;</span></label>
                                                <input name="" type="button"
                                                    class="btn btn-primary  form-control width-auto"
                                                    onclick="showTablemodal()" id="table_person"
                                                    value="<?php echo display('person'); ?>">
                                                <input type="hidden" id="table_member" name="table_member"
                                                    class="form-control" value="" />
                                            </div>
                                            <?php } ?>
                                            <div class="col-md-3 form-group mb-0" id="tblsec">

                                                <label for="store_id"><?php echo display('table'); ?> <span
                                                        class="color-red">*</span></label>

                                                <?php echo form_dropdown('tableid', $tablelist, (!empty($tablelist->tableid) ? $tablelist->tableid : null), 'class="postform js-basic-single" id="tableid" required onchange="checktable()"') ?>
                                                <input type="hidden" id="table_member_multi" name="table_member_multi"
                                                    class="form-control" value="0" />
                                                <input type="hidden" id="table_member_multi_person"
                                                    name="table_member_multi_person" class="form-control" value="0" />


                                            </div>
                                            <div class="col-md-3 form-group mb-0" id="cookingtime">
                                                <label for="Cooked Time"><?php echo display('cookedtime'); ?></label>
                                                <input name="cookedtime" type="text" class="form-control timepicker3"
                                                    id="cookedtime" placeholder="00:00:00" autocomplete="off">
                                            </div>

                                        </div>
                                    </div>
                                    <div id="thirdparty" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="store_id"><?php echo display('del_company'); ?><span
                                                        class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>

                                                <?php echo form_dropdown('delivercom', $thirdpartylist, (!empty($thirdpartylist->companyId) ? $thirdpartylist->companyId : null), 'class="form-control wpr_95 select2-hidden-accessible" id="delivercom" required disabled="disabled"') ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="third_id"><?php echo display('thirdparty_orderid'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <input name="thirdinvoiceid" type="text" class="form-control"
                                                    id="thirdinvoiceid" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="hidden" id="order_date" name="order_date"
                                            required value="<?php echo date('d-m-Y') ?>" />
                                        <input class="form-control" type="hidden" id="bill_info" name="bill_info"
                                            required value="1" />
                                        <input type="hidden" id="card_type" name="card_type" value="4" />
                                        <input type="hidden" id="isonline" name="isonline" value="0" />
                                        <input type="hidden" id="assigncard_terminal" name="assigncard_terminal"
                                            value="" />
                                        <input type="hidden" id="assignbank" name="assignbank" value="" />
                                        <input type="hidden" id="assignlastdigit" name="assignlastdigit" value="" />
                                        <input type="hidden" id="product_value" name="">
                                    </div>
                                </div>
                            </div>
                            <!-- Product list table -->
                            <div class="product-list_table">
                                <div class="table-responsive" id="addfoodlist">
                                    <?php $grtotal = 0;
                  $totalitem = 0;
                  $calvat = 0;
                  $discount = 0;
                  $itemtotal = 0;
                  $pdiscount = 0;
                  $multiplletax = array();
                  $this->load->model('ordermanage/order_model', 'ordermodel');
                  if ($cart = $this->cart->contents()) {
                    $prevarray = array();
                    foreach ($cart as $updateprev) {
                      $prevarray = $updateprev;
                      break;
                    }
                    if (array_key_exists("prevqty", $prevarray)) {
                      $this->cart->destroy();
                    }
                  ?>
                                    <table class="table custom-table_two table-striped table-condensed cartlistp"
                                        id="addinvoice">
                                        <thead>
                                            <tr class="active">
                                                <th><?php echo display('item') ?></th>
                                                <th><?php echo display('varient_name') ?></th>
                                                <th><?php echo display('price'); ?></th>
                                                <th><?php echo display('quantity'); ?></th>
                                                <th><?php echo display('total'); ?></th>
                                                <th><?php echo display('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="itemNumber">
                                            <?php

                        $i = 0;
                        $totalamount = 0;
                        $subtotal = 0;
                        $ptdiscount = 0;
                        $pvat = 0;
                        $currentDate = new DateTime();
                        foreach ($cart as $item) {
                          $iteminfo = $this->ordermodel->getiteminfo($item['pid']);
                          $itemprice = $item['price'] * $item['qty'];
                          $startDate = new DateTime($iteminfo->offerstartdate);
                          $endDate = new DateTime($iteminfo->offerendate);
                          $mypdiscountprice = 0;
                          if (!empty($taxinfos)) {
                            $tx = 0;
                            if ($iteminfo->OffersRate > 0) {
                              if ($currentDate >= $startDate && $currentDate <= $endDate) {
                                $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
                              }
                            }
                            $itemvalprice =  ($itemprice - $mypdiscountprice);
                            foreach ($taxinfos as $taxinfo) {
                              $fildname = 'tax' . $tx;
                              if (!empty($iteminfo->$fildname)) {
                                $vatcalc = Vatclaculation($itemvalprice, $iteminfo->$fildname);
                                $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                              } else {
                                $vatcalc = Vatclaculation($itemvalprice, $taxinfo['default_value']);
                                $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                              }

                              $pvat = $pvat + $vatcalc;
                              $vatcalc = 0;
                              $tx++;
                            }
                          } else {
                            $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
                            $pvat = $pvat + $vatcalc;
                          }

                          if ($iteminfo->OffersRate > 0) {
                            if ($currentDate >= $startDate && $currentDate <= $endDate) {
                            $mypdiscount = $iteminfo->OffersRate * $itemprice / 100;
                            $ptdiscount = $ptdiscount + ($iteminfo->OffersRate * $itemprice / 100);
                            }
                          } else {
                            $mypdiscount = 0;
                            $pdiscount = $pdiscount + 0;
                          }
                          if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
                            $nittotal = $item['addontpr'] + $item['alltoppingprice'];
                            $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
                          } else {
                            $nittotal = 0;
                            $itemprice = $itemprice;
                          }
                          $totalamount = $totalamount + $nittotal;
                          $subtotal = $subtotal + $nittotal + $item['price'] * $item['qty'];
                          $i++;
                        ?>


                                            <tr id="<?php echo $i; ?>">


                                                <th id="product_name_MFU4E" width="30%">
                                                    <div class="produce-name">
                                                        <div class="note" title="<?php echo display('foodnote') ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                onclick="itemnote('<?php echo $item['rowid'] ?>','<?php echo $item['itemnote'] ?>',<?php echo $item['qty']; ?>,2)"
                                                                title="<?php echo display('foodnote') ?>"
                                                                class="feather feather-edit">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </div>

                                                        <span style="flex: 1;"><?php echo  $item['name'];
                                                        if (!empty($item['addonsid'])) {
                                                          echo "<br>";
                                                          echo $item['addonname'];
                                                          if (!empty($taxinfos)) {

                                                            $addonsarray = explode(',', $item['addonsid']);
                                                            $addonsqtyarray = explode(',', $item['addonsqty']);
                                                            $getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $addonsarray)->get()->result_array();
                                                            $addn = 0;
                                                            foreach ($getaddonsdatas as $getaddonsdata) {
                                                              $tax = 0;

                                                              foreach ($taxinfos as $taxainfo) {

                                                                $fildaname = 'tax' . $tax;

                                                                if (!empty($getaddonsdata[$fildaname])) {
                                                                  $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn], $getaddonsdata[$fildaname]);
                                                                  $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                                                } else {
                                                                  $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn], $taxainfo['default_value']);
                                                                  $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                                                }

                                                                $pvat = $pvat + $avatcalc;

                                                                $tax++;
                                                              }
                                                              $addn++;
                                                            }
                                                          }
                                                        }
                                                        if (!empty($item['toppingid'])) {
                                                          $toppingarray = explode(',', $item['toppingid']);
                                                          $toppingnamearray = explode(',', $item['toppingname']);
                                                          $toppingpryarray = explode(',', $item['toppingprice']);
                                                          $t = 0;
                                                          foreach ($toppingarray as $tpname) {
                                                            if ($toppingpryarray[$t] > 0) {
                                                              echo "<br>";
                                                              echo $toppingnamearray[$t];
                                                            }
                                                            $t++;
                                                          }

                                                          if (!empty($taxinfos)) {
                                                            $gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $toppingarray)->get()->result_array();
                                                            //echo $this->db->last_query();
                                                            $tpn = 0;
                                                            foreach ($gettoppingdatas as $gettoppingdata) {
                                                              $tptax = 0;
                                                              foreach ($taxinfos as $taxainfo) {

                                                                $fildaname = 'tax' . $tptax;

                                                                if (!empty($gettoppingdata[$fildaname])) {
                                                                  $tvatcalc = Vatclaculation($toppingpryarray[$tpn], $gettoppingdata[$fildaname]);
                                                                  $multiplletax[$fildaname] = $multiplletax[$fildaname] + $tvatcalc;
                                                                } else {
                                                                  $tvatcalc = Vatclaculation($toppingpryarray[$tpn], $taxainfo['default_value']);
                                                                  $multiplletax[$fildaname] = $multiplletax[$fildaname] + $tvatcalc;
                                                                }

                                                                $pvat = $pvat + $tvatcalc;

                                                                $tptax++;
                                                              }
                                                              $tpn++;
                                                            }
                                                          }
                                                        }
                                                        ?></span>
                                                    </div>
                                                </th>

                                                <td><?php echo $item['size']; ?></td>

                                                <td width="">
                                                    <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                              } ?>
                                                    <?php echo numbershow($item['price'], $settinginfo->showdecimal); ?>
                                                    <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                              } ?></td>



                                                <td scope="row">

                                                    <div class="input-group bootstrap-touchspin justify-content-center">

                                                        <span class="input-group-btn">

                                                            <button class="btn btn-default bootstrap-touchspin-down"
                                                                type="button"
                                                                onclick="posupdatecart('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>,'del')">-</button>

                                                        </span> <span
                                                            class="input-group-addon bootstrap-touchspin-prefix"
                                                            style="display: none;"></span>

                                                        <span
                                                            id="productionsetting-<?php echo $item['pid']; ?>-<?php echo $item['sizeid'] ?>"
                                                            class="form-control">

                                                            <input id="item_pid_<?php echo $item['pid']; ?>"
                                                                style="width:100%; border:none; font-size:medium; text-align:center"
                                                                type="text" value="<?php echo $item['qty']; ?>"
                                                                onkeyup="posupdatecartKey('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>, this.value, event)">


                                                        </span>

                                                        <span class="input-group-addon bootstrap-touchspin-postfix"
                                                            style="display: none;"></span> <span
                                                            class="input-group-btn">

                                                            <button class="btn btn-default bootstrap-touchspin-up"
                                                                type="button"
                                                                onclick="posupdatecart('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>,<?php echo $item['qty']; ?>,'add')">
                                                                +
                                                            </button>

                                                        </span>

                                                    </div>
                                                </td>


                                                <td width="">
                                                    <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                              } ?>
                                                    <?php echo numbershow($itemprice - $mypdiscount, $settinginfo->showdecimal); ?>
                                                    <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                              } ?></td>
                                                <td>
                                                    <a class="btn btn-danger btn-sm btnrightalign"
                                                        onclick="removecart('<?php echo $item['rowid']; ?>')">

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-trash-2">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg></a>
                                                </td>
                                            </tr>
                                            <?php }
                        $itemtotal = $subtotal;
                        /*check $taxsetting info*/
                        if (empty($taxinfos)) {
                          if ($settinginfo->vat > 0) {
                            $calvat = Vatclaculation($itemtotal - $ptdiscount, $settinginfo->vat);
                          } else {
                            $calvat = $pvat;
                          }
                        } else {
                          $calvat = $pvat;
                        }
                        $grtotal = $itemtotal;
                        $totalitem = $i;
                        ?>

                                        </tbody>
                                    </table>
                                    <?php $pdiscount = $ptdiscount;
                  }

                  $multiplletaxvalue = htmlentities(serialize($multiplletax));
                  ?>
                                    <input name="subtotal" id="subtotal" type="hidden"
                                        value="<?php echo $subtotal; ?>" />

                                    <input name="multiplletaxvalue" id="multiplletaxvalue" type="hidden"
                                        value="<?php echo $multiplletaxvalue; ?>" />
                                    <?php
                  if (!empty($this->cart->contents())) {
                    if ($settinginfo->service_chargeType == 1) {
                      $totalsercharge = $subtotal - $pdiscount;
                      $servicetotal = $settinginfo->servicecharge * $totalsercharge / 100;
                    } else {
                      $servicetotal = $settinginfo->servicecharge;
                    }
                    $servicecharge = $settinginfo->servicecharge;
                  } else {
                    $servicetotal = 0;
                    $servicecharge = 0;
                  }
                  ?>
                                    <input name="sd" id="sd" type="hidden" value="<?php echo $servicecharge; ?>" />
                                    <div class="empty-cart">
                                        <div class="cart-icon">
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="fixedclasspos">
                                <div class="leftview">
                                    <input name="distype" id="distype" type="hidden"
                                        value="<?php echo $settinginfo->discount_type; ?>" />
                                    <input name="sdtype" id="sdtype" type="hidden"
                                        value="<?php echo $settinginfo->service_chargeType; ?>" />
                                    <input type="hidden" id="orginattotal"
                                        value="<?php echo $calvat + $itemtotal + $servicetotal - ($discount + $pdiscount); ?>"
                                        name="orginattotal">
                                    <input type="hidden" id="invoice_discount" class="form-control text-right"
                                        name="invoice_discount" value="<?php echo $discount + $pdiscount ?>">
                                    <table class="table table-bordered footersumtotal">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="row m-0">
                                                        <label for="date"
                                                            class="col-sm-8 mb-0"><?php echo display('vat_tax1') ?>:
                                                        </label>
                                                        <label class="col-sm-4 mb-0">
                                                            <input type="hidden" id="vat" name="vat"
                                                                value="<?php echo $calvat; ?>" autocomplete="off">
                                                        </label>
                                                        <strong>
                                                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                              } ?>
                                                            <span id="calvat">
                                                                <?php echo numbershow($calvat, $settinginfo->showdecimal); ?></span>
                                                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                              } ?>
                                                        </strong>

                                                    </div>
                                                </td>
                                                <td><label for="date" class="col-sm-8 mb-0"><?php echo display('service_chrg') ?><?php if ($settinginfo->service_chargeType == 0) {
                                                                                                          echo "(" . $currency->curr_icon . ")";
                                                                                                        } else {
                                                                                                          echo "(%)";
                                                                                                        } ?>:</label>
                                                    <div class="col-sm-4 p-0">
                                                        <input type="text" id="service_charge"
                                                            onkeyup="calculatetotal();"
                                                            class="form-control text-right mb-5"
                                                            value="<?php echo numbershow($servicecharge, $settinginfo->showdecimal); ?>"
                                                            name="service_charge" placeholder="0.00" autocomplete="off">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="grand-total d-flex">
                                    <span><?php echo display('grand_total') ?></span>
                                    <input type="hidden" id="orggrandTotal"
                                        value="<?php echo $calvat + $itemtotal + $servicetotal - ($discount + $pdiscount); ?>"
                                        name="orggrandTotal">
                                    <input name="grandtotal" type="hidden"
                                        value="<?php echo $calvat + $itemtotal + $servicetotal - ($discount + $pdiscount); ?>"
                                        id="grandtotal" />
                                    <span class="">
                                        <span class="text-right"><?php if ($currency->position == 1) {
                                                echo $currency->curr_icon;
                                              } ?></span>
                                        <span id="caltotal"><?php
                                        $isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
                                        if (!empty($isvatinclusive)) {
                                          //echo $itemtotal+$servicetotal-($discount+$pdiscount);
                                          echo numbershow($itemtotal + $servicetotal - ($discount + $pdiscount), $settinginfo->showdecimal);
                                        } else {
                                          echo numbershow($calvat + $itemtotal + $servicetotal - ($discount + $pdiscount), $settinginfo->showdecimal);
                                          //echo $calvat+$itemtotal+$servicetotal-($discount+$pdiscount);
                                        }
                                        ?></span>
                                        <span class="text-right"><?php if ($currency->position == 2) {
                                                echo $currency->curr_icon;
                                              } ?></span>
                                    </span>
                                </div>
                                <div class="button-content">
                                    <a class="btn btn-primary cusbtn" data-toggle="modal" data-target="#exampleModal"
                                        data-backdrop="static" data-keyboard="false"><i class="fa fa-calculator"
                                            aria-hidden="true"></i></a> <a
                                        href="<?php echo base_url("ordermanage/order/posclear") ?>" type="button"
                                        class="btn btn-danger cusbtn"><i class="fa fa-close"
                                            aria-hidden="true"></i><?php //echo display('cancel')
                                                                                                                                                                                                                                                                                                                                                                  ?>
                                    </a>
                                    <!--<input type="button" id="openfood" class="btn btn-success btn-large cusbtn" name="openfood" value="<?php //echo display('openfood');
                                                                                                                          ?>">-->
                                    <?php $sumdis = $discount + $pdiscount;
                  $encgtotal = $calvat + $itemtotal + $servicetotal - ($discount + $pdiscount);
                  $encodebill = $calvat . '|' . $sumdis . '|' . $servicetotal . '|' . $itemtotal . '|' . $encgtotal ?>
                                    <input name="denc" id="denc" type="hidden"
                                        value="<?php echo  base64_encode($encodebill); ?>" />

                                    <input type="hidden" id="getitemp" name="getitemp"
                                        value="<?php echo $totalitem - $discount; ?>" autocomplete="off">
                                    <?php if ($settinginfo->pos_order_mode == 2) {?>
                                    <input type="button" id="add_payment2" class="btn btn-primary btn-large cusbtn"
                                        onclick="cashQuickModeOrder()" name="add-payment"
                                        value="<?php echo display('cash') ?>" autocomplete="off">
                                    <input type="button" id="add_payment2" class="btn btn-info btn-large cusbtn"
                                        onclick="cardQuickModeOrder()" name="add-payment"
                                        value="<?php echo display('card') ?>" autocomplete="off">
                                    <?php }else{ ?>
                                    <input type="button" id="add_payment2" class="btn btn-primary btn-large cusbtn"
                                        onclick="quickorder()" name="add-payment"
                                        value="<?php echo display('quickorder') ?>" autocomplete="off">
                                    <?php } ?>
                                    <input type="button" id="add_payment" class="btn btn-success btn-large cusbtn"
                                        onclick="placeorder()" name="add-payment"
                                        value="<?php echo display('placeorder') ?>" autocomplete="off">
                                    <input type="hidden" id="production_setting"
                                        value="<?php echo $possetting->productionsetting; ?>" autocomplete="off">
                                    <input type="hidden" id="production_url"
                                        value="<?php echo base_url("production/production/ingredientcheck") ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile">
            <div class="row m-0" id="onprocesslist"> </div>
        </div>
        <div class="tab-pane fade" id="kitchen">
            <div class="row" id="kitchenstatus"> </div>
        </div>
        <?php if ($isAdmin == 1) {
      if ($qrapp == 1) { ?>
        <div class="tab-pane fade kitchenscrol" id="qrorder"> </div>
        <?php } ?>
        <div class="tab-pane fade kitchenscrol" id="settings"> </div>
        <div class="tab-pane fade kitchenscrol" id="messages"> </div>
        <div class="tab-pane fade kitchenscrol" id="thirdpartynav"></div>
        <?php } else {
      if ((!empty($getpermision)) && $getpermision->qrord == 1) {
        if ($qrapp == 1) { ?>
        <div class="tab-pane fade kitchenscrol" id="qrorder"> </div>
        <?php }
      }
      if ((!empty($getpermision)) && $getpermision->onlineord == 1) {
        ?>
        <div class="tab-pane fade kitchenscrol" id="settings"> </div>
        <?php }
      if ((!empty($getpermision)) && $getpermision->todayord == 1) { ?>
        <div class="tab-pane fade kitchenscrol" id="messages"> </div>
        <?php }
      if ((!empty($getpermision)) && $getpermision->thirdord == 1) { ?>
        <div class="tab-pane fade kitchenscrol" id="thirdpartynav"></div>
        <?php }
    } ?>
    </div>
</div>
<audio id="myAudio" src="<?php echo base_url() ?><?php echo $soundsetting->nofitysound; ?>" preload="auto"
    class="display-none"></audio>
<span style="display: none;" class="btn btn-info" id="test_audio_button"> Test audio button</span>
<div id="payprint2"> </div>
<div class="modal fade modal-warning" id="posprint" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="kotenpr"> </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="orderdetailsp" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
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

<div id="verify_order_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('verify_password')?></strong>
            </div>
            <div class="modal-body" id="verify_order_update_form">
                <!-- Change class to id -->
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

<div id="card_quick_order_mode_pay" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('card_payment')?></strong>
            </div>
            <div class="modal-body" id="bank_list_for_card_pay_mode">
                <!-- Change class to id -->
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

<script>
// $(document).ready(function(){
//   alert('test');
//   //datepicker
//   $(".customer_date_of_brith").datepicker({
//       dateFormat: "yy-mm-dd"
//   });

// });
</script>
<?php
$scan1 = scandir('application/modules/');
$getdisc = "";
foreach ($scan1 as $file) {
  if ($file == "loyalty") {
    if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
      $getdisc = 1;
    }
  }
}
//$this->load->view('include/pos_script');
?>



<script src="<?php echo base_url('ordermanage/order/possettingjs') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('ordermanage/order/quickorderjs') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/possetting.js?v=1.2'); ?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/newscript.js'); ?>" type="text/javascript">
</script>