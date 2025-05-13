<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/splitorder.css'); ?>">
            <div id="payprint_marge">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <ul class="nav nav-tabs custom-nav__tabs" role="tablist">
                        	<?php 
							if(!empty($tablefloor)){
							$f=0;	
							foreach($tablefloor as $floor){
							$f++;	
							?>
                        	<li class="<?php if($f==1){ echo "active";}?>"> <a href="#floor<?php echo $floor->tbfloorid;?>" id="florlist<?php echo $f;?>" role="tab" data-toggle="tab" class="home" onclick="showfloor(<?php echo $floor->tbfloorid;?>)"><?php echo $floor->floorName;?></a> </li>
                            <?php } } ?>
                        </ul>
                    </div>
                    <?php if($settinginfo->tablemaping==1){?>
                    <div class="modal-body">
                    	
                         <div class="tab-content">
                         	<?php 
							if(!empty($tablefloor)){
							$a=0;	
							foreach($tablefloor as $floor){
							$a++;	
							?>
        					<div class="tab-pane fade <?php if($a==1){echo "active in";}?>" id="floor<?php echo $floor->tbfloorid;?>"></div>
                            <?php } } ?>
                         </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="multi_table()"class="btn btn-success btn-md"><?php echo display('submit')?></button>
                        <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><?php echo display('cancel')?></button>
                    </div>
                    <?php }else{ 
					$where3="(order_status = 1 OR order_status = 2 OR order_status = 3) AND order_date='".date('Y-m-d')."'";
		$unavailable=$this->db->select("count(table_no) as totalunavailable")->from('customer_order')->where($where3)->group_by('table_no')->get()->num_rows();
		$totaltable=$this->db->select("count(tableid) as totaltable")->from('table_setting')->get()->row();
					?>
                    <div class="modal-body__content">
                      <div class="modal-body_inner">
                        <div class="modal-body_table">
                          <div class="modal-body__bar">
                            <span>Unavailable&nbsp;<span class="badge badge-danger"><?php echo $unavailable;?></span></span>&nbsp;&nbsp;&nbsp;
                            <span>Available&nbsp;<span class="badge badge-success"><?php echo $totaltable->totaltable-$unavailable;?></span></span>
                          </div>
                          <!-- Tab panes -->
                          <div class="tab-content custom-tab__content">
                          	<?php 
							if(!empty($tablefloor)){
							$a=0;	
							foreach($tablefloor as $floor){
							$a++;	
							?>
                            <div role="tabpane<?php echo $a;?>" class="tab-pane fade <?php if($a==1){echo "active in";}?>" id="floor<?php echo $floor->tbfloorid;?>" style="position: relative;">
                             
                            </div>
                             <?php } } ?>
                            
                          </div>
                        </div>
                        <div class="modal-body__waiter">
                          <div class="modal-body__waiter--cotent">
                            <div class="waiter-title">
                              Waiter
                            </div>
                            <div class="search-content">
                              <div class="search">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  class="feather feather-search">
                                  <circle cx="11" cy="11" r="8"></circle>
                                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input placeholder="Search term" id="fwaiter" autocomplete="off">
                              </div>
                            </div>
                            <div class="waiter-list">
                            <?php foreach($allwaiter as $waiter){
								$where="(order_status = 1 OR order_status = 2 OR order_status = 3) AND order_date='".date('Y-m-d')."'";
		$waiterorder=$this->db->select("count(order_id) as total")->from('customer_order')->where('waiter_id',$waiter->emp_his_id)->where($where)->group_by('table_no')->get();
		$totalpending=$waiterorder->num_rows();
		//echo $this->db->last_query();
								
								
								?>
                              <div class="waiter-list__item" id="auto-<?php echo $waiter->emp_his_id;?>">
                          <span title="<?php echo $waiter->emp_his_id;?>"><?php echo $waiter->first_name.' '.$waiter->last_name?></span>
                                <span class="badge badge-primary"><?php echo $totalpending->total ;?></span>
                              </div>
                              <?php } ?>
                              
                            </div>
                          </div>
                          <div class="modal-body__bottom">
                            <div>
                              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                            <div>
                              <button type="button" class="btn btn-success" id="setchecktable" onclick="checktable()"><?php echo display('submit')?></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
            <script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>
            <script>
            $(document).ready(function(){
    			$("#florlist1").trigger("click");
			});
			$('.waiter-list__item').on('click', function () {
        $('.waiter-list__item.current').removeClass('current');
		var waiterid=$(this).find("span").attr("title");
		$('#waiter').val(waiterid).trigger('change');
        $(this).addClass('current');
      });
	  
	 
	</script>
