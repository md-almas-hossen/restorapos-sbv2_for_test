<div class="form-group text-right">
 <?php if($this->permission->method('loyalty','create')->access()): ?>
<!--<button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"  ><i class="fa fa-plus-circle" aria-hidden="true"></i>-->
<?php //echo display('membership_add')?></button> 
<?php endif; ?>

</div>
<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('membership_add');?></strong>
            </div>
            <div class="modal-body">
           
<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">

                    <?php echo form_open('loyalty/loyalty/createupdate') ?>
                    <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                        <div class="form-group row">
                            <label for="membershipname" class="col-sm-4 col-form-label"><?php echo display('membership_name') ?> *</label>
                            <div class="col-sm-8">
                                <input name="membershipname" class="form-control" type="text" placeholder="<?php echo display('membership_name') ?>" id="membershipname" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="discount" class="col-sm-4 col-form-label"><?php echo display('discount') ?><a class="cattooltips" data-toggle="tooltip" data-placement="left" title="Use Number Only"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </label>
                            <div class="col-sm-8">
                                 <input name="discount" class="form-control" type="text" placeholder="<?php echo display('discount') ?>" id="discount" value="">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label for="facilities" class="col-sm-4 col-form-label"><?php echo display('other_facilities') ?></label>
                            <div class="col-sm-8">
                                 <input name="facilities" class="form-control" type="text" placeholder="<?php echo display('other_facilities') ?>" id="facilities" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="start" class="col-sm-4 col-form-label"><?php echo display('startamount');?></label>
                            <div class="col-sm-8">
                                 <input name="startpoint" class="form-control" type="text" placeholder="<?php echo display('startamount');?>" id="startpoint" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="endpoint" class="col-sm-4 col-form-label"><?php echo display('endamount');?></label>
                            <div class="col-sm-8">
                                 <input name="endpoint" class="form-control" type="text" placeholder="<?php echo display('endamount');?>" id="endpoint" value="">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
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
                <strong><?php echo display('membership_edit');?></strong>
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

        <div class="panel panel-default thumbnail"> 

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('membership_name') ?></th>
                            <th><?php echo display('discount') ?></th>
                            <th><?php echo display('startamount') ?></th>
                            <th><?php echo display('endamount') ?></th>
                            <th><?php echo display('action') ?></th> 
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($membershiplist)) {
							 $sl=0;
							  foreach ($membershiplist as $membership) {
								  $sl++;
								   ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $membership->membership_name; ?></td>
                                    <td><?php echo $membership->discount; ?></td>
                                    <td><?php echo $membership->startpoint; ?></td>
                                    <td><?php echo $membership->endpoint; ?></td>
                                   <td class="center">
                                    <?php if($this->permission->method('setting','update')->access()): ?>
                                    <input name="url" type="hidden" id="url_<?php echo $membership->id; ?>" value="<?php echo base_url("loyalty/loyalty/updatemembership") ?>" />
                                        <a onclick="editinfo('<?php echo $membership->id; ?>')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                         <?php endif; ?>
                                        
                                    </td>
                                    
                                </tr>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>

     
