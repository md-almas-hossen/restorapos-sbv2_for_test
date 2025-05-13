<div class="form-group text-right">
 <a href="<?php echo base_url('purchase/purchase/storeAssignInvetory') ;?>" class="btn btn-primary btn-md"><i class="fa fa-plus-circle" aria-hidden="true"></i>
Assign New</a> 

<a href="<?php echo base_url('purchase/purchase/assignInventoryListKitchenWise') ;?>" class="btn btn-success btn-md"><i class="fa fa-list" aria-hidden="true"></i>
Kitchen Wise List</a> 

</div>


<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default thumbnail">
               
        <div class="panel-heading">
            <div class="panel-title">
                <h4>Kitchen Assigns</h4>
            </div>
        </div>

            <div class="panel-body">

            <table class="datatable2 table table-striped table-bordered table-hover">
                <thead>
                    
                    <th>Product</th>
                    <th>Quantiity</th>
                    <th>Product Type</th>
                    <th>Kitchen</th>
                    <th>User</th>
                    <th>Assign Date</th>
                    <th>Action</th>
                </thead>
                <tbody>

                    <?php foreach($list as $key=>$li):?>

                        <?php
                            
                            if($li->product_type == 1){
                                $type = "Raw";
                            }elseif($li->product_type == 2){
                                $type = "Ingredient";
                            }else{
                                $type = "Addons";
                            }
                            
                        ?>

                    <tr>
                      
                       <td><?php echo $li->ingredient_name;?></td>
                       <td><?php echo $li->product_qty.' '.$li->uom_short_code;?></td>
                       <td><?php echo $type;?></td>
                       <td><?php echo $li->kitchen_name;?></td>
                       <td><?php echo $li->kitchen_user;?></td>
                       <td><?php echo $li->assigned_date;?></td>
                       <td>

                       <a href="<?php echo base_url('purchase/purchase/editAssignInventory/' . $li->id); ?>" class="btn btn-primary btn-sm">Edit <i class="fa fa-edit"></i></a>
                       <a href="<?php echo base_url('purchase/purchase/deleteAssignInventory/' . $li->id); ?>" class="btn btn-danger btn-sm">Delete <i class="fa fa-trash"></i></a>
                       

                       </td>
                    </tr>

                    <?php endforeach;?>

                </tbody>
            </table>
                
            </div>
        </div>
    </div>
</div>
