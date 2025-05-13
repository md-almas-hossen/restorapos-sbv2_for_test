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
   
   <link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
   <script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>

</head>

<body>
  
  <!-- Modal: Search Activity -->
  
  <!-- Modal: Item Details -->
  
  <!-- Start offcanvas --> 

  
  
  <div class="content-wrap" style="height: 100vh;">
    <!-- Start Top Header -->
    
    <!-- /.End Top Header -->
    
        <div class="p-3 gap-3" style="display: flex;justify-content: center;align-content: center;height: 100%;flex-wrap: wrap">
    	<div style="display: block;width: 100%;text-align: center;"><a href="<?php echo base_url()?>qr-menu" style="min-width:220px; font-size:26px;" class="btn btn-success align-items-center justify-content-center" >Go To Menu</a></div>
        <div style="display: block;width: 100%;text-align: center;"><a style="min-width:220px; font-size:26px;" href="<?php echo base_url()?>google-review" class="align-items-center btn btn-outline-success justify-content-center" >Google Review</a></div>
    	
    </div>
    
    
  </div>




	
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