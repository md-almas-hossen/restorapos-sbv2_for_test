<div class="row meta-config-area" id="meta-config-area">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>

            <div class="panel-body meta-config">
                <?php $this->view('common/progressive_tab') ?>

                <form method="post" action="<?php echo base_url('meta/messenger/set_config/app'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <?php $this->view('common/message') ?>

                            <div class="form-group">
                                <label for="app_id" class="form-label"><?php echo display('developer_app_id') ?> *</label>
                                <input name="appId" class="form-control" type="text" placeholder="<?php echo display('developer_app_id') ?>" id="app_id" value="<?php echo $meta_config->getConfig('appId') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="app_secret" class="form-label"><?php echo display('developer_app_secret') ?> *</label>
                                <input name="appSecret" class="form-control" type="text" placeholder="<?php echo display('developer_app_secret') ?>" id="app_secret" value="<?php echo $meta_config->getConfig('appSecret') ?>" required />
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