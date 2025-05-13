<?php $webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}

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
<!--PAGE HEADER AREA-->
<div class="page_header position-relative">
    <div class="top-0 start-0 p-0 p-sm-5 position-absolute">
        <img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/page_header-left.png"
            alt="">
    </div>
    <div class="bottom-0 end-0 p-0 p-sm-5 position-absolute d-none d-sm-block">
        <img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/page_header-right.png"
            alt="">
    </div>
    <div class="container wow fadeIn">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page_header_content text-center sect_pad">
                    <h1 class="fw-bold mb-0"><?php echo $seoinfo->title; ?></h1>
                    <ul class="m-0 nav pt-0 pt-lg-2 justify-content-center">
                        <li class="px-1"><a href="<?php echo base_url(); ?>"
                                class="fw-600"><?php echo display('home')?></a></li>
                        <li class="px-1">-</li>
                        <li class="active px-1 text-green" <?php echo $text_color_css;?>><a
                                class="fw-600"><?php echo $seoinfo->title; ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--PAGE HEADER AREA END-->

<div class="sec_pad">
    <div class="container-xxl">
        <div class="row g-3 g-xl-5">
            <div class="col-lg-5">
                <img src="<?php echo base_url(!empty($reservation_modern->image) ? $reservation_modern->image : 'dummyimage/363x363.jpg'); ?>"
                    class="img-fluid" alt="<?php echo $reservation_modern->title;?>">
            </div>
            <div class="col-lg-7">
                <div
                    class="align-items-center bg-white contact_form d-flex flex-wrap h-100 mt-4 mt-lg-0 p-3 p-sm-5 rounded-10 shadow position-relative">
                    <div class="bottom-0 end-0 position-absolute p-3">
                        <img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/page_header-left.png"
                            alt="">
                    </div>
                    <div class="d-block">
                        <h6 class="mb-0 fw-600 text-green" <?php echo $text_color_css;?>>Table</h6>
                        <h4 class="mb-4">Booking</h4>
                    </div>
                    <?php echo form_open('#','method="post"')?>
                    <?php if ($this->session->flashdata('message')) { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('message') ?>
                    </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('exception')) { ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('exception') ?>
                    </div>
                    <?php } ?>
                    <?php if (validation_errors()) { ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo validation_errors() ?>
                    </div>
                    <?php } ?>
                    <div class="row g-3">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control p-3" name="Name" id="Name"
                                placeholder="<?php echo display('name')?>" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" class="form-control p-3" name="email" id="email"
                                placeholder="<?php echo display('email')?>" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control p-3" name="reservation_contact"
                                id="reservation_contact" placeholder="<?php echo display('reservation_contact')?>"
                                autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control p-3 datepicker" id="reservation_date"
                                name="reservation_date" placeholder="<?php echo display('reservation_date')?>"
                                autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control p-3" name="reservation_person" onKeyup="maxperson()"
                                id="reservation_person" placeholder="<?php echo display('reservation_person')?>"
                                autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control p-3" name="reservation_time" id="reservation_time"
                                placeholder="<?php echo display('reservation_time')?>" autocomplete="off">
                        </div>
                        <div class="form-group col-md-12">
                            <input name="checkurl" id="checkurl" type="hidden"
                                value="<?php echo base_url("hungry/checkavailablity"); ?>" />
                            <button type="button" onclick="editreserveinfo()"
                                class="bg-green btn position-relative px-5 py-3 text-white z-index-5"
                                <?php echo $bg_color_css;?>><?php echo display('sendymsg')?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <div class="g-5 mt-5 mx-0 rounded-10 row table_chart_inner" id="searchreservation"
            style="box-shadow: 0 0.5rem 3rem rgba(0,0,0,.1)!important; margin-top: 5rem!important;">
            <!--<div class="col-lg-12" id="addmargind"></div>-->
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?php echo form_open('hungry/bookreservation',array('id'=>'reservesubmit')) ?>
        <div class="modal-content">

            <div class="modal-header bg-green text-white" <?php echo $bg_color_css;?>>
                <h5 class="modal-title" id="addtocartModalLabel"><?php echo display('reserve_table')?></h5>
                <button type="button" class="btn btn-transparent btn_close p-2 text-white" data-bs-dismiss="modal"
                    aria-label="Close"> <i class="ti-close"></i> </button>
            </div>
            <div class="modal-body p-4 editinfo">
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-warning me-2"><?php echo display('reset') ?></button>
                <button type="button" class="btn btn-success" <?php echo $bg_color_css;?>
                    onclick="submitreserve()"><?php echo display('confirm_reservation') ?></button>
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</div>
<!--End Table Chart-->