<div class="form-group text-right">
    <?php if($this->permission->method('add_po_request','create')->access()): ?>
    <a href="<?php echo base_url("purchase/purchase/add_po_request")?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_po_request')?></a>
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
                            <th><?php echo display('po_id'); ?></th>
                            <th><?php echo display('date') ?></th>
                            <th style="width:25%; padding: 8px 3%;" class='text-left'><?php echo display('action') ?>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                         if (!empty($getPoList)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($getPoList as $items) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td>
                                <!-- <a href="<?php echo base_url("purchase/purchase/purchaseinvoice/$items->id") ?>"> -->
                                <?php 
                                            
                                            // echo 'PO-' . $items->id; 
                                            echo $items->invoice_no;
                                            
                                            ?>
                                <!-- </a> -->
                            </td>
                            <td>
                                <?php $originalDate = $items->date;
									    echo $newDate = date("d-M-Y", strtotime($originalDate)); ?>
                            </td>




                            <td class="text-left" style="padding: 8px 4%;">
                                <?php if($this->permission->method('add_po_request','update')->access()): ?>

                                <?php if($items->status == 1 || $items->status == 6){ ?>

                                <input name="url" type="hidden" id="url_<?php echo $items->id; ?>"
                                    value="<?php echo base_url("purchase/purchase/poReqUpdateFrm") ?>" />

                                <a href="<?php echo base_url("purchase/purchase/poReqUpdateFrm/$items->id") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                <?php if($items->status == 6){ ?>

                                <a href="<?php echo base_url("purchase/purchase/poReqResend/$items->id") ?>"
                                    class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Resend"><i class="fa fa-refresh" aria-hidden="true"></i></a>

                                <?php } ?>
                                <?php } ?>

                                <?php endif;  ?>





                                <!-- here i have to add a modal -->
                                <a class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#example<?php echo $items->id?>">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <div class="modal fade bd-example-modal-lg" id="example<?php echo $items->id?>"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModal<?php echo $items->id?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: green; color: #fff;">
                                                <h5 style="font-size: large; position: relative; top: 14px;"
                                                    class="modal-title" id="example<?php echo $items->id?>">
                                                    <?php echo $items->invoice_no;?> Details</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span style="position: relative; bottom: 11px;"
                                                        aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <?php
                                                
                                                $po_details = $this->db->select('pod.*,i.conversion_unit')
                                                ->from('po_details_tbl pod')
                                                ->join('ingredients i', 'pod.productid = i.id')
                                                ->where('pod.po_id', $items->id)
                                                ->get()
                                                ->result();

                                               
                                                ?>



                                                <div class="table-responsive">
                                                    <table
                                                        class="datatable2 table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Products</th>
                                                                <th class="text-center">Asking QTY</th>
                                                                <th class="text-center">
                                                                    <?php echo display('qtn_storage') ?></th>
                                                                <th class="text-center">Delivered QTY</th>
                                                                <th class="text-center">Received QTY</th>
                                                                <th class="text-center">Unit Price</th>
                                                                <th class="text-center">Total Price</th>
                                                                <th class="text-center">Remark</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>


                                                            <?php foreach($po_details as $pod){?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <?php
                                                                                if($pod->producttype == 2){
                                                                                echo $product_name    = $this->db->select('*')->from('item_foods')->where('ProductsID', $pod->productid)->get()->row()->ProductName;
                                                                                }elseif($pod->producttype == 1){
                                                                                echo $ingredient_name = $this->db->select('*')->from('ingredients')->where('id', $pod->productid)->get()->row()->ingredient_name;
                                                                                }else{
                                                                                echo "Addons/Toppings";
                                                                                }
                                                                                ?>
                                                                </td>
                                                                <td class="text-center"> <?php echo $pod->quantity;?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo $pod->quantity/$pod->conversion_unit;?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo $pod->delivered_quantity;?> </td>
                                                                <td class="text-center">
                                                                    <?php echo $pod->delivered_quantity;?></td>
                                                                <td class="text-right"> <?php echo $pod->price;?> </td>
                                                                <td class="text-right">
                                                                    <?php echo $pod->price*$pod->delivered_quantity;?>
                                                                </td>
                                                                <td class="text-center"> <?php echo $pod->remark;?>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>



                                                        </tbody>
                                                    </table>
                                                </div>



                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!-- here end -->










                                <a href="<?php echo base_url("purchase/purchase/poReqDelete/$items->id") ?>"
                                    onclick="return confirm('Do you want to delete this?')"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>



                                <?php if($items->status == 1){ ?>
                                <span class="badge badge-pill badge-warning"
                                    style="color:white !important">Pending</span>
                                <?php }elseif($items->status == 2){ ?>
                                <span class="badge badge-pill badge-primary"
                                    style="color:white !important">Delivered</span>
                                <?php }elseif($items->status == 3){ ?>



                                <!-- <span class="badge badge-pill badge-info" style="color:white !important">Gate Passed</span> -->
                                <!-- <a href="<?php //echo base_url("purchase/purchase/poRequestReceived/$items->id") ?>" onclick="return confirm('Do you want to received this?')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Gate Passed">Gate Passed</a> -->


                                <a class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#exampleModal<?php echo $items->id?>">
                                    Gate Passed <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>

                                <!-- Modal -->
                                <div class="modal fade bd-example-modal-lg" id="exampleModal<?php echo $items->id?>"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel<?php echo $items->id?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: green; color: #fff;">
                                                <h5 style="font-size: large; position: relative; top: 14px;"
                                                    class="modal-title" id="exampleModalLabel<?php echo $items->id?>">
                                                    Invoice <?php echo $items->invoice_no;?> Gate Passed Details</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span style="position: relative; bottom: 11px;"
                                                        aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <?php
                                                
                                                $po_details = $this->db->select('*')
                                                ->from('po_details_tbl pod')
                                                ->join('ingredients i', 'pod.productid = i.id')
                                                ->where('pod.po_id', $items->id)
                                                ->get()
                                                ->result();

                                               
                                                ?>
                                                <form
                                                    action="<?php echo base_url("purchase/purchase/poRequestReceived/$items->id");?>"
                                                    method="POST">

                                                    <!-- Add the CSRF token field -->
                                                    <input type="hidden"
                                                        name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                                        value="<?php echo $this->security->get_csrf_hash(); ?>">


                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table striped table-hover">
                                                            <thead>
                                                                <tr style="background:#019868; color:#fff">
                                                                    <th style="border: none;"></th>
                                                                    <th class="text-center">Asking QTY</th>
                                                                    <th class="text-center">Delivered QTY</th>
                                                                    <th style="width: 17%;" class="text-center">Received
                                                                        QTY</th>
                                                                    <th class="text-center">Unit Price</th>
                                                                    <th class="text-center">Total Price</th>
                                                                    <th class="text-center">Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>


                                                                <?php foreach($po_details as $pod){?>
                                                                <tr>

                                                                    <td style="background: #019868 ;color: #fff; vertical-align: middle;"
                                                                        scope="row">

                                                                        <?php echo $pod->ingredient_name;?>

                                                                        <input type="hidden" name="po_details_id[]"
                                                                            value="<?php echo $pod->id;?>">
                                                                        <input type="hidden" name="ingredient_id[]"
                                                                            value="<?php echo $pod->productid;?>">
                                                                        <input type="hidden" name="ingredient_code[]"
                                                                            value="<?php echo $pod->ingredient_code;?>">
                                                                        <input type="hidden" name="variant_code[]"
                                                                            value="<?php echo $pod->variant_code;?>">

                                                                    </td>

                                                                    <td style="vertical-align: middle;">
                                                                        <?php echo $pod->quantity;?> </td>

                                                                    <td style="vertical-align: middle;">
                                                                        <?php echo $pod->delivered_quantity;?> </td>

                                                                    <td style="vertical-align: middle;">
                                                                        <input style="width:90%" type="text"
                                                                            class="form-control text-center"
                                                                            name="received_qty[]"
                                                                            id="rqty_<?php echo $pod->id;?>"
                                                                            onkeyup="total_price(<?php echo $pod->id;?>)"
                                                                            value="<?php echo $pod->delivered_quantity;?>">
                                                                    </td>

                                                                    <td
                                                                        style="vertical-align: middle; text-align:right">
                                                                        <?php echo $pod->price;?> </td>
                                                                    <td
                                                                        style="vertical-align: middle; text-align:right">

                                                                        <span id="tprice_<?php echo $pod->id;?>">
                                                                            <?php echo $pod->price*$pod->delivered_quantity;?>
                                                                        </span>

                                                                        <input type="hidden"
                                                                            id="dprice_<?php echo $pod->id;?>"
                                                                            value="<?php echo $pod->price;?> ">

                                                                    </td>
                                                                    <td
                                                                        style="vertical-align: middle; text-align:center">
                                                                        <?php echo $pod->remark;?></td>

                                                                </tr>
                                                                <?php } ?>


                                                                <script>
                                                                function total_price(id) {

                                                                    rqty = parseFloat($('#rqty_' + id).val());
                                                                    dprice = parseFloat($('#dprice_' + id).val());
                                                                    parseFloat($('#tprice_' + id).text(rqty * dprice))
                                                                        .tofixed(4);

                                                                }
                                                                </script>
                                                            </tbody>
                                                        </table>
                                                    </div>



                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Save changes</button>
                                            </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>





                                <?php }elseif($items->status == 4){ ?>
                                <span class="badge badge-pill badge-danger"
                                    style="color:white !important">Canceled</span>
                                <?php }elseif($items->status == 5){ ?>
                                <span class="badge badge-pill badge-success"
                                    style="color:white !important">Received</span>
                                <?php } elseif($items->status == 6){ ?>
                                <span class="badge badge-pill badge-danger" style="color:white !important">Failed</span>
                                <?php } ?>



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