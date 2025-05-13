<script src="<?php echo base_url('application/modules/ordermanage/assets/js/postop.js'); ?>" type="text/javascript"></script>

<div id="payprint_marge" class="modal fade" role="dialog">
  <div class="modal-dialog modal-inner" id="modal-ajaxview" role="document"> </div>
</div>
<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd">
               <div class="panel-heading">
                <div class="panel-title">
                	<div class="btn-group pull-right form-inline"> 
		                <?php $today = date('d-m-Y'); ?>
		                    <div class="form-group">
		                        <label class="" for="from_date"><?php echo display('start_date') ?></label>
		                        <input type="text" name="from_date" class="form-control datepicker5" id="from_date" value="" placeholder="<?php echo display('start_date') ?>" readonly="readonly" >
		                    </div> 

		                    <div class="form-group">
		                        <label class="" for="to_date"><?php echo display('end_date') ?></label>
		                        <input type="text" name="to_date" class="form-control datepicker5" id="to_date" placeholder="<?php echo "To"; ?>" value="" readonly="readonly">
		                    </div> 
                            <div class="form-group">
		                    <button  class="btn btn-success" id="filterordlist"><?php echo display('search') ?></button>
                            <button  class="btn btn-warning" id="filterordlistrst"><?php echo display('reset') ?></button>
                            </div>
		                   </div>
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
                <div class="panel-body">
					<div class="row">
                             <div class="col-sm-12" id="findfood">
                                <table class="table table-fixed table-bordered table-hover bg-white" id="tallitem">
                                <thead>
                                     <tr>
                                            <th class="text-center"><?php echo display('sl')?> </th>
                                            <th class="text-center"><?php echo display('ordate');?></th>
                                            <th class="text-center"><?php echo display('invoice_no');?></th>
                                            <th class="text-center"><?php echo display('item_name');?></th>
                                            <th class="text-center"><?php echo display('varient_name');?></th> 
                                            <th class="text-center"><?php echo display('note');?></th>
                                            <th class="text-center"><?php echo display('qty');?></th>
                                            <th class="text-center"><?php echo display('price');?></th> 
                                            <th class="text-right"><?php echo display('total');?></th>
                                            <th class="text-center"><?php echo "Cancel by";?></th>  
                                        </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                               
                            </table>
                            <div class="text-right"></div>
                            </div>
                        </div>
                </div> 
            </div>
        </div>
    </div>
    <div id="payprint_split" class="modal fade  bd-example-modal-lg" role="dialog">
  <div class="modal-dialog modal-lg" id="modal-ajaxview-split"> </div>
</div>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/cancellist.js'); ?>" type="text/javascript"></script>
