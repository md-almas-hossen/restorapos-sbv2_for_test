<div class="form-group text-right">
    <?php if($this->permission->method('purchase','create')->access()): ?>
    <a href="<?php echo base_url("purchase/purchase/addadjustment")?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('addadjustment')?></a>
    <?php endif; ?>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('adjustment_no'); ?></th>
                            <th><?php echo display('referenceno') ?></th>
                            <th><?php echo display('adjustdate') ?></th>
                            <th><?php echo display('adjusted_by') ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchaselist)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($purchaselist as $items) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td>
                                <a href="<?php echo base_url("purchase/purchase/adjustinvoice/$items->addjustid") ?>">
                                    <?php echo $items->adjustment_no; ?>
                                </a>
                            </td>
                            <td><?php echo $items->refarenceno; ?></td>
                            <td><?php $originalDate = $items->adjustdate;
									echo $newDate = date("d-M-Y", strtotime($originalDate));
									 ?></td>
                            <td><?php echo $items->savedby; ?></td>
                            <td class="center">
                                <?php if($this->permission->method('purchase','update')->access()): ?>
                                <input name="url" type="hidden" id="url_<?php echo $items->addjustid; ?>"
                                    value="<?php echo base_url("purchase/purchase/updateadjfrm") ?>" />
                                <a href="<?php echo base_url("purchase/purchase/updateadjfrm/$items->addjustid") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <?php endif;  ?>
                            </td>
                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
                <div class="text-right"><?php echo @$links?></div>
            </div>
        </div>
    </div>
</div>