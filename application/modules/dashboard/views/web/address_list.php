
 <div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd ">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
 					<?php echo  form_open('dashboard/web_setting/createaddress',array('id'=>'menuurl')) ?>
                        
                        <div class="form-group row">
                            <label for="widgettitle" class="col-sm-4 col-form-label"><?php echo display('location_zone') ?></label>
                            <div class="col-sm-8">
                                 <select class="form-control" onchange="getPrice(this.value)" name="zone_id" id="zone_id" require="">
                                     <option value="">--Select One--</option>
                                    <?php foreach($zone_list as $list){?>
                                    <option value="<?php echo $list->id;?>"><?php echo $list->zone_name;?></option>
                                    <?php } ?>
                                 </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="widgettitle" class="col-sm-4 col-form-label"><?php echo display('price') ?></label>
                            <div class="col-sm-8">
                                  <input type="text" value="0.00" class="form-control" name="price" id="price" readonly="">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="widgettitle" class="col-sm-4 col-form-label"><?php echo display('address') ?></label>
                            <div class="col-sm-8">
                            	 <textarea name="descp" id="descp" class="form-control"  placeholder="<?php echo display('address') ?>"  rows="4"></textarea>
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
                                <th><?php echo display('address') ?></th>
                                <th><?php echo display('price') ?></th>
                                <th><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($address_list)){ ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($address_list as $row) { 
                          
							$description=base64_encode($row->deladdress);  
							?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo $row->zone_name; ?></td>
                                <td><?php echo $row->deladdress; ?></td>
                                <td><?php echo $row->price; ?></td>
                                <td>
                                    <a onclick="editaddress('<?php echo $description; ?>',<?php echo $row->delivaryid; ?>,<?php echo $row->price; ?>,'<?php echo $row->zone_name; ?>',<?php echo $row->zone_id; ?>)"  data-toggle="tooltip" data-placement="left" title="Update" class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                   
                                    <a  href="<?php echo base_url('dashboard/web_setting/deleteaddress/'.$row->delivaryid);?>"  title="Update" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
					
							
							<?php  } }  ?>
                            
                        </tbody>
                    </table>


            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/dashboard/assest/js/address.js'); ?>" type="text/javascript"></script>

<script>
    function getPrice(id){

        var csrf = $('#csrfhashresarvation').val();
        $.ajax({
            url:basicinfo.baseurl+'dashboard/web_setting/getZonePrice',
            type: "POST",
            data: {
                id: id,
                csrf_test_name: csrf
            },
            success: function(data) {
               var obj= JSON.parse(data);
                $("#price").val(obj.price);
            },
            error: function(xhr) {
                alert('failed!');
            }
        }); 
    }
</script>





 