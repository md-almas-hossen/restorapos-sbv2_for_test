<?php 
$HmvcMenu["qrapp"] = array(
    //set icon
    "icon"           => "<i class='fa fa-qrcode' aria-hidden='true'></i>
", 

   "qr_order" => array( 
        "controller" => "qrmodule",
        "method"     => "index",
        "permission" => "read"
    ),
	"tableqr_code" => array( 
        "controller" => "qrmodule",
        "method"     => "tableqrcode",
        "permission" => "create"
    ),
	"qr_payment" => array( 
        "controller" => "qrmodule",
        "method"     => "qrpaymentsetting",
        "permission" => "create"
    ),
	"qr_themesetting" => array( 
        "controller" => "qrmodule",
        "method"     => "qrthemesetting",
        "permission" => "create"
    )
);
   


/*if (file_exists(APPPATH.'modules/qrapp/assets/data/env'))
if ($this->permission->module('qrapp')->access()) {
$this->permission->module('qrapp')->access();*/
?>
<!--<li class="treeview active">
                    
                    <a href="javascript:void(0)">
                        <i class="fa fa-qrcode"></i> <span><?php echo display('qrapp');?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a> 

                    <ul class="treeview-menu menu-open">
                     <?php if($this->permission->check_label('qr_order')->access()){?>
                     <li class="treeview"><a href="<?php echo base_url('qrapp/qrmodule/index') ?>"><i class="fa fa-adn"></i><span><?php echo display('qr_order');?></span> </a></li>
 					 <?php }
					 if($this->permission->check_label('tableqr_code')->access()){
					 ?>
                     <li class="treeview"><a href="<?php echo base_url('qrapp/qrmodule/tableqrcode') ?>"><i class="fa fa-adn"></i> <span><?php echo display('tableqr_code');?></span></a></li>
                    <?php } if($this->permission->check_label('qr_payment')->access()){?>
                      <li class="treeview"><a href="<?php echo base_url('qrapp/qrmodule/qrpaymentsetting') ?>"><i class="fa fa-adn"></i> <span><?php echo display('qr_payment');?></span></a></li>
                    <?php } if($this->permission->check_label('qr_themesetting')->access()){ ?>
                    <li class="treeview"><a href="<?php echo base_url('qrapp/qrmodule/qrthemesetting') ?>"><i class="fa fa-adn"></i> <span><?php echo display('qr_themesetting');?></span></a></li>
                    <?php } ?>
                    </ul>
                </li>-->
<?php //}?>
   

 