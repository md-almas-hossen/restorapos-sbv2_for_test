<style>
.shadow-02 {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 0px 3px;
}

.btn-plane {
    font-size: 15px;
    font-weight: 600;
    padding: 10px 15px;
    border: 0px;
    background: transparent;
}

.btn-plane:hover {
    color: #318d01;
}

.btn-plane.active {
    background-color: #37a000;
    color: #fff;
    border-radius: 6px;
}
</style>
<div class="panel panel-bd shadow-02">
    <div class="panel-body">
        <a href="<?php echo base_url('accounts/AccReportController/financial_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="financial_report" || $this->uri->segment(3)=="general_ledger_report_search"?'active':''); ?>">
                <?php echo display('general_ledger') ?></button> </a>
        <a href="<?php echo base_url('accounts/AccReportController/sub_ledger_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="sub_ledger_report" || $this->uri->segment(3)=="sub_ledger_report_search"?'active':''); ?>">
                <?php echo display('sub_ledger') ?></button> </a>
        <a href="<?php echo base_url('accounts/AccReportController/sub_ledger_merged_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="sub_ledger_merged_report" || $this->uri->segment(3)=="sub_ledger_merged_report_search"?'active':''); ?>">
                <?php echo display('sub_ledger_merged') ?></button> </a>
        <a href="<?php echo base_url('accounts/AccReportController/trial_balance_financial_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="trial_balance_financial_report" || $this->uri->segment(3)=="trial_balance_report_search"?' active':''); ?>">
                <?php echo display('trial_balance') ?></button></a>
        <a href="<?php echo base_url('accounts/AccReportController/profit_loss_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="profit_loss_report" || $this->uri->segment(3)=="profit_loss_report_search"?' active':''); ?>"><?php echo display('profit_loss') ?></button></a>
        <a href="<?php echo base_url('accounts/AccReportController/income_statement') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="income_statement" || $this->uri->segment(3)=="income_statement"?' active':''); ?>"><?php echo display('income_statement') ?></button></a>
        <a href="<?php echo base_url('accounts/AccReportController/balance_sheet_report') ?>"><button
                class="btn-plane <?php echo ($this->uri->segment(3)=="balance_sheet_report" || $this->uri->segment(3)=="balance_sheet_report_search"?' active':''); ?>"><?php echo display('balance_sheet') ?></button></a>
    </div>
</div>