<?php
// module name
if (file_exists(APPPATH.'modules/catering_service/assets/data/env'))
if ($this->permission->module('catering_service')->access()) {
$this->permission->module('catering_service')->access();

?>
<li class="treeview active">         
    <a href="javascript:void(0)">
    <i class="fa fa-eercast" aria-hidden="true"></i> <span><?php echo 'Catering Service';?> <span class="btn btn-sm btn-primary">NEW</span>  </span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a> 
    <ul class="treeview-menu menu-open">
    	<?php if($this->permission->check_label('catering_dashboard')->access()){?>
        <li class="treeview"><a href="<?php echo base_url('catering_service/cateringservice/catering_dashboard') ?>"><span><?php echo display('catering_dashboard');?></span> </a></li>
        <?php } if($this->permission->check_label('catering_order_list')->access()){?>
        <li class="treeview"><a href="<?php echo base_url('catering_service/cateringservice/catering_orderlist') ?>"><span><?php echo display('catering_order_list');?></span> </a></li>
        <?php }if($this->permission->check_label('add_catering_package')->access()){?>
        <li class="treeview"><a href="<?php echo base_url('catering_service/cateringservice/add_catering_package') ?>"><span><?php echo display('add_catering_package');?></span> </a></li>
        <?php }if($this->permission->check_label('catering_package_list')->access()){?>
        <li class="treeview"><a href="<?php echo base_url('catering_service/cateringservice/catering_package_list') ?>"><span><?php echo display('catering_package_list');?></span> </a></li>
         <?php }if($this->permission->check_label('catering_report')->access()){?>
        <li class="">
            <a href="#"><?php echo display('catering_report') ?>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu"> 
                    <li class=""><a href="<?php echo base_url('catering_service/reports/sellrpt') ?>"><?php echo display('sell_report');?></a></li>
            </ul>
        </li> 
        <?php } ?>

    </ul>
</li>
 <?php }?>
   

 