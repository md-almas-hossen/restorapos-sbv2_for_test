<?php
$webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
$qrtheme = $this->settingqr;

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
//print_r($webinfo);
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $qrtheme->theme; ?>">

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

  <link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>

</head>

<body class="<?php echo $qrtheme->theme; ?>1">
  <div class="modal fade" id="closenotice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="bg-primary modal-header">
          <h5 class="modal-title fs-5 text-white" id="exampleModalLabel"><?php echo display('restaurant_closed'); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
              <input type="text" id="foodname" autocomplete="off" class="form-control" placeholder="<?php echo display('search_food_item') ?>" onKeyUp="getfoodlist(0)">
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
  <!-- Start offcanvas -->
  <?php $totalqty = 0;
  $totalamount = 0;
  if ($this->cart->contents() > 0) {
    $totalqty = count($this->cart->contents());
  } ?>
  <!-- Start App Bottom Menu -->
  <div class="app-bottom-menu bottom-0 g-0 justify-content-center position-fixed row start-0 w-100">
  <a href="javascript:void(0)" onclick="orderlist()" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
      <!-- <img src="assets/img/icon/order-list.svg" alt=""> -->
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders">
        <line x1="4" y1="21" x2="4" y2="14"></line>
        <line x1="4" y1="10" x2="4" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="12"></line>
        <line x1="12" y1="8" x2="12" y2="3"></line>
        <line x1="20" y1="21" x2="20" y2="16"></line>
        <line x1="20" y1="12" x2="20" y2="3"></line>
        <line x1="1" y1="14" x2="7" y2="14"></line>
        <line x1="9" y1="8" x2="15" y2="8"></line>
        <line x1="17" y1="16" x2="23" y2="16"></line>
      </svg>
      <span class="d-block">Order List</span>
    </a>
    <a href="<?php echo base_url(); ?>qr-menu" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center" style="background:<?php echo !empty($qrtheme->qrbuttonhovercolor) ? $qrtheme->qrbuttonhovercolor : '#85054a'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
      <!-- <img src="assets/img/icon/home.svg" alt=""> -->
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
        <polyline points="9 22 9 12 15 12 15 22"></polyline>
      </svg>
      <span class="d-block">Home</span>
    </a>
    <input name="cqty" type="hidden" value="<?php echo $totalqty; ?>" id="cartitemandprice">
    <input name="isloginuser" id="isloginuser" type="hidden" value="<?php echo $this->session->userdata('CusUserID'); ?>">
    <?php if ($qrtheme->cartbtn == 0) { ?>
      <a href="javascript:void(0)" onclick="gotoappcart()" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center btnposition" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
        <div class="position-relative d-inline-block">
          <div class="wishlist-count wishlist-count__two rounded-circle text-white d-flex align-items-center justify-content-center position-absolute fw-medium"><span id="badgeshow" class="<?php if ($totalqty > 0) {
                                                                                                                                                                                                echo "badgedisplayblock";
                                                                                                                                                                                              } else {
                                                                                                                                                                                                echo "badgedisplaynone";
                                                                                                                                                                                              } ?> classic-badge2"><?php echo $totalqty; ?></span></div>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
        </div>
        <span class="d-block">My Cart</span>
      </a>
    <?php } ?>
  </div>
  <!-- /.End App Bottom Menu -->
  <div class="content-wrap">
    <!-- Start Top Header -->
    <div class="top-header py-4 px-3 d-flex align-items-center" style="background:<?php echo !empty($qrtheme->backgroundcolorqr) ? $qrtheme->backgroundcolorqr : '#000000'; ?>;">
      <div class="">
      <button type="button" class="btn btn-primary btn-menu p-0 d-flex align-items-center justify-content-center me-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"
         style="
          background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; 
          color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"
          onmouseover="this.style.backgroundColor='<?php echo !empty($qrtheme->qrbuttonhovercolor) ? $qrtheme->qrbuttonhovercolor : ''; ?>';"
          onmouseout="this.style.backgroundColor='<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>';">
          <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 13H8M1 1H17H1ZM1 7H17H1Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <a href="<?php echo base_url(); ?>qr-menu" class="logo-wrap">
        <img src="<?php echo base_url(!empty($webinfo->logo) ? $webinfo->logo : 'dummyimage/168x65.jpg'); ?>" alt="">
      </a>
      <div class="ms-auto">
        <button type="button" class="btn btn-light search-btn p-0 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalSearch">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="11" r="8" stroke="#484848" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M20.9999 20.9999L16.6499 16.6499" stroke="#484848" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
    </div>
    <!-- /.End Top Header -->
    <div class="header-item">
      <div class="py-3 ps-3">
        <div class="d-flex justify-content-between mb-3 me-3">
          <h5 class="mb-0 fw-semibold">Food Category</h5>
          <a href="<?php echo base_url(); ?>qr-category" class="fw-semibold">Show All</a>
        </div>
        <div class="d-flex flex-nowrap slide-content" role="tablist">
        <?php $c = 0;  ?>
