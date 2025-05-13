<?php $webinfo = $this->webinfo;
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;

// Dynamic Css apply
$text_color_css = "style='color: green'";
$button_color_css = "style='background-color: green'";
if(isset($color_setting->web_text_color)){
    $text_color_css = "style='color: $color_setting->web_text_color !important'";
    $button_color_css = "style='background-color: $color_setting->web_button_color !important'";
}

?>

<!-- Dynamic Css apply -->
<?php
    $btn_color_css = "#1aa25a !important";
    if(isset($color_setting->web_text_color)){
        $btn_color_css = $color_setting->web_button_color.' !important';
?>
<style>
    .nav-pills .nav-link.active {
        background-color: <?php echo $btn_color_css;?>;
    }
</style>
<?php }?>

<span class="rightSidebar_inner"></span>
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
	<?php $abouttop = $this->db->select('*')->from('tbl_widget')->where('widgetid', 25)->where('status', 1)->get()->row();
if (!empty($abouttop)) {
	$title=explode('-',$abouttop->widget_title);
?>

	<!--Start About Us-->
    <section class="sec_pad">
        <div class="container-xxl">
            <div class="align-items-center row reverse-lg">
                <div class="col-lg-5 wow fadeIn">
                    <div class="bg-white me-xl-n90 mt-0 mt-5 mt-xl-0 p-0 p-xl-5 position-relative rounded-3 xl_shadow">
                        <h5 class="fw-600 mb-3 text-green text-uppercase" <?php echo $text_color_css;?>><?php echo $abouttop->widget_name;?></h5>
                        <h2 class="fw-bold my-3 text-capitalize fs-40 lh-50"><?php echo $title['0'];?> <br><span class="text-green" <?php echo $text_color_css;?>><?php echo $title['1'];?></span></h2>
                        <div class="mb-0"><?php echo $abouttop->widget_desc;?></div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                    <?php foreach ($banner_modern as $image) { ?>
                        <div class="col-sm-6">
                            <div class="wow fadeIn rounded-3">
                                <img src="<?php echo base_url(!empty($image->image) ? $image->image : 'dummyimage/363x363.jpg'); ?>" class="img-fluid rounded-10" alt="<?php echo $image->title;?>">
                            </div>
                        </div>
                         <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End About Us-->
    <?php } ?>
    
    <div class="bg_img position-relative">
    	<div class="container-xxl">
    		<div class="position-relative row reverse-lg">
    			<div class="col-xl-7">
    				<div class="d-block py-5 text-center">
    					<h2 class="fs-36 fw-bold mb-4 text-capitalize text-white"><?php echo $banner_middle->title;?> <span class="text-green" <?php echo $text_color_css;?>><?php echo $banner_middle->subtitle;?></span></h2>
    					<a href="<?php echo $banner_middle->slink;?>" class="bg-green btn px-5 py-3 rounded-pill text-white text-uppercase" <?php echo $button_color_css;?>><?php echo display('seeallmenu') ?></a>
    				</div>
    			</div>
    			<div class="col-xl-5">
    				<div class="position-relative h-100">
						<div class="block_img text-center">
							<img src="<?php echo base_url(!empty($banner_middle->image) ? $banner_middle->image : 'dummyimage/363x363.jpg'); ?>" class="img-fluid" alt="<?php echo $banner_middle->title;?>">
						</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
	<?php $aboutbottom = $this->db->select('*')->from('tbl_widget')->where('widgetid', 26)->where('status', 1)->get()->row();
if (!empty($aboutbottom)) {
?>
	<!--Start About Us-->
    <section class="sec_pad">
        <div class="container-xxl">
            <div class="row align-items-center">
                <div class="col-xl-7">
                    <div class="row g-3">
                        <?php foreach ($banner_modern as $image) { ?>
                        <div class="col-sm-6">
                            <div class="wow fadeIn rounded-3">
                                <img src="<?php echo base_url(!empty($image->image) ? $image->image : 'dummyimage/363x363.jpg'); ?>" class="img-fluid rounded-10" alt="<?php echo $image->title;?>">
                            </div>
                        </div>
                         <?php } ?>
                    </div>
                </div>
                <div class="col-xl-5 wow fadeIn">
                    <div class="bg-white ms-xl-n90 mt-0 mt-5 mt-xl-0 p-0 p-xl-5 position-relative rounded-3 xl_shadow">
                        <h6 class="fw-400 mb-3 text-green text-uppercase" <?php echo $text_color_css;?>><?php echo $aboutbottom->widget_name;?></h6>
                        <h2 class="fw-bold my-3 text-capitalize"><?php echo $aboutbottom->widget_title;?></h2>
                        <?php echo $aboutbottom->widget_desc;?>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End About Us-->
    <?php } ?>