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
<!--PAGE HEADER AREA-->
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
	<!--PAGE HEADER AREA END-->
	
	<div class="sec_pad">
        <div class="container-xxl">
            <div class="row mb-7 g-3">
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center">
                      	<div class="icon-part rounded-circle text-center">
                      		<i class="fas fa-map-marker-alt fs-2 text-green" <?php echo $text_color_css;?>></i>
                      	</div>                        
                        <div class="ps-3">
                           <?php echo $webinfo->address; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center">
                      	<div class="icon-part rounded-circle text-center">
                      		<i class="fas fa-phone-alt fs-2 text-green" <?php echo $text_color_css;?>></i>
                      	</div>
                        <div class="ps-3">
                            <?php echo $webinfo->phone; ?> <br>
                            <?php echo $webinfo->phone_optional; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center">
                      	<div class="icon-part rounded-circle text-center">
                      		<i class="fas fa-envelope-open-text fs-2 text-green" <?php echo $text_color_css;?>></i>
                      	</div>
                        <div class="ps-3">
                             <?php echo $webinfo->email; ?> <br>
                             <?php echo $webinfo->email; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-6">
                  	<div class="border h-100 p-3 rounded-10">
                    <?php $googlemap = $this->db->select('*')->from('tbl_widget')->where('widgetid', 14)->where('status', 1)->get()->row();
						if (!empty($googlemap)) {
					?>
                  		<div class="map"><?php echo htmlspecialchars_decode($googlemap->widget_desc); ?></div>
                        <?php } ?>
                  	</div>                    
                </div>
                <div class="col-lg-6">
                    <div class="contact_form">
                    	<?php if ($this->session->flashdata('message')) { ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('message') ?>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('exception')) { ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('exception') ?>
                        </div>
                    <?php } ?>
                    <?php if (validation_errors()) { ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors() ?>
                        </div>
                    <?php }
                    $contactsms = $this->db->select('*')->from('tbl_widget')->where('widgetid', 23)->where('status', 1)->get()->row();
                    ?>
                        <h5 class="mb-3 fw-600"><?php echo display('send_us')?></h5>
                        <p class="mb-4"><?php if (!empty($contactsms)) {?><?php echo $contactsms->widget_desc; ?><?php } ?></p>
                        <?php echo form_open('hungry/sendemail','method="post"')?>
                            <div class="row g-3">
                                <div class="form-group col-md-6">
                                    <input type="text" class="border-0 form-control px-3 py-3 bg-grey" id="firstname" name="firstname" autocomplete="off" placeholder="<?php echo display('name') ?>">
                                    <input type="hidden" id="lastname" name="lastname" autocomplete="off">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="border-0 form-control px-3 py-3 bg-grey" id="email" name="email" autocomplete="off" placeholder="<?php echo display('email') ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="text" class="border-0 form-control px-3 py-3 bg-grey" id="phone" name="phone" autocomplete="off" placeholder="<?php echo display('phone') ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea class="border-0 form-control px-3 py-3 bg-grey" id="comments" name="comments" commentsrows="4" placeholder="<?php echo display('write_comments')?>"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="bg-green btn px-5 py-3 text-white" <?php echo $bg_color_css;?>><?php echo display('submit') ?></button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Area -->