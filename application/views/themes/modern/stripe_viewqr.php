
<?php $webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
$qrtheme = $this->settingqr;
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $qrtheme->theme;?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Green Chilli is a simple Restaurent and Cafe website">

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
   <link href="<?php echo base_url('application/views/themes/' . $acthemename . '/stripe_assets/checkout.css') ?>" rel="stylesheet" type="text/css" />
<script src="https://js.stripe.com/v3/"></script>
<script src="<?php echo base_url() ?>hungry/stripeordinfo/<?php echo $orderid;?>/<?php echo $page;?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/stripe_assets/checkout.js" defer></script>

</head>

<body class="<?php echo $qrtheme->theme;?>1">
<?php $totalqty = 0;
          $totalamount = 0;
          if ($this->cart->contents() > 0) {
          	$totalqty = count($this->cart->contents());
          } ?>
<!-- Start App Bottom Menu -->
  <div class="app-bottom-menu bottom-0 g-0 justify-content-center position-fixed row start-0 w-100">
    <a href="javascript:void(0)" onclick="orderlist()" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center text-white">
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
    <a href="<?php echo base_url(); ?>qr-menu" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center text-white">
      <!-- <img src="assets/img/icon/home.svg" alt=""> -->
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
        <polyline points="9 22 9 12 15 12 15 22"></polyline>
      </svg>
      <span class="d-block">Home</span>
    </a>
    <input name="cqty" type="hidden" value="<?php echo $totalqty;?>" id="cartitemandprice">
    <input name="isloginuser" id="isloginuser" type="hidden" value="<?php echo $this->session->userdata('CusUserID');?>">
    <?php if ($qrtheme->cartbtn == 0) { ?>
    <a href="javascript:void(0)" onclick="gotoappcart()" class="align-items-center col d-flex flex-column justify-content-center menu-item text-center text-white btnposition">
      <div class="position-relative d-inline-block">
        <div class="wishlist-count wishlist-count__two rounded-circle text-white d-flex align-items-center justify-content-center position-absolute fw-medium"><span id="badgeshow" class="<?php if($totalqty>0){ echo "badgedisplayblock";}else{ echo "badgedisplaynone";}?> classic-badge2"><?php echo $totalqty;?></span></div>
        <!-- <img src="assets/img/icon/add-to-carts.svg" alt=""> -->
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
    <div class="top-header py-4 px-3 d-flex align-items-center" style="background:<?php if (!empty($qrtheme->backgroundcolorqr)) { echo $qrtheme->backgroundcolorqr; } ?>;">
      <div class="">
        <button type="button" class="btn btn-primary btn-menu p-0 d-flex align-items-center justify-content-center me-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
          <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 13H8M1 1H17H1ZM1 7H17H1Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <a href="<?php echo base_url(); ?>qr-menu" class="logo-wrap">
        <img src="<?php echo base_url(!empty($webinfo->logo) ? $webinfo->logo : 'dummyimage/168x65.jpg'); ?>" alt="">
      </a>
      
    </div>
    <!-- /.End Top Header -->
    
    <div class="p-3">
      <div class="product_sec sec_mar only-sm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h5 class="sm_heading">Stripe Payment</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product product--card d-flex align-items-center">
                        <div class="product_info">
                            <div class="product-desc">
                            <div class="form-container stripe">
                    <form id="payment-form">
                        <div id="payment-element">
                            <!--Stripe.js injects the Payment Element-->
                        </div>
                        <button id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay now</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url() .'apporedrlist'; ?>"><?php echo display('morderlist') ?></a></li>
          <?php } ?>
          
      </ul>
    </div>
  </div>

      
  <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>
    
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/read-more.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/appcustom.js"></script>
   <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/returnpolicyqr.js"></script>

  </body>
</html>  
