<?php 
if (file_exists(APPPATH.'modules/device/assets/data/env')){
if ($this->permission->module('device')->access()) {
    if ($this->permission->module('device')->access()) { ?>
    
    	<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-signal"></i> <span><?php echo display('device');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                        <?php if($this->permission->check_label('set_your_device')->access()){?>
                   		<li class="treeview active">
                            <li class="treeview"><a href="<?php echo base_url('device/device/create_device_ip') ?>"><i class="fa fa-signal"></i><span><?php echo display('set_your_device');?></span> </a>
                            
                        </li>
                        <?php } ?>
                    </ul>
                </li>

        

<?php 
}

}
}
?>


