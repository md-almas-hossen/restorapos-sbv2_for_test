<?php
if (file_exists(APPPATH . 'modules/meta/assets/data/env')) :
    if ($this->permission->module('meta')->access()) :
?>
        <li class="treeview active">
            <a href="javascript:void(0)">
                <i class="fa fa-facebook-official" aria-hidden="true"></i><span><?php echo display('meta_settings'); ?></span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>

            <ul class="treeview-menu menu-open">
                <?php if ($this->permission->check_label('messenger_settings')->access()) : ?>
                    <li class="treeview">
                        <a href="<?php echo base_url('meta/messenger/config') ?>">
                            <i class="fa fa-hand-o-right"></i>
                            <span><?php echo display('messenger_settings'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($this->permission->check_label('whatsapp_settings')->access()) : ?>
                    <li class="treeview">
                        <a href="<?php echo base_url('meta/whatsapp/config') ?>">
                            <i class="fa fa-hand-o-right"></i>
                            <span><?php echo display('whatsapp_settings'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>
<?php
    endif;
endif;
?>