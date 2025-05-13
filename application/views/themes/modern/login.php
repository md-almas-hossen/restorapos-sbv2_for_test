 <?php $webinfo = $this->webinfo;
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;

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
<span class="rightSidebar_inner"></span>
<div class="modal fade" id="lostpassword" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-green text-white" <?php echo $bg_color_css;?>>
                  <h5 class="modal-title" id="loginModalLabel"><?php echo display('forgot_password'); ?></h5>
                  <button type="button" class="btn btn-transparent btn_close p-0 text-white" data-bs-dismiss="modal" aria-label="Close"> <i class="ti-close"></i> </button>
                </div>
                <div class="modal-body p-3 passwordupdate">
                    <div class="form-group">
                         <label class="control-label" for="user_email"><?php echo display('please_enter_your_email')?></label>
                         <input type="text" id="user_email2" class="form-control" name="user_email2">
                     </div>
                </div>
                <div class="modal-footer">
              <a type="button" onclick="lostpassword();" class="btn btn-sm btn-dark px-3 py-2 lost-pass"><?php echo display('submit')?></a>
            </div>
			</div>
		</div>
	</div>
 
 <div class="page_header position-relative">
		<div class="top-0 start-0 p-0 p-sm-5 position-absolute">
			<img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/page_header-left.png" alt="">
		</div>
		<div class="bottom-0 end-0 p-0 p-sm-5 position-absolute d-none d-sm-block">
			<img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/page_header-right.png" alt="">
		</div>
		<div class="container wow fadeIn">
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="page_header_content text-center sect_pad">
						<h1 class="fw-bold mb-0"><?php echo $seoinfo->title; ?></h1>
						<ul class="m-0 nav pt-0 pt-lg-2 justify-content-center">
							<li class="px-1"><a href="<?php echo base_url(); ?>" class="fw-600"><?php echo display('home')?></a></li>
							<li class="px-1">-</li>
							<li class="active px-1 text-green" <?php echo $text_color_css;?>><a class="fw-600"><?php echo $seoinfo->title; ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
 <!--Start Login Area-->
 <section class="sect_pad">
     <div class="container-xxl">
     	 
         <div class="row p-4">
             <div class="panel-body">
                 <p><?php echo display('shopping_details_information_msg')?></p>
                 <div class="row">
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label class="control-label" for="user_email"><?php echo display('email') ?></label>
                             <input type="text" id="user_email" class="form-control" name="user_email" value="">
                         </div>
                     </div>
                     <div class="col-sm-6">
                         <div class="form-group">
                             <label class="control-label" for="u_pass"><?php echo display('password') ?> <abbr class="required" title="required">*</abbr></label>
                             <input type="password" id="u_pass" class="form-control" name="u_pass" value="">
                         </div>
                     </div>
                     <div class="col-sm-12">
                         <div class="checkbox checkbox-success">
                             <input id="brand1" type="checkbox" name="isremember" value="1">
                             <label for="brand1"><?php echo display('remember_me')?></label>
                             <a href="javascript:void(0);" class="lost-pass classic-lostpass" data-bs-toggle="modal" data-bs-target="#lostpassword"><u><?php echo display('forgot_password')?></u></a>
                         </div>
                         <a class="btn bg-green text-white px-5 py-2 btn-switch" <?php echo $bg_color_css;?> onclick="logincustomer();"><?php echo display('login')?></a>&nbsp; <?php echo display('or')?> &nbsp;<a href="<?php echo base_url() . 'hungry/signup' ?>" class="btn bg-green text-white px-5 py-2 btn-switch" <?php echo $bg_color_css;?>><?php echo display('register')?></a><?php $facrbooklogn = $this->db->where('directory', 'facebooklogin')->where('status', 1)->get('module')->num_rows(); if ($facrbooklogn == 1) { ?>&nbsp; <?php echo display('or')?> &nbsp;
                         <a class="btn bg-green text-white px-5 py-2 btn-switch" <?php echo $bg_color_css;?> href="<?php echo base_url('facebooklogin/facebooklogin/index/1') ?>"><i class="fa fa-facebook pr-1"></i><?php echo display('facebook_login') ?></a>
                     <?php } ?>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!--End Login Area-->
  <?php 
 $webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;?>
 <!--End Login Area-->
 <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/login.js"></script>
 