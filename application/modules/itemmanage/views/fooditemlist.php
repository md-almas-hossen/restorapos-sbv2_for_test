<div class="modal fade" id="createbarcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title" id="exampleModalLabel">Barcode Create</h5>
                </div>
      <div class="modal-body pd-15">
        	<div class="row">
            	<div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="user_email">How Many Barcode Would You Like to print!!!</label>
                                                <input type="number" min="1" id="numberofbarcode" class="form-control" name="totalbarcode">
                                                <input name="barid" id="barid" type="hidden" />
                                                
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <a class="btn btn-success btn-md text-white" id="barcgenerate">Create Barcode</a>
                                        </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail"> 
        <div class="panel-heading">
                <div class="panel-title d-flex justify-content-between align-items-center">
                    <h4><?php echo (!empty($title)?$title:null) ?> </h4>
                    <div class="pull-right font-p-fw d-flex align-items-center"><select name="kitchen" class="form-control" id="kitchenid">
<option value="" selected="selected"><?php echo display('kitchen_name') ?></option> 
                            <?php foreach($allkitchen as $kitchen){?>
                            <option value="<?php echo $kitchen->kitchenid;?>" class='bolden' <?php if($productinfo->kitchenid==$kitchen->kitchenid){echo "selected";}?>><strong><?php echo $kitchen->kitchen_name;?></strong></option>
                            <?php } ?>

</select>
&nbsp; <button type="button" class="width-100 btn btn-success" onclick="kitchecnassign()"><?php echo display('assign_kitchen_op');?></button>
</div>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="allselect" value="all" id="allselect" name="allselect" onclick="checkAllFood()" /><?php echo display('serial') ?></th>
                            <th><?php echo display('image') ?></th>
                            <th><?php echo display('foodcode'); ?></th>
                            <th><?php echo display('app_kitchen'); ?></th>
                            <th><?php echo display('category_name') ?></th>
                            <th><?php echo display('item_name') ?></th> 
                            <th><?php echo display('ftposition') ?></th>
                            <th><?php echo display('component') ?></th>
                            <th><?php echo display('vat_tax') ?></th>  
                            <th><?php echo display('status') ?></th>
                            <th><?php echo display('action') ?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($fooditemslist)) { 
						?>
                            <?php $sl =  $pagenum+1; ?>
                            <?php foreach ($fooditemslist as $fooditems) { 
							$kitchen=$this->fooditem_model->read('kitchen_name', 'tbl_kitchen', array('kitchenid' => $fooditems->kitchenid));

                            // Evaluate the Vat 
                            
                            $vat = '';
                            for($i=0; $i<$total_vat_tax_count; $i++){
                                $tax_no = 'tax'.$i;
                                if($i==0){
                                    $vat = $fooditems->$tax_no != null ? $fooditems->$tax_no.' %':'0 %';
                                }else{
                                    $vat .= $fooditems->$tax_no != null ? ' , '.$fooditems->$tax_no.' %':' , 0 %';
                                }
                                // echo $vat;
                            }

                            // End
							?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><input type="checkbox" class="singleselect" name="singleselect" value="<?php echo $fooditems->ProductsID;?>" id="single_<?php echo $fooditems->ProductsID;?>" />&nbsp;<?php echo $sl; ?></td>
                                    <td><img src="<?php echo base_url(!empty($fooditems->ProductImage)?$fooditems->ProductImage:'assets/img/icons/default.jpg'); ?>" alt="Image" width="80" ></td>
                                     <td><?php echo $fooditems->foodcode; ?></td>
                                     <td><?php echo $kitchen->kitchen_name; ?></td>
                                     <td><?php echo $fooditems->Name; ?></td>
                                    <td><?php echo $fooditems->ProductName; ?></td>
                                    <td><?php echo $fooditems->itemordering; ?></td>
                                    <td><?php echo $fooditems->component; ?></td>
                                    <td><?php echo $vat; ?></td>
                                    <td><?php if($fooditems->ProductsIsActive==1){echo "Active";}else{echo "Inactive";} ?></td>
                                    <td class="center">
                                    <?php if($this->permission->method('itemmanage','update')->access()): 
									if($fooditems->isgroup==1){
									?>

                                        <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                            <a href="<?php echo base_url("itemmanage/item_food/addgroupfood/$fooditems->ProductsID") ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                         
                                        <?php }?>
                                         
                                         <?php 
									}else{?>

                                        <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                            <a href="<?php echo base_url("itemmanage/item_food/create/$fooditems->ProductsID") ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                        
                                        <?php }else{?>
                                            <!-- For View button when  as SUB BRANCH Mode -->
                                            <a href="<?php echo base_url("itemmanage/item_food/viewFood/$fooditems->ProductsID") ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-eye" aria-hidden="true"></i></a> 

                                        <?php } ?>

                                        <a onclick="barcodemodal(<?php echo $fooditems->ProductsID;?>);" class="btn btn-info btn-sm" title="Barcode Print"><i class="fa fa-barcode" aria-hidden="true"></i></a> 

                                    <?php }endif; 
										 if($this->permission->method('itemmanage','delete')->access()): 
										 ?>

                                            <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                            <a href="<?php echo base_url("itemmanage/item_food/delete/$fooditems->ProductsID") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>  
                                            
                                            <?php }?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
                 <div class="text-right"></div>
            </div>
        </div>
    </div>
</div>
 
<script>
function checkAllFood() {
			if( $('#allselect').is(':checked') ){
				$(".singleselect").prop('checked', true); 
			}
			else{
				$(".singleselect").prop('checked', false);
			}
     }
function kitchecnassign(){
	   var myurl =baseurl+'itemmanage/item_food/bulkAssignkitchen';
	   var csrf = $('#csrfhashresarvation').val();
	   var kitchenid = $('#kitchenid').val();
	   if(kitchenid==''){
		alert("Please Select Kitchen!!!");
		return false;   
	   }
	   var array = [];
	   $("input:checkbox[name='singleselect']:checked").each(function(){    
  			array.push($(this).val());    		
  		});
	   var dataString = "foodid="+array+"&kitchenid="+kitchenid+"&csrf_test_name="+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#kitchenid').select2().val('').trigger('change');
			 alert("Kitchen Assigned Done!!!");  
			 window.location.href=baseurl+'itemmanage/foodlist';
		 	} 
		});
	}
</script>