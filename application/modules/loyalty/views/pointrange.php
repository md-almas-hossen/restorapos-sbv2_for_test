<div class="form-group text-right">
  <?php if($this->permission->method('loyalty','create')->access()): 
  if(empty($rowst)){ ?>
  <button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"  ><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo display('add_range');?></button>
  <?php } endif; ?>
</div>
<div id="add0" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <strong><?php echo display('add_range');?></strong> </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <div class="panel">
              <div class="panel-body">
                <?php echo form_open('loyalty/loyalty/addpointsrange') ?>
                <?php echo form_hidden('psid', (!empty($intinfo->psid)?$intinfo->psid:null)) ?>
                
                <div class="form-group row">
                  <label for="startamount" class="col-sm-4 col-form-label"><?php echo display('amount') ?> *</label>
                  <div class="col-sm-8">
                    <input name="startamount" class="form-control" type="text" placeholder="<?php echo display('amount') ?>" id="startamount">
                  </div>
                </div>
                <!--<div class="form-group row">
                  <label for="endamount" class="col-sm-4 col-form-label"><?php //echo display('endamount') ?> *</label>
                  <div class="col-sm-8">
                    <input name="endamount" class="form-control" type="text" placeholder="<?php //echo display('endamount') ?>" id="endamount">
                  </div>
                </div>-->
                <div class="form-group row">
                  <label for="earn_point" class="col-sm-4 col-form-label"><?php echo display('earn_point') ?> *</label>
                  <div class="col-sm-8">
                    <input name="earn_point" class="form-control" type="text" placeholder="<?php echo display('earn_point') ?>" id="earn_point">
                    </div>
                </div>
                <div class="form-group text-right">
                  <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                  <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
                </div>
                <?php echo form_close() ?> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer"> </div>
  </div>
</div>
<div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <strong><?php echo display('editpoint');?></strong> </div>
      <div class="modal-body editinfo"> </div>
    </div>
    <div class="modal-footer"> </div>
  </div>
</div>
<div class="row"> 
  <!--  table area -->
  <div class="col-sm-12">
    <div class="panel panel-default thumbnail">
      <div class="panel-body">
        <table class="table datatable2 table-fixed table-bordered table-hover bg-white" id="purchaseTable">
          <thead>
            <tr>
              <th class="text-center"><?php echo display('sl');?>. </th>
              <th class="text-center"><?php echo display('amount');?> </th>
              <!--<th class="text-center"><?php //echo display('endamount');?></th>-->
              <th class="text-center"><?php echo display('earn_point');?></th>
              <th class="text-center"><?php echo display('action') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($allpoints)){
				$i=0;
				foreach($allpoints as $points){
					$i++;
				?>
            <tr>
              <td class="text-center"><?php echo $i;?></td>
              <td class="text-center"><?php echo $points->amountrangestpoint;?></td>
              <!--<td class="text-center"><?php //echo $points->amountrangeedpoint;?></td>-->
              <td class="text-center"><?php echo $points->earnpoint;?></td>
              <td class="text-center"><?php if($this->permission->method('loyalty','update')->access()): ?>
		<input name="url" type="hidden" id="url_<?php echo $points->psid; ?>" value="<?php echo base_url("loyalty/loyalty/updateintfrm") ?>" />
                                        <a onclick="editinfo('<?php echo $points->psid; ?>')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                         <?php endif; ?></td>
            </tr>
            <?php } } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
