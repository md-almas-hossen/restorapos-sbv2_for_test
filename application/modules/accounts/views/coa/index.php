<link href="<?php echo  base_url('application/modules/accounts/assets/coa/vakata-jstree/dist/themes/default/style.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('application/modules/accounts/assets/coa/jqueryui/jquery-ui.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('application/modules/accounts/assets/coa/css/dailog.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo  base_url('application/modules/accounts/assets/css/bootstrapClass.css'); ?>">

<?php // echo include('backend.layouts.common.message'); 
?>
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><?php echo display('c_o_a'); ?></h3>
            </div>
            <div class="text-start">


            </div>
        </div>
    </div>

    <div class="card-body">


        <?php echo include($this->load->view('accounts/coa/subblade/confirm')); ?>
        <div class="row">

            <div class="col-md-6" style="border:1px solid #eee;padding:12px">
                <div class="search mb-2">
                    <div class="search__inner tree-search">
                        <input id="treesearch" type="text" class="form-control search__text" placeholder="Tree Search..." autocomplete="off">
                        <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                    </div>
                </div>
                <?php echo include($this->load->view('accounts/coa/subblade/coatree')); ?>


            </div>
            <div class="col-md-6" style="border:1px solid #eee;padding:12px">
                <?php echo include($this->load->view('accounts/coa/subblade/coaform')); ?>
            </div>

        </div>



    </div>
    <input type="hidden" id="url" value="<?php echo  base_url('') ?>" />
    <input type="hidden" id="accsubType" value='<?php echo json_encode($accSubType, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>' />
</div>

<script src="<?php echo  base_url('application/modules/accounts/assets/coa/vakata-jstree/dist/jstree.min.js?v=1') ?>"></script>
<script src="<?php echo  base_url('application/modules/accounts/assets/coa/js/tree-view.active.js?v=1') ?>"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/coa/js/account.js?v=1') ?>"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/coa/jqueryui/jquery-ui.min.js?v=1') ?>"></script>