<?php 

// dd($itemlist);

foreach ($itemlist as $item) {
  $isexists = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $item->order_id)->where('itemid', $item->menu_id)->where('varient', $item->variantid)->get()->num_rows();
  $condition = "orderid=" . $item->order_id . " AND menuid=" . $item->menu_id . " AND varient=" . $item->variantid;
  $accepttime = $this->db->select('*')->from('tbl_itemaccepted')->where($condition)->get()->row();
  $readytime = $this->db->select('*')->from('tbl_orderprepare')->where($condition)->get()->row();
?>
  <div class="single_item">
    <div class="align-center justify-between mb-13 position-relative">
      <div>
        <div> <span class="display-block"><?php echo $item->ProductName; ?></span> <span class="font-p-fw"><?php echo $item->variantName; ?></span> </div>
      </div>
      <?php if ($item->food_status == 1) { ?>
        <h6 class="quantity font-colr1"><?php echo display('ready') ?><br />Accept Time:<?php echo date("H:i:s", strtotime($accepttime->accepttime)); ?><br />Ready Time:<?php echo date("H:i:s", strtotime($readytime->preparetime)); ?></h6>
      <?php } ?>
      <?php if ($item->food_status == 0) {
        if ($isexists > 0) {

      ?>
          <h6 class="quantity"><?php echo display('proccessing') ?><br />Accept Time:<?php echo date("H:i:s", strtotime($accepttime->accepttime)); ?></h6>
        <?php } else { ?>
          <h6 class="quantity"><?php echo display('kitnotacpt') ?></h6>
      <?php }
      } ?>
    </div>
    <div><?php echo quantityshow($item->menuqty, $item->is_customqty); ?>X </div>
  </div>


<!-- addons -->
  <div>
    <p><?php echo $item->add_on_name;?></p>
    <p><?php echo $item->addonsqty;?></p>
  </div>



  <?php }


if (!empty($allcancelitem)) {
  foreach ($allcancelitem as $cancelitem) {
  ?>
    <div class="single_item single_item-bg">
      <div class="align-center justify-between mb-13 position-relative">
        <div>
          <div> <span class="display-block"><?php echo $cancelitem->ProductName; ?></span> <span class="font-p-fw"><?php echo $cancelitem->variantName; ?></span> </div>
        </div>
        <h6 class="quantity"><?php echo display('cancel'); ?><br /><?php echo $cancelitem->itencancelreason; ?></h6>
      </div>
      <div><?php echo $item->menuqty; ?>X </div>
    </div>
<?php }
} ?>



<div>
<?php 
  
    
    $top = explode(',', $item->tpid);

    foreach($top as $topping_id){

      $topping_name = $this->db->select('*')->from('add_ons')->where('add_on_id', $topping_id)->get()->row()->add_on_name;
 
  ?>

  


    <!-- toppings -->
    <p><?php echo $topping_name;?></p>
    <!-- toppings -->


<?php } ?>
</div>