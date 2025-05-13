<script src="http://localhost/bhojonsaas_2newacc/assets/js/jquery-1.12.4.min.js" type="text/javascript"></script>
<script>
function printDiv(divName) {
	$("#new").hide();
    window.print();
	
}

</script>
<style type="text/css" media="print">
@media print {
    /* Your styles here */
}
@page {
  size: 210mm 297mm;
  outline: 1pt dotted;
  margin: 0;
  padding-top: 0;

}

@media screen {
  .a4355page {
    width: 210mm;
    height: 297mm;
    padding-top: 7mm;
    padding-left: 6.4mm;
    margin: 0;
    outline: 1px dotted;
  }
  .print_label {
    height: 31.5mm; /* should be 31.0 */
    width: 63.5mm; /* should be 63.5 */
  }
}

.a4355page {
  width: 210mm;
  height: 296mm;
  padding-top: 9mm;
  padding-left: 6.4mm;
}

.print_label {
  height: 31mm; /* should be 31.0 */
  width: 63.5mm; /* should be 63.5 */
}

.print_label {

  padding-top: 2mm;
  padding-left: 3mm;
  margin-left: 2.6mm;
  outline: 1pt dotted;
  border-radius: 10mm;
  float: left;
  font-size: 3mm;
}
</style>
<a  class="btn btn-info" onclick="printDiv('printableArea3')" id="new"><?php echo 'Print' ?></a>
<div id="printableArea3">
<div class="row" style="width:100%; float:left; position:relative;">

  <?php 
$qty=$totalbarcode;
$count=0;
for ($i = 0; $i < $qty; $i++) {
	$thisfoodcode=$foodinfo->ProductsID.$foodinfo->foodcode;
?>
  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-3 d-flex" style="width:25%; float:left; position:relative;">
    <div class="info-box d-flex position-relative  flex-fill w-100 overflow-hidden">
      <div class="info-box-content d-flex flex-column justify-content-center"  style="text-align:center"> <span class="info-box-text fw-bold fs-17px text-center"><?php echo $foodinfo->ProductName;?></span>
        <div class="progress-description fs-14"><img src="<?php echo site_url();?>itemmanage/item_food/itemsbarcode/<?php echo $thisfoodcode;?>"></div>
      </div>
    </div>
  </div>
  <?php
       if($count == 3){
         echo "</div><div class='row'>";
       }
     ?>
  <?php $count++; 
      }
    ?>
    </div>
</div>