<div class="slide-item text-center me-2 <?php if ($c == 0) {
  echo "active";
} ?>" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home-all" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
<img src="<?php echo base_url('assets/img/default/all_product.png'); ?>" alt="" class="rounded-4" style="width: 130px; height: 100px;">
<h6 class="fw-semibold mt-2 slide-item__name mb-0">All</h6>
</div>
<?php
          foreach ($categorylist as $category) {
            $c++;
            $pgetcat = str_replace(' ', '', $category->Name);
            $phcategoryname = preg_replace("/[^a-zA-Z0-9\s]/", "", $pgetcat);
          ?>

            <div class="slide-item text-center me-2" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home<?php echo $phcategoryname . $c; ?>" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
              <img src="<?php echo base_url(!empty($category->CategoryImage) ? $category->CategoryImage : 'assets/img/default/default_category.png'); ?>" alt="" class="rounded-4"  style="width: 130px; height: 100px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_category.png'); ?>';">
              <h6 class="fw-semibold mt-2 slide-item__name mb-0"><?php echo $category->Name; ?></h6>
            </div>
          <?php } ?>
          <div class="slide-item text-center me-2" id="nav-home-tab" style="display:none;" data-bs-toggle="tab" data-bs-target="#nav-home-search" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <h6 class="fw-semibold mt-2 slide-item__name mb-0">ss</h6>
          </div>
        </div>
      </div>
    </div>
    <div class="p-3">
      <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-success" role="alert">
          <!-- <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button> -->
          <a href='<?php echo base_url() . 'apporedrlist'; ?>'><?php echo $this->session->flashdata('message') ?></a>
        </div>
      <?php } ?>
      <?php if ($this->session->flashdata('exception')) { ?>
        <div class="alert alert-danger" role="alert">
          <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
          <?php echo $this->session->flashdata('exception') ?>
        </div>
      <?php } ?>
      <div class="tab-content" id="searchqritem">
        
        

            
<div class="tab-pane fade show <?php if ($op == 0) {echo "active";} ?>" id="nav-home-all" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
  <!-- Start Items Title -->
  <div class="d-flex justify-content-between mb-3">
    <h5 class="mb-0 fw-semibold">All Product</h5>
  </div>
  <!-- /.End Items Title -->
  <!-- Start Item -->      
  <?php $op = 0;
