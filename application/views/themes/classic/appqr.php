<?php $webinfo = $this->webinfo;
$qrtheme = $this->settingqr;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;

$openingtime = $this->settinginfo->opentime;
$closetime = $this->settinginfo->closetime;
if (strpos($openingtime, 'AM') !== false || strpos($openingtime, 'am') !== false) {
    $starttime = strtotime($openingtime);
} else {
    $starttime = strtotime($openingtime);
}
if (strpos($closetime, 'PM') !== false || strpos($closetime, 'pm') !== false) {
    $endtime = strtotime($closetime);
} else {
    $endtime = strtotime($closetime);
}
$comparetime = strtotime(date("h:i:s A"));
if (($comparetime >= $starttime) && ($comparetime < $endtime)) {
    $restaurantisopen = 1;
} else {
    $restaurantisopen = 0;
}
 $op = 0;
        foreach ($categorylist as $category) {
            $op++;
            $getcat = str_replace(' ', '', $category->Name);
            $hcategoryname = preg_replace("/[^a-zA-Z0-9\s]/", "", $getcat);
        ?>
        <div class="tab-pane fade show <?php if($op==1){ echo "active";}?>" id="nav-home<?php echo $hcategoryname . $op; ?>" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
          <!-- Start Items Title -->
          <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0 fw-semibold"><?php echo $category->Name; ?></h5>
            <a href="<?php echo base_url(); ?>qr-categoryitems/<?php echo $category->CategoryID;?>" class="fw-semibold">Show All</a>
          </div>
          <!-- /.End Items Title -->
          <!-- Start Item -->
          <?php $allcat = "";
				foreach ($category->sub as $subcat) {
					$allcat .= $subcat->CategoryID . ",";
				}
				$mainwithsub = $allcat . $category->CategoryID;
				$condition = "item_foods.CategoryID IN($mainwithsub)";
				$itemlist = $this->hungry_model->qrmenue($condition);
				$k = 0;
				foreach ($itemlist as $item){
					$item=(object)$item;
					//print_r($item);
					$k++;
					$this->db->select('*');
					$this->db->from('menu_add_on');
					$this->db->where('menu_id', $item->ProductsID);
					$query = $this->db->get();
					$this->db->select('*');
					$this->db->from('tbl_menutoping');
					$this->db->where('menuid', $item->ProductsID);
					$tpquery = $this->db->get();
					$getadons = "";
					if ($query->num_rows() > 0  || $tpquery->num_rows() > 0) {
						$getadons = 1;
					} else {
						$getadons =  0;
					}
				?>
          <a href="<?php echo base_url(); ?>app-details/<?php echo $item->ProductsID; ?>/0" class="card border-0 mb-2 p-3 rounded-4 shadow text-inherit">
            <div class="row g-3">
              <div class="col-auto">
              <?php if($qrtheme->image==0){?>
                <img src="<?php echo base_url(!empty($item->medium_thumb) ? $item->medium_thumb : 'assets/img/no-image.png'); ?>" alt="" class="item-image rounded-4">
                <?php } ?>
              </div>
              <div class="col">
                <h6 class="fw-semibold item-title mb-0 overflow-hidden"><?php echo $item->ProductName; ?></h6>
                <div class="text-danger"><?php echo $item->variantName; ?></div>
                <p class="item-des mb-1 overflow-hidden"><?php echo substr($item->descrip, 0, 60); ?></p>
                <div class="fs-6 fw-semibold item-price text-primary"><?php echo $item->price; ?></div>
              </div>
              <div class="align-self-end col-auto">
                <!-- Button trigger modal -->
                <?php if ($restaurantisopen == 1) {
                     if ($getadons == 1 || $item->totalvarient>1) { ?>
     <input name="ddf" type="hidden" value="<?php echo $item->totalvarient; ?>" />
     <input name="sizeid" type="hidden" id="sizeid_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->variantid; ?>" />
     <input type="hidden" name="catid" id="catid_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->CategoryID; ?>">
     <input type="hidden" name="itemname" id="itemname_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->ProductName; ?>">
     <input type="hidden" name="varient" id="varient_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->variantName; ?>">
     <input type="hidden" name="cartpage" id="cartpage_<?php echo $item->CategoryID . $k; ?>" value="1">
     <input name="itemprice" type="hidden" value="<?php echo $item->price; ?>" id="itemprice_<?php echo $item->CategoryID . $k; ?>" />
     <?php $myid2 = $item->CategoryID . $item->ProductsID . $item->variantid;
			if (count($this->cart->contents()) > 0) {
			$allid2 = "";
			foreach ($this->cart->contents() as $cartitem) {
			if ($cartitem['id'] == $myid2) {
			$allid2 .= $myid2 . ","; ?>
            <button class="simple_btn d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
            	<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
            </button>
            <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
               <div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>                                                     
            </div>
            <?php }
            }
			$str2 = implode(',', array_unique(explode(',', $allid2)));
			$myvalue2 = trim($str2, ',');
			if ($myid2 != $myvalue2) { ?> 
            <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
            </button>
            <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
               <div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>                                                 
            </div>
            <?php }
             } else {
           ?>                                               
    		 <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
            </button>
             <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
             	<div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>
             </div>
			<?php }
        	} else {
            ?>
    <input name="sizeid" type="hidden" id="sizeid_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->variantid; ?>" />
    <input type="hidden" name="catid" id="catid_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->CategoryID; ?>">
    <input type="hidden" name="itemname" id="itemname_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->ProductName; ?>">
    <input type="hidden" name="varient" id="varient_<?php echo $item->CategoryID . $k; ?>" value="<?php echo $item->variantName; ?>">
    <input type="hidden" name="cartpage" id="cartpage_<?php echo $item->CategoryID . $k; ?>" value="1">
   <input name="itemprice" type="hidden" value="<?php echo $item->price; ?>" id="itemprice_<?php echo $item->CategoryID . $k; ?>" />
   <?php $myid = $item->CategoryID . $item->ProductsID . $item->variantid;
		if (count($this->cart->contents()) > 0) {
			$allid = "";
			foreach ($this->cart->contents() as $cartitem) {
				if ($cartitem['id'] == $myid) {
					$allid .= $myid . ","; 
		?>
        <button class="simple_btn d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
        </button>
        
        <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
        	<div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>
        </div>
		<?php } else if ($cartitem['id'] != $myid) {
        }  }
        $str = implode(',', array_unique(explode(',', $allid)));
        $myvalue = trim($str, ',');
        if ($myid != $myvalue) { ?>
          <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
             <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
         </button>
          <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
          <div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>
   		  </div>
         <?php }
         } else {
         ?>
         <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg>
         </button>
         <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
            <div class="col-5 align-self-end w-100">
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty'])?$cartitem['qty']:1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                
                <div class="input-group-append">
                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>                                                
         </div>
		<?php }
        }
    } else { ?>
<button type="button" class="btn btn-primary btn-add p-0 align-items-center justify-content-center" onclick="resclose()">
<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                  </svg></button>
<?php } ?>
                
              </div>
            </div>
          </a>
          <?php } ?>
    
          <!-- /.End Item -->
        </div>
        <?php } ?>