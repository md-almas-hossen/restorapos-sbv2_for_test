<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row m-0">
                    <?php if (isset($error)) : ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE) : ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('You_can_export_example_csv_file_Example'); ?>-<br /><a
                            class="btn btn-primary btn-md"
                            href="<?php echo base_url() ?>purchase/purchase/downloadformat"><i class="fa fa-download"
                                aria-hidden="true"></i><?php echo display('Download_CSV_Format'); ?></a></h3>
                    <h4>Invoice,Supplier,Suplier Mobile,Purchasedate,Total Amount,<br /> Paid
                        Amount,ProductType(Raw,Finish,Addons),Item name,<br /> Expire date,Unit,Qty,Itemprice</h4>
                    <h4>01,Jhon,01717435123,2022-02-23,2000,2000,Raw,Onion,2022-04-19,kg.,5,35</h4>
                    <h2><?php echo display('upload_food_csv') ?></h2>
                    <?php echo form_open_multipart('purchase/purchase/bulkpurchaseupload', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_attendance')) ?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                    <?php echo form_close() ?>



                </div>

            </div>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <div class="panel-title">
                    <h4><?php echo display('addadjustment') ?></h4>
                </div>
                <?php if($this->permission->method('purchase','create')->access()): ?>
                <a href="<?php echo base_url("purchase/purchase/adjustmentlist")?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('adjustment_list')?></a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <?php echo form_hidden('purID', (!empty($intinfo->purID) ? $intinfo->purID : null)) ?>

                <?php echo form_open_multipart('purchase/purchase/adjustment_entry', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>



                <div class="row">

                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-3 col-form-label"><?php echo display('date') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control datepicker" name="adjust_date"
                                    data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="date"
                                    required="" tabindex="2" readonly="readonly">
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="invoice_no"
                                class="col-sm-4 col-form-label"><?php echo display('referenceno') ?><i
                                    class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="3" class="form-control" name="referenceno"
                                    placeholder="<?php echo display('referenceno') ?>" id="referenceno"
                                    onchange="verifyrefeference()" required="">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">





                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('adjusted_by') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="adjustby"
                                    value="<?php echo $this->session->userdata('fullname'); ?>" id="date" required=""
                                    tabindex="2" readonly="readonly">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-4 col-form-label"><?php echo display('notes') ?></label>
                            <div class="col-sm-8">
                                <textarea class="form-control" tabindex="4" id="adress" name="notes"
                                    placeholder=" <?php echo display('note') ?>" rows="1"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-4 col-form-label">Adjustment Type</label>
                            <div class="col-sm-8">
                                <select name="adjusted_type" id="adjusted_type" class="form-control" required>
                                    <option value=""></option>
                                    <option value="reduce">-</option>
                                    <option value="added">+</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>




                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="20%"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('current_stock') ?></th>
                            <th class="text-center"><?php echo display('adjusted_stock') ?> <i class="text-danger">*</i>
                            </th>
                            <th class="text-center"><?php echo 'Unit Price' ?> </th>
                            <th class="text-center"><?php echo 'Total Price' ?> </th>

                            <th class="text-center"><?php echo display('final_stock') ?><i class="text-danger">*</i>
                            </th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addjustedItem">
                        <tr>
                            <td><select name="product_type[]" id="product_type_1" class="form-control"
                                    onchange="product_typesad(1)" required>
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="1">Raw Ingredients</option>
                                    <option value="2">Finish Goods</option>
                                    <option value="3">Add-ons</option>
                                </select>
                            </td>

                            <td class="span3 supplier">
                                <select name="product_id[]" id="product_id_1" class="postform resizeselect form-control"
                                    onchange="product_listad(1)" required>
                                    <option value=""><?php echo display('select'); ?>
                                        <?php echo display('ingredients'); ?></option>
                                </select>
                            </td>


                            <td class="wt">
                                <input type="text" id="current_stock_1" name="current_stock[]"
                                    class="form-control text-right stock_ctn_1" placeholder="0.00" readonly="">
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="adjusted_stock[]" id="adjusted_stock_1"
                                    onkeyup="calculate_reset(1);" onchange="calculate_reset(1);"
                                    class="form-control text-right adjusted_stock_1" placeholder="0.00" value=""
                                    min="0.01" tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>


                            <td class="text-right">
                                <input type="number" step="0.00001" name="price[]" id="price_1"
                                    class="form-control text-right price_1" placeholder="0.00" value="" min="0.01"
                                    tabindex="7" readonly>
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="total_price[]" id="total_price_1"
                                    class="form-control text-right total_price_1 tp" placeholder="0.00" value=""
                                    min="0.01" tabindex="7" readonly>
                            </td>


                            <td class="test">
                                <input type="number" step="0.00001" name="final_stock[]" id="final_stock_1"
                                    class="form-control product_rate_1 text-right" placeholder="0.00" value="" readonly
                                    min="0.01" tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete"
                                    onclick="adjustmentdeleteRow(this)"
                                    tabindex="8"><?php echo display('delete') ?></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>


                        <tr>
                            <td style="text-align:right" colspan="5"> <b>Total</b></td>
                            <td style="text-align:right"><b id="final_price"></b>
                                <input type="hidden" name="final_price" id="final_amount">
                            </td>
                            <td colspan="3"></td>

                        </tr>


                        <tr>
                            <td colspan="9">
                                <input type="button" id="add_invoice_item" class="btn btn-success"
                                    name="add-invoice-item" onclick="addmore('addjustedItem');"
                                    value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                    tabindex="9">
                            </td>

                        </tr>
                    </tfoot>
                </table>

                <div class="form-group row mt-10">
                    <div class="col-sm-12">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large pull-right"
                            name="add-purchase" value="<?php echo display('submit') ?>">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="cntra" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo display('ingredients'); ?></option>

</div>
<div id="types" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">Raw Ingredients</option>
    <option value="2">Finish Goods</option>
    <option value="3">Add-ons</option>
</div>
<div id="vattypes" name="vat_type[]" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<script src="<?php echo base_url('application/modules/purchase/assets/js/adjusted_script.js?v=18'); ?>"
    type="text/javascript"></script>