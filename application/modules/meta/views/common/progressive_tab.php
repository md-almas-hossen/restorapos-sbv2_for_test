<div class="progressive_nav_tab">
    <ul class="nav nav-tabs progressive_tab">
        <?php
    
        $isActive = false;
        $isDisabled = false;
        $progessing_tabs = [
            'app' => 'meta_app_config',
            'page' => 'facebook_page_config',
            'messaging' => 'messenger_messenging_config',
            'wit_ai' => 'wit_ai_config'
        ];
    
        foreach ($progessing_tabs as $t_key => $tab) :
            ($isActive = $t_key == $type) && $isDisabled = true;
        ?>
            <li class="<?php echo $isActive ? 'active' : ($isDisabled ? 'disabled' : ''); ?>">
                <a href="<?php echo !$isDisabled ? base_url('meta/messenger/config/' . $t_key) : 'javascript:;'; ?>">
                    <?php echo display($tab); ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</div>