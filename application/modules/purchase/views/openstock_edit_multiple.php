
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <?php echo form_hidden('purID', (!empty($intinfo->purID) ? $intinfo->purID : null)) ?>

                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo display('openingstock') ?>
                        <?php if($this->permission->method('purchase','read')->access()): ?>
                            <a href="<?php echo base_url("purchase/purchase/openingstock")?>" class="btn btn-primary btn-md pull-right m-r-5"><i class="fa fa-list" aria-hidden="true"></i>
                            <?php echo display('openingstock') ?></a>
                        <?php endif; ?>
                    </legend>
                    
                </fieldset>


           
                <?php echo form_open_multipart('purchase/purchase/openstock_entry_multiple', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                    
         <div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
                        <?php date_default_timezone_set("Asia/Dhaka"); $today = date('d-m-Y'); ?>
                        <div class="col-md-6 form-group">
                            <label class="" for="from_date">Financial Year</label>
                            <select name="fiyear_id" class="form-control" id="fiyear_id" onchange="setOpeningStockDate()">
                                <option value="" selected=""><?php echo display('select') ?></option>
                                <?php foreach($financial_years as $fy){?>
                                <option  <?php echo $fy->fiyear_id == $old->fiyear_id?'selected':'' ;?> value="<?php echo $fy->fiyear_id;?>"><?php echo $fy->title;?></option>
                                <?php } ?> 
                            </select>
                        </div>  
                        <div class="col-md-6 form-group">
                            <label class="" for="opening_stock_date">Date</label>
                            <input type="text" name="entrydate" class="form-control" id="opening_stock_date" placeholder="" readonly="readonly" value="<?php echo $old->end_date;?>">
                        </div> 
		            </div>
		        </div>
		    </div>
	    </div>









                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i class="text-danger">*</i></th>
                            <th class="text-center" width="20%"><?php echo display('item_information') ?><i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('qtn_storage') ?> <i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('qtn_ingredient') ?> <i class="text-danger">*</i></th>
                            <th class="text-center">Unit Rate<i class="text-danger">*</i></th>
                            <th class="text-center">Total<i class="text-danger">*</i></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                   
                    <input type="hidden" id="actual_count" value="<?php echo (int)count($opening_stock)+1;?>">
                    
                    <?php foreach($opening_stock as $key=>$stock):?>
                
                        <tr>
                            <td><select name="product_type[]" id="product_type_<?php echo $key+1?>" class="form-control" onchange="product_types_openstock(<?php echo $key+1?>)" required>
                                    <option value=""><?php echo display('select_option'); ?></option>
                                    <option <?php echo $stock->itemtype == 0?'selected':'' ;?> value="1">Raw Ingredients</option>
                                    <option <?php echo $stock->itemtype == 1?'selected':'' ;?> value="2">Finish Goods</option>
                                </select>
                            </td>

                            <td class="span3 supplier">

                            
                                <select name="product_id[]" id="product_id_<?php echo $key+1?>" class="postform resizeselect form-control" onchange="product_item_select(<?php echo $key+1?>)" required>
                                    

                                <option value=""><?php echo display('select'); ?><?php echo display('ingredients'); ?></option>


                                <?php 
                                
                                $items = $this->db->select('*')->from('ingredients')->where('type', $stock->itemtype==0?1:2)->get()->result();
                                echo $this->db->last_query();
                            
                        
                   

                                foreach($items as $item){?>

                                <option  <?php echo $item->id == $stock->itemid?'selected':'';?>  value="<?php echo $item->id;?>"><?php echo $item->ingredient_name;?></option>

                                <?php } ?>

                                </select>
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="storage_quantity[]" id="box_<?php echo $key+1?>" class="form-control text-right storage_cal_<?php echo $key+1?>" placeholder="0.00" value="<?php echo $stock->storageqty;?>" min="0.01" tabindex="7" required=""  onkeyup="canculatevaluestorage(<?php echo $key+1?>)" onchange="canculatevaluestorage(<?php echo $key+1?>)">
                                <input type="hidden" name="conversion_value[]" id="conversion_value_<?php echo $key+1?>" value="<?php echo $item->conversion_unit;?>">
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="product_quantity[]" id="cartoon_<?php echo $key+1?>" class="form-control text-right store_cal_<?php echo $key+1?>" placeholder="0.00" value="<?php echo $stock->openstock;?>" min="0.01" tabindex="7" required="" onkeyup="getQtyStorage(<?php echo $key+1?>)" onchange="getQtyStorage(<?php echo $key+1?>)">
                            </td>

                            <td class="test">
                                <input type="number" step="0.00001" name="product_rate[]" id="product_rate_<?php echo $key+1?>" class="form-control product_rate_<?php echo $key+1?> text-right" placeholder="0.00" value="<?php echo $stock->unitprice;?>" min="0.01" tabindex="7" required="" onkeyup="getTotalPrice(<?php echo $key+1?>)">
                            </td>

                            <td class="test">
                                <input type="number" step="0.00001" name="total_product_rate[]" id="total_product_rate_<?php echo $key+1?>" class="form-control total_product_rate_<?php echo $key+1?> text-right" placeholder="0.00" value="<?php echo $stock->unitprice * $stock->openstock;?>" min="0.01" tabindex="7" required="" readonly>
                            </td>

                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this, <?php echo $stock->itemid; ?>)" tabindex="8"><?php echo display('delete') ?></button>
                            </td>
                        </tr>

                    <?php endforeach;?>



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <input type="button" id="add_invoice_item" class="btn btn-success" name="add-invoice-item" onclick="addmore('addPurchaseItem');" value="<?php echo display('add_more') ?> <?php echo display('item') ?>" tabindex="9">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group row mt-10">
                    <div class="col-sm-6">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-opening-stock" value="<?php echo display('submit') ?>">
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
</div>
<div id="vattypes" name="vat_type[]" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<script src="<?php echo base_url('application/modules/purchase/assets/js/open_stock_edit_multiple_script.js?v=20'); ?>" type="text/javascript"></script>