$allMainCat = "";
foreach ($categorylist as $cat) {
  $allMainCat .= $cat->CategoryID . ",";
  foreach ($cat->sub as $subcat) {
    $allMainCat .= $subcat->CategoryID . ",";
  }
}
$mainCateWithSub = $allMainCat . $category->CategoryID;
$mainCategoryCondition = "item_foods.CategoryID IN($mainCateWithSub)";
$mainCateItemList = $this->hungry_model->qrmenue($mainCategoryCondition);
$k = 0;
            foreach ($mainCateItemList as $item) {
              // dd($item);
              $item = (object)$item;
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
// dd($mainCateItemList);
?>   
<a href="<?php echo base_url(); ?>app-details/<?php echo $item->ProductsID; ?>/0" class="card border-0 mb-2 p-3 rounded-4 shadow text-inherit">
                <div class="row g-3">
                  <div class="col-auto">
                    <?php if ($qrtheme->image == 0) { ?>
                      <img src="<?php echo base_url(!empty($item->medium_thumb) ? $item->medium_thumb : 'assets/img/default/default_food.png'); ?>" alt="" class="item-image rounded-4" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
                    <?php } ?>
                  </div>
                  <div class="col">
                    <h6 class="fw-semibold item-title mb-0 overflow-hidden"><?php echo $item->ProductName; ?></h6>
                    <div class="text-danger"><?php echo $item->variantName; ?></div>
                    <p class="item-des mb-1 overflow-hidden"><?php echo substr(strip_tags($item->descrip), 0, 60); ?></p>
                    <div class="fs-6 fw-semibold item-price text-primary"><?php echo $item->price; ?></div>
                  </div>
                  <?php if ($qrtheme->cartbtn == 0) { ?>
                    <div class="align-self-end col-auto">
                      <!-- Button trigger modal -->
                      <?php if ($restaurantisopen == 1) {
                        if ($getadons == 1 || $item->totalvarient > 1) { ?>
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
                                <button class="simple_btn withaddons d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                  </svg>
                                </button>
                                <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                  <div class="col-5 align-self-end w-100">
                                    <div class="input-group input-group-sm ">
                                      <div class="input-group-prepend">
                                        <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>, event',<?php echo $item->CategoryID . $k; ?>, event)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                      </div>
                                      <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                      <div class="input-group-append">
                                        <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php }
                            }
                            $str2 = implode(',', array_unique(explode(',', $allid2)));
                            $myvalue2 = trim($str2, ',');
                            if ($myid2 != $myvalue2) { ?>
                              <button class="simple_btn withaddons btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                </svg>
                              </button>
                              <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                <div class="col-5 align-self-end w-100">
                                  <div class="input-group input-group-sm ">
                                    <div class="input-group-prepend">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                    </div>
                                    <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                    <div class="input-group-append">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php }
                          } else {
                            ?>
                            <button class="simple_btn withaddons btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                              </svg>
                            </button>
                            <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                              <div class="col-5 align-self-end w-100">
                                <div class="input-group input-group-sm ">
                                  <div class="input-group-prepend">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                  </div>
                                  <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                  <div class="input-group-append">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
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
                          <?php $myid = $item->CategoryID . $item->ProductsID . $item->variantid. $item->ProductsID . $item->variantid;
                          if (count($this->cart->contents()) > 0) {
                            $allid = "";
                            foreach ($this->cart->contents() as $cartitem) {
                              if ($cartitem['id'] == $myid) {
                                $allid .= $myid . ",";
                          ?>
                                <button class="simple_btn d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                  </svg>
                                </button>
                                <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                  <div class="col-5 align-self-end w-100">
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-prepend">
                                        <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>', '<?php echo $item->variantid; ?>', <?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                      </div>
                                      <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                      <div class="input-group-append">
                                        <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php } else if ($cartitem['id'] != $myid) {
                              }
                            }
                            $str = implode(',', array_unique(explode(',', $allid)));
                            $myvalue = trim($str, ',');
                            // dd($allid);
                            if ($myid != $myvalue) { ?>
                              <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                </svg>
                              </button>
                              <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                <div class="col-5 align-self-end w-100">
                                  <div class="input-group input-group-sm ">
                                    <div class="input-group-prepend">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                    </div>
                                    <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                    <div class="input-group-append">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php }
                          } else {
                            ?>
                            <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)"  style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                              </svg>
                            </button>
                            <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                              <div class="col-5 align-self-end w-100">
                                <div class="input-group input-group-sm ">
                                  <div class="input-group-prepend">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                  </div>
                                  <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                  <div class="input-group-append">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
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
                  <?php } ?>
                </div>
              </a>
            <?php } ?>
                                                                 
