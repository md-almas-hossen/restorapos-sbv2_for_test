<?php if (file_exists(APPPATH.'modules/whatsapp/assets/data/env'))
if ($this->permission->module('whatsapp')->access()) {
$this->permission->module('whatsapp')->access();
?>
<li class="treeview active">
                    <a href="javascript:void(0)">
                        <i class="fa fa-whatsapp" aria-hidden="true"></i><span><?php echo display('whatsapp_setting');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 
                    <ul class="treeview-menu menu-open">
                     <?php if($this->permission->check_label('whatsapp_setting')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('whatsapp/whatsappback/showsetting') ?>"><i class="fa fa-hand-o-right"></i><span><?php echo display('whatsapp_setting');?></span> </a></li>
                      <?php } ?>
 					 
                     
                    </ul>
                </li>
                <?php }?>