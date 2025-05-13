<?php if (file_exists(APPPATH.'modules/taxsetting/assets/data/env'))
if ($this->permission->module('taxsetting')->access()) {
$this->permission->module('taxsetting')->access();?>
<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-gavel" aria-hidden="true"></i> <span><?php echo display('tex_setting');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                     <?php if($this->permission->check_label('tex_setting')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('taxsetting/taxsettingback/showtaxsetting') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('tex_setting');?></span> </a></li>
                     <?php }
					 if($this->permission->check_label('tex_enable')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('taxsetting/taxsettingback/taxsetting') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('tex_enable');?></span> </a></li>
 					 <?php } ?>
                     
                    </ul>
                </li>
<?php }?>