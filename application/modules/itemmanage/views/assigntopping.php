<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/css/splitorder.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('application/modules/ordermanage/assets/css/kitchen_ajax.css'); ?>">

<div id="add0" class="modal bd-example-modal-lg fade" role="dialog">
    <div class="modal-dialog modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('assign_topping');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?php echo  form_open('itemmanage/menu_topping/assigntoppingcreate','method="post" id="myfrmreset"') ?>
                                <?php echo form_hidden('tpassignid', (!empty($addonsinfo->tpassignid)?$addonsinfo->tpassignid:null)) ?>

                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label for="menuid"
                                            class="col-sm-2 col-form-label"><?php echo display('item_name') ?>*</label>
                                        <div class="col-sm-10">
                                            <?php 
						if(empty($toppingmenulist)){$toppingmenulist = array('' => '--Select--');}
						echo form_dropdown('menuid',$menudropdown,(!empty($addonsinfo->menuid)?$addonsinfo->menuid:null),'class="form-control" required') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label for="toppingtitle"
                                            class="col-sm-2 col-form-label"><?php echo display('topping_head');?>
                                            *</label>
                                        <div class="col-sm-4">
                                            <input name="toppingtitle[]" class="form-control" type="text"
                                                placeholder="<?php echo display('topping_head');?>" id="toppingtitle"
                                                value="<?php echo (!empty($addonsinfo->tptitle)?$addonsinfo->tptitle:null) ?>"
                                                required>
                                        </div>
                                        <label for="toppingtitle"
                                            class="col-sm-2 col-form-label"><?php echo display('max_sl_topping');?>
                                            *</label>
                                        <div class="col-sm-4">
                                            <input name="maxselection[]" class="form-control" type="number"
                                                placeholder="<?php echo display('max_sl_topping');?>"
                                                id="toppingtitle_1"
                                                value="<?php echo (!empty($addonsinfo->tptitle)?$addonsinfo->tptitle:null) ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="toppingid"
                                            class="col-sm-2 col-form-label"><?php echo display('toppingname') ?>*</label>
                                        <div class="col-sm-6">
                                            <?php 
						if(empty($toppingmenulist)){$toppingmenulist = array('' => '--Select--');}
						echo form_dropdown('toppingid[]',$toppingdropdown,(!empty($addonsinfo->add_on_id)?$addonsinfo->add_on_id:null),'class="form-control" multiple="multiple" required') ?>
                                        </div>


                                        <div class=" kitchen-tab checkAll col-sm-2">
                                            <input id="is_paid" name="is_paid[]" type="checkbox" class="selectall"
                                                value="" autocomplete="off">
                                            <label for="is_paid">
                                                <span class="radio-shape">
                                                    <i class="fa fa-check"></i>
                                                </span>
                                                <?php echo display('is_paid') ?> </label>
                                        </div>
                                        <div class=" kitchen-tab checkAll col-sm-2">
                                            <input id="isoption" name="isoption[]" type="checkbox" class="selectall"
                                                value="" autocomplete="off">
                                            <label for="isoption">
                                                <span class="radio-shape">
                                                    <i class="fa fa-check"></i>
                                                </span>
                                                <?php echo display('is_position') ?> </label>
                                        </div>
                                    </div>

                                </div>
                                <div id="moretopping">


                                </div>
                                <div class="form-group text-right">
                                    <button type="button" onclick="addaccount()"
                                        class="btn btn-success w-md m-b-5"><?php echo display('add_more') ?></button>
                                </div>
                                <?php if(!empty($addonsinfo->tpassignid)){?>
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

<div id="edit" class="modal bd-example-modal-lg fade" role="dialog">
    <div class="modal-dialog modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('update_assign');?></strong>
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
                        <button type="button" class="btn btn-success btn-md" id="addtoppingmodal"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i>
                            <?php echo display('assign_topping')?></button>
                        <?php endif; ?>

                    <?php }?>

                    </div>
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('item_name') ?></th>
                            <th><?php echo display('topping_head') ?></th>
                            <th><?php echo display('toppingname') ?></th>

                            <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <th><?php echo display('action') ?></th>

                            <?php }?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php //print_r($toppingmenulist2);
						if (!empty($toppingmenulist2)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($toppingmenulist2 as $addonsmenu) { 
							$tpmid=$addonsmenu['tpassignid'];
							?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $addonsmenu['ProductName']; ?></td>
                            <td><?php echo $addonsmenu['tptitle']; ?></td>
                            <td><?php echo $addonsmenu['toppingname']; ?></td>

                            <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <td class="center">
                                    <?php if($this->permission->method('itemmanage','update')->access()): ?>

                                    <a onclick="asstopingeditinfo('<?php echo $tpmid; ?>')" class="btn btn-info btn-sm"
                                        data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"
                                            aria-hidden="true"></i></a>
                                    <?php endif; 
                                            if($this->permission->method('itemmanage','delete')->access()): ?>
                                    <a href="<?php echo base_url("itemmanage/menu_topping/assigntoppingdelete/$tpmid") ?>"
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
                <div class="text-right"><?php echo @$links?></div>
            </div>
        </div>
    </div>
</div>
<div id="alltopping" hidden>
    <option value=''>Select One</option>
    <?php foreach ($alltoppingdropdown as $toppinglist) {?><option value='<?php echo $toppinglist->add_on_id;?>'>
        <?php echo $toppinglist->add_on_name;?></option><?php }?>
</div>