<?php 
if (file_exists(APPPATH.'modules/loyalty/assets/data/env'))
if ($this->permission->module('loyalty')->access()) {
$this->permission->module('loyalty')->access();
?>
<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-trophy"></i> <span><?php echo display('loyalty');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                    <?php if($this->permission->check_label('pointstting')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('loyalty/loyalty/') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('pointstting');?></span> </a></li>
                     <?php }
					 if($this->permission->check_label('membership_list')->access()){
					 ?>
 					 <li class="treeview"><a href="<?php echo base_url('loyalty/loyalty/membershiplist') ?>"><i class="fa fa-adn"></i> <span><?php echo display('membership_list');?></span></a></li>
                     <?php }
					 if($this->permission->check_label('membership_card')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('loyalty/loyalty/customerbarcode') ?>"><i class="fa fa-adn"></i> <span><?php echo display('membership_card');?></span></a></li>
                     <?php }
					 if($this->permission->check_label('customerpointlist')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('loyalty/loyalty/customerpointlist') ?>"><i class="fa fa-gift"></i> <span><?php echo display('customerpointlist');?></span></a></li>
                     <?php }
					 if($this->permission->check_label('review_rating')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('loyalty/loyalty/review_rating') ?>"><i class="fa fa-star"></i> <span><?php echo display('review_rating');?></span></a></li>
                     <?php } ?>
                    </ul>
                </li>
                <?php }?>

   

 