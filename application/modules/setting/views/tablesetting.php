<link href="<?php echo base_url('application/modules/setting/assets/css/tablesetting.css'); ?>" rel="stylesheet" type="text/css"/>
  <div class="classList" id="classList"></div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail tablemainbox"> 

            <div class="panel-body">
               <div class="row" id="gallery">
               <ul class="nav nav-tabs" role="tablist">
               		<?php $t=0;
					foreach($tablefloor as $floor){
						$t++;
						?>
         			 <li class="<?php if($t==1){ echo "active";}?>"> <a href="#floor<?php echo $floor->tbfloorid;?>" role="tab" data-toggle="tab" title="<?php echo $floor->floorName;?>" id="slfloor<?php echo $t;?>" onclick="floorselect(<?php echo $floor->tbfloorid;?>)"><?php echo $floor->floorName;?></a></li>
                     <?php } ?>
               </ul>
               <div class="pos-panel tab-content" id="tabmaincontent">
               		<?php $k=0;
						foreach($tablefloor as $floor){
						$k++;
						?>
                   <div class="tab-pane tabmaincontent <?php if($k==1){ echo "fade active in";}?>" id="floor<?php echo $floor->tbfloorid;?>">
                   
                   </div>
                    <?php } ?>
               </div>
             
                    
               </div>
               
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/setting/assets/js/tablesetting_script.js'); ?>" type="text/javascript"></script>