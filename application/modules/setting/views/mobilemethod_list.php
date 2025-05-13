
 <div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd ">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
 					<?php echo  form_open('setting/paymentmethod/createmethod',array('id'=>'menuurl')) ?>
                        <div class="form-group row">
                            <label for="widgettitle" class="col-sm-2 col-form-label"><?php echo display('mobilemethodName') ?></label>
                            <div class="col-sm-4">
                            	<input name="mpname" id="mpname" class="form-control" type="text" placeholder="Bkash,M-Cash" />
                            </div>
                            <label for="commission" class="col-sm-2 col-form-label"><?php echo display('commission') ?> </label>
                            <div class="col-sm-3">
                            	<input name="commission" id="commission" class="form-control" type="text" placeholder="<?php echo display('commission') ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group text-right" id="upbtn">
                            <button type="submit" class="btn btn-success w-md m-b-5 menu_dashboard_display" id="btnchnage" ><?php echo display('Ad') ?></button>
                        </div>
                    <?php echo form_close() ?>
                    <table class="table table-bordered table-hover" id="RoleTbl">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('mobilemethodName') ?></th>
                                <th><?php echo display('commission') ?></th>
                                <th><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($mplist)){ ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($mplist as $row) {
							?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo $row->mobilePaymentname; ?></td>
                                <td><?php echo $row->comissionrate; ?></td>
                                <td>
                                    <a onclick="editmethod('<?php echo $row->mobilePaymentname; ?>',<?php echo $row->mpid; ?>,<?php echo $row->comissionrate; ?>)"  data-toggle="tooltip" data-placement="left" title="Update" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                    <a href="<?php echo base_url('setting/paymentmethod/delete/'.$row->mpid)?>" class="btn btn-sm btn-danger"> Delete</a>
                                	
                                </td>
                            </tr>
					
							
							<?php  } }  ?>
                            
                        </tbody>
                    </table>


            </div>
        </div>
    </div>
</div>





 