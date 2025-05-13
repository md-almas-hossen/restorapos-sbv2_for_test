<div class="row">
    <div class="col-sm-8">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body" style=" height: 700px;overflow: scroll;">

                <?php echo form_open_multipart('setting/InvoiceTemplate/create','class="form-inner"') ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title"><?php echo display('layout_name'); ?></label>
                            <input name="title" type="text" class="form-control" id="title"
                                placeholder="<?php echo display('layout_name'); ?>" value="">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="design_for"><?php echo display('design_for'); ?></label>
                            <select for="design_for" class="form-control" name="design_type" id="design_type">
                                <option value="1">A4 (Normal)</option>
                                <option value="2">Pos Invoice(Template1)</option>
                                <option value="3">Pos Invoice(Template2)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="logo"><?php echo display('logo'); ?> </label>
                            <input name="logo" type="file" class="form-control" id="logo">
                            <span>Max 1 MB, jpeg,gif,png formats only.</span>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="invoice_logo_show" value="1" checked=""
                                    id="invoice_logo_show" autocomplete="off">
                                <label for="invoice_logo_show"><?php echo display('logo_show_hide'); ?><i
                                        class="text-danger">*</i></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="store_name"><?php echo display('store_name'); ?></label>
                            <input type="text" class="form-control" id="store_name" name="store_name"
                                onkeyup="store_lavel()">
                            <!-- tinymce -->

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="company_name" value="1" checked="" id="company_name"
                                    autocomplete="off">
                                <label for="company_name"><?php echo display('store_name_show_hide'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="tax" value="1" checked="" id="tax" autocomplete="off">
                            <label for="tax"><?php echo display('tax_show_hide'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="mushak" value="1" checked="" id="mushak"
                                    autocomplete="off">
                                <label for="mushak"><?php echo display('mushak_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="mushk_level"><?php echo display('text') ?></label>
                            <input name="mushktext_level" placeholder="<?php echo display('text') ?>" type="text"
                                class="form-control" onkeyup="mushklevel()" id="mushk_level">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="web_level"><?php echo display('website'); ?></label>
                            <input name="web_level" type="text" class="form-control" onkeyup="weblevel()"
                                id="web_level">

                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="website" value="1" checked="" id="website" autocomplete="off">
                            <label for="website"><?php echo display('website_show_hide'); ?></label>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="company_address" value="1" checked="" id="company_address"
                                    autocomplete="off">
                                <label for="company_address"><?php echo display('company_address'); ?></label>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="a4_company_email">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="email" value="1" checked="" id="email" autocomplete="off">
                                <label for="email"><?php echo display('company_email_show_hide') ?> </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="a4_mobile_num">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="mobile_num" value="1" checked="" id="mobile_num"
                                    autocomplete="off">
                                <label for="mobile_num"><?php echo display('compnay_mobile_show_hide') ?> </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="a4_customer_info">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="customer_address" value="1" checked=""
                                    id="customer_address" autocomplete="off">
                                <label for="customer_address"><?php echo display('customer_address') ?> </label>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="customer_email" value="1" checked="" id="customer_email"
                                    autocomplete="off">
                                <label for="customer_email"><?php echo display('customer_email_show_hide') ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="customer_mobile" value="1" checked="" id="customer_mobile"
                                autocomplete="off">
                            <label for="customer_mobile"><?php echo display('customer_mobile_show_hide') ?></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6" id="bin_lev_pos_print">
                        <div class="form-group">
                            <label for="bin_level"><?php echo display('tin_level'); ?></label>
                            <input name="bin_level" type="text" class="form-control" onkeyup="binlevel()"
                                id="bin_level">

                        </div>
                    </div>
                    <div class="col-sm-6" id="bin_pos_print">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="bit_pos_show" checked="" value="1" id="bit_pos_show"
                                autocomplete="off">
                            <label for="bit_pos_show"><?php echo display('tin_vat_show_hide'); ?></label>
                        </div>
                    </div>

                </div>



                <div class="row" id="invoice_level_a4">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="invoice_level"><?php echo display('invoice_level'); ?></label>
                            <input name="invoice_level" type="text" class="form-control" onkeyup="invoiceLevel()"
                                id="invoice_level">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="invoice_level_show" value="1" checked=""
                                    id="invoice_level_show" autocomplete="off">
                                <label for="invoice_level_show"><?php echo display('invoice_level_show_hide'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="date_level"><?php echo display('date_level'); ?></label>
                            <input name="date_level" type="text" class="form-control" id="date_level"
                                onkeyup="date_lavel()" placeholder="Date Level">

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="date_time_formate"><?php echo display('date_time_format') ?></label>
                            <select class="form-control" name="date_time_formate" required="" id="date_time_formate">
                                <option value="d-m-Y">dd-mm-yyyy</option>
                                <option value="m-d-Y">mm-dd-yyyy</option>
                                <option value="d/m/Y">dd/mm/yyyy</option>
                                <option value="m/d/Y">mm/dd/yyyy</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="date_show" value="1" checked="" id="date_show"
                                autocomplete="off">
                            <label for="date_show"><?php echo display('date_show_hide'); ?></label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox checkbox-success" style="margin-top:26px;">
                            <input type="checkbox" name="time_show" value="1" checked="" id="time_show"
                                autocomplete="off">
                            <label for="time_show"><?php echo display('time_show_hide'); ?></label>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="subtotal_level"><?php echo display('subtotal_level'); ?> </label>
                            <input name="subtotal_level" type="text" class="form-control" id="subtotal_level"
                                onkeyup="subtotalLevel()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="subtotal_level_show" value="1" checked=""
                                    id="subtotal_level_show" autocomplete="off">
                                <label for="subtotal_level_show"><?php echo display('subtotal_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title"><?php echo display('service_chrg'); ?></label>
                            <input name="service_charge" type="text" class="form-control" id="service_charge"
                                onkeyup="serviceCharge()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="servicechargeshow" value="1" checked=""
                                    id="servicechargeshow" autocomplete="off">
                                <label for="servicechargeshow"><?php echo display('service_charge_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="vat_level"><?php echo display('vat_level') ?></label>
                            <input name="vat_level" type="text" class="form-control" id="vat_level"
                                onkeyup="vatlevel()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="vatshow" value="1" checked="" id="vatshow"
                                    autocomplete="off">
                                <label for="vatshow"><?php echo display('vat_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="discount_level"><?php echo display('discount_level') ?></label>
                            <input name="discount_level" type="text" class="form-control" id="discount_level"
                                onkeyup="discountLevel()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="discountshow" checked="" value="1" id="discountshow"
                                    autocomplete="off">
                                <label for="discountshow"><?php echo display('discount_show_hide'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title"><?php echo display('grand_total_level') ?></label>
                            <input name="grand_total" type="text" class="form-control" id="grand_total"
                                onkeyup="grandTotal()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="grandtotalshow" value="1" checked="" id="grandtotalshow"
                                    autocomplete="off">
                                <label for="grandtotalshow"><?php echo display('grand_total_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="cutomer_paid_amount"><?php echo display('customer_paid_amount_level'); ?></label>
                            <input name="cutomer_paid_amount" type="text" class="form-control" id="cutomer_paid_amount"
                                onkeyup="cutomer_paidAmount()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="customer_paid_show" value="1" checked=""
                                    id="customer_paid_show" autocomplete="off">
                                <label
                                    for="customer_paid_show"><?php echo display('customer_paid_amount_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title"><?php echo display('total_due_level'); ?></label>
                            <input name="total_due" type="text" class="form-control" id="total_due"
                                onkeyup="totalDue()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="total_due_show" value="1" checked="" id="total_due_show"
                                    autocomplete="off">
                                <label for="total_due_show"><?php echo display('total_due_show_hide'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="change_due_level"><?php echo display('change_due_level'); ?></label>
                            <input name="change_due_level" type="text" class="form-control" id="change_due_level"
                                onkeyup="change_dueLevel()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="change_due_show" value="1" checked="" id="change_due_show"
                                    autocomplete="off">
                                <label for="change_due_show"><?php echo display('change_due_show_hide') ?></label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" id="pos_toatl_payment">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="total_payment"><?php echo display('total_payment_level'); ?></label>
                            <input name="total_payment" type="text" class="form-control" id="total_payment"
                                onkeyup="totalPayment()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="total_payment_show" value="1" checked=""
                                    id="total_payment_show" autocomplete="off">
                                <label for="total_payment_show"><?php echo display('total_payment_show_hide') ?>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="billing_to"><?php echo display('billing_to_level') ?></label>
                            <input name="billing_to" type="text" class="form-control" id="billing_to"
                                onkeyup="billingTo()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="billing_to_show" checked="" value="1" id="billing_to_show"
                                    autocomplete="off">
                                <label for="billing_to_show"><?php echo display('bill_to_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="bill_by"><?php echo display('bill_by_level') ?></label>
                            <input name="bill_by" type="text" class="form-control" id="bill_by" onkeyup="billBy()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="bill_by_show" value="1" checked="" id="bill_by_show"
                                    autocomplete="off">
                                <label for="bill_by_show"><?php echo display('bill_by_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="waiter_by"><?php echo display('waiter_level') ?></label>
                            <input name="waiter_by" type="text" class="form-control" id="waiter_by"
                                onkeyup="waiterBy()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="waiter_by_show" value="1" checked="" id="waiter_by_show"
                                    autocomplete="off">
                                <label for="waiter_by_show"><?php echo display('waiter_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="table_pos_print">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="table_level"><?php echo display('table_level') ?></label>
                            <input name="table_level" type="text" class="form-control" id="table_level"
                                onkeyup="tableLevel()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="table_level_show" value="1" checked=""
                                    id="table_level_show" autocomplete="off">
                                <label for="table_level_show"><?php echo display('table_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="order_no"><?php echo display('order_no_level'); ?></label>
                            <input name="order_no" type="text" class="form-control" id="order_no" onkeyup="orderNo()">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="order_no_show" checked="" value="1" id="order_no_show"
                                    autocomplete="off">
                                <label for="order_no_show"><?php echo display('order_no_show_hide') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="payment_status"><?php echo display('payment_status_level'); ?></label>
                            <input name="payment_status" type="text" class="form-control" id="payment_status"
                                onkeyup="paymentStatus()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success" style="margin-top:26px;">
                                <input type="checkbox" name="payment_status_show" value="1" checked=""
                                    id="payment_status_show" autocomplete="off">
                                <label for="payment_status_show"><?php echo display('payment_status_show_hide'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="lineheightgpos">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lineHeight"><?php echo display('line_height_px'); ?></label>
                            <input name="lineHeight" type="number" max="80" min="10" class="form-control"
                                id="lineHeight" onkeyup="lineHeightP()">
                        </div>
                    </div>
                </div>

                <div class="row" id="fontsize">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="fontsize_px"><?php echo display('fonts_size_px'); ?></label>
                            <input name="fontsize" type="number" max="80" min="10" class="form-control"
                                onkeyup="fontsizeP()" id="fontsize_px">
                        </div>
                    </div>
                </div>
                <div class="row" id="">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="custom_fonts"><?php echo display('fonts'); ?></label>
                            <select id="custom_fonts" name="custom_fonts" class="form-control">
                                <option value="Arial,sans-serif">Arial</option>
                                <option value='"Times New Roman",sans-serif'>Times New Roman</option>
                                <option value='Verdana,sans-serif'>Verdana</option>
                                <option value='Georgia'>Georgia</option>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="footer_pos_print">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="footer_text"><?php echo display('footer_text'); ?></label>
                            <input type='text' class="form-control" name="footer_text" id="footer_text"
                                onkeyup="footertext()">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" name="footertextshow" checked="" value="1" id="footertextshow"
                                    autocomplete="off">
                                <label for="footertextshow"><?php echo display('footer_text_show') ?> </label>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-group text-right">
                    <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4" id="pos_print">
        <div class="invoice-card ">
            <div class="invoice-head line-height">
                <img src="<?php echo base_url();?><?php echo $storeinfo->logo?>" id="previewImg" alt="" width="50%"
                    class="imgprev">
                <div id="headerinfo" class="line-height fontsizepx">
                    <h4 class="line-height fontsizepx text-center store_name" id="bhojon_name">Bhojon</h4>
                    <p class="line-height fontsizepx my-0 bhojon_address " id="bhojon_address">98 Green Road, Farmgate,
                        Dhaka-1215</p>
                    <p class="my-0 text-center linehight a4phn_number fontsize_px"><strong>Mobile:
                            <?php echo $storeinfo->phone;?></strong></p>
                    <p class="my-0 text-center linehight pweb fontsize_px"><strong class="domain">Website:
                            <?php echo base_url();?></strong></p>
                    <p class="my-0 text-center linehight pmushak fontsize_px"><strong><span
                                class="msktext">Mushak-6.3</span></strong></p>
                </div>
            </div>
            <div class="line-height fontsizepx invoice_address">
                <div class="line-height fontsizepx  row-data">
                    <div class="line-height fontsizepx item-info">
                        <!-- <h5 class="item-title date_pre"> <span class="dlevel">Date</span> : <span class="preDate"><?php echo date('m-d-Y');?></span></h5><span id="time_show_hide">10:12:20</span><h5 class="bit_pos_hide">BIN:123455</h5> -->
                        <div class="line-height fontsizepx"><span class="item-title date_pre fontsizepx"><span
                                    class="dlevel fontsizepx">Date</span>:<span
                                    class="preDate fontsizepx"><?php echo date('m-d-Y');?></span></span><span
                                class="time_show_hide fontsizepx" style="margin-left:3px ;">Time:10:10:12</span><span
                                class="bit_pos_hide fontsizepx" style="margin-left:3px ;"><span
                                    id="bin_level_prev">TIN</span>:101000</span></div>
                    </div>
                </div>
            </div>

            <div class="invoice-details line-height fontsizepx">
                <div class="invoice-list line-height fontsizepx">
                    <div class="invoice-title line-height fontsizepx">
                        <h4 class="heading line-height fontsizepx"><?php echo display('item')?></h4>
                        <h4 class="heading heading-child line-height fontsizepx"><?php echo display('total')?></h4>
                    </div>

                    <div class="invoice-data line-height fontsizepx">

                        <div class="row-data line-height fontsizepx">
                            <div class="item-info line-height fontsizepx">
                                <h5 class="item-title line-height fontsizepx">Pizza</h5>
                                <p class="item-size line-height fontsizepx">Small</p>
                                <p class="item-number line-height fontsizepx">100 x 1</p>
                            </div>
                            <h5>$100</h5>
                        </div>

                    </div>
                </div>
            </div>
            <div class="invoice-footer mb-15 line-height fontsizepx">
                <div class="row-data subtotal_level_hide line-height fontsizepx">
                    <div class="item-info line-height fontsizepx">
                        <h5 class="item-title subtotal_level_prev line-height fontsizepx">
                            <?php echo display('subtotal')?></h5>
                    </div>
                    <h5 class="my-5 line-height fontsizepx">$100.00</h5>
                </div>
                <div class="line-height fontsizepx row-data vat_hide">
                    <div class="item-info line-height fontsizepx">
                        <h5 class="line-height fontsizepx item-title vat_prev"><?php echo display('vat_tax')?>(10%)</h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$50.00</h5>
                </div>
                <div class="line-height fontsizepx row-data servicecharge_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class=" line-height fontsizepx item-title service_level_prev">
                            <?php echo display('service_chrg')?></h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$100.00</h5>
                </div>
                <div class="line-height fontsizepx row-data tax_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title"><?php echo display('tax')?></h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$100.00</h5>
                </div>
                <div class="line-height fontsizepx row-data discount_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title discount_prev"><?php echo display('discount')?>
                        </h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$100.00</h5>
                </div>

                <div class="line-height fontsizepx row-data border-top grand_total_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title text-bold grand_total_prev">
                            <?php echo display('grand_total')?></h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$1000.00</h5>
                </div>

                <div class="line-height fontsizepx row-data gcutomer_paidAmount_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title gcutomer_paidAmount_prev">
                            <?php echo display('customer_paid_amount')?></h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$1000.00</h5>
                </div>

                <div class="line-height fontsizepx row-data total_due_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title total_due_prev"><?php echo display('total_due')?>
                        </h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$10.00</h5>
                </div>
                <div class="line-height fontsizepx row-data change_due_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title change_due_prev"><?php echo display('change_due')?>
                        </h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$5.00</h5>
                </div>

                <div class="line-height fontsizepx row-data total_payment_hide">
                    <div class="line-height fontsizepx item-info">
                        <h5 class="line-height fontsizepx item-title total_payment_prev">
                            <?php echo display('totalpayment')?></h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5">$5.00</h5>
                </div>
            </div>

            <div class="line-height fontsizepx invoice_address">
                <div class="line-height fontsizepx row-data ">

                    <div class="line-height fontsizepx item-info billing_to_hide">
                        <h5 class="line-height fontsizepx item-title billing_to_prev">
                            <?php echo display('billing_to');?>:Mr. David</h5>
                    </div>
                    <h5 class="line-height fontsizepx my-5 bill_by_prev"><?php echo display('bill_by');?>:Jhon Smith
                    </h5>
                </div>
                <div class="line-height fontsizepx middle-data">

                    <div class="line-height fontsizepx item-info gap_right table_level_hide">
                        <span
                            class="line-height fontsizepx item-title table_level_prev"><?php echo display('table');?>:</span>10
                    </div>


                    <div class="line-height fontsizepx item-info order_no_hide">
                        <span class=" line-height fontsizepx item-title "><span
                                class="order_no_prev"><?php echo display('orderno')?></span></span>1001
                    </div>

                </div>
                <div class="line-height fontsizepx middle-data">
                    <div class="line-height fontsizepx text-center waiter_by_prev">
                        <span class="line-height fontsizepx item-title"
                            style="font-size:16px; font-weight:400;"><?php echo "Waiter";?></span>:<strong
                            style="font-size:16px; font-weight:400;">Jhon Doe</strong>
                    </div>
                </div>

                <div class="line-height fontsizepx middle-data payment_status_hide">
                    <div class="line-height fontsizepx text-center">
                        <span class="line-height fontsizepx item-title payment_status_prev"
                            style="font-size:18px; font-weight:bold;"><?php echo "Payment Status";?></span>:<strong
                            style="font-size:18px; font-weight:bold;">Due</strong>
                    </div>
                </div>
                <div class="text-center ">
                    <h3 class="mt-10 footer_text_prev fontsizepx" id="footertext_hide">
                        <?php echo display('thanks_you')?></h3>
                    <p class="b_top fontsizepx"><?php echo display('powerbybdtask')?></p>

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4" id="normal_print">
        <div class="row">
            <div class="col-sm-10 wpr_68 display-inlineblock">
                <!-- <img src="<?php //echo base_url();?><?php //echo $storeinfo->logo?>" class="img img-responsive height-mb" alt=""> -->
                <img src="<?php echo base_url();?><?php echo $storeinfo->logo?>"
                    class="img img-responsive height-mb imgprev" id="a4previewImg">
                <br>
                <span
                    class="label label-success-outline m-r-15 p-10 bill_by_prev"><?php echo display('billing_from') ?></span>
                <address class="mt-10">
                    <strong class="store_name">Bdtask</strong>
                    <span class="bhojon_address"><br><?php echo $storeinfo->address;?></span>
                    <abbr class="a4phn_number"><br><?php echo display('mobile') ?>:12345678910</abbr>
                    <abbr id="showemail"><br><?php echo display('email') ?>:example@gmail.com</abbr><br>
                    <abbr class="pweb"><span class="domain">Website: <?php echo base_url();?></span></abbr><br>
                    <abbr class="pmushak"><span class="msktext">Mushak-6.3</span></abbr><br>
                </address>

            </div>
            <div class="col-sm-2 text-left mb-display">
                <h2 class="m-t-0" id="invoice_level_hide">Invoice</h2>
                <div id="" class="order_no order_no_hide"><span class="order_no_prev">Invoice No</span>:010</div>
                <div id="" class="waiter waiter_by_prev"><span class="">Waiter</span>:Jhon</div>
                <div class="payment_status_hide">
                    <div class="payment_status_prev"><?php echo display('order_status') ?></div>:Paid
                </div>
                <div class=" date_pre"><span class="dlevel">Date</span>:<span
                        class="preDate"><?php echo date('m-d-Y');?></span></div><span
                    class="time_show_hide">10:10:12</span>
                <!-- m-b-15 -->


                <span
                    class="label label-success-outline m-r-15 billing_to_prev"><?php echo display('billing_to') ?></span>
                <address class="mt-10">
                    <strong>John Smith</strong><br>
                    <div id="customer_address_hide">
                        <abbr><?php echo display('address') ?>:</abbr>
                        <c class="wmp">Khilkhet,Bdtask- 1229,Dhaka</c><br>
                    </div>
                    <div id="customer_mobile_hide">
                        <abbr><?php echo display('mobile') ?>:</abbr>0183000121024</abbr>
                    </div>
                    <div id="customer_email_hide">
                        <abbr><?php echo "Email Address" ?> :
                        </abbr>Customer@gmail.com</abbr>
                    </div>
                </address>

            </div>
        </div>


        <hr>

        <div class="table-responsive m-b-20">
            <table class="table table-fixed table-bordered table-hover bg-white" id="purchaseTable">
                <thead>
                    <tr>
                        <th class="text-center"><?php echo display('item')?> </th>
                        <th class="text-center"><?php echo display('size')?></th>
                        <th class="text-center wp_100"><?php echo display('unit_price')?></th>
                        <th class="text-center wp_100"><?php echo display('qty')?></th>
                        <th class="text-center"><?php echo display('total_price')?></th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>
                            Product Name
                        </td>
                        <td>Large</td>
                        <td class="text-right">$500</td>
                        <td class="text-right">8</td>
                        <td class="text-right">
                            <strong>$400</strong>
                        </td>
                    </tr>

                    <tr class="subtotal_level_hide">
                        <td class="text-right " colspan="4"><strong
                                class="subtotal_level_prev"><?php echo display('subtotal')?></strong>
                        </td>
                        <td class="text-right">
                            <strong>$10</strong>
                        </td>
                    </tr>

                    <tr class="discount_hide">
                        <td class="text-right" colspan="4">
                            <strong class="discount_prev"><?php echo display('discount')?>(10%)</strong>
                        </td>
                        <td class="text-right">
                            <strong>$10</strong>
                        </td>
                    </tr>


                    <tr class="servicecharge_hide">
                        <td class="text-right" colspan="4">
                            <strong class="service_level_prev"><?php echo display('service_chrg')?>(23)%</strong>
                        </td>
                        <td class="text-right">
                            <strong>$7</strong>
                        </td>
                    </tr>

                    <tr class="vat_hide">
                        <td class="text-right" colspan="4"><strong class="vat_prev"><?php echo display('vat_tax')?>
                                (10%)</strong></td>
                        <td class="text-right">
                            <strong>$10</strong>
                        </td>
                    </tr>
                    <tr class="tax_hide">
                        <td class="text-right" colspan="4"><strong>Tax</strong>
                        </td>
                        <td class="text-right">
                            <strong>
                                $10
                            </strong>
                        </td>
                    </tr>
                    <tr class="grand_total_hide">
                        <td class="text-right" colspan="4">
                            <strong class="grand_total_prev"><?php echo display('grand_total')?></strong>
                        </td>
                        <td class="text-right">
                            <strong>$100</strong>
                        </td>
                    </tr>

                    <tr class="gcutomer_paidAmount_hide">
                        <td align="right" colspan="4">
                            <nobr class="gcutomer_paidAmount_prev"><?php echo display('customer_paid_amount')?></nobr>
                        </td>
                        <td align="right">
                            <nobr>$100</nobr>
                        </td>
                    </tr>
                    <tr class="total_due_hide">
                        <td align="right" colspan="4">
                            <nobr class="total_due_prev"><?php echo display('total_due')?></nobr>
                        </td>
                        <td align="right">
                            <nobr>$200</nobr>
                        </td>
                    </tr>


                    <tr class="change_due_hide">
                        <td align="right" colspan="4">
                            <nobr class="change_due_prev"><?php echo display('change_due')?></nobr>
                        </td>
                        <td align="right">
                            <nobr>$100</nobr>
                        </td>
                    </tr>


                </tbody>
                <tfoot>

                </tfoot>
            </table>

        </div>





    </div>
    <div class="col-sm-4" id="pos_print_narrow">
        <div id="printableArea" class="bill__container bill-pos-mini__container"
            style="width: 278px; font-size:11px; margin: 0 auto;">
            <div class="pt-5">
                <div class="bill-pos-mini__logo border line-height fontsizepx" align="center"><img
                        src="<?php echo base_url();?><?php echo $storeinfo->logo?>" class="img img-responsive imgprev"
                        alt=""></div>
            </div>
            <div class="px-4">
                <p class="text-note text-center mb-3 store_name line-height fontsizepx" id="bhojon_name">Bhojon</p>
                <p class="text-note text-center mb-3 bhojon_address line-height fontsizepx" id="bhojon_address">Mannan
                    Plaza, B-25 Khilkhet, Dhaka-1229</p>
                <p class="text-note text-center mb-3 a4phn_number line-height fontsizepx"><strong>Mobile:
                        <?php echo $storeinfo->phone;?></strong></p>
                <p class="text-note text-center mb-3 pweb line-height fontsizepx"><strong class="domain">Website:
                        <?php echo base_url();?></strong></p>
                <p class="text-note text-center mb-3 pmushak line-height fontsizepx"><strong
                        class="msktext">Mushak-6.3</strong></p>
                <div>
                    <p class="mb-0 order_no_hide line-height fontsizepx"><b
                            class="text-bold fontsizepx"><?php echo display('orderno')?>: </b> #0071</p>
                    <p class="mb-0 bit_pos_hide line-height fontsizepx"><b class="text-bold fontsizepx">TIN: </b>1233444
                    </p>
                    <p class="mb-0 date_pre"><b class="text-bold fontsizepx">Date: </b><?php echo date('m-d-Y');?>
                        10:10:12 pm</p>
                </div>
                <div class="pb-3 border-bottom--dashed">
                    <table class="w-100">
                        <thead>
                            <th style="width: 50%;" class="fontsizepx"><strong class="fontsizepx">Item</strong></th>
                            <th style="width: 50%; text-align: right;" class="fontsizepx"><strong
                                    class="fontsizepx">Total</strong></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-0">
                                    <p class="mb-0 fontsizepx">Water Mineral-250 ml</p><small
                                        class="mb-0 text-italic">15 x 1</small>
                                </td>
                                <td valign="top" class="p-0 text-right fontsizepx">
                                    <p class="mb-0 fontsizepx"> 15 ৳</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="border-bottom--dashed">
                    <div class="d-flex justify-content-between align-items-center mb-2 subtotal_level_hide">
                        <p class="mb-0 text-note text-primary text-bold subtotal_level_prev line-height fontsizepx">
                            Subtotal:</p>
                        <p class="mb-0 text-note text-primary text-bold line-height fontsizepx"> 15 ৳</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 discount_hide">
                        <p class="mb-0 text-note text-primary discount_prev line-height fontsizepx">Discount</p>
                        <p class="mb-0 text-note text-primary line-height fontsizepx">- 0 ৳</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 servicecharge_hide">
                        <p class="mb-0 text-note text-primary service_level_prev line-height fontsizepx">Service Charge:
                        </p>
                        <p class="mb-0 text-note text-primary line-height fontsizepx">10৳</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 vat_hide">
                        <p class="mb-0 text-note text-primary vat_prev line-height fontsizepx">Vat(10.00%):</p>
                        <p class="mb-0 text-note text-primary line-height fontsizepx"> 1 ৳</p>
                    </div>
                </div>
                <div class="border-bottom--dashed">
                    <div class="d-flex justify-content-between align-items-center mb-2 grand_total_hide">
                        <p class="mb-0 text-note text-primary text-bold grand_total_prev line-height fontsizepx">Grand
                            Total:</p>
                        <p class="mb-0 text-note text-primary text-bold line-height fontsizepx"> 25 ৳</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center total_due_hide">
                        <p class="mb-0 text-note text-primary total_due_prev line-height fontsizepx">Total Due:</p>
                        <p class="mb-0 text-note text-primary line-height fontsizepx"> 25 ৳</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center change_due_hide">
                        <p class="mb-0 text-note text-primary change_due_prev line-height fontsizepx">Change Due:</p>
                        <p class="mb-0 text-note text-primary line-height fontsizepx"> 0.00 ৳</p>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="mx-auto mb-1" style="width: 25%; border: 1px solid rgb(0, 0, 0);"></div>
                <p class="text-note text-center text-bold mb-2 billing_to_hide line-height fontsizepx"><span
                        class="billing_to_prev fontsizepx"><?php echo display('billing_to');?></span></p>
                <div class="middle-data payment_status_hide">
                    <div class="text-center">
                        <p class="item-title line-height waiter_by_prev fontsizepx"><span class="">Waiter</span>: Jhon
                            Doe </p>
                        <p class="item-title line-height fontsizepx"><span
                                class="payment_status_prev fontsizepx">Payment Status</span>: Due </p>
                    </div>
                </div>

            </div>
            <div class="border-top py-1" id="footertext_hide2">
                <p class="text-note text-primary text-center mb-0 text-bold footer_text_prev line-height fontsizepx">
                    Thank you very much</p>
                <p class="text-note text-primary text-center mb-0 line-height fontsizepx">Powered  By: RESTORAPOS,
                    www.restorapos.com</p>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {

    $("#pos_print").hide();
    $("#pos_print_narrow").hide();
    $('#footer_pos_print').hide();
    $('#table_pos_print').hide();
    $('#bin_pos_print').hide();
    $('#bin_lev_pos_print').hide();
    $('#pos_toatl_payment').hide();
    $('#lineheightgpos').hide();
    $('#fontsize').hide();

    $('#footer_pos_print input[type="checkbox"]').attr("disabled", true);
    $('#table_pos_print input[type="checkbox"]').attr("disabled", true);
    $('#bin_pos_print input[type="checkbox"]').attr("disabled", true);
    $('#pos_toatl_payment input[type="checkbox"]').attr("disabled", true);
    $('#bin_lev_pos_print input[type="checkbox"]').attr("disabled", true);

    $("#design_type").change(function() {
        var design_type = $('#design_type').val();


        if (design_type == 1) {
            $("#pos_print").hide();
            $("#pos_print_narrow").hide();
            $('#footer_pos_print').hide();
            $('#table_pos_print').hide();
            $('#bin_pos_print').hide();
            $('#bin_lev_pos_print').hide();
            $('#lineheightgpos').hide();
            $('#fontsize').hide();
            $('#invoice_level_a4').show();
            $('#a4_mobile_num').show();
            $('#normal_print').show();
            $('#pos_toatl_payment').hide();

            $('#a4_customer_info').show();
            $('#a4_company_email').show();

            $('#footer_pos_print input[type="checkbox"]').attr("disabled", true);
            $('#table_pos_print input[type="checkbox"]').attr("disabled", true);
            $('#bin_pos_print input[type="checkbox"]').attr("disabled", true);
            $('#bin_lev_pos_print input[type="checkbox"]').attr("disabled", true);
            $('#pos_toatl_payment input[type="checkbox"]').attr("disabled", true);



            $('#a4_customer_info input[type="checkbox"]').attr("disabled", false);
            $('#invoice_level_a4 input[type="checkbox"]').attr("disabled", false);
            $('#a4_mobile_num input[type="checkbox"]').attr("disabled", false);
            $('#a4_company_email input[type="checkbox"]').attr("disabled", false);
        } else if (design_type == 2) {
            $("#pos_print").show();
            $("#pos_print_narrow").hide();
            $('#footer_pos_print').show();
            $('#table_pos_print').show();
            $('#bin_pos_print').show();
            $('#bin_lev_pos_print').show();
            $('#pos_toatl_payment').show();

            $('#invoice_level_a4').hide();
            $('#a4_mobile_num').show();
            $('#normal_print').hide();

            $('#a4_customer_info').show();



            $('#a4_customer_info input[type="checkbox"]').attr("disabled", false);
            $('#invoice_level_a4 input[type="checkbox"]').attr("disabled", true);
            /*$('#a4_mobile_num input[type="checkbox"]').attr("disabled", true);*/
            //$('#a4_company_email input[type="checkbox"]').attr("disabled", true);
            $('#a4_company_email').show();
            $('#lineheightgpos').show();
            $('#fontsize').show();



            $('#footer_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#table_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#bin_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#bin_lev_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#pos_toatl_payment input[type="checkbox"]').attr("disabled", false);
        } else {
            $("#pos_print_narrow").show();
            $("#pos_print").hide();
            $('#footer_pos_print').show();
            $('#table_pos_print').show();
            $('#bin_pos_print').show();
            $('#bin_lev_pos_print').show();
            $('#pos_toatl_payment').show();

            $('#invoice_level_a4').hide();
            $('#a4_mobile_num').show();
            $('#normal_print').hide();

            $('#a4_customer_info').show();



            $('#a4_customer_info input[type="checkbox"]').attr("disabled", false);
            $('#invoice_level_a4 input[type="checkbox"]').attr("disabled", true);
            /*$('#a4_mobile_num input[type="checkbox"]').attr("disabled", true);*/
            //$('#a4_company_email input[type="checkbox"]').attr("disabled", true);
            $('#a4_company_email').show();
            $('#lineheightgpos').show();
            $('#fontsize').show();


            $('#footer_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#table_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#bin_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#bin_lev_pos_print input[type="checkbox"]').attr("disabled", false);
            $('#pos_toatl_payment input[type="checkbox"]').attr("disabled", false);




        }

    });
    $("#logo").change(function() {
        var file = $("input[type=file]").get(0).files[0]
        //    file.name
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                // $(".previewImg").attr("src", reader.result);
                $(".imgprev").attr("src", reader.result);

            }
            reader.readAsDataURL(file);
        }

    });

    $("#invoice_logo_show").click(function() {
        if ($("#invoice_logo_show").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.imgprev').show();
        } else {
            $('.imgprev').hide();
            // alert("Check box is Unchecked");
        }
    });


    $("#mobile_num").click(function() {
        if ($("#mobile_num").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.a4phn_number').show();
        } else {
            $('.a4phn_number').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#mushak").click(function() {
        if ($("#mushak").is(":checked")) {
            // alert("Check box in Checked");
            $('.pmushak').show();
        } else {
            $('.pmushak').hide();
            // alert("Check box is Unchecked");
        }
    });


    $("#website").click(function() {
        if ($("#website").is(":checked")) {
            // alert("Check box in Checked");
            $('.pweb').show();
        } else {
            $('.pweb').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#email").click(function() {
        if ($("#email").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('#showemail').show();
        } else {
            $('#showemail').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#tax").click(function() {
        if ($("#tax").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.tax_hide').show();
        } else {
            $('.tax_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#invoice_level_show").click(function() {
        if ($("#invoice_level_show").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('#invoice_level_hide').show();
        } else {
            $('#invoice_level_hide').hide();
            // alert("Check box is Unchecked");
        }
    });

    $("#date_show").click(function() {
        if ($("#date_show").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.date_pre').show();
        } else {
            $('.date_pre').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#subtotal_level_show").click(function() {
        if ($("#subtotal_level_show").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.subtotal_level_hide').show();
        } else {
            $('.subtotal_level_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#servicechargeshow").click(function() {
        if ($("#servicechargeshow").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.servicecharge_hide').show();
        } else {
            $('.servicecharge_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#vatshow").click(function() {
        if ($("#vatshow").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.vat_hide').show();
        } else {
            $('.vat_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#discountshow").click(function() {
        if ($("#discountshow").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.discount_hide').show();
        } else {
            $('.discount_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#grandtotalshow").click(function() {
        if ($("#grandtotalshow").is(
                ":checked")) {
            // alert("Check box in Checked");
            $('.grand_total_hide').show();
        } else {
            $('.grand_total_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#customer_paid_show").click(function() {
        if ($("#customer_paid_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.gcutomer_paidAmount_hide').show();
        } else {
            $('.gcutomer_paidAmount_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#total_due_show").click(function() {
        if ($("#total_due_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.total_due_hide').show();
        } else {
            $('.total_due_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#change_due_show").click(function() {
        if ($("#change_due_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.change_due_hide').show();
        } else {
            $('.change_due_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#total_payment_show").click(function() {
        if ($("#total_payment_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.total_payment_hide').show();
        } else {
            $('.total_payment_hide').hide();
            // alert("Check box is Unchecked");
        }
    });

    $("#billing_to_show").click(function() {
        if ($("#billing_to_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.billing_to_prev').show();
        } else {
            $('.billing_to_prev').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#bill_by_show").click(function() {
        if ($("#bill_by_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.bill_by_prev').show();
        } else {
            $('.bill_by_prev').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#waiter_by_show").click(function() {
        if ($("#waiter_by_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.waiter_by_prev').show();
        } else {
            $('.waiter_by_prev').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#table_level_show").click(function() {
        if ($("#table_level_show").is(":checked")) {
            // alert("Check box in Checked");
            $('.table_level_hide').show();
        } else {
            $('.table_level_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#order_no_show").click(function() {
        if ($("#order_no_show").is(":checked")) {

            // alert("Check box in Checked");
            $('.order_no_hide').show();
        } else {
            $('.order_no_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#payment_status_show").click(function() {
        if ($("#payment_status_show").is(":checked")) {

            // alert("Check box in Checked");
            $('.payment_status_hide').show();
        } else {
            $('.payment_status_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#footertextshow").click(function() {
        if ($("#footertextshow").is(":checked")) {

            // alert("Check box in Checked");
            $('#footertext_hide').show();
            $('#footertext_hide2').show();
        } else {
            $('#footertext_hide').hide();
            $('#footertext_hide2').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#bit_pos_show").click(function() {
        if ($("#bit_pos_show").is(":checked")) {

            // alert("Check box in Checked");
            $('.bit_pos_hide').show();
        } else {
            $('.bit_pos_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#customer_address").click(function() {
        if ($("#customer_address").is(":checked")) {

            // alert("Check box in Checked");
            $('#customer_address_hide').show();
        } else {
            $('#customer_address_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#customer_email").click(function() {
        if ($("#customer_email").is(":checked")) {

            // alert("Check box in Checked");
            $('#customer_email_hide').show();
        } else {
            $('#customer_email_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#customer_mobile").click(function() {
        if ($("#customer_mobile").is(":checked")) {

            // alert("Check box in Checked");
            $('#customer_mobile_hide').show();
        } else {
            $('#customer_mobile_hide').hide();
            // alert("Check box is Unchecked");
        }
    });
    $("#time_show").click(function() {
        if ($("#time_show").is(":checked")) {

            // alert("Check box in Checked");
            $('.time_show_hide').show();
        } else {
            $('.time_show_hide').hide();
            // alert("Check box is Unchecked");
        }
    });





    $("#companyinfo").click(function() {
        if ($("#companyinfo").is(":checked")) {
            // alert("Check box in Checked");
            $('#footerinfo').show();
            $('#headerinfo').show();
        } else {
            // alert("Check box is Unchecked");
            $('#footerinfo').hide();
            $('#headerinfo').hide();
        }
    });

    $("#company_name").click(function() {
        if ($("#company_name").is(
                ":checked")) {
            // $('#bhojon_name').show();
            $('.store_name').show();
        } else {
            // $('#bhojon_name').hide();
            $('.store_name').hide();
        }
    });
    $("#company_address").click(function() {
        if ($("#company_address").is(":checked")) {
            // $('#bhojon_address').show();
            $('.bhojon_address').show();
        } else {
            // $('#bhojon_address').hide();
            $('.bhojon_address').hide();
        }
    });

    $("#date_time_formate").change(function() {
        var date_time_formate = $('#date_time_formate').val();
        if (date_time_formate == 'd-m-Y') {
            $(".preDate").text($.datepicker.formatDate("dd-mm-yy", new Date()));
        } else if (date_time_formate == 'm-d-Y') {
            $(".preDate").text($.datepicker.formatDate("mm-dd-yy", new Date()));
        } else if (date_time_formate == 'd/m/Y') {
            $(".preDate").text($.datepicker.formatDate("dd/mm/yy", new Date()));
        } else {
            $(".preDate").text($.datepicker.formatDate("mm/dd/yy", new Date()));
        }
        // $("#txtDate").val();
        // console.log($.datepicker.formatDate(date_time_formate, new Date()));
        // console.log($.datepicker.formatDate(date_time_formate, new Date()));
    });





});

function date_lavel() {
    var dlevel = $('#date_level').val();
    $(".dlevel").text(dlevel);
}

function store_lavel() {
    var store_name = $('#store_name').val();
    $(".store_name").text(store_name);
}

function invoiceLevel() {
    var invoice_level = $('#invoice_level').val();
    //  console.log(invoice_level);
    $("#invoice_level_hide").text(invoice_level);
}

function subtotalLevel() {
    var subtotalLevel = $('#subtotal_level').val();
    $(".subtotal_level_prev").text(subtotalLevel);
}

function serviceCharge() {
    var service_charge = $('#service_charge').val();
    $(".service_level_prev").text(service_charge);
}

function vatlevel() {
    var vat_level = $('#vat_level').val();
    // console.log('vat_level');
    $(".vat_prev").text(vat_level);
}

function discountLevel() {
    var discount_level = $('#discount_level').val();
    // console.log('vat_level');
    $(".discount_prev").text(discount_level);
}

function grandTotal() {
    var grand_total = $('#grand_total').val();
    // console.log('vat_level');
    $(".grand_total_prev").text(grand_total);
}

function cutomer_paidAmount() {
    var cutomer_paidAmount = $('#cutomer_paid_amount').val();
    // console.log('vat_level');
    $(".gcutomer_paidAmount_prev").text(cutomer_paidAmount);
}

function totalDue() {
    var total_due = $('#total_due').val();
    // console.log('vat_level');
    $(".total_due_prev").text(total_due);
}

function change_dueLevel() {
    var change_due_level = $('#change_due_level').val();
    // console.log('vat_level');
    $(".change_due_prev").text(change_due_level);
}

function totalPayment() {
    var total_payment = $('#total_payment').val();
    // console.log('vat_level');
    $(".total_payment_prev").text(total_payment);
}

function billingTo() {
    var billingTo = $('#billing_to').val();
    // console.log('vat_level');
    $(".billing_to_prev").text(billingTo);
}

function billBy() {
    var billingTo = $('#bill_by').val();
    // console.log('vat_level');
    $(".bill_by_prev").text(billingTo);
}

function waiterBy() {
    var waiterTo = $('#waiter_by').val();
    // console.log('vat_level');
    $(".waiter_by_prev").text(waiterTo);
}

function orderNo() {
    var order_no = $('#order_no').val();
    // console.log(order_no);
    $(".order_no_prev").text(order_no);
}

function tableLevel() {
    var order_no = $('#table_level').val();
    // console.log(order_no);
    $(".table_level_prev").text(order_no);
}

function paymentStatus() {
    var payment_status = $('#payment_status').val();
    // console.log(order_no);
    $(".payment_status_prev").text(payment_status);
}

function footertext() {
    var footer_text = $('#footer_text').val();
    console.log(order_no);
    $(".footer_text_prev").text(footer_text);
}

function binlevel() {
    var binlevel = $('#bin_level').val();
    // console.log(binlevel);
    $("#bin_level_prev").text(binlevel);
}

function weblevel() {
    var weblevel = $('#web_level').val();
    // console.log(binlevel);
    $(".domain").text(weblevel);
}

function mushklevel() {
    var mushaktext = $('#mushk_level').val();
    $(".msktext").text(mushaktext);
}

function lineHeightP() {
    setTimeout(function() {
        var lineHeight = $('#lineHeight').val();
        if (lineHeight <= 8 || lineHeight > 41) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 2000
            };
            toastr.error('Not Allowed  min 8 px and Max 40px', 'Error')
        } else {
            $(".line-height").css("line-height", lineHeight + "px");
        }
    }, 1000);

}

function fontsizeP() {
    var fontsize = $('#fontsize_px').val();
    $(".fontsizepx").css("font-size", fontsize + "px");
}
</script>
<link rel="stylesheet" type="text/css"
    href="<?php //echo base_url('application/modules/ordermanage/assets/css/pos_token.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php //echo base_url('application/modules/ordermanage/assets/css/pos_print.css'); ?>">

<style>
/* .line-height{
   line-height: 10px !important;
 }    */
@charset "utf-8";
/* CSS Document */

.mb-0 {
    margin-bottom: 0;
}

.my-50 {
    margin-top: 50px;
    margin-bottom: 50px;
}

.my-0 {
    margin-top: 0;
    margin-bottom: 0;
}

.my-5 {
    margin-top: 4px !important;
    margin-bottom: 4px !important;
}

.mt-10 {
    margin-top: 10px;
}

.mb-15 {
    margin-bottom: 15px;
}

.mr-18 {
    margin-right: 18px;
}

.mr-25 {
    margin-right: 25px;
}

.mb-25 {
    margin-bottom: 25px;
}

.h4,
.h5,
.h6,
h4,
h5,
h6 {
    margin-top: 10px;
    margin-bottom: 10px;
}

.login-wrapper {
    background: url(../img/bhojon/login-bg.jpg) no-repeat;
    background-size: 100% 100%;
    height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.login-wrapper:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: block;
    background: rgba(0, 0, 0, 0.5);
}

.login_box {
    text-align: center;
    position: relative;
    width: 400px;
    background: #343434;
    padding: 40px 30px;
    border-radius: 10px;
}

.login_box .form-control {
    height: 60px;
    margin-bottom: 25px;
    padding: 12px 25px;
}

.btn-login {
    color: #fff;
    background-color: #45C203;
    border-color: #45C203;
    width: 100%;
    line-height: 45px;
    font-size: 17px;
}

.btn-login:hover,
.btn-login:focus {
    color: #fff;
    background-color: transparent;
    border-color: #fff;
}

/*Bhojon List*/

.invoice-card {
    display: flex;
    flex-direction: column;
    padding: 25px;
    /* width:300px; */
    width: 350px;
    background-color: #fff;
    border-radius: 5px;

    margin: 35px auto;
}

.dark-mode .invoice-card {
    background-color: var(--dark-secondary);
}


.invoice-head,
.invoice-card .invoice-title {
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.invoice-head {
    flex-direction: column;
    margin-bottom: 10px;
}

.invoice-card .invoice-title {
    margin: 10px 0;
}

.invoice-title span {
    color: rgba(0, 0, 0, 0.4);
}

.invoice-details {
    border-top: 0.5px dashed #747272;
    border-bottom: 0.5px dashed #747272;
}

.invoice-list {
    width: 100%;
    border-collapse: collapse;
    border-bottom: 1px dashed #858080;
}

.invoice-list .row-data {
    border-bottom: 1px dashed #858080;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.invoice-list .row-data:last-child {
    border-bottom: 0;
    margin-bottom: 0;
}

.invoice-list .heading {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.invoice-list thead tr td {
    font-size: 15px;
    font-weight: 600;
    padding: 5px 0;
}

.invoice-list tbody tr td {
    line-height: 25px;
}

.row-data {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    width: 100%;
}

.middle-data {
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-info {
    /* max-width: 200px; */
}

.item-title {
    font-size: 14px;
    margin: 0;
    line-height: 19px;
    font-weight: 500;
}

.item-size {
    line-height: 19px;
}

.item-size,
.item-number {
    margin: 5px 0;
}

.invoice-footer {
    margin-top: 10px;
}

.gap_right {
    border-right: 1px solid #ddd;
    padding-right: 15px;
    margin-right: 15px;
}

.b_top {
    border-top: 1px solid #858080;
    padding-top: 12px;
}


.food_item {
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    align-items: center;

    border: 1px solid #ddd;
    border-top: 5px solid #1DB20B;
    padding: 15px;
    margin-bottom: 25px;
    transition-duration: 0.4s;
}

.bhojon_title {
    margin-top: 6px;
    margin-bottom: 6px;
    font-size: 14px;
}

.food_item .img_wrapper {
    padding: 15px 5px;
    background-color: #ececec;
    border-radius: 6px;
    position: relative;
    transition-duration: 0.4s;
}

.food_item .table_info {
    font-size: 11px;
    background: #1db20b;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 4px 8px;
    color: #fff;
    border-radius: 15px;
    text-align: center;
}

.food_item:focus,
.food_item:hover {
    background-color: #383838;
}

.food_item:focus .bhojon_title,
.food_item:hover .bhojon_title {
    color: #fff;
}

.food_item:hover .img_wrapper,
.food_item:focus .img_wrapper {
    background-color: #383838;
}

.btn-4 {
    border-radius: 0;
    padding: 15px 22px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    min-width: 130px;
}

.btn-4.btn-green {
    background-color: #1DB20B;
}

.btn-4.btn-green:focus,
.btn-4.btn-green:hover {
    background-color: #3aa02d;
    color: #fff;
}

.btn-4.btn-blue {
    background-color: #115fc9;
}

.btn-4.btn-blue:focus,
.btn-4.btn-blue:hover {
    background-color: #305992;
    color: #fff;
}

.btn-4.btn-sky {
    background-color: #1ba392;
}

.btn-4.btn-sky:focus,
.btn-4.btn-sky:hover {
    background-color: #0dceb6;
    color: #fff;
}

.btn-4.btn-paste {
    background-color: #0b6240;
}

.btn-4.btn-paste:hover,
.btn-4.btn-paste:focus {
    background-color: #209c6c;
    color: #fff;
}

.btn-4.btn-red {
    background-color: #eb0202;
}

.btn-4.btn-red:focus,
.btn-4.btn-red:hover {
    background-color: #ff3b3b;
    color: #fff;
}

.text-center {
    text-align: center;
}


/*New template*/
.w-25 {
    width: 25% !important;
}

.w-50 {
    width: 50% !important;
}

.w-75 {
    width: 75% !important;
}

.w-100 {
    width: 100% !important;
}

.w-auto {
    width: auto !important;
}

.h-25 {
    height: 25% !important;
}

.h-50 {
    height: 50% !important;
}

.h-75 {
    height: 75% !important;
}

.h-100 {
    height: 100% !important;
}

.h-auto {
    height: auto !important;
}

.mw-100 {
    max-width: 100% !important;
}

.mh-100 {
    max-height: 100% !important;
}

.m-0 {
    margin: 0 !important;
}

.mt-0,
.my-0 {
    margin-top: 0 !important;
}

.mr-0,
.mx-0 {
    margin-right: 0 !important;
}

.mb-0,
.my-0 {
    margin-bottom: 0 !important;
}

.ml-0,
.mx-0 {
    margin-left: 0 !important;
}

.m-1 {
    margin: 0.25rem !important;
}

.mt-1,
.my-1 {
    margin-top: 0.25rem !important;
}

.mr-1,
.mx-1 {
    margin-right: 0.25rem !important;
}

.mb-1,
.my-1 {
    margin-bottom: 0.25rem !important;
}

.ml-1,
.mx-1 {
    margin-left: 0.25rem !important;
}

.m-2 {
    margin: 0.5rem !important;
}

.mt-2,
.my-2 {
    margin-top: 0.5rem !important;
}

.mr-2,
.mx-2 {
    margin-right: 0.5rem !important;
}

.mb-2,
.my-2 {
    margin-bottom: 0.5rem !important;
}

.ml-2,
.mx-2 {
    margin-left: 0.5rem !important;
}

.m-3 {
    margin: 1rem !important;
}

.mt-3,
.my-3 {
    margin-top: 1rem !important;
}

.mr-3,
.mx-3 {
    margin-right: 1rem !important;
}

.mb-3,
.my-3 {
    margin-bottom: 1rem !important;
}

.ml-3,
.mx-3 {
    margin-left: 1rem !important;
}

.m-4 {
    margin: 1.5rem !important;
}

.mt-4,
.my-4 {
    margin-top: 1.5rem !important;
}

.mr-4,
.mx-4 {
    margin-right: 1.5rem !important;
}

.mb-4,
.my-4 {
    margin-bottom: 1.5rem !important;
}

.ml-4,
.mx-4 {
    margin-left: 1.5rem !important;
}







.p-0 {
    padding: 0 !important;
}

.pt-0,
.py-0 {
    padding-top: 0 !important;
}

.pr-0,
.px-0 {
    padding-right: 0 !important;
}

.pb-0,
.py-0 {
    padding-bottom: 0 !important;
}

.pl-0,
.px-0 {
    padding-left: 0 !important;
}

.p-1 {
    padding: 0.25rem !important;
}

.pt-1,
.py-1 {
    padding-top: 0.25rem !important;
}

.pr-1,
.px-1 {
    padding-right: 0.25rem !important;
}

.pb-1,
.py-1 {
    padding-bottom: 0.25rem !important;
}

.pl-1,
.px-1 {
    padding-left: 0.25rem !important;
}

.p-2 {
    padding: 0.5rem !important;
}

.pt-2,
.py-2 {
    padding-top: 0.5rem !important;
}

.pr-2,
.px-2 {
    padding-right: 0.5rem !important;
}

.pb-2,
.py-2 {
    padding-bottom: 0.5rem !important;
}

.pl-2,
.px-2 {
    padding-left: 0.5rem !important;
}

.p-3 {
    padding: 1rem !important;
}

.pt-3,
.py-3 {
    padding-top: 1rem !important;
}

.pr-3,
.px-3 {
    padding-right: 1rem !important;
}

.pb-3,
.py-3 {
    padding-bottom: 1rem !important;
}

.pl-3,
.px-3 {
    padding-left: 1rem !important;
}

.p-4 {
    padding: 1.5rem !important;
}

.pt-4,
.py-4 {
    padding-top: 1.5rem !important;
}

.pr-4,
.px-4 {
    padding-right: 1.5rem !important;
}

.pb-4,
.py-4 {
    padding-bottom: 1.5rem !important;
}

.pl-4,
.px-4 {
    padding-left: 1.5rem !important;
}

.p-5 {
    padding: 3rem !important;
}

.pt-5,
.py-5 {
    padding-top: 3rem !important;
}

.pr-5,
.px-5 {
    padding-right: 3rem !important;
}

.pb-5,
.py-5 {
    padding-bottom: 3rem !important;
}

.pl-5,
.px-5 {
    padding-left: 3rem !important;
}

.m-auto {
    margin: auto !important;
}

.mt-auto,
.my-auto {
    margin-top: auto !important;
}

.mr-auto,
.mx-auto {
    margin-right: auto !important;
}

.mb-auto,
.my-auto {
    margin-bottom: auto !important;
}

.ml-auto,
.mx-auto {
    margin-left: auto !important;
}

.text-bold {
    font-weight: 700;
}

.text-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}

.text-justify {
    text-align: justify !important;
}

.text-nowrap {
    white-space: nowrap !important;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.text-left {
    text-align: left !important;
}

.text-right {
    text-align: right !important;
}

.text-center {
    text-align: center !important;
}

.text-italic {
    font-style: italic;
}

.border-bottom--dashed {
    border-bottom: 1px #666 dashed;
}

.justify-content-start {
    -ms-flex-pack: start !important;
    justify-content: flex-start !important;
}

.justify-content-end {
    -ms-flex-pack: end !important;
    justify-content: flex-end !important;
}

.justify-content-center {
    -ms-flex-pack: center !important;
    justify-content: center !important;
}

.justify-content-between {
    -ms-flex-pack: justify !important;
    justify-content: space-between !important;
}

.justify-content-around {
    -ms-flex-pack: distribute !important;
    justify-content: space-around !important;
}

.align-items-start {
    -ms-flex-align: start !important;
    align-items: flex-start !important;
}

.align-items-end {
    -ms-flex-align: end !important;
    align-items: flex-end !important;
}

.align-items-center {
    -ms-flex-align: center !important;
    align-items: center !important;
}

.align-items-baseline {
    -ms-flex-align: baseline !important;
    align-items: baseline !important;
}

.align-items-stretch {
    -ms-flex-align: stretch !important;
    align-items: stretch !important;
}

.align-content-start {
    -ms-flex-line-pack: start !important;
    align-content: flex-start !important;
}

.align-content-end {
    -ms-flex-line-pack: end !important;
    align-content: flex-end !important;
}

.align-content-center {
    -ms-flex-line-pack: center !important;
    align-content: center !important;
}

.align-content-between {
    -ms-flex-line-pack: justify !important;
    align-content: space-between !important;
}

.align-content-around {
    -ms-flex-line-pack: distribute !important;
    align-content: space-around !important;
}

.align-content-stretch {
    -ms-flex-line-pack: stretch !important;
    align-content: stretch !important;
}

.align-self-auto {
    -ms-flex-item-align: auto !important;
    align-self: auto !important;
}

.align-self-start {
    -ms-flex-item-align: start !important;
    align-self: flex-start !important;
}

.align-self-end {
    -ms-flex-item-align: end !important;
    align-self: flex-end !important;
}

.align-self-center {
    -ms-flex-item-align: center !important;
    align-self: center !important;
}

.align-self-baseline {
    -ms-flex-item-align: baseline !important;
    align-self: baseline !important;
}

.align-self-stretch {
    -ms-flex-item-align: stretch !important;
    align-self: stretch !important;
}

.d-flex {
    display: flex;
}

.text-white {
    color: #fff !important;
}
</style>