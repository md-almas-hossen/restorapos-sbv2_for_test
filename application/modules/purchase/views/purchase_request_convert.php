<style>
    input[type="number"] {
    -moz-appearance: textfield; /* For Firefox */
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none; /* For Chrome, Safari, Edge, and Opera */
    margin: 0;
    }
</style>


<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                   
                    <fieldset class="border p-2">
                       <legend  class="w-auto"><?php echo display('purchase_add') ?></legend>
                    </fieldset>
                    <?php echo form_open_multipart('purchase/purchase/converted_purchase_entry',array('class' => 'form-vertical', 'id' => 'insert_purchase','name' => 'insert_purchase'));





					 echo form_hidden('oldsupplier', (!empty($purchaseinfo->suplierID)?$purchaseinfo->suplierID:null));
					 $originalDate = $purchaseinfo->purchasedate;
					 $purchasedate = date("d-m-Y", strtotime($originalDate));
					 $purchaseinfo->total_price-($purchaseinfo->vat+$purchaseinfo->transpostcost+$purchaseinfo->labourcost+$purchaseinfo->othercost);
					 
					 $originalexDate = $purchaseinfo->purchaseexpiredate;
					 $expiredatedate = date("d-m-Y", strtotime($originalexDate));
					 ?>
                     <input name="oldinvoice" type="hidden" id="oldinvoice" value="<?php echo (!empty($purchaseinfo->invoiceid)?$purchaseinfo->invoiceid:null) ?>" />
                     <input name="purID" type="hidden" id="purID" value="<?php echo (!empty($purchaseinfo->purID)?$purchaseinfo->purID:null) ?>" />
                    <input name="url" type="hidden" id="url" value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                    <div class="row">
                             <div class="col-sm-7">
                               <div class="form-group row">
                                    <label for="supplier_sss" class="col-sm-3 col-form-label"><?php echo display('supplier_name') ?> <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-5">
                                        <?php 
						if(empty($supplier)){$supplier = array('' => '--Select--');}
						echo form_dropdown('suplierid',$supplier,(!empty($purchaseinfo->suplierID)?$purchaseinfo->suplierID:null),'class="form-control"  id="suplierid"') ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="<?php echo base_url("setting/supplierlist/index") ?>"><?php echo display('supplier_add') ?></a>
                                    </div>
                                </div> 
                            </div>
                             <div class="col-sm-5">
                                <div class="form-group row">
                                    <label for="invoice_no" class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?> <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" tabindex="3" class="form-control" name="invoice_no" value="<?php echo $purchaseinfo->invoiceid;?>" placeholder="<?php echo display('invoice_no') ?>" onchange="verifyinvoice()" id="invoice_no" required="">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-7">
                            <div class="form-group row">
                                    <label for="date" class="col-sm-3 col-form-label"><?php echo display('purdate') ?><i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-3">
                                 <input type="text" class="form-control datepicker" name="purchase_date" value="<?php echo $purchasedate;?>" id="date" required="" tabindex="2">
                                    </div>
                                    <input type="hidden" class="form-control datepicker" name="expire_date" data-date-format="mm/dd/yyyy" value="<?php echo $expiredatedate;?>" id="expire_date" required="" tabindex="2" readonly="readonly">
                                     <!--<label for="date" class="col-sm-3 col-form-label"><?php echo display('expdate') ?></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control datepicker" name="expire_date" data-date-format="mm/dd/yyyy" value="<?php echo $expiredatedate;?>" id="expire_date" required="" tabindex="2" readonly="readonly">
                                    </div>-->
                                </div>
                                
                            </div>

                            <div class="col-sm-5">
                               <div class="form-group row">
                                    <label for="adress" class="col-sm-4 col-form-label"><?php echo display('details') ?></label>
                                    <div class="col-sm-8">
                                           <textarea class="form-control" tabindex="4" id="adress" name="purchase_details" placeholder=" <?php echo display('details') ?>" rows="1"><?php echo $purchaseinfo->details;?></textarea>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    <div class="row">
                  	    <div class="col-sm-4">
                               <div class="form-group row">
                                    <label for="adress" class="col-sm-3 col-form-label"><?php echo display('ptype') ?></label>
                                    <div class="col-sm-5">
                                    	<select name="paytype" id="payment_type" class="form-control" required="" onchange="bank_paymet(this.value)">
                                            <option value="1" <?php if($purchaseinfo->paymenttype==1){ echo "Selected";}?>><?php echo display('casp') ?></option>
                                            <option value="2" <?php if($purchaseinfo->paymenttype==2){ echo "Selected";}?>><?php echo display('bnkp') ?></option>
                                            <option value="3" <?php if($purchaseinfo->paymenttype==3){ echo "Selected";}?>>Due Payment</option> 
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-4">
                               <div class="form-group row" id="showbank" style="display:<?php if($purchaseinfo->paymenttype!=2){ echo "none";}?>;">
                                    <label for="adress" class="col-sm-4 col-form-label"><?php echo display('sl_bank') ?></label>
                                    <div class="col-sm-8">
                                    	<select name="bank" id="bankid" class="form-control purchasedit_w_100">
                                            <option value=""><?php echo display('sl_bank') ?></option>
                                            <?php if(!empty($banklist)){
												foreach($banklist as $bank){?>
												 <option value="<?php echo $bank->bankid?>" <?php if($purchaseinfo->bankid==$bank->bankid){ echo "Selected";}?>><?php echo $bank->bank_name?></option>
											<?php } }?>	
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group row">
                                    <label for="adress" class="col-sm-4 col-form-label">Expected Date</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" name="expected_date" id="expected_date" value="<?php echo $purchaseinfo->expected_date;?>" >
                                    </div>
                                </div>
                            </div>
                    </div>
                     <table class="table table-bordered table-hover" id="purchaseTable">
                                <thead>
                                     <tr>
                                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i class="text-danger">*</i></th>
                                            <th class="text-center"><?php echo display('item_information') ?><i class="text-danger">*</i></th> 
                                            <th class="text-center"><?php echo display('expdate') ?></th>
                                            <th class="text-center"><?php echo display('stock') ?>/<?php echo display('qty') ?></th>
                                            <th class="text-center" width="9%"><?php echo display('qtn_storage') ?> <i class="text-danger">*</i></th>
                                            <th class="text-center" width="7%"><?php echo display('qty') ?> <i class="text-danger">*</i></th>
                                            <th class="text-center"><?php echo display('rate_') ?> <span style="font-size: small; color:#e5343d">(<?php echo display('unit') ?>)</span><i class="text-danger">*</i></th>
                                            <th class="text-center"><?php echo display('rate_') ?> <span style="font-size: small; color:#e5343d">(<?php echo display('total') ?>)</span></th>
                                            <th class="text-center"><?php echo display('vat') ?></th>
                                            <th class="text-center" width="2%"><?php echo display('vat_type') ?></th>
                                            <th class="text-center"><?php echo display('total') ?></th>
                                            <th class="text-center"></th>
                                        </tr>
                                </thead>
                                <tbody id="addPurchaseItem">
                                <?php $i=0;
								$subtotal=0;
                                $this->load->model('purchase/purchase_model', 'purchasemodel');
								foreach($iteminfo as $item){
									$i++;
									$itemtotal=$item->quantity*$item->price;
									$subtotal=$itemtotal+$subtotal;
                                    
                                    $stockinfo =  $this->purchasemodel->get_total_product($item->indredientid);
                                    // dd($stockinfo);
									?>
                                    <tr>
                                    	<td><select name="product_type[]" id="product_type_<?php echo $i;?>" class="form-control" onchange="product_types(<?php echo $i;?>)" required>
                    					<option value=""><?php echo display('select');?> <?php echo "Type";?></option>
                                        <option value="1" <?php if($item->typeid==1){ echo "Selected";}?>>Raw Ingredients</option>
                                        <option value="2" <?php if($item->typeid==2){ echo "Selected";}?>>Finish Goods</option>
                                        <option value="3" <?php if($item->typeid==3){ echo "Selected";}?>>Add-ons</option>
                                        </select>
                                        </td> 
                                        <td class="span3 supplier">
                                        <select name="product_id[]" id="product_id_<?php echo $i;?>" class="postform resizeselect form-control" onchange="product_list(<?php echo $i;?>)">
                    					<option value=""><?php echo display('select');?> <?php echo display('ingredients');?></option>
										<?php foreach ($ingrdientslist as $ingrdients) {?>
                    							<option value="<?php echo $ingrdients->id;?>" <?php if($ingrdients->id==$item->indredientid){ echo "selected";}?>><?php echo $ingrdients->ingredient_name.'('.$ingrdients->uom_short_code.')';?></option>
                    					<?php }?>
                                        </select>
                                        </td>
										<td><input type="text" name="expriredate[]" id="expriredate_<?php echo $i;?>" class="form-control expriredate_<?php echo $i;?> datepicker5" value="<?php echo date('Y-m-d');?>" tabindex="7" required="" readonly=""></td>
                                       <td class="wt">
                                                <input type="text" id="available_quantity_<?php echo $i;?>" class="form-control text-right stock_ctn_<?php echo $i;?>" placeholder="0.00" value="<?php echo $stockinfo['total_purchase'];?>" readonly="">
                                            </td>
                                        <td class="text-right">
                                            <input type="number" step="0.00001" name="storage_quantity[]" id="box_<?php echo $i;?>" onkeyup="calculate_singleqty(<?php echo $i;?>);" onchange="calculate_singleqty(<?php echo $i;?>);" class="form-control text-right storage_cal_1" placeholder="0.00" value="<?php echo $item->quantity/$item->conversationrate;?>" min="0.01" tabindex="7" required="" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                            <input type="hidden" name="conversion_value[]" id="conversion_value_<?php echo $i;?>" value="<?php echo $item->conversationrate;?>">
                                        </td>
                                        
                                            <td class="text-right">
                                                <input type="number" step="0.00001" name="product_quantity[]" id="cartoon_<?php echo $i;?>" class="form-control text-right store_cal_1" onkeyup="calculate_store(<?php echo $i;?>);calculate_storageqty(<?php echo $i;?>);" onchange="calculate_store(<?php echo $i;?>);calculate_storageqty(<?php echo $i;?>);" placeholder="0.00" value="<?php echo $item->quantity;?>" min="0" required="" tabindex="6" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                            </td>




                                            <td class="test">
                                                <input type="number" step="0.00001" name="product_rate[]" onkeyup="calculate_store(<?php echo $i;?>);" onchange="calculate_store(<?php echo $i;?>);" id="product_rate_<?php echo $i;?>" class="form-control product_rate_<?php echo $i;?> text-right" placeholder="0.00" value="<?php echo $item->price;?>" min="0" tabindex="7" required="" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                            </td>
                                            <td class="text-right">
                                                <input name="purchase_price[]" type="number" step="0.00001" onkeyup="calculate_store(<?php echo $i;?>);" onchange="calculate_store(<?php echo $i;?>);" id="purchase_price_<?php echo $i;?>" class="form-control purchase_price_<?php echo $i;?> text-right" placeholder="0.00" value="<?php echo $item->price*$item->quantity;?>" min="0.00" tabindex="7" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                            </td>



                                            <td>
                                                <input type="number" step="0.00001" name="product_vat[]" onkeyup="calculate_store(<?php echo $i;?>);" onchange="calculate_store(<?php echo $i;?>);" id="product_vat_<?php echo $i;?>" class="form-control product_vat_<?php echo $i;?> text-right" placeholder="0.00" value="<?php echo $item->itemvat;?>" min="0"  tabindex="8" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                            </td>
											<td>
                                                <select name="vat_type[]" id="vat_type_<?php echo $i;?>" class="form-control vattype" onkeyup="calculate_store(<?php echo $i;?>);" onchange="calculate_store(<?php echo $i;?>);">
                                                    <!--<option value=""><?php //echo display('select');?> <?php //echo "Type";?></option>-->
                                                    <option value="1" <?php if($item->vattype==1){ echo "Selected";}?>>%</option>
                                                    <option value="0" <?php if($item->vattype==0){ echo "Selected";}?>><?php echo $currency->curr_icon;?></option>
                                                </select>
                                            </td>


                                            

                                            <td class="text-right">
                                                <input class="form-control total_price text-right" type="text" name="total_price[]" id="total_price_<?php echo $i;?>" value="<?php echo $item->totalprice;?>" readonly="readonly">
                                            </td>


                                            <td>
                                                <button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8"><?php //echo display('delete') ?>
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </button>
                                            </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <input type="button" id="add_invoice_item" class="btn btn-success" name="add-invoice-item" onclick="addmore('addPurchaseItem');" value="<?php echo display('add_more') ?> <?php echo display('item') ?>" tabindex="9">
                                        </td>
                                        <td  colspan="7" class="text-right"><b><?php echo display('subtotal') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="subtotal" class="text-right form-control" name="subtotal_total_price" value="<?php echo $subtotal+$purchaseinfo->vat;?>" readonly="readonly">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right"><b><?php echo display('vat') ?> <?php echo display('amount') ?>:</b></td>
                                        <td class="text-right"><input type="number" id="vatamount" class="text-right form-control" name="vatamount" value="<?php echo $purchaseinfo->vat;?>" placeholder="0.00" readonly="readonly"></td>
                                        <td class="text-right"><b><?php echo display('labourcost') ?> :</b></td>
                                    	<td class="text-right"><input type="number" id="labourcost" class="text-right form-control" name="labourcost" value="<?php echo $purchaseinfo->labourcost;?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');"></td>
                                        <td class="text-right"><b><?php echo display('transpostcost') ?> :</b></td>
                                    	<td class="text-right"><input type="number" id="transpostcost" class="text-right form-control" name="transpostcost" value="<?php echo $purchaseinfo->transpostcost;?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"  class="text-right"><b><?php echo display('other_cost') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="number" id="othercost" class="text-right form-control" name="othercost" value="<?php echo $purchaseinfo->othercost;?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                        </td>
                                        <td class="text-right"><b><?php echo display('discount') ?> <?php echo display('amount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="number" id="discount" class="text-right form-control" name="discount" value="<?php echo $purchaseinfo->discount;?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');" <?php if($purchaseinfo->paymenttype==3){ echo 'readonly';}?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"  class="text-right"><b><?php echo display('grand') ?> <?php echo display('total') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="text-right form-control" name="grand_total_price" value="<?php echo $purchaseinfo->total_price;?>" step="0.0001" readonly="readonly">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10" class="text-right"><b><?php echo display('paid') ?> <?php echo display('amount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="number" id="paidamount" class="text-right form-control" name="paidamount" value="<?php echo $purchaseinfo->paid_amount;?>" placeholder="0.00" step="0.00001" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');" <?php if($purchaseinfo->paymenttype==3){ echo 'readonly';}?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"  class="text-right"><b><?php echo display('total_due') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="dueTotal" class="text-right form-control" name="due_total_price" value="<?php echo $purchaseinfo->total_price-$purchaseinfo->paid_amount;?>" readonly="readonly">
                                        </td>
                                    </tr>
                                    
                                </tfoot>
                            </table>
                          
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="control-group">                     
                                    <div class="controls">
                                    <div class="tabbable">
                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#external_note" data-toggle="pill">Note</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" style="background-color:#019868;font-color:#fff;" href="#terms_and_condition" data-toggle="pill">Terms &amp; Condition</a>
                                        </li>
                                    </ul>                           
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="external_note">
                                            <textarea class="col-sm-12 form-control" name="note" rows="4" placeholder="Note to customer"><?php echo $purchaseinfo->note;?></textarea>
                                        </div>
                                        <div class="tab-pane" id="terms_and_condition">
                                            <textarea class="col-sm-12 form-control" name="terms_cond" rows="4" placeholder="Terms &amp; Condition"><?php echo $purchaseinfo->terms_cond;?></textarea>
                                        </div>
                                    </div>
                                    </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-10">
                            <div class="col-sm-6">
                                <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase" value="<?php echo display('submit') ?>">
                            </div>
                        </div>
                        </form>
                </div> 
            </div>
        </div>
    </div>
     <div id="cntra" hidden>
    <option value=""><?php echo display('select');?> <?php echo display('ingredients');?></option>

	</div>
    <div id="types" hidden>
        <option value=""><?php echo display('select');?> <?php echo "Type";?></option>
        <option value="1">Raw Ingredients</option>
        <option value="2">Finish Goods</option>
        <option value="3">Add-ons</option>
    </div>
    <div id="vattypes" hidden>
    <!--<option value=""><?php //echo display('select');?> <?php //echo "Type";?></option>-->
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon;?></option>
    </div>
    <script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseedit_script.js?v=2.2'); ?>" type="text/javascript"></script>