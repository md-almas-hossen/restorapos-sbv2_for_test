


                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">

                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('device/Device/setting'); ?>

                                <div class="form-group row">

                                    <label for="real_ip" class="col-xs-3 col-form-label">Use Real IP</label>

                                    <div class="col-xs-9">
                                        <select name="real_ip" id="real_ip" class="form-control">
                                                <option value=""></option>
                                                <option <?php echo $setting->real_ip==1?'selected':'';?> value="1">Yes</option>
                                                <option <?php echo $setting->real_ip==0?'selected':'';?> value="0">No</option>
                                        </select>
                                    </div>

                                </div>


                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>
            