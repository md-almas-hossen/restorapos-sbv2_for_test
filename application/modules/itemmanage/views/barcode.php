<script src="http://localhost/bhojonsaas_2newacc/assets/js/jquery-1.12.4.min.js" type="text/javascript"></script>
<script>
function printDiv(divName) {
	$("#new").hide();
    window.print();
}

</script>


<style type="text/css" media="print">
 html, body {padding:0; margin:0;}
@media print {
  @page {
    size: 30mm 21mm;
    margin: 0;
    padding: 0;
  }
  html, body { 
    position: relative;
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 97%;
    margin: 0;
    padding: 0;
  }
  svg {
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 100%;
  }
}
</style>
<a  class="btn btn-info" onclick="printDiv('printableArea3')" id="new" style="background:#0FF; color:#000; padding:5px 8px; border-radius: 5px;margin-bottom:5px; width:100%;"><?php echo 'Print' ?></a>
<div id="printableArea3">
<div>

  <?php 
$qty=$totalbarcode;
$count=0;
for ($i = 0; $i < $qty; $i++) {
	
	foreach($foodinfo as $item){
	$thisfoodcode=$item->ProductsID.'.'.$item->variantid.'.'.$item->foodcode;
?>
  <div>
      <div style="width:98%; float:left; position:relative; text-align:left"> 
      <span class="info-box-text fw-bold fs-17px" style="width:100%; float:left; position:relative; text-align:left; padding-left:30px;"><?php echo $item->ProductName;?>-(<?php echo $item->variantName;?>)</span>
      <img src="<?php echo site_url();?>itemmanage/item_food/itemsbarcode/<?php echo $thisfoodcode;?>">
      </div>
  </div>
  <?php
       if($count == 3){
         echo "</div><div class='row'>";
       }
     ?>
  <?php $count++; 
	}
}
    ?>
    
    </div>
</div>
