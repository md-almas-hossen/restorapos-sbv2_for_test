<div class="row">
    <div class="col-sm-12">
        <div style="margin-bottom: 10px" class="btn-group pull-right form-inline">
            <?php if ($this->permission->method('accounts', 'create')->access()): ?>
            <div class="form-group">
                <a href="<?php echo base_url("accounts/AccPredefinedController/predefined_form") ?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                    <?php echo display('create_predefined'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading panel-aligner">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php elseif ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <table width="100%" class="table table-striped table-bordered table-hover" id="predefined_list">
                    <thead>
                        <tr>
                            <th><?php echo display('sl_no') ?></th>
                            <th><?php echo display('predefined_name') ?></th>
                            <th><?php echo display('description') ?></th>
                            <th><?php echo display('predefined_accounts') ?></th>
                            <th><?php echo display('coa_head') ?></th>
                            <th><?php echo display('create_by_id') ?></th>
                            <th><?php echo display('create_date') ?></th>
                            <th><?php echo display('status') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/accounts/assets/predefined/predefined.js'); ?>"
    type="text/javascript"></script>