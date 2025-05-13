<!-- work of this file -->
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('set_productionunit') ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="btn-group pull-right">
                        <?php if($this->permission->method('production','create')->access()): ?>
                        <a href="<?php echo base_url("production/production/productionunit")?>"
                            class="btn btn-success btn-md"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                            <?php echo display('production_add')?></a>
                        <?php endif; ?>
                    </div>
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <!--  -->

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('item_name') ?></th>
                            <th><?php echo display('varient_name') ?></th>
                            <th><?php echo display('production_cost') ?></th>
                            <th><?php echo display('selling_price') ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productionlist)) {
                            $this->load->model('production/production_model', 'productionmodel');
                            ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($productionlist as $items) {
                                $pitemid = $items->foodid;
                                $pvid = $items->variantid;
                                $lastupdateprice = $this->productionmodel->iteminfo($pitemid, $pvid);
                                $totalcost = 0;
                                if ($lastupdateprice) {
                                    foreach ($lastupdateprice as $itemprice) {
                                        $i++;
                                        $totalcost = number_format($itemprice->uprice, 3, '.', '') * $itemprice->qty + $totalcost;
                                    }
                                }

                            ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $items->ProductName; ?></td>
                            <td><?php echo $items->variantName; ?></td>
                            <td><?php echo number_format($totalcost, 3); ?></td>
                            <td><?php echo number_format($items->sellprice,3); ?></td>
                            <td class="center">
                                <input name="url" type="hidden"
                                    id="url_<?php echo $items->foodid . $items->variantid; ?>"
                                    value="<?php echo base_url("production/production/getset/$items->foodid/$items->variantid") ?>" />
                                <?php if ($this->permission->method('production', 'update')->access()) : ?>
                                <a href="<?php echo base_url("production/production/updateintfrm/$items->foodid/$items->variantid") ?>"
                                    class="btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <a onclick="editsetinfo(<?php echo $items->foodid . $items->variantid; ?>)"
                                    class="btn-success btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
                <div class="text-right"></div>
            </div>
        </div>
    </div>
</div>