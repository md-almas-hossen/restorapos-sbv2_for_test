<?php $webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
if ($title != "Menu") {
	$this->session->unset_userdata('product_id');
	$this->session->unset_userdata('categoryid');
}
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}
/*for whatsapp modules*/
$WhatsApp = $this->db->where('directory', 'whatsapp')->where('status', 1)->get('module');
$whatsapp_count = $WhatsApp->num_rows();
/*end whatsmoudles*/
$defaultship=$this->session->userdata('shippingid');
$shiptype=$this->session->userdata('shiptype');
if(empty($shiptype)){
	$shipaddress=$this->settinginfo->address;
}else{
	if($shiptype==3){
		$shipaddress="";
	}else{
		$shipaddress=$this->settinginfo->address;
		}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo $seoinfo->description; ?>">
	<meta name="keywords" content="<?php echo $seoinfo->keywords; ?>">

	<title><?php echo $seoinfo->title; ?></title>
	<link rel="shortcut icon" type="image/ico" href="<?php echo base_url((!empty($this->settinginfo->favicon) ? $this->settinginfo->favicon : 'application/views/themes/' . $acthemename . '/assets_web/images/favicon.png')) ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/fontawesome/css/all.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/themify-icons/themify-icons.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/animate-css/animate.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/catDrop/cat-drop.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/style.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/custome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/modal.css" rel="stylesheet">
	<!--====== Custom CSS Files ======-->
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/pos-topping.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/js/product.js.php"></script>
	<script src="<?php echo base_url(); ?>assets/js/category.js.php"></script>
    <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>
	<!-- for whatsapp modules -->
	<?php if ($whatsapp_count  == 1) {
		$whatsapp_data = $WhatsApp->row();
		$whatsapp_url =  str_replace("/images/thumbnail.jpg", "", $whatsapp_data->image);
	?>
		<link href="<?php echo base_url() . $whatsapp_url; ?>/css/floating-wpp.min.css" rel="stylesheet">
		<script src="<?php echo base_url() . $whatsapp_url; ?>/js/floating-wpp.min.js"></script>

	<?php
	}
	
	// Dynamic Css apply
	$text_color_css = "style='color: green'";
	$bg_color_css = "style='background-color: green'";
	$head_bg_color_css = "style='background-color: #212529'";
	$footer_bg_color_css = "style='background-color: #212529'";
	if(isset($color_setting->web_text_color)){
		$text_color_css = "style='color: $color_setting->web_text_color !important'";
		$bg_color_css = "style='background-color: $color_setting->web_button_color !important'";
	}
	if(isset($color_setting->web_header_bg_color)){
		$head_bg_color_css = "style='background-color:  $color_setting->web_header_bg_color !important'";
	}
	if(isset($color_setting->web_footer_bg_color)){
		$footer_bg_color_css = "style='background-color:  $color_setting->web_footer_bg_color !important'";
	}
	
	?>

	<!-- Dynamic Css apply -->
	<?php
		$button_color_css = "#1aa25a !important";
		if(isset($color_setting->web_text_color)){
			$button_color_css = $color_setting->web_button_color.' !important';
	?>
	<style>
		.nav-pills .nav-link.active {
			background-color: <?php echo $button_color_css;?>;
		}
	</style>
	<?php }?>
	
</head>

<body>

	<!-- Food Details Modal -->
	<div class="modal fade" id="addons" tabindex="-1" aria-labelledby="addtocartModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content addonsinfo">
				
			</div>
		</div>
	</div>
	<!-- Mobile Menu -->
	<div class="bg-green bottom-0 d-flex flex-column p-3 position-fixed start-0 text-white top-0 mobileNav" <?php echo $bg_color_css;?>>
		<div class="ms-auto text-white">
			<button class="closeMobileNav">
				<i class="ti-close text-white"></i>
			</button>
		</div>
		<hr>
		<ul class="nav nav-pills flex-column mb-auto">
        	<?php $allmenu = $this->allmenu;
							$myid = $this->session->userdata('CusUserID');
							foreach ($allmenu as $menu) {
								$dropdown = '';
								$dropdownassest = '';
								$dropdownaclass = '';
								$activeclass = '';
								//echo $menu->menu_slug;
								if ($menu->menu_name == 'Home') {
									$activeclass = 'active';
									$href = base_url() . $menu->menu_slug;
								} else {
									$activeclass = '';
									$href = base_url() . $menu->menu_slug;
								}
								if ($menu->menu_slug == 'myprofile') {
									if (empty($myid)) {
										$menu->menu_name = "Login";
										$href = base_url() ."mylogin";
									}
								}
								if(!empty($menu->sub)) {
									$dropdown = 'dropdown';
									$dropdownassest = 'type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"';
									$dropdownaclass = 'dropdown-toggle';
									$href = '#';
								}
							?>
			<li class="nav-item <?php echo $dropdown; ?>">
            	
            	<a class="nav-link px-3 text-white <?php echo $dropdownaclass; ?> <?php echo $activeclass; ?>" href="<?php echo $href; ?>" <?php echo $dropdownassest; ?>><?php echo $menu->menu_name; ?></a>
                <?php if (!empty($menu->sub)) {?>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                	<?php foreach ($menu->sub as $submenu) {
								$menurl = $submenu->menu_slug;
								$menuname = $submenu->menu_name;
								if ($submenu->menu_slug == 'logout') {
									if (empty($myid)) {
										$menurl = "mylogin";
										$menuname = "Login";
									}
								}
					?>
                	<li><a class="dropdown-item" href="<?php echo base_url() . $menurl; ?>"><?php echo $menuname; ?></a></li>
                    <?php } ?>
                </ul>
                <?php } ?>
				
			</li>
            <?php } ?>
			
		</ul>
	</div>

	

	<!-- header -->
	<header class="py-3 bg-dark text-white" <?php echo $head_bg_color_css;?>>
		<div class="container-xxl">
			<div class="d-flex flex-wrap align-items-center justify-content-lg-start">
				<button class="d-block d-lg-none fs-4 me-2 me-sm-3 text-white openMobileNav">
					<i class="ti-menu"></i>
				</button>
				<a href="<?php echo base_url(); ?>" class="d-flex align-items-center text-white text-decoration-none">
					<img src="<?php echo base_url(!empty($webinfo->logo_footer) ? $webinfo->logo_footer : 'dummyimage/168x65.jpg'); ?>" class="logo_img" alt="">
				</a>

				<ul class="col-12 col-lg-auto d-lg-flex d-none justify-content-center mb-2 mb-md-0 me-2 ms-lg-auto nav">
                    <?php $allmenu = $this->allmenu;
							$myid = $this->session->userdata('CusUserID');
							foreach ($allmenu as $menu) {
								$dropdown = '';
								$dropdownassest = '';
								$dropdownaclass = '';
								$activeclass = '';
								//echo $menu->menu_slug;
								if ($menu->menu_name == 'Home') {
									$activeclass = 'active';
									$href = base_url() . $menu->menu_slug;
								} else {
									$activeclass = '';
									$href = base_url() . $menu->menu_slug;
								}
								if ($menu->menu_slug == 'myprofile') {
									if (empty($myid)) {
										$menu->menu_name = "Login";
										$href = base_url() ."mylogin";
									}
								}
								if(!empty($menu->sub)) {
									$dropdown = 'dropdown';
									$dropdownassest = 'type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"';
									$dropdownaclass = 'dropdown-toggle';
									$href = '#';
								}
							?>
					<li class="<?php echo $dropdown; ?>">
                    <a class="nav-link px-3 text-white <?php echo $dropdownaclass; ?> <?php echo $activeclass; ?>" href="<?php echo $href; ?>" <?php echo $dropdownassest; ?>><?php echo $menu->menu_name; ?></a>
                   <?php if (!empty($menu->sub)) {?>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    	<?php foreach ($menu->sub as $submenu) {
								$menurl = $submenu->menu_slug;
								$menuname = $submenu->menu_name;
								if ($submenu->menu_slug == 'logout') {
									if (empty($myid)) {
										$menurl = "mylogin";
										$menuname = "Login";
									}
								}
					?>
                		<li><a class="dropdown-item" href="<?php echo base_url() . $menurl; ?>"><?php echo $menuname; ?></a></li>
                    <?php } ?>
                    </ul>
  					<?php } ?>
                    </li>
                    <?php } ?>
				</ul>

				
			</div>
		</div>
	</header>

	

	

	<?php if (isset($content)) {
		echo $content;
	} ?>
	<!--Footer Area-->
    <div class="bg-dark <?php if($seoterm== "home") {echo "footer-area";}?> py-5" <?php echo $footer_bg_color_css;?>>
		<div class="container-xxl">
			<div class="mt-5 pt-4 row">
            <?php
				$footerfirst = $this->db->select('*')->from('tbl_widget')->where('widgetid', 1)->where('status', 1)->get()->row();
				if (!empty($footerfirst)) {
				?>
				<div class="col-xl-3 col-lg-6">
					<div class="mb-5 mb-xl-0">
						<div class="footer-logo">
                        <a class="d-block mb-5" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(!empty($webinfo->logo_footer) ? $webinfo->logo_footer : 'dummyimage/168x65.jpg'); ?>" alt="" class="logo_img"></a>
						</div>
						<div class="footer-init">
							<div class="text-justify text-white"><?php echo $footerfirst->widget_desc;?></div>
						</div>
						<div class="footer-social-bookmark">
							<ul class="d-flex mb-0 list-unstyled ps-0">
                            <?php 
								foreach ($this->sociallink as $slink) {
								$icon = substr($slink->icon, 4);
							?>
								<li class="me-2"><a href="<?php echo $slink->socialurl; ?>" target="_blank"><i class="text-white fab <?php echo $icon; ?>"></i></a></li>
                                <?php } ?>
							</ul>
						</div>
					</div>
				</div>
                <?php } ?>
				<div class="col-xl-8 offset-xl-1">
					<div class="row">
                    	<?php $footermiddle = $this->db->select('*')->from('tbl_widget')->where('widgetid', 2)->where('status', 1)->get()->row();
							if(!empty($footermiddle)) {
						?>
						<div class="col-xl-4 col-lg-6">
							<div class="text-white mb-5 mb-lg-0">
								<h4 class="mb-5"><?php echo $footermiddle->widget_title; ?></h4>
								<div class="footer_widget_body">
									<?php echo $footermiddle->widget_desc; ?>
								</div>
							</div>
						</div>
                        <?php }
						$footerlast = $this->db->select('*')->from('tbl_widget')->where('widgetid', 3)->where('status', 1)->get()->row();
						if (!empty($footerlast)) {
						?>
						<div class="col-xl-4 col-lg-6">
							<div class="text-white">
								<h4 class="mb-5"><?php echo $footerlast->widget_title; ?></h4>
								<div class="footer_widget_body">
									<div class="footer-address">
										<?php echo $footerlast->widget_desc; ?>
									</div>
								</div>
							</div>
						</div>
                        <?php } ?>
						<div class="col-xl-4 col-lg-6">
							<div class="text-white mt-4 mt-xl-0">
								<h4 class="mb-5"><?php echo display('subscribe_to_newsletter')?></h4>
								<div class="d-flex">
                                	<input type="text" class="form-control py-3 rounded-0" placeholder="Enter Your Email" name="youremail" id="youremail">
									<button class="bg-green btn rounded-0 text-white" <?php echo $bg_color_css;?> type="submit" onClick="subscribeemail()"><?php echo display('subscribe')?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--End Footer Area-->
	<?php
	$openingtimerv = strtotime($this->settinginfo->reservation_open);
	$closetimerv = strtotime($this->settinginfo->reservation_close);
	$compareretime = strtotime(date("H:i:s A"));
	if (($compareretime >= $openingtimerv) && ($compareretime < $closetimerv)) {
		$reservationopen = 1;
	} else {
		$reservationopen = 0;
	}
	?>
	<!-- for whatsapp modules -->
	<?php if ($whatsapp_count  == 1) {
		$whatsapp_data = $WhatsApp->row();
		$whatsapp_url =  str_replace("/images/thumbnail.jpg", "", $whatsapp_data->image);
		$wtapp = $this->db->select('*')->from('whatsapp_settings')->get()->row();
		if($wtapp->chatenable==1){
	?>
		<div id="WAButton"></div>
		<script type="text/javascript">
			$(function() {
				$('#WAButton').floatingWhatsApp({
					phone: '<?php echo $this->settinginfo->whatsapp_number; ?>', //WhatsApp Business phone number
					headerTitle: '<?php echo display('whatsapp_chat') ?>', //Popup Title
					popupMessage: '<?php echo display('hello,_how_can_we_help_you?') ?>', //Popup Message
					showPopup: true, //Enables popup display
					buttonImage: '<img src="<?php echo base_url() . $whatsapp_url; ?>/images/whatsapp.png" />', //Button Image
					//headerColor: 'crimson', //Custom header color
					//backgroundColor: 'crimson', //Custom background button color
					position: "left" //Position: left | right

				});
			});
		</script>

	<?php }
	} ?>
	<!-- end whatsapp modules -->
	<!--====== SCRIPTS JS ======-->
	<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
	<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/owl-carousel/owl.carousel.min.js"></script>
	<?php if($webinfo->ismapenable==1){?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $webinfo->mapapikey;?>&libraries=places"></script>
	<?php } ?>
    <!-- <script type="text/javascript" async="" src="https://maps.google.com/maps/api/js?v=3&amp;key=AIzaSyCLfJcTtMIyuPdaUA-W2w-2uhPYups4u5Y&amp;language=en"></script> -->
    <!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4Hg8op0k4LdOthDxIN6LEWh5BcanJLjA&callback=initMap"></script>-->
    <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC4Hg8op0k4LdOthDxIN6LEWh5BcanJLjA&sensor=false"></script>-->
    <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?callback=__google_maps_api_provider_initializator__&amp;key=AIzaSyBPBMxEWLq8TizF_yBofhmpuBgMayQbyxc"></script>-->
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/wow/wow.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/theia-sticky-sidebar/dist/ResizeSensor.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/theia-sticky-sidebar/dist/theia-sticky-sidebar.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/catDrop/fontfaceobserver.min.js"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/custom.js"></script>
	<script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/select2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/modern_theme.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/catDrop/cat-drop.js"></script>
    <?php if($webinfo->ismapenable==1){?>
	<script>
	function initialize() {
        var input = document.getElementById('location');
        var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
            //   document.getElementById('city2').value = place.name;
            //   document.getElementById('cityLat').value = place.geometry.location.lat();
            //   document.getElementById('cityLng').value = place.geometry.location.lng();
          });
      }
      google.maps.event.addDomListener(window, 'load', initialize);
      
	  function initializemobile() {
        var input = document.getElementById('mlocation');
        var autocomplete = new google.maps.places.Autocomplete(input);
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
          });
      }

	  google.maps.event.addDomListener(window, 'load', initializemobile);
	  </script>
	  <?php } ?>
</body>
</html>