
<?php $m = 0; ?>                  
<?php foreach ($allcategory as $value) { 
$allitem = $this->fooditem_model->allfooditem($value->CategoryID);
if (!empty($allitem)){
    ?>
    <input type="hidden" name="module[]" value="<?php echo $value->CategoryID;?>">
                    <table class="table table-bordered table-hover" id="RoleTbl">
                     	<h2><?php echo $value->Name;?></h2>
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('item_name') ?></th>
                                <th><div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" class="allcheck" name="allcreate" value="1" id="allcreate_<?php echo $m?>" title="create" usemap="<?php echo $m?>">
                                        <label for="allcreate_<?php echo $m?>"><strong><?php echo display('is_access') ?></strong></label>
                                    </div></th>
                            </tr>
                        </thead>
						
                        <tbody id="userselect">
                           
                            <?php $sl = 0; ?>
                            <?php foreach ($allitem as $iteminfo) {
								$ck_data = $this->db->select('*')
                                ->where('userid',$userid)
								->where('catid',$iteminfo->CategoryID)
                                ->where('menuid',$iteminfo->ProductsID)->get('tbl_itemwiseuser')->row();
								 ?>
                            <tr>
                                <td><?php echo $sl+1; ?></td>
                                <td class="text"><?php echo $iteminfo->ProductName; ?></td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" class="create_<?php echo $m?>" name="create[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->isacccess==1)?"checked":null) ?> id="create[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="create[<?php echo $m?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <input type="hidden" name="menu_id[<?php echo $m?>][<?php echo $sl ?>][]" value="<?php echo $iteminfo->ProductsID?>">
                            </tr>
                            <?php $sl++ ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    
 <?php $m++; }  } ?>
                    <div class="form-group text-right">
                       <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
<script src="<?php echo base_url('application/modules/itemmanage/assets/js/itemsaaign.js'); ?>" type="text/javascript"></script>
