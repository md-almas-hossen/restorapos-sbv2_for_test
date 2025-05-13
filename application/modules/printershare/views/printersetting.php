<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <fieldset class="border p-2">
                       <legend  class="w-auto"><?php echo display('pintersetting') ?></legend>
                    </fieldset>
					<div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab" id="option">
                                                <input id="radio-1760" type="radio" class="individual printerst" name="printtype" value="1" <?php if($pseting->printtype==1){ echo "checked";}?>>
                                                <label for="radio-1760" class="display-inline-flex">
                                                   <?php echo display('autop').' '.display('print') ?>
                                                </label>
                                                <input id="radio-1761" type="radio" class="individual printerst" name="printtype" value="2" <?php if($pseting->printtype==2){ echo "checked";}?>>
                                                <label for="radio-1761" class="display-inline-flex">                                                    
                                                   <?php echo display('manual').' '.display('print') ?>
                                                </label>
                                                <input id="radio-1762" type="radio" class="individual printerst" name="printtype" value="3" <?php if($pseting->printtype==3){ echo "checked";}?>>
                                                <label for="radio-1762" class="display-inline-flex">
                                                   <?php echo display('automanual').' '.display('print') ?>
                                                </label>
                                             
                            </div>
                        </div>
                   </div>      
      </div>
        </div>
    </div>
    <script src="<?php echo base_url('application/modules/printershare/assets/js/printersetting.js'); ?>" type="text/javascript"></script>
