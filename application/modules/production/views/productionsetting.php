<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">

                    <h4><?php echo $title; ?></h4>
                </div>
            </div>

            <div class="panel-body">

                <div class="row bg-brown">
                    <div class="col-sm-12 kitchen-tab" id="option">
                        <p class="productionset_rightg">
                            <strong class="productionset_color"><?php echo display('note');?>***:</strong>
                            <?php echo display('note1');?><br />
                            <?php echo display('note2');?>
                        </p>
                        <input id="chkbox-1760" type="checkbox" class="individual" name="productionsetting"
                            value="productionsetting" <?php if($possetting->productionsetting==1){ echo "checked";}?>>
                        <label for="chkbox-1760" class="productionsets_color">
                            <span class="radio-shape">
                                <i class="fa fa-check"></i>
                            </span>
                            <?php echo display('select_auto') ?>
                        </label>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/production/assets/js/production.js'); ?>" type="text/javascript">
</script>