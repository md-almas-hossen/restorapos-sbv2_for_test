<?php 
if (file_exists(APPPATH.'modules/printershare/assets/data/env'))
if ($this->permission->module('printershare')->access()) {
$this->permission->module('printershare')->access();
?>
<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-print"></i> <span><?php echo display('pintersetting');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                     <?php if($this->permission->check_label('kitchen_printers')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('printershare/printer/printersetting') ?>"><i class="fa fa-adn"></i><span><?php echo display('kitchen_printers');?></span> </a></li>
 					 <?php }
					 if($this->permission->check_label('invoiceprinter')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('printershare/printer/invoiceprinter') ?>"><i class="fa fa-adn"></i> <span><?php echo display('invoiceprinter');?></span></a></li>
                    <?php } 
					if($this->permission->check_label('pintersetting')->access()){
					?>
                     <li class="treeview"><a href="<?php echo base_url('printershare/printer/printdialog') ?>"><i class="fa fa-adn"></i> <span><?php echo display('pintersetting');?></span></a></li>
                    <?php } ?>
                    </ul>
                </li>
<?php }?>
   

 