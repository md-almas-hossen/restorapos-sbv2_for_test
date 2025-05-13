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

                <form method="post" action="<?php echo base_url('meta/messenger/set_config/messaging'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <?php $this->view('common/message') ?>

                            <div class="form-group">
                                <label for="get_started" class="form-label"><?php echo display('get_started_text') ?> *</label>
                                <textarea name="getStartedText" class="form-control" placeholder="<?php echo display('get_started_text') ?>" id="get_started" rows="4" required><?php echo $meta_config->getConfig('getStartedText') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="browse_menu_label" class="form-label"><?php echo display('browse_menu_label') ?> *</label>
                                <input name="browseMenuLabel" class="form-control" type="text" placeholder="<?php echo display('browse_menu_label') ?>" id="browse_menu_label" value="<?php echo $meta_config->getConfig('browseMenuLabel') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="order_confirmed" class="form-label"><?php echo display('order_confirmed_text') ?> *</label>
                                <textarea name="orderConfirmedText" class="form-control" placeholder="<?php echo display('order_confirmed_text') ?>" id="order_confirmed" rows="4" required><?php echo $meta_config->getConfig('orderConfirmedText') ?></textarea>
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