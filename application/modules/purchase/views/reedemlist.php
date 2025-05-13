<?php 
    $path = base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png'));
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<div class="form-group text-right">
    <!-- create -->
    <?php if ($this->permission->check_label('reedem_list')->create()->access()) : ?>

    <a href="<?php echo base_url('purchase/purchase/addReedem'); ?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('new_consumption');?></a>

    <?php endif;?>
    <!-- cretae -->

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
                        <th><?php echo display('consumption_by');?></th>
                        <th><?php echo display('consumption_date');?></th>
                        <th><?php echo display('action');?></th>
                    </thead>
                    <tbody>

                        <?php foreach ($list as $key => $li) : ?>



                        <tr>
                            <td><?php echo $li->code; ?></td>
                            <td><?php echo $li->kitchen_name; ?></td>
                            <td><?php echo $li->reedem_user; ?></td>
                            <td><?php echo $li->date; ?></td>
                            <td>

                                <!-- edit -->
                                <?php if ($this->permission->check_label('reedem_list')->update()->access()) : ?>
                                <a href="<?php echo base_url('purchase/purchase/editReedem/' . $li->id); ?>"
                                    class="btn btn-primary btn-sm"><?php echo display('edit');?> <i
                                        class="fa fa-edit"></i></a>
                                <?php endif;?>
                                <!-- edit -->

                                <!-- delete -->
                                <?php if ($this->permission->check_label('reedem_list')->delete()->access()) : ?>
                                <a href="<?php echo base_url('purchase/purchase/deleteReedem/' . $li->id); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('<?php echo display('Are_you_sure_you_want_to_delete');?>');"><?php echo display('delete');?>
                                    <i class="fa fa-trash"></i></a>
                                <?php endif;?>
                                <!-- delete -->

                                <!-- Modal View Starts -->
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target=".bd-example-modal-lg<?php echo $li->id; ?>"><?php echo display('view');?>
                                    <i class="fa fa-eye"></i></button>

                                <div class="modal fade bd-example-modal-lg<?php echo $li->id; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: #019868; color: #fff; font-size: 16px">
                                                <p class="modal-title" id="exampleModalLabel"><?php echo $li->code; ?>
                                                    <?php echo display('details');?></p>
                                            </div>
                                            <div class="modal-body">

                                                <?php

                                                    $details = $this->db->select('trd.*, i.ingredient_name')->from('tbl_reedem_details trd')
                                                        ->join('ingredients i', 'trd.product_id = i.id', 'left')
                                                        ->where('trd.reedem_id', $li->id)->get()->result();

                                                
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
                                                            <th><?php echo display('name');?></th>
                                                            <th><?php echo display('used');?></th>
                                                            <th><?php echo display('wastage');?></th>
                                                            <th><?php echo display('expired');?></th>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($details as $detail):?>
                                                            <tr>
                                                                <td>
                                                                    <?php 
                                                                        if($detail->product_type==1){
                                                                            echo 'Raw';
                                                                        }elseif($detail->product_type==2){
                                                                            echo 'Finish';
                                                                        }else{
                                                                            echo 'Addons';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $detail->ingredient_name;?></td>
                                                                <td><?php echo $detail->used_qty;?></td>
                                                                <td><?php echo $detail->wastage_qty;?></td>
                                                                <td><?php echo $detail->expired_qty;?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-warning"
                                                    onclick="printDiv('printArea')"><?php echo display('print'); ?></button>
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-dismiss="modal"><?php echo display('close'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal View Ends -->

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
    window.location.href = basicinfo.baseurl + "purchase/purchase/reedemList";
}
</script>