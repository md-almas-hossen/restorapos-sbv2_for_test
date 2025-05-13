<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/kitchen_ajax.css'); ?>">
<?php 
$isAdmin=$this->session->userdata('user_type');
$getpermision=$this->db->select('*')->where('userid',$loguid)->get('tbl_posbillsatelpermission')->row();
if($isAdmin==1){
?>
<div class="col-md-12">
  <div class="row kitchen-tab dark-th kitchenscrol">
    <div class="grid">
    <?php foreach($kitchenorder as $orderinfo){?>
      <div class="grid-col col-vxs-12 col-xs-6 col-md-4 col-lg-3 col-xlg-4">
        <div class="grid-item-content overflow-visible">
          <div class="food_item position-relative <?php if($orderinfo->order_status!=3){ echo "pending";}?>">
            <div class="food_item_top">
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('table') ?>: <?php echo $orderinfo->tablename;?></h4>
                <h4 class="kf_info"><?php echo $orderinfo->first_name.' '.$orderinfo->last_name;?></h4>
              </div>
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('tok') ?>: <?php echo $orderinfo->tokenno;?></h4>
                <h4 class="kf_info"><?php echo display('ord') ?>: #<?php echo getPrefixSetting()->sales. '-'.$orderinfo->order_id;?></h4>
              </div>
            </div>
            <div class="circle-openk" id="circlek<?php echo $orderinfo->order_id;?>" onclick="showhide(<?php echo $orderinfo->order_id;?>)"><i class="fa fa-caret-down arrow-kitchen thisrotate<?php echo $orderinfo->order_id;?> rotate"></i></div>
            <div class="food_select dark hidden-kitem" id="item<?php echo $orderinfo->order_id;?>">
              
            </div>
          </div>
        </div>
      </div>
       <?php }?>
    </div>
  </div>
</div>
<?php } else{
if((!empty($getpermision)) && $getpermision->ordercancel==1){	
?>
<div class="col-md-12">
  <div class="row kitchen-tab dark-th kitchenscrol">
    <div class="grid">
    <?php foreach($kitchenorder as $orderinfo){?>
      <div class="grid-col col-vxs-12 col-xs-6 col-md-4 col-lg-3 col-xlg-4">
        <div class="grid-item-content overflow-visible">
          <div class="food_item position-relative <?php if($orderinfo->order_status!=3){ echo "pending";}?>">
            <div class="food_item_top">
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('table') ?>: <?php echo $orderinfo->tablename;?></h4>
                <h4 class="kf_info"><?php echo $orderinfo->first_name.' '.$orderinfo->last_name;?></h4>
              </div>
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('tok') ?>: <?php echo $orderinfo->tokenno;?></h4>
                <h4 class="kf_info"><?php echo display('ord') ?>: #<?php echo $orderinfo->order_id;?></h4>
              </div>
            </div>
            <div class="circle-openk" id="circlek<?php echo $orderinfo->order_id;?>" onclick="showhide(<?php echo $orderinfo->order_id;?>)"><i class="fa fa-caret-down arrow-kitchen thisrotate<?php echo $orderinfo->order_id;?> rotate"></i></div>
            <div class="food_select dark hidden-kitem" id="item<?php echo $orderinfo->order_id;?>">
              
            </div>
          </div>
        </div>
      </div>
       <?php }?>
    </div>
  </div>
</div>
<?php }else{?>
<div class="col-md-12">
  <div class="row kitchen-tab dark-th kitchenscrol">
    <div class="grid">
    <?php foreach($kitchenorder as $orderinfo){?>
      <div class="grid-col col-vxs-12 col-xs-6 col-md-4 col-lg-3 col-xlg-4">
        <div class="grid-item-content overflow-visible">
          <div class="food_item position-relative <?php if($orderinfo->order_status!=3){ echo "pending";}?>">
            <div class="food_item_top">
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('table') ?>: <?php echo $orderinfo->tablename;?></h4>
                <h4 class="kf_info"><?php echo $orderinfo->first_name.' '.$orderinfo->last_name;?></h4>
              </div>
              <div class="item_inner">
                <h4 class="kf_info"><?php echo display('tok') ?>: <?php echo $orderinfo->tokenno;?></h4>
                <h4 class="kf_info"><?php echo display('ord') ?>: #<?php echo $orderinfo->order_id;?></h4>
              </div>
            </div>
            <div class="circle-openk" id="circlek<?php echo $orderinfo->order_id;?>" onclick="showhide(<?php echo $orderinfo->order_id;?>)"><i class="fa fa-caret-down arrow-kitchen thisrotate<?php echo $orderinfo->order_id;?> rotate"></i></div>
            <div class="food_select dark hidden-kitem" id="item<?php echo $orderinfo->order_id;?>">
              
            </div>
          </div>
        </div>
      </div>
       <?php }?>
    </div>
  </div>
</div>
<?php } } ?>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/orderlist.js'); ?>" type="text/javascript"></script>
