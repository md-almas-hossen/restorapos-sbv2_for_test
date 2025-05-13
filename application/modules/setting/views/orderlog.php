<div id="showlogdetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Order details Log</strong>
            </div>
            <div class="modal-body" id="detailslog">
            	
    		</div>
     
            </div>
        </div>

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
                                <table class="table table-fixed table-bordered table-hover bg-white" id="allorderlog">
                                <thead>
                                     <tr>
                                            <th class="text-center"><?php echo display('sl')?> </th>
                                            <th class="text-center"><?php echo display('orderid');?></th>
                                            <th class="text-center"><?php echo display('title');?></th>
                                            <th class="text-center"><?php echo display('date');?></th> 
                                            <th class="text-center"><?php echo display('action');?></th>  
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
<script src="<?php echo base_url('application/modules/setting/assets/js/orderlog.js'); ?>" type="text/javascript"></script>
