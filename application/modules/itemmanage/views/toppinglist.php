<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add_topping');?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?php  
                                echo form_open_multipart("itemmanage/menu_topping/create",'method="post" id="myfrmreset"') ?>
                                <?php  echo form_hidden('id',$this->session->userdata('id')); ?>
                                <?php echo form_hidden('tpid', (!empty($addonsinfo->add_on_id)?$addonsinfo->add_on_id:null)) ?>
                                <div class="form-group row">
                                    <label for="firstname"
                                        class="col-sm-4 col-form-label"><?php echo display('toppingname');?> *</label>
                                    <div class="col-sm-8">
                                        <input name="toppingname" class="form-control" type="text"
                                            placeholder="<?php echo display('toppingname');?>" id="toppingname"
                                            value="<?php echo (!empty($addonsinfo->add_on_name)?$addonsinfo->add_on_name:null) ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit_name');?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <select name="unit" class="form-control">
                                            <option value="" selected="selected">Select Option</option>
                                            <?php foreach($unitlist as $unit){?>
                                            <option value="<?php echo $unit->id?>"
                                                <?php if(!empty($addonsinfo)){if($addonsinfo->unit==$unit->id){echo "Selected";}} else{echo "Selected";} ?>>
                                                <?php echo $unit->uom_name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastname"
                                        class="col-sm-4 col-form-label"><?php echo display('status');?></label>
                                    <div class="col-sm-8">
                                        <select name="status" class="form-control">
                                            <option value="" selected="selected">Select Option</option>
                                            <option value="1"
                                                <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==1){echo "Selected";}} else{echo "Selected";} ?>>
                                                Active</option>
                                            <option value="0"
                                                <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==0){echo "Selected";}} ?>>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tpprice" class="col-sm-4 col-form-label"><?php echo display('price');?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="price" class="form-control" type="text"
                                            placeholder="<?php echo display('price');?>" id="toppingname"
                                            value="<?php echo (!empty($addonsinfo->price)?$addonsinfo->price:null) ?>"
                                            required>
                                    </div>
                                </div>
                                <?php if(!empty($addonsinfo->add_on_id)){?>
                                <div class="form-group text-right">
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('update')?></button>
                                </div>
                                <?php } else{?>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-primary w-md m-b-5"
                                        id="frmresetBtn"><?php echo display('reset')?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('save')?></button>
                                </div>
                                <?php } ?>
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
                <strong><?php echo display('update_topping');?></strong>
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

                    <?php if (!$this->session->userdata('is_sub_branch')) {?>

                        <?php if($this->permission->method('itemmanage','create')->access()): ?>
                        <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"
                            data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-circle"
                                aria-hidden="true"></i>
                            <?php echo display('add_topping')?></button>
                        <?php endif; ?>

                    <?php }?>

                    </div>

                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('toppingname') ?></th>
                            <th style="align-right"><?php echo display('price') ?></th>
                            <th><?php echo display('status') ?></th>

                            <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <th><?php echo display('action') ?></th>

                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($toppinglist)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($toppinglist as $addons) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $addons->add_on_name; ?></td>
                            <td style="align-right"><?php echo $addons->price; ?></td>
                            <td><?php if($addons->is_active==1){echo "Active";}else{echo "Inactive";} ?></td>

                            <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <td class="center">
                                    <?php if($this->permission->method('itemmanage','update')->access()): ?>
                                    <a onclick="topingeditinfo('<?php echo $addons->add_on_id; ?>')"
                                        class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                        title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php endif; 
                                            if($this->permission->method('itemmanage','delete')->access()): ?>
                                    <a href="<?php echo base_url("itemmanage/menu_topping/delete/$addons->add_on_id") ?>"
                                        onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                        class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                        title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                </td>

                            <?php }?>

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