<div class="row meta-config-area" id="meta-config-area">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>

            <div class="panel-body meta-config">
                <?php $this->view('common/wa_progressive_tab') ?>

                <form method="post" action="<?php echo base_url('meta/whatsapp/set_config'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <?php $this->view('common/message') ?>

                            <div class="form-group">
                                <label for="biz_phone_no" class="form-label"><?php echo display('wa_biz_phone_no') ?> *</label>
                                <input name="waBusinessPhoneNumber" class="form-control" type="text" placeholder="<?php echo display('wa_biz_phone_no') ?>" id="biz_phone_no" value="<?php echo $wa_config->getConfig('waBusinessPhoneNumber') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="access_token" class="form-label"><?php echo display('wa_access_token') ?> *</label>
                                <input name="waAccessToken" class="form-control" type="text" placeholder="<?php echo display('wa_access_token') ?>" id="access_token" value="<?php echo $wa_config->getConfig('waAccessToken') ?>" required />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-md m-b-5">Next</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>