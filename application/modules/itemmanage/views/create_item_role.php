<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/dashboard/assest/css/role.css'); ?>">
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd ">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <?php echo form_open("itemmanage/item_food/save_useritem") ?>
            <div class="panel-body">

                    <div class="form-group row">
                        <label for="role_name" class="col-xs-3 col-form-label"><?php echo display('user') ?> <i class="text-danger">*</i></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="user_id" id="user_id" required="" onchange="getuserinf(this.value)">
                                        <option value="">--Select--</option>
                                        <?php 
                                           foreach($alluser as $val){
                                            echo '<option value="'.$val->id.'">'.$val->firstname.' '.$val->lastname.'.</option>';
                                        }
                                        ?>
                                    </select>
                        </div>
                    </div>
				<div id="loadmenus"></div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/itemmanage/assets/js/itemsaaign.js'); ?>" type="text/javascript"></script>