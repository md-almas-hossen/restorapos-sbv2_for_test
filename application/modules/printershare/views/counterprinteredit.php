<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <?php echo  form_open('printershare/printer/addinvprinter') ?>
                     <?php echo form_hidden('pid', (!empty($intinfo->pid)?$intinfo->pid:null)) ?>
                        <div class="form-group row">
                      	<label for="counter" class="col-sm-4 col-form-label"><?php echo display('counter_no') ?> *</label>
                            <div class="col-sm-8">
                            <select name="counter" class="form-control">
                                <option value=""  selected="selected">Select Option</option>
                                <option value="9999">Default Printer</option>
                                <?php foreach($counterlist as $counter){?>
                                <option value="<?php echo $counter->counterno;?>" <?php if($intinfo->counterno==$counter->counterno){ echo "Selected";}?>><?php echo $counter->counterno;?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                      		<label for="ipaddress" class="col-sm-4 col-form-label"><?php echo display('ip_address') ?> *</label>
                            <div class="col-sm-8">
                          <input name="ipaddress" class="form-control" type="text" placeholder="<?php echo display('ip_address') ?>" id="ipaddress" value="<?php echo (!empty($intinfo->ipaddress)?$intinfo->ipaddress:null) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                      		<label for="ipport" class="col-sm-4 col-form-label"><?php echo display('printerport') ?> *</label>
                            <div class="col-sm-8">
                          <input name="ipport" class="form-control" type="text" placeholder="<?php echo display('printerport') ?>" id="ipport" value="<?php echo (!empty($intinfo->port)?$intinfo->port:null) ?>">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    <?php echo form_close() ?>

                </div>  
            </div>
        </div>
    </div>