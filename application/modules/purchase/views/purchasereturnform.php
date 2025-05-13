
<div class="row" id="supplier_info">
    <div class="col-sm-4">
    <div class="form-group row">
        <label for="date" class="col-sm-6 col-form-label"><?php echo display('return_date') ?> <i class="text-danger"></i></label>
            <div class="col-sm-6">
            <input type="text" tabindex="2" class="form-control datepicker" name="return_date" value="<?php echo date('Y-m-d')?>" placeholder="<?php echo display('return_date') ?>" readonly="readonly" required/>
            </div>
        </div> 
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="adress" class="col-sm-6 col-form-label"><?php echo display('ptype') ?></label>
            <div class="col-sm-6">
                <select name="paytype" class="form-control" required="" onchange="bank_paymet(this.value)">
                    <option value="1"><?php echo display('casp') ?></option>
                    <option value="2"><?php echo display('bnkp') ?></option>
                    <option value="3">Due Payment</option> 
                </select>
            </div>
        </div> 
    </div>
    <div class="col-sm-4">
        <div class="form-group row purchase_d_none" id="showbank">
            <label for="adress" class="col-sm-6 col-form-label"><?php echo display('sl_bank') ?></label>
            <div class="col-sm-6">
                <select name="bank" id="bankid" class="form-control row purchase_w_100">
                    <option value=""><?php echo display('sl_bank') ?></option>
                </select>
            </div>
        </div> 
    </div>
    </div>
    <div class="row">
        <div class="col-sm-4 m-b-10">
                <strong>Full Invoice Return</strong>
                <input type="checkbox" id="full-invoice-return" name="full_invoice_return" value="">
        </div>
    </div>
    <table class="table table-bordered table-hover" id="purchase">
        <thead>
            <tr>
                <th class="text-center"><?php echo display('ingredient_name') ?> <i class="text-danger"></i></th>
                <th class="text-center"><?php echo display('purchase_qty') ?></th>
                <th class="text-center"><?php echo display('qtn_storage') ?> <i class="text-danger">*</i></th>
                <th class="text-center"><?php echo display('return_qty') ?>  <i class="text-danger">*</i></th>
                
                <th class="text-center"><?php echo display('price') ?> <i class="text-danger"></i></th>
                    <th class="text-center"><?php echo display('discount') ?></th>
                    <th class="text-center"><?php echo display('vat') ?></th>
                <th class="text-center"><?php echo display('total') ?></th>
                <!-- <th class="text-center"><?php echo display('select') ?> <i class="text-danger">*</i></th> -->
            </tr>
 
        </thead>
        <tbody id="addinvoiceItem">
            <?php 
      
     
            $sl=1;
            foreach ($returnitem as $key=>$return) {

                // d($return);
                
                $return_qty=$this->db->select("SUM(qty) as qty")->from('purchase_return_details')->where('product_id',$return->indredientid)->where('po_no',$invoice)->get()->row();
               
                $qty=($return->quantity - $return_qty->qty);
            //    d($return->quantity);
            //    d($return_qty->qty);

            // d($return->quantity);
            // d( $return_qty);
            ?>
                
            
            <tr>
                <td class="purchasereturnform_w_200">
                    <input type="text" name="product_name" value="<?php echo $return->ingredient_name; ?>" class="form-control productSelection" required placeholder='<?php echo display('product_name') ?>' id="product_names" tabindex="3" readonly="">
                    <input type="hidden" name="product_id[]" class="product_id_<?php echo $sl;?> autocomplete_hidden_value" value="<?php echo $return->indredientid;?>" id="product_id_<?php echo $sl;?>"/>
                    <input type="hidden" name="conversion_id[]" class="conversion_id_<?php echo $sl;?>" value="<?php echo $return->conversion_unit;?>" id="conversion_id_<?php echo $sl;?>"/>
                </td>
                <td>
                    <input type="text" name="recv_qty[]" class="form-control text-right " id="orderqty_<?php echo $sl;?>" value="<?php echo $qty; ?>" readonly="" />
                </td>
                <td>
                    <input type="number" name="total_qnttstorage[]"  id="storagequantity_<?php echo $sl;?>" class="form-control text-right storage_cal_<?php echo $sl;?>" onkeyup="calculate_singleqty(<?php echo $sl;?>);" onchange="calculate_singleqty(<?php echo $sl;?>);" placeholder="0" value="" step="0.00001"  min="0.00000" tabindex="8"/>
                </td>
                <td>
                    <input type="number" name="total_qntt[]"  id="quantity_<?php echo $sl;?>" class="form-control text-right store_cal_<?php echo $sl;?>" onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>),calculate_storageqty(<?php echo $sl;?>);" onchange="calculate_store(<?php echo $sl;?>),calculate_storageqty(<?php echo $sl;?>);" placeholder="0" value="" step="0.00001"  min="0.00000" tabindex="8"/>
                </td>
                    
                <td>
                    <input type="text"  name="product_rate[]"  onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>);" onchange="calculate_store(<?php echo $sl;?>);" id="product_rate_<?php echo $sl;?>" class="form-control product_rate_<?php echo $sl;?> text-right" placeholder="0" value="<?php echo $return->price;?>" min="0" tabindex="9" required="required"  readonly/>
                </td>
                <td class="test">
                    <input type="text" name="discount[]" onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>);" id="discount_<?php echo $sl;?>" class="form-control discount_<?php echo $sl;?> discount_amount text-right" placeholder="0.00" value="" min="0" tabindex="9"/>
                </td>
                <td class="test">
                    <input type="text" name="vat[]" onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>);" id="vat_<?php echo $sl;?>" class="form-control vat_<?php echo $sl;?> text-right" placeholder="0.00" value="<?php echo $return->itemvat;?>" min="0" tabindex="9" readonly/>
                    <input type="hidden" name="vattype[]" onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>);" id="vattype_<?php echo $sl;?>" class="form-control vattype_<?php echo $sl;?> text-right" placeholder="0.00" value="<?php echo $return->vattype;?>" min="0" tabindex="9"/>
                    <input type="hidden" name="vat_amount[]" onkeyup="calculate_store(<?php echo $sl;?>),checkqty(<?php echo $sl;?>);" id="vat_amount_<?php echo $sl;?>" class="form-control vat_amount"/>
                </td>
                <!-- Discount -->
                <td>
                        <input class="form-control total_price text-right" type="text" name="total_price[]" id="total_price_<?php echo $sl;?>" value="0"  readonly="readonly" />                                            
                </td>
                    <!-- <td>


                </td> -->
            </tr>                              
        <?php $sl++; } 
            ?>
        </tbody>                               
        <tfoot>
            <tr>
                <td colspan="6"></td>
                <td class="text-right"><b><?php echo display('discount') ?>:</b></td>
                <td class="text-right"><input type="text" id="total_discount" name="total_discount" value="0" class="form-control text-right" readonly></td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td class="text-right"><b><?php echo display('total_vat') ?>:</b></td>
                <td class="text-right"><input type="text" id="total_vat" name="total_vat" value="0" class="form-control text-right" readonly></td>
            </tr>
            <tr>
                <td colspan="6">
                     <label  for="reason" class="col-form-label text-center"><?php echo display('reason') ?></label> 
                    <textarea class="form-control" name="reason" id="reason" placeholder="<?php echo display('reason') ?>"></textarea> <br></td>
               <td class="text-right"><b><?php echo display('total_amount') ?>:</b></td>
               <td class="text-right">
                    <input type="text" id="grandTotal" name="grand_total_price" class="form-control text-right"  value="" readonly="readonly" />
                </td>
                    <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url();?>"/>  
            </tr>
        
        </tfoot>
    </table>
    <div class="form-group row">
    <label for="example-text-input" class=" col-form-label"></label>
    <div class="col-sm-12 text-right">
        <input type="submit" id="add_return" class="btn btn-success btn-large" name="pretid" value="<?php echo display('return') ?>" tabindex="9"/>
    </div>
</div>
<?php echo form_close()?>
<script src="<?php echo base_url('application/modules/purchase/assets/js/purchasereturnform_script.js'); ?>" type="text/javascript"></script>