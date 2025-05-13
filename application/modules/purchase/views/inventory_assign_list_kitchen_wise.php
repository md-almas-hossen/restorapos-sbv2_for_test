<?php 
    $path = base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png'));
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<div class="form-group text-right">
    <!-- create -->
    <?php if ($this->permission->check_label('assigned_kitchen')->create()->access()) : ?>
    <a href="<?php echo base_url('purchase/purchase/assigninventory'); ?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('assign_kitchen');?></a>
    <?php endif; ?>
    <!-- create -->
</div>


<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title;?></h4>
                </div>
            </div>

            <div class="panel-body">

                <table class="datatable2 table table-striped table-bordered table-hover">
                    <thead>

                        <th><?php echo display('code');?></th>
                        <th><?php echo display('kitchen_name');?></th>
                        <th><?php echo display('kitchen_user');?></th>
                        <th><?php echo display('assign_date');?></th>
                        <th><?php echo display('note');?></th>
                        <th><?php echo display('action');?></th>
                    </thead>
                    <tbody>

                        <?php foreach ($list as $key => $li) : ?>

                        <tr>
                            <td><?php echo $li->code; ?></td>
                            <td><?php echo $li->kitchen_name; ?></td>
                            <td><?php echo $li->kitchen_user; ?></td>
                            <td><?php echo $li->date; ?></td>
                            <td><?php echo $li->kitchennote; ?></td>
                            <td>

                                <!-- Modal View Starts -->
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target=".bd-example-modal-lg<?php echo $li->id; ?>"><?php echo display('view');?>
                                    <i class="fa fa-eye"></i></button>

                                <div id="myModal" class="modal fade bd-example-modal-lg<?php echo $li->id; ?>"
                                    tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: #019868; color: #fff; font-size: 16px">
                                                <p class="modal-title" id="exampleModalLabel"><?php echo $li->code; ?>
                                                    <?php echo display('details');?></p>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                        $details = $this->purchase_model->watchKitchenAssign($li->id);
                                                        ?>
                                                <div class="table-responsive" id="printArea">

                                                    <div class="text-center">
                                                        <img src="<?php echo  $base64; ?>" alt="logo">
                                                        <h3> <?php echo $setting->storename;?> </h3>
                                                        <h4><?php echo $setting->address;?> </h4>
                                                        <h4> <?php echo "Print Date" ?>:
                                                            <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                                    </div>

                                                    <table
                                                        class="datatable2 table table-striped table-bordered table-hover"
                                                        id="test">

                                                        <thead>
                                                            <th><?php echo display('product_type');?></th>
                                                            <th><?php echo display('product');?></th>
                                                            <th><?php echo display('qty');?></th>
                                                            <th><?php echo display('assign_date');?></th>
                                                        </thead>

                                                        <tbody>

                                                            <?php foreach ($details as $key => $data) : ?>

                                                            <?php

                                                                        if ($data->product_type == 1) {
                                                                            $type = "Raw";
                                                                        } elseif ($data->product_type == 2) {
                                                                            $type = "Ingredient";
                                                                        } else {
                                                                            $type = "Addons";
                                                                        }

                                                                        ?>

                                                            <tr>
                                                                <td><?php echo $type; ?></td>
                                                                <td><?php echo $data->ingredient_name; ?></td>
                                                                <td><?php echo $data->product_qty . ' ' . $data->uom_short_code; ?>
                                                                </td>
                                                                <td><?php echo $data->assigned_date; ?></td>

                                                            </tr>

                                                            <?php endforeach; ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-warning"
                                                    onclick="printDiv('printArea')"><?php echo display('print'); ?></button>
                                                <button id="closeModalBtn" type="button"
                                                    class="btn btn-sm btn-secondary"
                                                    data-dismiss="modal"><?php echo display('close');?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal View Ends -->

                                <!-- update -->
                                <?php if ($this->permission->check_label('assigned_kitchen')->update()->access()) : ?>
                                <?php if ($li->status == 0) :  ?>
                                <a href="<?php echo base_url('purchase/purchase/editAssignInventory/' . $li->id); ?>"
                                    class="btn btn-primary btn-sm"><?php echo display('edit');?> <i
                                        class="fa fa-edit"></i></a>
                                <?php endif; ?>
                                <?php endif; ?>
                                <!-- update -->

                                <!-- delete -->
                                <?php if ($this->permission->check_label('assigned_kitchen')->delete()->access()) : ?>
                                <a href="<?php echo base_url('purchase/purchase/deleteKitchenAssign/' . $li->id); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('<?php echo display('Are_you_sure_you_want_to_delete');?>');"><?php echo display('delete');?>
                                    <i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                                <!-- delete -->

                                <!-- make edit disable for admin -->
                                <?php
                                        $logged_in_user = $this->session->userdata('id');
                                        $check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;
                                        ?>

                                <?php if ($check != 1) : ?>
                                <?php if ($li->status == 0) :  ?>
                                <a href="<?php echo base_url('purchase/purchase/updateStatusAssignInventory/' . $li->id); ?>"
                                    class="btn btn-primary btn-sm">OK<i class="fa fa-check"></i></a>
                                <?php endif; ?>
                                <?php endif; ?>
                                <!-- make edit disable for admin -->

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    $('#test_filter').hide();
    $(".dt-buttons").hide();
    $(".dataTables_info").hide();

    window.print();
    document.body.innerHTML = originalContents;
    window.location.href = basicinfo.baseurl + "purchase/purchase/assigninventoryList";
}
</script>