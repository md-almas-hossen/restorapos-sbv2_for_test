<div class="row">
    <div style="margin-bottom: 10px" class="col-sm-12 col-md-12">
        <div class="btn-group pull-right form-inline">
            <?php if ($this->permission->method('accounts', 'read')->access()): ?>
            <div class="form-group">
                <a href="<?php echo base_url("accounts/AccPredefinedController/predefined_accounts") ?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('predefined_accounts'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-12  mb-20">
        <?php // echo include($this->load->view('accounts/header/voucher_header')) 
        ?>
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading panel-aligner">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>
            <div class="panel-body" style="margin-top: 10px;">
                <?php echo  form_open_multipart('accounts/AccPredefinedController/predefined_update/' . $predefineSettings->id) ?>
                <div class="col-md-6">
                    <div class="form-group mb-2 mx-0 row">
                        <label for="acc_coa_id"
                            class="col-sm-3 col-form-label ps-0"><?php echo display('predefined_name'); ?><span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" name="predefined_seeting_name" id="predefined_seeting_name"
                                value="<?php echo $predefineSettings->predefined_seeting_name; ?>" required />
                        </div>
                    </div>
                    <div class="form-group mb-2 mx-0 row">
                        <label for="acc_coa_id"
                            class="col-sm-3 col-form-label ps-0"><?php echo display('description'); ?></label>
                        <div class="col-lg-9">
                            <textarea class="form-control"
                                name="predefined_seeting_description"><?php echo $predefineSettings->predefined_seeting_description; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-2 mx-0 row">
                        <label for="status" class="col-sm-3 col-form-label"><?php echo display('status') ?> <i
                                class="text-danger">*</i></label>
                        <div class="col-sm-9">
                            <label class="radio-inline my-1">
                                <input type="radio" name="is_active" value="1"
                                    <?php echo ($predefineSettings->is_active == 1 ? 'checked' : ''); ?> id="is_active"
                                    required>
                                <?php echo display('active') ?>
                            </label>
                            <label class="radio-inline my-2">
                                <input type="radio" name="is_active" value="0"
                                    <?php echo ($predefineSettings->is_active == 0 ? 'checked' : ''); ?> id="is_active"
                                    required>
                                <?php echo display('inactive') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 mx-0 row">
                        <label for="predefined_name"
                            class="col-sm-3 col-form-label ps-0"><?php echo display('predefined_accounts'); ?><span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="predefined_id" id="predefined_id" class="form-control select-basic-single"
                                required>
                                <option value=""><?php echo  display('select_one'); ?></option>
                                <?php foreach ($predefineCode as $key => $predefine) { ?>
                                <option value="<?php echo  $predefine->id; ?>"
                                    <?php echo ($predefineSettings->predefined_id == $predefine->id ? 'selected' : ''); ?>>
                                    <?php echo $predefine->predefined_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-2 mx-0 row">
                        <label for="coa_head"
                            class="col-sm-3 col-form-label ps-0"><?php echo display('coa_head'); ?><span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="acc_coa_id" id="acc_coa_id" class="form-control select-basic-single" required>
                                <option value=""><?php echo  display('select_one'); ?></option>
                                <?php foreach ($allheads as $key => $allhead) { ?>
                                <option value="<?php echo  $allhead->id; ?>"
                                    <?php echo ($predefineSettings->acc_coa_id == $allhead->id ? 'selected' : ''); ?>>
                                    <?php echo $allhead->account_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success submit_button pull-right"
                        id="create_submit"><?php echo display('update'); ?></button>
                </div>


                <?php echo  form_close() ?>
            </div>
        </div>
    </div>
</div>