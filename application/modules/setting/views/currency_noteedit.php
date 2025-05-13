<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <?php echo  form_open('setting/currency/save_currencynote'); 
					//print_r($intinfo);
					?>
                    <?php echo form_hidden('currencynoteid', (!empty($intinfo->id) ? $intinfo->id:null)) ?>
                        
  						<div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label"><?php echo display('note_name') ?> *</label>
                            <div class="col-sm-8">
                                <input name="title" class="form-control" type="text" placeholder="<?php echo display('note_name') ?>" id="title" value="<?php echo (!empty($intinfo->title) ? $intinfo->title:null) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount') ?> *</label>
                            <div class="col-sm-8">
                                <input name="amount" class="form-control" type="text" placeholder="<?php echo display('amount') ?>" id="amount" value="<?php echo (!empty($intinfo->amount)?$intinfo->amount:null) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                       <label for="amount" class="col-sm-4 col-form-label">Ordering No. *</label>
                            <div class="col-sm-8">
                        	<input name="orderno" class="form-control" type="number" placeholder="Ordering No." id="orderno" value="<?php echo (!empty($intinfo->orderpos)?$intinfo->orderpos:null) ?>" required>
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