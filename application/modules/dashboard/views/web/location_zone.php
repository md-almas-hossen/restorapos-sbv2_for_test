
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd ">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
 					<?php echo  form_open('dashboard/web_setting/createzone',array('id'=>'menuurl')) ?>
                        
 
                     <div class="form-group row">
                         <label for="widgettitle" class="col-sm-4 col-form-label"><?php echo display('location_zone') ?></label>
                         <div class="col-sm-8">
                              <input name="zone_name" id="zone_name" class="form-control"  placeholder="<?php echo display('location_zone') ?>" >
                         </div>
                     </div>
                        <div class="form-group row">
                            <label for="widgettitle" class="col-sm-4 col-form-label"><?php echo display('price') ?></label>
                            <div class="col-sm-8">
                                  <input type="text" value="0.00" class="form-control" name="price" id="price" >
                            </div>
                        </div>
                       
                        
                        <div class="form-group text-right" id="upbtn">
                            <button type="submit" class="btn btn-success w-md m-b-5 menu_dashboard_display" id="btnchnage" ><?php echo display('Ad') ?></button>
                        </div>
                    <?php echo form_close() ?>
                    <table class="table table-bordered table-hover" id="RoleTbl">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('location_zone') ?></th>
                                <th><?php echo display('price') ?></th>
                                <th><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($zone_list)){ ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($zone_list as $row) { 
                          
							// $description=base64_encode($row->deladdress);  
							?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo $row->zone_name; ?></td>
                                <td><?php echo $row->price; ?></td>
                                <td>
                                    <a onclick="editzone(<?php echo $row->id; ?>,<?php echo $row->price; ?>,'<?php echo $row->zone_name; ?>')"  data-toggle="tooltip" data-placement="left" title="Update" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                   
                                    <a  href="<?php echo base_url('dashboard/web_setting/deletezone/'.$row->id);?>"  title="Update" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
					
							
							<?php  } }  ?>
                            
                        </tbody>
                    </table>


            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript Document
// JavaScript Document
"use strict";
function editzone(id,price,zone_name){
    // alert(zone_name);
	// var option = new Option(zone_name,id, true, true);
	// $("#zone_id").append(option).trigger('change');
	
	$("#price").val(price);
	$("#zone_name").val(zone_name);
	$("#btnchnage").show();
	$("#btnchnage").text("Update");
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"dashboard/web_setting/editzone/"+id);
	$(window).scrollTop(0);
	}


</script>