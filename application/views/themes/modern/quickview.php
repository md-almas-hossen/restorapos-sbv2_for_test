<?php $iteminfo=(object)$iteminfo;
if($type=="menu"){$flag="menu";}else{ $flag="other";}

// Dynamic Css apply
$text_color_css = "style='color: green'";
$button_color_css = "style='background-color: green'";
$bg_color_css = "style='background-color: green'";
if(isset($color_setting->web_text_color)){
    $text_color_css = "style='color: $color_setting->web_text_color !important'";
    $button_color_css = "style='background-color: $color_setting->web_button_color !important'";
    $bg_color_css = "style='background-color: $color_setting->web_button_color !important'";
}

?>
 <input name="itemname" type="hidden" id="itemname_1<?php echo $type;?>" value="<?php echo $iteminfo->ProductName; if(!empty($iteminfo->itemnotes)){ echo " -".$iteminfo->itemnotes;}?>" />
    <input name="sizeid" type="hidden" id="sizeid_1<?php echo $type;?>" value="<?php echo $iteminfo->variantid;?>" />
    <input name="size" type="hidden" value="<?php echo $iteminfo->variantName;?>" id="varient_1<?php echo $type;?>" />
    <input type="hidden" name="catid" id="catid_1<?php echo $type;?>" value="<?php echo $iteminfo->CategoryID;?>">
    <input name="itemprice" type="hidden" value="<?php echo $iteminfo->price;?>" id="itemprice_1<?php echo $type;?>" />
    <input type="hidden" name="cartpage" id="cartpage_1<?php echo $type;?>" value="<?php if($type=="menu"){echo "1";}else{echo "0";}?>">
<div class="row">
  <div class="col-lg-6">
    <div class="demo">
      <div class="item">
      
        <div class="clearfix" style="max-width:474px;">
          <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
            <li data-thumb="<?php echo base_url(!empty($iteminfo->bigthumb) ? $iteminfo->bigthumb : 'dummyimage/555x370.jpg'); ?>" style="position:relative">    <?php if($iteminfo->OffersRate>0){ ?>
            					<div class="offer-price">
                                	<div class="offer-price-number"><?php echo 	$iteminfo->OffersRate ?>% <span>OFF</span></div>
                            	</div>
                                <?php } ?>
                                <img class="w-100 img-fluid" src="<?php echo base_url(!empty($iteminfo->bigthumb) ? $iteminfo->bigthumb : 'assets/img/default/default_food.png'); ?>" alt="<?php echo $iteminfo->ProductName; ?>" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';"> </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mt-4 mt-lg-0 product-summary-content">
      <h4 class="font-roboto"><?php echo $iteminfo->ProductName; ?></h4>
      <div class="align-items-center d-flex product-price my-2">
        <h4 class="mb-0 me-2 text-green" <?php echo $text_color_css;?>><?php if ($this->storecurrency->position == 1){echo $this->storecurrency->curr_icon;} ?><span id="vpricedt"><?php echo $iteminfo->price;?></span><?php if ($this->storecurrency->position == 2){echo $this->storecurrency->curr_icon;} ?></h4></div>
      <div class="align-items-center d-flex my-2">
        <h6 class="my-0 me-2"><?php echo display('size')?>:</h6>
        <div class="w-25">
          <select name="varientinfo" class="form-select" aria-label="Default select example" id="varientinfoquickview">
            <?php foreach($varientlist as $thisvarient){?>
                        <option value="<?php echo $thisvarient->variantid;?>" data-title="<?php echo $thisvarient->variantName;?>" data-price="<?php echo $thisvarient->price;?>" <?php if($iteminfo->variantid==$thisvarient->variantid){ echo "selected";}?>><?php echo $thisvarient->variantName;?></option>
                        <?php }?>
          </select>
        </div>
      </div>
      <div class="short-description">
        <p><?php echo $iteminfo->descrip; ?></p>
      </div>
      <div class="align-items-center bg-grey d-flex p-3">
        <div class="align-items-center cart_counter modal_counter d-flex p-1 radius-30 me-4">
          <button onclick="var result = document.getElementById('sst61_<?php echo $type;?>'); var sst = result.value; if( !isNaN( sst ) && sst > 0 ) result.value--;return false;" class="bg-dark border-0 items-count reduced rounded-circle text-white" <?php echo $bg_color_css;?> type="button"> <i class="ti-minus"></i> </button>
          <input type="text" name="qty" id="sst61_<?php echo $type;?>" maxlength="12" value="1" title="<?php echo display('quantity')?>:" class="border-0 input-text qty text-center width_40 bg-transparent" readonly="readonly">
          <input type="hidden" name="cartpage" id="cartpage">
          <button onclick="var result = document.getElementById('sst61_<?php echo $type;?>'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="bg-dark border-0 increase items-count rounded-circle text-white" <?php echo $bg_color_css;?> type="button"> <i class="ti-plus"></i> </button>
        </div>
        <button type="button" onclick="addonsfoodtocart(<?php echo $iteminfo->ProductsID;?>,1,'<?php echo $flag;?>')" class="btn bg-green rounded-pill text-white w-sm-100 btn-sm px-2 px-sm-4 py-2 mt-2 mt-md-0" <?php echo $bg_color_css;?>><?php echo display('add_to_cart')?></button>
      </div>
      <div class="product-meta my-3">
        <div class="posted-in font-roboto"> <strong><?php echo display('category')?>: </strong> <a href="#"> <?php echo $category->Name; ?></a></div>
        <div class="tag-as font-roboto"> <strong><?php echo display('tag')?>: </strong> <a href="#"> <?php echo $iteminfo->component; ?></a></div>
      </div>
    </div>
  </div>
