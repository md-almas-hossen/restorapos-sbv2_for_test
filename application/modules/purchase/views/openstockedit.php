<?php 
$ingredientinfo=$this->db->select("*")->from('ingredients')->where('id',$intinfo->itemid)->order_by('ingCode','desc')->get()->row();
$totalvalue=0;
if(!empty($ingredientinfo)){
    $totalvalue=$ingredientinfo->conversion_unit;
}

?>
<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">

                    <?php echo  form_open('purchase/purchase/openstock_entry') ?>
                    <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                    <input name="conversionvalue" type="hidden" id="conversionvalue2" value="<?php echo $totalvalue;?>">
                    	
                        <div class="form-group row">
                        <label for="itemname" class="col-sm-4 col-form-label"><?php echo display('ingredient_name') 	
?>*</label>
                        <div class="col-sm-8">
                        <select name="foodid" class="form-control" id="foodid" required="">
                            <option value="" selected=""><?php echo display('select') ?></option>
                            <?php foreach($ingredients as $row){?>
                            <option value="<?php echo $row->id; ?>"  <?php if($intinfo->itemid==$row->id){ echo "selected";}?>><?php echo $row->ingredient_name; ?> (<?php echo $row->uom_short_code; ?>)</option>
                            <?php } ?>
                        </select>
                       
                        </div>
                    </div>
                        
                    <div class="form-group row">
                            <label for="openstockqtystorage" class="col-sm-4 col-form-label"><?php echo display('qtn_storage') ?> *</label>
                            <div class="col-sm-8">
                                <input name="openstockqtystorage" class="form-control" type="text" placeholder="<?php echo display('qtn_storage') ?>" id="openstockqtystorage2" value="<?php echo (!empty($intinfo->storageqty)?$intinfo->storageqty:null) ?>" required="" onkeyup="canculatevaluestorageedit()">
                            </div>
                        </div>
                        
						<div class="form-group row">
                            <label for="openstockqty" class="col-sm-4 col-form-label"><?php echo display('openstockqty') ?> *</label>
                            <div class="col-sm-8">
                                <input name="openstockqty" class="form-control" type="text" placeholder="<?php echo display('openstockqty') ?>" id="openstockqty2" value="<?php echo (!empty($intinfo->openstock)?$intinfo->openstock:null) ?>" required="" onkeyup="canculatevalueedit()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="unitprice" class="col-sm-4 col-form-label"><?php echo display('s_rate') ?> *</label>
                            <div class="col-sm-8">
                                <input name="unitprice" class="form-control" type="text" placeholder="<?php echo display('s_rate') ?>" id="unitprice" value="<?php echo (!empty($intinfo->unitprice)?$intinfo->unitprice:null) ?>" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="entrydate" class="col-sm-4 col-form-label"><?php echo display('date') ?> *</label>
                            <div class="col-sm-8">
                                <input name="entrydate" class="form-control datepicker5" type="text" placeholder="<?php echo display('date') ?>" id="entrydate" value="<?php echo (!empty($intinfo->entrydate)?$intinfo->entrydate:null) ?>" required="">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    <?php echo form_close() ?>

                </div>  
            </div>
        </div>
    </div>