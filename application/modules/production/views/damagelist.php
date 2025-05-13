<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('expire_damageentry');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-bd">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="btn-group pull-right">
                                        <?php if($this->permission->method('itemmanage','create')->access()): ?>
                                        <button type="button" class="btn btn-success btn-md" data-target="#add0"
                                            data-toggle="modal" data-backdrop="static" data-keyboard="false"><i
                                                class="fa fa-plus-circle" aria-hidden="true"></i>
                                            <?php echo display('add_varient')?></button>
                                        <?php endif; ?>
                                    </div>
                                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                                </div>
                            </div>

                            <div class="panel-body">

                                <?php echo  form_open('production/production/damage_entry') ?>
                                <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                                <div class="form-group row">
                                    <label for="ptype" class="col-sm-4 col-form-label"><?php echo display('select');?>
                                        <?php echo "Type";?> *</label>
                                    <div class="col-sm-8">
                                        <select name="ptype" class="form-control" id="ptype" onchange="getitemList();"
                                            required="">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                            <option value="2">Raw Ingredients</option>
                                            <option value="1">Finish Goods</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="itemname" class="col-sm-4 col-form-label"><?php echo display('item_name') 	
?>*</label>
                                    <div class="col-sm-8">
                                        <select name="foodid" class="form-control" id="foodid" required="">
                                            <option value="" selected=""><?php echo display('select') ?></option>

                                        </select>

                                    </div>
                                </div>
                                <div class="form-group row" id="finishitem" style="display:none">
                                    <label for="varientname"
                                        class="col-sm-4 col-form-label"><?php echo display('varient_name') ?> *</label>
                                    <div class="col-sm-8">
                                        <select name="varientname" class="form-control" id="varientname">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                            <?php foreach($catinfoinfo as $category){?>
                                            <option value="<?php echo $category->CategoryID;?>">
                                                <?php echo $category->Name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="expireqty"
                                        class="col-sm-4 col-form-label"><?php echo display('expireqty') ?> </label>
                                    <div class="col-sm-8">
                                        <input name="expireqty" class="form-control" type="text"
                                            placeholder="<?php echo display('expireqty') ?>" id="expireqty" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="damageqty"
                                        class="col-sm-4 col-form-label"><?php echo display('damageqty') ?> </label>
                                    <div class="col-sm-8">
                                        <input name="damageqty" class="form-control" type="text"
                                            placeholder="<?php echo display('damageqty') ?>" id="damageqty" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="expiredamagedate"
                                        class="col-sm-4 col-form-label"><?php echo display('expiredamagedate') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="expiredamagedate" class="form-control datepicker5" type="text"
                                            placeholder="<?php echo display('expiredamagedate') ?>"
                                            id="expiredamagedate" value="">
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
                <strong><?php echo display('edit_damage') ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="btn-group pull-right">
                        <?php if($this->permission->method('production','create')->access()): ?>
                        <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i>
                            <?php echo display('expire_damageentry')?></button>
                        <?php endif; ?>
                    </div>
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('item_name') ?></th>
                            <th><?php echo display('varient_name') ?></th>
                            <th><?php echo display('expireqty') ?></th>
                            <th><?php echo display('damageqty') ?></th>
                            <th><?php echo display('expiredamagedate') ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($damagelist)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($damagelist as $items) {
								//   if($items->dtype==2){
								//   $pname=$this->db->select('ingredient_name')->from('ingredients')->where('id',$items->pid)->get()->row();								   
								//   $items->ProductName=$pname->ingredient_name;
								//   }
								 ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $items->ingredient_name; ?></td>
                            <td><?php echo $items->variantName; ?></td>
                            <td><?php echo $items->expire_qty; ?></td>
                            <td><?php echo $items->damage_qty; ?></td>
                            <td><?php echo $items->expireordamage; ?></td>
                            <td class="center">
                                <input name="url" type="hidden" id="url_<?php echo $items->id ?>"
                                    value="<?php echo base_url("production/production/editdamage/$items->id") ?>" />
                                <?php if($this->permission->method('production','update')->access()): ?>
                                <a onclick="editdamage('<?php echo $items->id; ?>')" class="btn btn-info btn-sm"
                                    data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>

                                <?php endif; 
										 if($this->permission->method('production','delete')->access()): ?>
                                <a href="<?php echo base_url("production/production/deletedemage/$items->id") ?>"
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
                <div class="text-right"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/production/assets/js/damagescript.js?v=1.5'); ?>"
    type="text/javascript"></script>