</div>
<?php if(!empty($addonslist)){?>
<div class="row mb-3">
  <div class="col-md-12">
    <form class="align-items-center d-flex flex-wrap px-4 py-3 shadow-extra">
      <div class="ext_title me-3 px-3 py-3 text-center">
        <h6 class="my-0">Extra Addons</h6>
      </div>
       <?php $k=0;
			foreach($addonslist as $addons){
			$k++;
		?>
      <div class="align-items-center d-flex form-check m-2">
        <input type="checkbox" class="form-check-input mb-1" role="<?php echo $addons->price;?>" title="<?php echo $addons->add_on_name;?>"  name="addons" value="<?php echo $addons->add_on_id;?>"  id="addons_<?php echo $addons->add_on_id;?>">
        <label class="align-items-center d-flex form-check-label ms-2" for="addons_<?php echo $addons->add_on_id;?>">
        <span><?php echo $addons->add_on_name;?> (<?php if ($this->storecurrency->position == 1) { echo $this->storecurrency->curr_icon; } echo $addons->price; if ($this->storecurrency->position == 2) { echo $this->storecurrency->curr_icon;}?>)</span>
        <div class="border cart_counter d-flex justify-content-end p-1 radius-30 ms-3">
          <button onClick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst2 = result.value; if( !isNaN( sst2 ) &amp;&amp; sst2 > 0 ) result.value--;return false;" class="bg-green border-0 items-count reduced rounded-circle text-white" <?php echo $bg_color_css;?> type="button"> <i class="ti-minus"></i> </button>
          <input type="text"  name="qty" id="addonqty_<?php echo $addons->add_on_id;?>" maxlength="12" value="1" title="Quantity:" class="border-0 input-text qty text-center width_40">
          <button onClick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst2 = result.value; if( !isNaN( sst2 )) result.value++;return false;" class="bg-green border-0 increase items-count rounded-circle text-white" <?php echo $bg_color_css;?> type="button"> <i class="ti-plus"></i> </button>
        </div>
        </label>
      </div>
      <?php } ?>
      
    </form>
  </div>
</div>
<?php } ?>
