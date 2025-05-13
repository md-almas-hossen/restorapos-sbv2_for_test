<?php 
if (file_exists(APPPATH.'modules/wastemangment/assets/data/env'))
if ($this->permission->module('wastemangment')->access()) {
$this->permission->module('wastemangment')->access();
?>
<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                      <i class="fas fa-trash-alt" aria-hidden="true"></i>  <span><?php echo display('waste_tracking ');?></span>
                        <span class="pull-right-container">

                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                        <?php if($this->permission->check_label('packaging_food')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('wastemangment/wastetracking/addpackagingfood'); ?>"><i class="fas fa-archive"></i><span><?php echo display('packaging_food');?></span> </a></li>
                     <?php }
					 if($this->permission->check_label('purchase_food_waste')->access()){
					 ?>
 					   <li class="treeview"><a href="<?php echo base_url('wastemangment/wastetracking/addpurchasfoodwaste'); ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('purchase_food_waste');?></span> </a></li>
 					   <?php }
					 if($this->permission->check_label('makeing_food_waste')->access()){
					 ?>
                       <li class="treeview"><a href="<?php echo base_url('wastemangment/wastetracking/makeingfoodwaste'); ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('makeing_food_waste');?></span> </a></li>
                     <?php }?>
                    </ul>
                </li>
                <?php }?>