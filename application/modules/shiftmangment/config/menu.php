<?php 
if (file_exists(APPPATH.'modules/shiftmangment/assets/data/env'))
if ($this->permission->module('shiftmangment')->access()) {
$this->permission->module('shiftmangment')->access();
?>
<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-users" aria-hidden="true"></i> <span><?php echo display('shift_mangment');?></span>
                        <span class="pull-right-container">

                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                        <?php if($this->permission->check_label('create/edit_shift')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('shiftmangment/shiftmangmentback/addeditshift') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('create/edit_shift');?></span> </a></li>
                     <?php }
					 if($this->permission->check_label('assign_shift')->access()){
					 ?>
 					   <li class="treeview"><a href="<?php echo base_url('shiftmangment/shiftmangmentback/assign_shift') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('assign_shift');?></span> </a></li>
 					    <?php }?>
                     
                    </ul>
                </li>
                <?php }?>