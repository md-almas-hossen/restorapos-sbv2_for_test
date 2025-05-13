<style>
.pagination>.active>a,
.pagination>.active>a:focus,
.pagination>.active>a:hover,
.pagination>.active>span,
.pagination>.active>span:focus,
.pagination>.active>span:hover {
    z-index: 3;
    color: #fff;
    cursor: default;
    background-color: #37a000;
    border-color: #37a000;
}


.pagination>li>a,
.pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #37a000;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}
</style>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('voucher_approval') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo  form_open_multipart('accounts/AccPendingVoucherController/getPendingVoucherList_another', array('class' => 'form-inline','id'=>'filter-form')) ?>
                <div class="form-group form-group-new">
                    <label for="dtpFromDate"><?php echo display('financial_year') ?> :</label>
                </div>
                <div class="form-group form-group-new empdropdown">
                    <select id="financial_year" class="form-control" name="dtpYear">
                        <option value="">Select Financial Year</option>
                        <?php foreach ($financial_years as $year): ?>
                        <option value="<?php echo $year['title']; ?>"
                            data-start_date="<?php echo $year['start_date']; ?>"
                            data-end_date="<?php echo $year['end_date']; ?>"
                            <?php echo (isset($financialyears) && $financialyears->title == $year['title']) ? 'selected' : ''; ?>>
                            <?php echo $year['title']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-group-new">
                    <label for="dtpFromDate"><?php echo display('from_date') ?> :</label>
                    <input type="text" name="dtpFromDate"
                        value="<?php echo isset($financialyears) ? $financialyears->start_date : ''; ?>"
                        class="form-control" id="from_date" />
                </div>
                <div class="form-group form-group-new">
                    <label for="dtpToDate"><?php echo display('to_date') ?> :</label>
                    <input type="text" class="form-control" name="dtpToDate"
                        value="<?php echo isset($financialyears) ? $financialyears->end_date : ''; ?>" id="to_date" />
                </div>
                <div class="form-group form-group-new">
                    <label><?php echo display('voucher_type') ?> :</label>
                </div>

                <div class="form-group form-group-new empdropdown">
                    <select name="voucher_type" id="voucher_type" class="form-control" required>
                        <option value="-1" selected>All Vouchers</option>
                        <?php foreach ($voucher_types as $key => $type) { ?>
                        <option value="<?php echo  $type->id; ?>"><?php echo $type->name; ?></option>
                        <?php } ?>
                    </select>
                </div>




                <div class="form-group form-group-new">
                    <label for="row">Row :</label>
                </div>
                <div class="form-group form-group-new empdropdown">
                    <select id="row" class="form-control" name="row">

                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="-1">All</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                <?php echo form_close() ?>
            </div>
        </div>



        <div class="panel-heading panel-aligner">
            <div class="panel-title">
                <h4 class="text-center">
                    <div class="btn-group pull-center form-inline">
                        <?php if ($this->permission->method('accounts', 'create')->access()): ?>
                        <div class="form-group">
                            <?php $attributes = array('id' => 'voucherApprovalForm');
                                echo form_open_multipart('accounts/AccPendingVoucherController/voucher_approved', $attributes); ?>
                            <button type="submit"
                                class="btn btn-success"><?php echo display('approved_all_check');?></button>
                            <?php echo  form_close() ?>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">


                            <?php if ($this->permission->method('accounts', 'create')->access()): ?>
                            <div class="form-group">
                                <a href="<?php echo base_url("accounts/AccVoucherController/voucher_form") ?>"
                                    class="btn btn-success btn-md pull-right"><i class="fa fa-plus-circle"
                                        aria-hidden="true"></i>
                                    <?php echo display('create_voucher'); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>
                </h4>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">

            <div class="panel-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="pendingvouchers">
                        <thead>
                            <tr>

                                <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"
                                    style="width: 163.889px;" aria-label="Check All">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="selectall"
                                            autocomplete="off">
                                        <label class="form-check-label" for="selectall">
                                            Check All </label>
                                    </div>
                                </th>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('voucher_no') ?></th>
                                <th><?php echo display('date') ?></th>
                                <th><?php echo display('remark') ?></th>
                                <th class="text-right"><?php echo display('amount') ?></th>
                                <th class="text-center"><?php echo display('status') ?></th>
                            </tr>
                        </thead>
                        <tbody id="ledger-body">
                            <tr class="text-center">
                                <td colspan="7">No Data Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <nav class="text-right" aria-label="Page navigation">
                    <ul id="pagination" class="pagination justify-content-center"></ul>
                </nav>



            </div>
        </div>
    </div>
</div>
<?php echo include($this->load->view('accounts/modal/voucher_details')) ?>
<script src="<?php echo base_url('application/modules/accounts/assets/vouchers/voucher_another.js?v=2.1'); ?>"
    type="text/javascript"></script>