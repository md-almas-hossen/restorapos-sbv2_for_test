<?php $webinfo = $this->webinfo;
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
<link href="<?php echo base_url('application/views/themes/'.$acthemename.'/assets_web/css/profile.css') ?>"
    rel="stylesheet" type="text/css" />
<div class="modal fade" id="updatep" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-green text-white" <?php echo $bg_color_css;?>>
                <h5 class="modal-title" id="exampleModalLabel"><?php echo display('update'); ?></h5>
                <button type="button" class="btn btn-transparent btn_close p-0 text-white" data-bs-dismiss="modal"
                    aria-label="Close"> <i class="ti-close"></i> </button>
            </div>
            <div class="modal-body ">
                <?php echo form_open_multipart('hungry/updateprofile', array('id' => 'profileupdate')) ?>
                <input type="hidden" name="Customerid" class="form-control" id="Customerid"
                    value="<?php echo $customerinfo->customer_id; ?>">
                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label"><?php echo display('name') ?></label>
                    <div class="col-sm-8"><input type="text" name="customer_name" class="form-control"
                            id="customer_name" placeholder="<?php echo display('name') ?>"
                            value="<?php echo (!empty($customerinfo->customer_name) ? $customerinfo->customer_name : null) ?>"
                            required></div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label"><?php echo display('mobile') ?></label>
                    <div class="col-sm-8"><input type="text" name="mobile" class="form-control" id="mobile"
                            placeholder="<?php echo display('mobile') ?>"
                            value="<?php echo (!empty($customerinfo->customer_phone) ? $customerinfo->customer_phone : null) ?>"
                            required></div>
                </div>
                <div class="form-group row">
                    <label for="mail" class="col-sm-4 col-form-label"><?php echo display('email') ?></label>
                    <div class="col-sm-8"><input type="email" name="email" class="form-control" id="email"
                            placeholder="<?php echo display('email') ?>"
                            value="<?php echo (!empty($customerinfo->customer_email) ? $customerinfo->customer_email : null) ?>"
                            <?php if (isset($customerinfo->facebook_id) && $customerinfo->facebook_id != null) { ?>
                            <?php } else { ?>readonly="readonly" disabled="disabled" <?php } ?>></div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label"><?php echo display('password') ?></label>
                    <div class="col-sm-8"><input type="text" name="password" class="form-control" id="password"
                            placeholder="<?php echo display('password') ?>" value=""></div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label"><?php echo display('profile_picture')?></label>
                    <div class="col-sm-8"><input type="file" name="UserPicture" class="form-control" id="UserPicture">
                    </div>
                    <input type="hidden" name="oldimage" class="form-control" id="oldimage"
                        value="<?php echo $customerinfo->customer_picture; ?>">
                </div>
                <div class="form-group row">
                    <label for="message" class="col-sm-4 col-form-label"><?php echo display('address')?></label>
                    <div class="col-sm-8"><textarea class="form-control" name="address" id="address" rows="3"
                            placeholder="<?php echo display('address')?>"><?php echo $customerinfo->customer_address; ?></textarea>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                    <button type="submit" class="btn btn-success"
                        <?php echo $bg_color_css;?>><?php echo display('submit') ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>
<!--Start Menu Area-->
<section class="m-b-5 mt-5 sect_pad">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-3 col-md-4 sidebar">
                <div class="category_choose p-3 mb-3">
                    <div class="card-header">
                        <div class="text-center"> <img
                                src="<?php echo base_url(!empty($customerinfo->customer_picture) ? $customerinfo->customer_picture : 'assets/img/icons/default.jpg'); ?>"
                                width="100px;" height="100px;" class="img-circle"></div>
                    </div>
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h6 class="panel-title"><a href="<?php echo base_url(); ?>myprofile"
                                        class="accordion-toggle"><?php echo display('my_profile')?></a></h6>
                                <h6 class="panel-title"><a href="<?php echo base_url(); ?>myorderlist"
                                        class="accordion-toggle"><?php echo display('morderlist')?></a></h6>
                                <h6 class="panel-title"><a href="<?php echo base_url(); ?>myoreservationlist"
                                        class="accordion-toggle"><?php echo display('my_reservation')?></a></h6>
                                <h6 class="panel-title"><a href="<?php echo base_url(); ?>hungry/logout"
                                        class="accordion-toggle"><?php echo display('logout')?></a></h6>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="need_booking py-4 px-3 mb-3 text-center">
                    <?php $help = $this->db->select('*')->from('tbl_widget')->where('widgetid', 11)->get()->row(); ?>
                    <h6 class="mb-3"><?php echo $help->widget_title; ?></h6>
                    <div class="need_booking_inner">
                        <?php echo $help->widget_desc; ?>
                    </div>
                </div>

            </div>
            <div class="col-xl-9 col-md-8">
                <div class="col-sm-12 col-md-12 rating-block myinfotable">
                    <span class="profile_font25px text-center"><?php echo display('positional_information')?></span>
                    <table class="table table-hover" width="100%">
                        <tbody>
                            <tr>
                                <th><a class="btn btn-success btn-sm search profile_fff" <?php echo $bg_color_css;?>
                                        data-bs-toggle="modal"
                                        data-bs-target="#updatep"><strong><?php echo display('profile_update')?></strong></a>
                                </th>
                                <td></td>
                            </tr>
                            <tr>
                                <th><?php echo display('name')?></th>
                                <td><?php echo $customerinfo->customer_name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo display('email') ?></th>
                                <td><?php echo $customerinfo->customer_email; ?></td>
                            </tr>

                            <tr>
                                <th><?php echo display('phone')?></th>
                                <td><?php echo $customerinfo->customer_phone; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo display('address')?></th>
                                <td><?php echo $customerinfo->customer_address; ?></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>
<!--End Menu Area