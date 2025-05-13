<div class="form-group text-right">
    <?php if($this->permission->method('setting','create')->access()): ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_ingredient')?></button> &nbsp;
    <button data-target="#bulk0" data-toggle="modal"
        class="btn btn-success pull-right"><?php echo display('bulk_upload');?></button>
    <?php endif; ?>

</div>
<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add_ingredient');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">


                                <?php echo  form_open('setting/ingradient/create') ?>
                                <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                                <input name="types" id="types" type="hidden" value="1">
                                <!-- <div class="form-group row">
                        <label for="lastname" class="col-sm-4 col-form-label"><?php echo "Type"; ?>*</label>
                        <div class="col-sm-8 customesl">
                        <select name="types" id="types" class="form-control"  required>
                    					<option value=""><?php echo display('select');?> <?php echo "Type";?></option>
                                        <option value="1">Raw Ingredients</option>
                                        <option value="2">Finish Goods</option>
                                        <option value="3">Add-ons</option>
                                        </select>
                        </div>
                    </div> -->
                                <div class="form-group row">
                                    <label for="storage"
                                        class="col-sm-4 col-form-label"><?php echo display('storageunit'); ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="storageunit" class="form-control" type="text"
                                            placeholder="<?php echo display('storageunit') ?>" id="storageunit"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastname"
                                        class="col-sm-4 col-form-label"><?php echo display('unit_name') ?>*</label>
                                    <div class="col-sm-8 customesl">
                                        <?php 
						if(empty($categories)){$categories = array('' => '--Select--');}
						echo form_dropdown('unitid',$unitdropdown,(!empty($intinfo->id)?$intinfo->id:null),'class="form-control" required') ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="conversation"
                                        class="col-sm-4 col-form-label"><?php echo display('conversion_unit'); ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="conversationqty" class="form-control" type="text"
                                            placeholder="<?php echo display('conversion_unit') ?>" id="conversationqty"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unit_name"
                                        class="col-sm-4 col-form-label"><?php echo display('ingredient_name') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="ingredientname" class="form-control" type="text"
                                            placeholder="<?php echo display('ingredient_name') ?>" id="unitname"
                                            onchange="getingname()" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="min_stock"
                                        class="col-sm-4 col-form-label"><?php echo display('stock_limit') ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="min_stock" class="form-control" type="text"
                                            placeholder="<?php echo display('stock_limit') ?>" id="unitname" value=""
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sku" class="col-sm-4 col-form-label"><?php echo display('sku'); ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="sku" class="form-control" type="text"
                                            placeholder="<?php echo display('sku') ?>" id="sku"
                                            value="<?php echo $sino;?>" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="barcode"
                                        class="col-sm-4 col-form-label"><?php echo display('barcode'); ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="barcode" class="form-control" type="text"
                                            placeholder="<?php echo display('barcode') ?>" id="barcode" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastname"
                                        class="col-sm-4 col-form-label"><?php echo display('status'); ?>*</label>
                                    <div class="col-sm-8 customesl">
                                        <select name="status" class="form-control" required>
                                            <option value="" selected="selected">Select Option</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('update_ingredient');?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<div id="bulk0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload');?></strong>
            </div>
            <div class="modal-body">
                <div class="row m-0">
                    <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE): ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('You_can_export_example_csv_file_Example');?>-<br /><a
                            class="btn btn-success btn-md"
                            href="<?php echo base_url() ?>setting/ingradient/downloadformat"><i class="fa fa-download"
                                aria-hidden="true"></i><?php echo display('Download_CSV_Format');?></a></h3>
                    <h4>Storage Unit,UnitName,Conversation Rate,Barcode,Unit Short Name,Ingredient,Re-Stock
                        Level,Status(Active/Inactive)</h4>
                    <h4>BOX,Raw,Kilogram,10,2436467,kg.,Onion,10,Active</h4>
                    <h2><?php echo display('upload_food_csv')?></h2>
                    <?php echo form_open_multipart('setting/ingradient/bulkinupload',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_attendance'))?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-success">
                    <?php echo form_close()?>



                </div>

            </div>

        </div>
    </div>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd lobidrag ">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo (!empty($title) ? $title : null) ?>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('ingredient_name') ?></th>
                            <th><?php echo display('sku') ?></th>
                            <th><?php echo display('type') ?></th>
                            <th><?php echo display('storageunit') ?></th>
                            <th><?php echo display('unit_name') ?></th>
                            <th><?php echo display('conversion_unit') ?></th>
                            <!-- <th><?php //echo display('availavail_storate') ?></th>
                            <th><?php //echo display('availavail_ing') ?></th> -->
                            <th><?php echo display('barcode'); ?></th>
                            <th><?php echo display('status'); ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ingredientlist)) { 
						
						?>
                        <?php $sl = 1; ?>
                        <?php foreach ($ingredientlist as $ingredient) {
								
								if($ingredient->type==1 || $ingredient->type==''){
									$ingtype="Raw Ingredients";
								}
								if($ingredient->type==2){
									$ingtype="Finish Goods";
								}
								if($ingredient->type==3){
									$ingtype="Add-ons";
								}

                                if($ingredient->is_active==1){
									$isactive="Active";
								}
                                if($ingredient->is_active==0){
									$isactive="Inactive";
								}
								 ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $ingredient->ingredient_name; ?></td>
                            <td><?php echo $ingredient->ingCode; ?></td>
                            <td><?php echo $ingtype; ?></td>
                            <td><?php echo $ingredient->storageunit; ?></td>
                            <td><?php echo $ingredient->uom_name; ?></td>
                            <td><?php echo $ingredient->conversion_unit; ?></td>
                            <!-- <td><?php //echo $ingredient->conversion_unit; ?></td>
                                    <td><?php //echo $ingredient->conversion_unit; ?></td> -->
                            <td><?php echo $ingredient->barcode; ?></td>
                            <td><?php echo $isactive; ?></td>
                            <td class="center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                                <input name="url" type="hidden" id="url_<?php echo $ingredient->id; ?>"
                                    value="<?php echo base_url("setting/ingradient/updateintfrm") ?>" />
                                <a onclick="editinfo('<?php echo $ingredient->id; ?>')" class="btn btn-info btn-sm"
                                    data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <?php endif; 
										 if($this->permission->method('setting','delete')->access()): ?>
                                <a href="<?php echo base_url("setting/ingradient/delete/$ingredient->id") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>