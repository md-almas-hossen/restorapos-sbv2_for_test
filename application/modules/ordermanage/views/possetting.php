<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('placr_setting') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1760" type="checkbox" class="individual placeord" name="waiter"
                                value="waiter"
                                <?php if ($possetting->waiter == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-1760" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('waiter') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1763" type="checkbox" class="individual placeord" name="tablemaping"
                                value="tablemaping"
                                <?php if ($possetting->tablemaping == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                            <label for="chkbox-1763" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('table_map') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1761" type="checkbox" class="individual placeord" name="table"
                                value="tableid"
                                <?php if ($possetting->tableid == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-1761" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('table') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1764" type="checkbox" class="individual placeord" name="soundenable"
                                value="soundenable"
                                <?php if ($possetting->soundenable == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                            <label for="chkbox-1764" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('is_sound') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1762" type="checkbox" class="individual placeord" name="cooktime"
                                value="cooktime"
                                <?php if ($possetting->cooktime == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-1762" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('cookedtime') ?>
                            </label>

                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkboxcl-dayclosingbutton" type="checkbox" class="individual placeord"
                                name="closingbutton" value="closingbutton"
                                <?php if ($possetting->closingbutton == 1) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?>>
                            <label for="chkboxcl-dayclosingbutton" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('Day_Closing') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="radio-17060" type="radio" class="individual placeord" name="isautoapproved"
                                value="1" autocomplete="off"
                                <?php if ($possetting->isautoapproved == 1) { echo "checked"; } ?>>
                            <label for="radio-17060" class="display-inline-flex">
                                <?php echo display('auto_approved_paid') ?> </label>

                            <input id="radio-17061" type="radio" class="individual placeord" name="isautoapproved"
                                value="2" autocomplete="off"
                                <?php if ($possetting->isautoapproved == 2) {
                                                                                                                                                                echo "checked";                                                                                                                 } ?>>
                            <label for="radio-17061" class="display-inline-flex">
                                <?php echo display('auto_approved_paid_free') ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input id="radio-17062" type="radio" class="individual placeord" name="isautoapproved"
                                value="0" autocomplete="off"
                                <?php if ($possetting->isautoapproved == 0) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?>>
                            <label for="radio-17062" class="display-inline-flex">
                                <?php echo display('no_auto_approved')?></label>

                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('quick_ord') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1860" type="checkbox" class="individual quick" name="waiter"
                                value="waiter"
                                <?php if ($quickorder->waiter == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                            <label for="chkbox-1860" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('waiter') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1863" type="checkbox" class="individual quick" name="tablemaping"
                                value="tablemaping"
                                <?php if ($quickorder->tablemaping == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-1863" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('table_map') ?>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1861" type="checkbox" class="individual quick" name="table"
                                value="tableid"
                                <?php if ($quickorder->tableid == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                            <label for="chkbox-1861" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('table') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1864" type="checkbox" class="individual quick" name="soundenable"
                                value="soundenable"
                                <?php if ($quickorder->soundenable == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-1864" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('is_sound') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1862" type="checkbox" class="individual quick" name="cooktime"
                                value="cooktime"
                                <?php if ($quickorder->cooktime == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-1862" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('cookedtime') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <label for="lastname" class="col-sm-2 col-form-label">
                            <?php echo display('Pos_Theme_Color') ?></label>
                        <div class="col-sm-2">
                            <select name="colormode" class="form-control" id="colormode">
                                <option value="" selected="selected"><?php echo display('select') ?></option>
                                <option value="light-theme" <?php if ($globalsetting->posepagecolor == "light-theme") {
                                                                echo "selected";
                                                            } ?> class='bolden'>Light Mode</option>
                                <option value="dark-theme" <?php if ($globalsetting->posepagecolor == "dark-theme") {
                                                                echo "selected";
                                                            } ?>>Dark Mode</option>
                            </select>
                        </div>

                        <label for="lastname"
                            class="col-sm-2 col-form-label"><?php echo display('Invoice_Layout') ?></label>
                        <div class="col-sm-2">
                            <select name="printerlayout" class="form-control" id="printerlayout">
                                <option value="1" class='bolden' <?php if ($invsetting->invlayout == 1) {
                                                                        echo "selected";
                                                                    } ?>>Layout 1</option>
                                <option value="2" <?php if ($invsetting->invlayout == 2) {
                                                        echo "selected";
                                                    } ?>>Layout 2</option>
                            </select>
                        </div>
                        <label for="lastname"
                            class="col-sm-2 col-form-label"><?php echo display('TableMap_Layout') ?></label>
                        <div class="col-sm-2">
                            <select name="tablemapping" class="form-control" id="tablemapping">
                                <option value="1" class='bolden' <?php if ($globalsetting->tablemaping == 1) {
                                                                        echo "selected";
                                                                    } ?>>Standart Layout</option>
                                <option value="2" <?php if ($globalsetting->tablemaping == 2) {
                                                        echo "selected";
                                                    } ?>>Customize Layout</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">

                        <label for="lastname"
                            class="col-sm-2 col-form-label"><?php echo display('order_mode') ?></label>
                        <div class="col-sm-2">
                            <select name="pos_order_mode" class="form-control" id="pos_order_mode">
                                <option value="1" class='bolden'
                                    <?php if ($globalsetting->pos_order_mode == 1) {echo "selected";} ?>>
                                    <?php echo display('regular_mode') ?>
                                </option>
                                <option value="2" <?php if ($globalsetting->pos_order_mode == 2) { echo "selected";} ?>>
                                    <?php echo display('quick_mode') ?>
                                </option>
                            </select>
                        </div>

                        <label for="lastname"
                            class="col-sm-2 col-form-label"><?php echo display('order_number_format') ?></label>
                        <div class="col-sm-2">
                            <select name="order_number_format" class="form-control" id="order_number_format">
                                <option value="0" class='bolden'
                                    <?php if ($invsetting->order_number_format == 0) {echo "selected";} ?>>
                                    <?php echo display('regular_number') ?>
                                </option>
                                <option value="1" <?php if ($invsetting->order_number_format == 1) { echo "selected";} ?>>
                                    <?php echo display('random_numbers') ?>
                                </option>
                            </select>
                        </div>

                        <label for="lastname"
                            class="col-sm-2 col-form-label"><?php echo display('items_sorting') ?></label>
                        <div class="col-sm-2">
                            <select name="items_sorting" class="form-control" id="items_sorting">
                                <option value="0" class='bolden'
                                    <?php if ($possetting->items_sorting == 0) {echo "selected";} ?>>
                                    <?php echo display('item_position') ?>
                                </option>
                                <option value="1" <?php if ($possetting->items_sorting == 1) { echo "selected";} ?>>
                                    <?php echo display('alphabetical_order') ?>
                                </option>
                            </select>
                        </div>

                    </div>

                </div>
            </div>
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('Invoice_Option_Show_Hide') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-logo" type="checkbox" class="individual inv" name="invoicelogo"
                                value="invlogo"
                                <?php if ($invsetting->invlogo == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-logo" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('logo') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-tableno" type="checkbox" class="individual inv" name="invtableno"
                                value="invtable"
                                <?php if ($invsetting->invtable == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-tableno" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('table'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-vat" type="checkbox" class="individual inv" name="invvat" value="invvat"
                                <?php if ($invsetting->invvat == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?>>
                            <label for="chkbox-vat" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('vat_tax') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-billto" type="checkbox" class="individual inv" name="invbillto"
                                value="invbillto"
                                <?php if ($invsetting->invbillto == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-billto" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('billing_to'); ?>
                            </label>
                        </div>
                    </div>
                    <!--<div class="row bg-brown">
                              <div class="col-sm-12 kitchen-tab" id="option">
                             	<input id="chkbox-qrinvoice" type="checkbox" class="individual inv" name="qroninvoice" value="qroninvoice" <?php if ($invsetting->qroninvoice == 1) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>>
                                <label for="chkbox-qrinvoice" class="display-inline-flex">
                                                    <span class="radio-shape">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                 <?php echo display('isqrshowinvoice'); ?>
                                                </label><a style="right:50%;" class="cattooltipsimg" data-toggle="tooltip" data-placement="top" title="" data-original-title="Only For Saudi Arabia"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                              </div>
                            </div>-->
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-sumnienable" type="checkbox" class="individual inv" name="sumnienable"
                                value="sumnienable"
                                <?php if ($invsetting->sumnienable == 1) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>>
                            <label for="chkbox-sumnienable" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo "is sunmi Enable?"; ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-title" type="checkbox" class="individual inv" name="invtitle"
                                value="invtitle"
                                <?php if ($invsetting->invtitle == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-title" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('title') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-order" type="checkbox" class="individual inv" name="invorderno"
                                value="invorder"
                                <?php if ($invsetting->invorder == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-order" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('orderno'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-service" type="checkbox" class="individual inv" name="invservice"
                                value="invservice"
                                <?php if ($invsetting->invservice == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-service" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('service_chrg') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-billby" type="checkbox" class="individual inv" name="invbillby"
                                value="invbillby"
                                <?php if ($invsetting->invbillby == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-billby" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('bill_by'); ?>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-address" type="checkbox" class="individual inv" name="invaddress"
                                value="invaddress"
                                <?php if ($invsetting->invaddress == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-address" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('address') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-thankyou" type="checkbox" class="individual inv" name="invthankyou"
                                value="invthank"
                                <?php if ($invsetting->invthank == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label for="chkbox-thankyou" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('thankinv'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-discount" type="checkbox" class="individual inv" name="invdiscount"
                                value="invdiscount"
                                <?php if ($invsetting->invdiscount == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                            <label for="chkbox-discount" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('discount') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-mushok" type="checkbox" class="individual inv" name="mushok"
                                value="mushok"
                                <?php if ($invsetting->mushok == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                            <label for="chkbox-mushok" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo "Mushak"; ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="token_history" type="checkbox" class="individual inv" name="token_history"
                                value="token_history"
                                <?php if ($invsetting->token_history == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                            <label for="token_history" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('token_history'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-tin" type="checkbox" class="individual inv" name="invtin" value="invtin"
                                <?php if ($invsetting->invtin == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?>>
                            <label for="chkbox-tin" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('tinvat'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-power" type="checkbox" class="individual inv" name="invpower"
                                value="invpower"
                                <?php if ($invsetting->invpower == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-power" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('powerinv'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-changedue" type="checkbox" class="individual inv" name="invchangedue"
                                value="invchangedue"
                                <?php if ($invsetting->invchangedue == 1) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>>
                            <label for="chkbox-changedue" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('change_due') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-mobile" type="checkbox" class="individual inv" name="mobile"
                                value="mobile"
                                <?php if ($invsetting->mobile == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                            <label for="chkbox-mobile" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('mobile'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-website" type="checkbox" class="individual inv" name="website"
                                value="website"
                                <?php if ($invsetting->website == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label for="chkbox-website" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('website'); ?>
                            </label>
                        </div>
                    </div>

                </div>
            </div>


            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('Day_Closing_Report_setting') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkboxcl-111" type="checkbox" class="individual inv" name="isitemsummery"
                                value="isitemsummery"
                                <?php if ($invsetting->isitemsummery == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                            <label for="chkboxcl-111" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('Show_Item_summery') ?>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">

                </div>
                <div class="col-md-3">

                </div>


            </div>

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('order_password_verification_setting') ?></h4>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <label for="lastname" class="col-sm-2 col-form-label"> <?php echo display('password') ?></label>
                    <div class="col-sm-2">
                        <input id="alert_password" type="password" class="form-control" name="alert_password"
                            placeholder="<?php echo display('password') ?>"
                            value="<?php if($globalsetting->alert_password != null){echo '******';}?>"
                            <?php if($globalsetting->alert_password != null){echo 'readonly';}?>>
                    </div>
                    <div class="col-sm-2">
                        <span type="button" class="btn btn-success" id="update_password"
                            style="<?php if($globalsetting->alert_password != null){echo 'display:none;';}?>"><?php echo display('set') ?></span>
                        <span type="button" class="btn btn-danger" id="reset_up_password"
                            style="<?php if($globalsetting->alert_password == null){echo 'display:none;';}?>"><?php echo display('reset') ?></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/possettingpage.js'); ?>"
    type="text/javascript"></script>