  <?php 
  foreach ($suborderid as $id) {
  		if($paid_info->sub_id==$id){
			$status=1;
		}else{
			$status=0;
		}
       ?>
  <div class="col-md-6">
                                    <div class="info_part split-item" onclick="selectelement(this,<?php echo $status;?>)" data-value="<?php echo $id; ?>">
                                        <div class="table-topper">
                                            <div class="">

                                                <label for="chkbox-">
                                                  
                                                    <div>
                                                        <span class="display-block">Mridul <?php echo display('ord');?></span>
                                                    </div>
                                                </label>
                                                <table class="table table-modal table-title">
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo display('ord');?></td>
                                                            <td><?php echo $id; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <table class="table table-bordered table-modal table-info text-center" id="table-tbody-<?php echo $orderid;?>-<?php echo $id;?>">
                                            <thead>
                                                <tr>
                                                    <th><?php echo display('item');?></th>
                                                    <th><?php echo display('varient_name');?></th>
                                                    <th><?php echo display('unit_price');?></th>
                                                    <th><?php echo display('qty');?></th>
                                                     <th class="text-center"><?php echo display('total_price')?></th> 
                                                </tr>
                                            </thead>
                                            <tbody >

                                           

                                            </tbody>
                                            <input type="hidden" id="service-<?php echo $id;?>" value="<?php echo $sc;?>">
                                            <input type="hidden" id="orderid-<?php echo $id;?>" value="<?php echo $orderid;?>">
                                        </table>
                                       

                                            <div class="customer-select">
                                                <label for="customer" class="customer-label">Customer</label>
                                                <?php 
                                                                    echo form_dropdown('customer_name[]',$customerlist,(!empty($cusid)?$cusid:1),'class="form-control " id="customer-'.$id.'" required') ?>
                                                
                                            </div>
                                     
                                        <div class="submit_area">                                            
                                            <button class="btn btn-clear" id="subpay-<?php echo $id;?>" onclick="paySuborder(this)" data-url="<?php echo base_url().'ordermanage/order/paysuborder';?>"><?php echo display('pay_print')?></button>
                                        </div>
                                    </div>
                                </div>
                                   <?php                            
}
?>

 