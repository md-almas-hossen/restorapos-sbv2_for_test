<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('qr_setting');?></h4>
                </div>
            </div>
            <?php 			
				echo form_open_multipart('qrapp/qrmodule/saveqrsetting','class="form-inner"') ?>
            <?php echo form_hidden('id',$qrsetting->id) ?>
            <div class="panel-body">
                <div class="row border-btm m-b-10">
                    <div class="col-md-4">
                        <div class="checkbox checkbox-success">
                            <input id="chkbox-image" type="checkbox" class="individual qrsetting" name="image" value="1"
                                <?php if($qrsetting->image==1){ echo "checked";}?> />
                            <label class="fw-700" style="font-weight: 700;"
                                for="chkbox-image"><?php echo display('item_image');?></label>
                        </div>
                        <div class="checkbox checkbox-success">
                            <input id="chkbox-cart" type="checkbox" class="individual qrsetting" name="cartbtn"
                                value="1" <?php if($qrsetting->cartbtn==1){ echo "checked";}?> />
                            <label class="fw-700" style="font-weight: 700;"
                                for="chkbox-cart"><?php echo "Hide Cart Button";?></label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 ps-0"><?php echo display('change_theme') ?></label>
                            <div class="col-sm-7">
                                <select name="theme" class="form-control" id="theme">
                                    <option value=""><?php echo display('select')?></option>
                                    <option value="bg-light"
                                        <?php if($qrsetting->theme=="bg-light"){ echo "selected";}?> class='bolden'>
                                        Light Mode</option>
                                    <option value="dark-theme"
                                        <?php if($qrsetting->theme=="dark-theme"){ echo "selected";}?>>Dark Mode
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group row">
                            <label for="example-color-input"
                                class="col-sm-5 col-form-label"><?php echo display('select_header_color') ?></label>
                            <div class="col-sm-7">
                                <input name="headercolor" class="form-control" type="color"
                                    value="<?php echo $qrsetting->backgroundcolorqr ?>" id="headercolor" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-color-input"
                                class="col-sm-5 col-form-label"><?php echo display('select_header_font_color') ?></label>
                            <div class="col-sm-7">
                                <input name="headerfontcolor" class="form-control" type="color"
                                    value="<?php echo $qrsetting->qrheaderfontcolor ?>" id="headerfontcolor" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-color-input"
                                class="col-sm-5 col-form-label"><?php echo display('select_qr_button_color') ?></label>
                            <div class="col-sm-7">
                                <input name="qrbuttoncolor" class="form-control" type="color"
                                    value="<?php echo $qrsetting->qrbuttoncolor ?>" id="qrbuttoncolor" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-color-input"
                                class="col-sm-5 col-form-label"><?php echo display('select_qr_button_hover_color') ?></label>
                            <div class="col-sm-7">
                                <input name="qrbuttonhovercolor" class="form-control" type="color"
                                    value="<?php echo $qrsetting->qrbuttonhovercolor ?>" id="qrbuttonhovercolor" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-color-input"
                                class="col-sm-5 col-form-label"><?php echo display('select_qr_button_text_color') ?></label>
                            <div class="col-sm-7">
                                <input name="qrbuttontextcolor" class="form-control" type="color"
                                    value="<?php echo $qrsetting->qrbuttontextcolor ?>" id="qrbuttontextcolor" />
                            </div>
                        </div>
                    </div>




                    <div class="col-md-3">
                        <div class="checkbox checkbox-success">
                            <input id="chkbox-review" type="checkbox" class="individual qrsetting" name="isreview"
                                value="1" <?php if($qrsetting->isactivereview==1){ echo "checked";}?> />
                            <label class="fw-700" style="font-weight: 700;"
                                for="chkbox-review"><?php echo display('inactive_google_review') ?></label>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea"><?php echo display('insert_google_review_code') ?></label>
                            <textarea class="form-control" id="exampleTextarea" rows="3"
                                name="review_code"><?php echo $qrsetting->review_code; ?></textarea>
                            <a href="https://dash.elfsight.com/apps/google-reviews"
                                target="_new"><?php echo display('get_your_google_review_code') ?></a>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success  pull-right addBtn"><?php echo display('save') ?></button>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>