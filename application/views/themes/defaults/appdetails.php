<?php $webinfo = $this->webinfo;
$storeinfo = $this->settinginfo;
$currency = $this->storecurrency;
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
$qrtheme = $this->settingqr;
$iteminfo=(object)$iteminfo;

?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $qrtheme->theme;?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $seoinfo->description; ?>">
        <meta name="keywords" content="<?php echo $seoinfo->keywords; ?>">
        <title><?php echo $title; ?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url((!empty($this->settinginfo->favicon) ? $this->settinginfo->favicon : 'application/views/themes/' . $acthemename . '/assets_web/images/favicon.png')) ?>">
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/jquery-3.3.1.min.js"></script>
    <!--====== Plugins CSS Files =======-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/app.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/pos-topping.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/appdetails.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
   <script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
        
    </head>
    <body class="<?php echo $qrtheme->theme;?>1">
          <div class="modal fade" id="closenotice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="bg-primary modal-header">
                    <h5 class="modal-title fs-5 text-white" id="exampleModalLabel"><?php echo display('restaurant_closed'); ?></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo display('closed_msg'); ?> <?php echo $this->settinginfo->opentime; ?>- <?php echo $this->settinginfo->closetime; ?></p>
                </div>

            </div>
        </div>
    </div>
          <!-- Modal: Search Activity -->
          <div class="modal fade" id="modalSearch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="search-content">
                <!--Search form-->
                <div class="search-form">
                  <div class="mb-3">
                    <div class="input-group input-group-merge input-group-flush">
                      <span class="input-group-text bg-transparent border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                          <circle cx="11" cy="11" r="8"></circle>
                          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg></span>
                      <input type="text"  id="foodname" autocomplete="off" class="form-control" placeholder="<?php echo display('search_food_item')?>" onKeyUp="getfoodlist(0)">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <!-- Modal: Item Details -->
          <div class="modal fade" id="addons" tabindex="-1" aria-labelledby="itemDetailsLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content addonsinfo">
                
              </div>
            </div>
          </div>
          <!-- Start Cart Total -->
 			 <button type="button" class="cart-total d-flex align-items-center justify-content-between bg-primary text-white position-fixed rounded-3 border-0" onClick="addonsitemdetailsqr()">
    <div class="">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
      </svg>
      <span class="ms-1 fw-medium">Add To Cart</span>
    </div>
    <span class="fs-6 fw-medium"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                        } ?><?php echo $iteminfo->price; ?><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span>
  </button>
  			<!-- /.End Cart Total -->
          <div class="content-wrap">
    <div class="details-header position-relative">
    <?php if($qrtheme->image==0){?>
      <img src="<?php echo base_url(!empty($iteminfo->bigthumb) ? $iteminfo->bigthumb : 'dummyimage/555x370.jpg'); ?>" alt="<?php echo $iteminfo->ProductName; ?>" class="img-fluid w-100">
      <?php } ?>
      <a href="<?php echo base_url(); ?>qr-menu" class="btn btn-primary position-absolute btn-back p-0 d-flex align-items-center justify-content-center">
        <svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M10 19L1.5 10.5L10 2" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
    <div class="p-3">
      <div class="row g-3 justify-content-between mb-2">
        <h5 class="col fw-semibold"><?php 
		echo $iteminfo->ProductName; ?></h5>
        <div class="col-4 col-auto ms-3">
        	<div class="">
            	<div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button onclick="var result = document.getElementById('sst61'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="number" inputmode="decimal" style="text-align: center" class="form-control number-input  border-0 input-text qty" name="itemqty" id="sst61" maxlength="12" value="1" step="1">
                <div class="input-group-append">
                  <button onclick="var result = document.getElementById('sst61'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="readmore js-read-more" data-rm-words="14">
       <?php echo $iteminfo->descrip; ?>
      </div>
      <div class="product-size d-flex flex-wrap align-items-center mt-3">
        <h6 class="fs-16 fw-semibold me-3 mb-0">Size:</h6>
		<?php $k=0;
		foreach($varientlist as $thisvarient){
			$k++
			?>
        <input class="d-none" type="radio" name="varientinfo" id="m<?php echo $thisvarient->variantid;?>" value="<?php echo $thisvarient->variantid;?>" <?php if($k==1){ echo "checked";}?> lang="<?php echo $thisvarient->variantName;?>">
        <label class="mr-1" for="m<?php echo $thisvarient->variantid;?>"><span class="size border d-block fw-semibold rounded text-dark"><?php echo $thisvarient->variantName;?></span></label>
        <?php } ?>

       
      </div>
      
      <!--  /.End of product Size -->
    </div>
     <input name="pid" type="hidden" id="pid" value="<?php echo $iteminfo->ProductsID;?>" /> 
     <input type="hidden" name="catid" id="catid" value="<?php echo $iteminfo->CategoryID; ?>">
     <input type="hidden" name="itemname" id="itemname" value="<?php echo $iteminfo->ProductName; ?>">
     <input name="itemprice" type="hidden" value="<?php echo $iteminfo->price; ?>" id="itemprice" />
    <div class="p-3">
      <!-- Start Item -->
      <?php //print_r($topinglist);
	  
	  if(!empty($topinglist)){
$totaltp=count($topinglist);?>          
<div class="mb-3 topping-section">
<?php 
$m=0;
foreach($topinglist as $topping){
$m++;
$alltopping=explode(',',$topping['toppingname']);
?>
<div class="align-items-center d-flex mb-1">
  <h6 class="fw-semibold mb-0 me-2"><?php echo $topping['tptitle']?></h6>
  <hr class="flex-grow-1 m-0">
</div>
<?php if($topping['maxoption']>1){?><p>Select up to <?php echo $topping['maxoption'];?></p><?php }else{?><p>Select up one</p><?php }?>
<?php if(!empty($alltopping)){
		  if($topping['maxoption']>1){
		  	$maxsl=$topping['maxoption'];
		  	$j=0;
		  	foreach($alltopping as $alltoppname){
			$j++;
			$sql=$this->db->select("*")->from('add_ons')->where('add_on_name ',$alltoppname)->get()->row();
			if($topping['ispaid']>0){
				$isprice=$sql->price;
			}else{
				$isprice=0;  
			}
	?>
<div class="form-check mb-1">
  <input pos="3" role="<?php echo $topping['tpassignid'];?>" lang="<?php echo $isprice;?>" title="<?php echo $alltoppname;?>" type="checkbox" class="form-check-input checker topp checkbox<?php echo $topping['tpassignid'];?>" onclick="gettotalcheck(<?php echo $maxsl;?>,'<?php echo $topping['tpassignid'];?>','<?php echo $sql->add_on_id;?>')" name="topping_<?php echo $m;?>" value="<?php echo $sql->add_on_id;?>" id="topping_<?php echo $topping['tpassignid'].$sql->add_on_id;?>">
  <label class="form-check-label d-flex justify-content-between check-label" for="topping_<?php echo $topping['tpassignid'].$sql->add_on_id;?>">
    <span><?php echo $alltoppname;?></span>
    <?php if($topping['ispaid']>0){?><span><?php echo $this->storecurrency->curr_icon.' '.$sql->price;?></span><?php } ?>
  </label>
  <?php  if($topping['isposition']>0){?>
         
         <duv class="expandable mt-1 radio-toggle-buttons">
                <input type="radio" class="btn-check btn_push" name="<?php echo $alltoppname;?>" lang="<?php echo $topping['tpassignid'].$sql->add_on_id;?>" id="option1<?php echo $topping['tpassignid'].$sql->add_on_id;?>" autocomplete="off" value="1">
                <label class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center fs-13" for="option1<?php echo $topping['tpassignid'].$sql->add_on_id;?>"><svg class="me-1" width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <title>Left Half Topping</title>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.99999C10 0.895421 9.09555 -0.0208739 8.01277 0.197443C3.44193 1.11904 0 5.15756 0 9.99999C0 14.8424 3.44193 18.8809 8.01277 19.8025C9.09555 20.0209 10 19.1046 10 18V12C9.44772 12 9 11.5523 9 11C9 10.4477 9.44772 10 10 10V1.99999ZM8 4C8 4.55228 7.55228 5 7 5C6.44772 5 6 4.55228 6 4C6 3.44772 6.44772 3 7 3C7.55228 3 8 3.44772 8 4ZM8 16C8.55228 16 9 15.5523 9 15C9 14.4477 8.55228 14 8 14C7.44772 14 7 14.4477 7 15C7 15.5523 7.44772 16 8 16ZM4.5 10C5.32843 10 6 9.32843 6 8.5C6 7.67157 5.32843 7 4.5 7C3.67157 7 3 7.67157 3 8.5C3 9.32843 3.67157 10 4.5 10Z" fill="currentColor"></path>
                    <path d="M20 10C20 14.838 16.5645 18.8735 12 19.8V17.748C15.4505 16.8599 18 13.7277 18 10C18 6.27236 15.4505 3.14016 12 2.25207V0.200073C16.5645 1.12661 20 5.16212 20 10Z" fill="currentColor"></path>
                  </svg>Left Half</label>
                <input type="radio" class="btn-check btn_push" name="<?php echo $alltoppname;?>" id="option2<?php echo $topping['tpassignid'].$sql->add_on_id;?>" lang="<?php echo $topping['tpassignid'].$sql->add_on_id;?>" autocomplete="off" value="2">
                <label class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center fs-13" for="option2<?php echo $topping['tpassignid'].$sql->add_on_id;?>"><svg class="me-1" width="16" height="16" viewBox="0 0 21 20" fill="none" aria-hidden="true"><title>Right Half Topping</title><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 18C10.5 19.1046 11.4045 20.0209 12.4872 19.8025C17.0581 18.8809 20.5 14.8424 20.5 9.99999C20.5 5.15756 17.0581 1.11904 12.4872 0.197442C11.4045 -0.0208738 10.5 0.895421 10.5 1.99999L10.5 10C11.0523 10 11.5 10.4477 11.5 11C11.5 11.5523 11.0523 12 10.5 12L10.5 18ZM15.5 6.00001C15.5 7.10458 14.6046 8.00001 13.5 8.00001C12.3954 8.00001 11.5 7.10458 11.5 6.00001C11.5 4.89544 12.3954 4.00001 13.5 4.00001C14.6046 4.00001 15.5 4.89544 15.5 6.00001ZM17.5 11C17.5 11.5523 17.0523 12 16.5 12C15.9477 12 15.5 11.5523 15.5 11C15.5 10.4477 15.9477 10 16.5 10C17.0523 10 17.5 10.4477 17.5 11ZM15.5 15.5C15.5 16.3284 14.8284 17 14 17C13.1716 17 12.5 16.3284 12.5 15.5C12.5 14.6716 13.1716 14 14 14C14.8284 14 15.5 14.6716 15.5 15.5Z" fill="currentColor"></path><path d="M0.5 9.99998C0.5 5.16206 3.93552 1.12655 8.5 0.200012L8.5 2.25201C5.04954 3.1401 2.5 6.2723 2.5 9.99998C2.5 13.7277 5.04955 16.8599 8.5 17.7479V19.7999C3.93552 18.8734 0.5 14.8379 0.5 9.99998Z" fill="currentColor"></path></svg>Right Half</label>
                <input type="radio" class="btn-check btn_push" name="<?php echo $alltoppname;?>" lang="<?php echo $topping['tpassignid'].$sql->add_on_id;?>" id="option4<?php echo $topping['tpassignid'].$sql->add_on_id;?>" autocomplete="off" value="3" checked="checked">
                <label class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center fs-13" for="option4<?php echo $topping['tpassignid'].$sql->add_on_id;?>"><svg class="me-1" width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true"><title>Whole Topping</title><path fill-rule="evenodd" clip-rule="evenodd" d="M10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20ZM7 5C7.55228 5 8 4.55228 8 4C8 3.44772 7.55228 3 7 3C6.44772 3 6 3.44772 6 4C6 4.55228 6.44772 5 7 5ZM13 8C14.1046 8 15 7.10457 15 6C15 4.89543 14.1046 4 13 4C11.8954 4 11 4.89543 11 6C11 7.10457 11.8954 8 13 8ZM16 12C16.5523 12 17 11.5523 17 11C17 10.4477 16.5523 10 16 10C15.4477 10 15 10.4477 15 11C15 11.5523 15.4477 12 16 12ZM11 11C11 11.5523 10.5523 12 10 12C9.44772 12 9 11.5523 9 11C9 10.4477 9.44772 10 10 10C10.5523 10 11 10.4477 11 11ZM9 15C9 15.5523 8.55228 16 8 16C7.44772 16 7 15.5523 7 15C7 14.4477 7.44772 14 8 14C8.55228 14 9 14.4477 9 15ZM13.5 17C14.3284 17 15 16.3284 15 15.5C15 14.6716 14.3284 14 13.5 14C12.6716 14 12 14.6716 12 15.5C12 16.3284 12.6716 17 13.5 17ZM6 8.5C6 9.32843 5.32843 10 4.5 10C3.67157 10 3 9.32843 3 8.5C3 7.67157 3.67157 7 4.5 7C5.32843 7 6 7.67157 6 8.5Z" fill="currentColor"></path></svg>Whole</label>
              </duv>
	<?php }?>
</div>
 <?php } }else{
			$k=0;
			foreach($alltopping as $alltoppname){
			$k++;
			$sql=$this->db->select("*")->from('add_ons')->where('add_on_name ',$alltoppname)->get()->row();
		    if($topping['ispaid']>0){
		    $isprice=$sql->price;
		    }else{
			 $isprice=0;  
		    }?>	 
<div class="form-check mb-1">
  <input pos="0" title="<?php echo $alltoppname;?>" lang="<?php echo $isprice;?>" role="<?php echo $topping['tpassignid'];?>" type="radio" class="topp form-check-input" name="topping_<?php echo $m;?>" id="topping_<?php echo $topping['tpassignid'].$sql->add_on_id;?>" value="<?php echo $sql->add_on_id;?>">
  <label class="form-check-label d-flex justify-content-between check-label" for="topping_<?php echo $topping['tpassignid'].$sql->add_on_id;?>">
    <span><?php echo $alltoppname;?></span>
    <?php if($topping['ispaid']>0){?><span><?php echo $this->storecurrency->curr_icon.' '.$sql->price;?></span><?php } ?>
  </label>
</div>
<?php } 
 }  } } 
 ?>
  <input name="totaltopping" type="hidden" value="<?php echo $totaltp;?>" id="numoftopping" />
</div>
<?php }
if(!empty($addonslist)){
 ?>
<div class="align-items-center d-flex mb-1">
  <h6 class="fw-semibold mb-0 me-2">Addons Info</h6>
  <hr class="flex-grow-1 m-0">
</div>
<?php $k=0;
foreach($addonslist as $addons){
$k++;
?>

<div class="mb-3">
<div class="form-check mb-1">
  <input class="form-check-input" type="checkbox" role="<?php echo $addons->price;?>" title="<?php echo $addons->add_on_name;?>" name="addons" value="<?php echo $addons->add_on_id;?>" id="addons_<?php echo $addons->add_on_id;?>">
  <label class="form-check-label d-flex justify-content-between" for="addons_<?php echo $addons->add_on_id;?>">
    <span><?php echo $addons->add_on_name;?></span>
    <span><?php echo $addons->price;?></span>
  </label>
  <div class="col-5 align-self-end">
             
              <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                  <button onclick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                </div>
                <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="addonqty" id="addonqty_<?php echo $addons->add_on_id;?>" style="text-align: center" maxlength="12" value="1" title="Quantity:" step="1">
                
                <div class="input-group-append">
                  <button onclick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                </div>
              </div>
            </div>
</div>

</div>
 <?php } }?>
      
    </div>
  </div>

        <!--====== SCRIPTS JS ======-->
       <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>
    
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/read-more.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/appcustom.js"></script>
        <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/qrappdetails.js"></script>
       
    </body>
</html>