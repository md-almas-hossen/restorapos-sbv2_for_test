<div class="row">




    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">
            

            <div class="form-group text-right">
 <a href="<?php echo base_url('purchase/purchase/assignInventoryList') ;?>" class="btn btn-success btn-md"><i class="fa fa-arrow-left" aria-hidden="true"></i>
Back to Main List</a> 

</div>
                <fieldset class="border p-2">
                    <!-- <legend class="w-auto">Kitchen Name: <?php echo $list[0]->kitchen_name;?> </legend> -->
                </fieldset>

                

                <table class="datatable2 table table-striped table-bordered table-hover">

                <thead>
                    <th>Product Type</th>
                    <th>Product</th>
                    <th>Quantiity</th>
                    <th>Assign Date</th>
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
                       <td><?php echo $type;?></td>
                       <td><?php echo $li->ingredient_name;?></td>
                       <td><?php echo $li->product_qty.' '.$li->uom_short_code;?></td>
                       <td><?php echo $li->assigned_date;?></td>
                       
                    </tr>

                    <?php endforeach;?>

                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
