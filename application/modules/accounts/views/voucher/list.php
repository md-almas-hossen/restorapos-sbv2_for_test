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
    <div class="col-sm-12 m-b-10">
        <div class="btn-group pull-right form-inline">
            <?php if ($this->permission->method('accounts', 'create')->access()): ?>
            <div class="form-group">
                <a href="<?php echo base_url("accounts/AccVoucherController/voucher_form") ?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                    <?php echo display('create_voucher'); ?></a>
            </div>

            <?php
                $unpostedSalesVoucher = $this->db->select('count(*) as unposted')->from('bill b')->join('customer_order co', 'co.order_id = b.order_id', 'inner')->where('co.order_status', 4)->where('b.VoucherNumber', NULL)->get()->row();                
                $unpostedPurchaseVoucher = $this->db->select('count(*) as unposted')->from('purchaseitem')->where('VoucherNumber', NULL)->get()->row();
            ?>

            <!-- Button trigger modal -->
            <button style="margin-right:5px" type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
            Voucher Sync ( <?php echo $unpostedSalesVoucher->unposted + $unpostedPurchaseVoucher->unposted;?> )
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Voucher Sync</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Total Unposted Sales Voucher Number: <b><?php echo $unpostedSalesVoucher->unposted; ?></b></p>
                    <p>Total Unposted Purchase Voucher Number: <b><?php echo $unpostedPurchaseVoucher->unposted; ?></b></p>
                    <a class="btn btn-success" href="<?php echo base_url('accounts/AccVoucherController/voucher_sync') ?>">Sync Voucher</a>
                    <p><i><b>Note:</b> It will take some time</i></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>


            <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('filter') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo  form_open_multipart('accounts/AccVoucherController/getVoucherList', array('class' => 'form-inline','id'=>'filter-form')) ?>
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
                        <option value="0">Error Vouchers</option>
                    </select>
                </div>
                <div class="form-group form-group-new">
                    <label for="dtpToDate"><?php echo display('status') ?> :</label>
                </div>
                <div class="form-group form-group-new empdropdown">
                    <select name="status" id="status" class="form-control" required>
                        <option value="-1" selected>Both Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
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
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div>
                    <h4><?php echo display('vouchers') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('voucher_no') ?></th>
                                <th><?php echo display('date') ?></th>
                                <th><?php echo display('remark') ?></th>
                                <th class="text-right"><?php echo display('amount') ?></th>
                                <th class="text-center"><?php echo display('status') ?></th>
                                <th class="text-center"><?php echo display('action') ?></th>
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
<script src="<?php echo base_url('application/modules/accounts/assets/vouchers/voucher.js?v=6.9.9'); ?>"
    type="text/javascript"></script>