</div>
<?php




        foreach ($categorylist as $category) {
          $op++;
          $getcat = str_replace(' ', '', $category->Name);
          $hcategoryname = preg_replace("/[^a-zA-Z0-9\s]/", "", $getcat);
        ?>
          <div class="tab-pane fade" id="nav-home<?php echo $hcategoryname . $op; ?>" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <!-- Start Items Title -->
            <div class="d-flex justify-content-between mb-3">
              <h5 class="mb-0 fw-semibold"><?php echo $category->Name; ?></h5>
              <a href="<?php echo base_url(); ?>qr-categoryitems/<?php echo $category->CategoryID; ?>" class="fw-semibold">Show All</a>
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
            foreach ($itemlist as $item) {
              $item = (object)$item;
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
                    <?php if ($qrtheme->image == 0) { ?>
                      <img src="<?php echo base_url(!empty($item->medium_thumb) ? $item->medium_thumb : 'assets/img/default/default_food.png'); ?>" alt="" class="item-image rounded-4" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
                    <?php } ?>
                  </div>
                  <div class="col">
                    <h6 class="fw-semibold item-title mb-0 overflow-hidden"><?php echo $item->ProductName; ?></h6>
                    <div class="text-danger"><?php echo $item->variantName; ?></div>
                    <p class="item-des mb-1 overflow-hidden"><?php echo substr(strip_tags($item->descrip), 0, 60); ?></p>
                    <div class="fs-6 fw-semibold item-price text-primary"><?php echo $item->price; ?></div>
                  </div>
                  <?php if ($qrtheme->cartbtn == 0) { ?>
                    <div class="align-self-end col-auto">
                      <!-- Button trigger modal -->
                      <?php if ($restaurantisopen == 1) {
                        if ($getadons == 1 || $item->totalvarient > 1) { ?>
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
                                <button class="simple_btn withaddons d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">
                                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                  </svg>
                                </button>
                                <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                  <div class="col-5 align-self-end w-100">
                                    <div class="input-group input-group-sm ">
                                      <div class="input-group-prepend">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                      </div>
                                      <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                      <div class="input-group-append">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>

                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php }
                            }
                            $str2 = implode(',', array_unique(explode(',', $allid2)));
                            $myvalue2 = trim($str2, ',');
                            if ($myid2 != $myvalue2) { ?>
                              <button class="simple_btn withaddons btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                </svg>
                              </button>
                              <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                <div class="col-5 align-self-end w-100">
                                  <div class="input-group input-group-sm ">
                                    <div class="input-group-prepend">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                    </div>
                                    <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                    <div class="input-group-append">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php }
                          } else {
                            ?>
                            <button class="simple_btn withaddons btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="addonsitemqr('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                              </svg>
                            </button>
                            <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                              <div class="col-5 align-self-end w-100">
                                <div class="input-group input-group-sm ">
                                  <div class="input-group-prepend">
                                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                  </div>
                                  <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                  <div class="input-group-append">
                                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
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
                                <button class="simple_btn d-none btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                  </svg>
                                </button>

                                <div class="cart_counter active" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                  <div class="col-5 align-self-end w-100">
                                    <div class="input-group input-group-sm ">
                                      <div class="input-group-prepend">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                      </div>
                                      <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                      <div class="input-group-append">
                                      <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php } else if ($cartitem['id'] != $myid) {
                              }
                            }
                            $str = implode(',', array_unique(explode(',', $allid)));
                            $myvalue = trim($str, ',');
                            if ($myid != $myvalue) { ?>
                              <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                                </svg>
                              </button>
                              <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                                <div class="col-5 align-self-end w-100">
                                  <div class="input-group input-group-sm ">
                                    <div class="input-group-prepend">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                    </div>
                                    <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                    <div class="input-group-append">
                                    <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php }
                          } else {
                            ?>
                            <button class="simple_btn btn btn-primary btn-add p-0 d-flex align-items-center justify-content-center" id="backadd<?php echo $item->CategoryID . $k; ?>" onClick="appcart('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">
                              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7.93956H9.06044V0H7.93956V7.93956H0V9.06044H7.93956V17H9.06044V9.06044H17V7.93956Z" fill="white" />
                              </svg>
                            </button>
                            <div class="cart_counter hidden_cart" id="removeqtyb<?php echo $item->CategoryID . $k; ?>">
                              <div class="col-5 align-self-end w-100">
                                <div class="input-group input-group-sm ">
                                  <div class="input-group-prepend">
                                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemreduce('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>, event)" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                                  </div>
                                  <input type="text" class="form-control number-input  border-0 input-text qty" inputmode="decimal" name="qty" id="sst<?php echo $item->CategoryID . $k; ?>" style="text-align: center" maxlength="12" value="<?php echo (!empty($cartitem['qty']) ? $cartitem['qty'] : 1) ?>" title="Quantity:" step="1" onchange="changeqty('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)">

                                  <div class="input-group-append">
                                  <button id="<?php echo $item->CategoryID . $k; ?>" onclick="itemincrese('<?php echo $item->ProductsID; ?>','<?php echo $item->variantid; ?>',<?php echo $item->CategoryID . $k; ?>)" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
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
                  <?php } ?>
                </div>
              </a>
            <?php } ?>

            <!-- /.End Item -->
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <!--END HEADER TOP-->
  <!-- Start offcanvas -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <!-- <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5> -->
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url() . 'app-terms'; ?>"><?php echo display('terms_condition') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url() . 'app-refund-policty'; ?>"><?php echo display('refundp') ?></a>
        </li>
        <?php
        if ($this->session->userdata('CusUserID') != "") { ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url() . 'apporedrlist'; ?>"><?php echo display('morderlist') ?></a></li>
        <?php } ?>

      </ul>
    </div>
  </div>
  <!-- /.End offcanvas -->



  <!--====== SCRIPTS JS ======-->
  <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-messaging.js"></script>
  <link rel="manifest" href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/manifest.json">
  <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>

  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/read-more.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/appcustom.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/qrapp_main.js"></script>

</body>

</html>