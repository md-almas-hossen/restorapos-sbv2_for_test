<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('addopeningstock');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?php echo  form_open('purchase/purchase/openstock_entry') ?>
                                <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                                <input name="conversionvalue" type="hidden" id="conversionvalue">
                                <div class="form-group row">
                                    <label for="itemname" class="col-sm-4 col-form-label"><?php echo display('ingredient_name') 	
?>*</label>
                                    <div class="col-sm-8">
                                        <select name="foodid" class="form-control" id="foodid" required="">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                            <?php foreach($ingredients as $row){?>
                                            <option value="<?php echo $row->id; ?>"><?php echo $row->ingredient_name; ?>
                                                (<?php echo $row->uom_short_code; ?>)</option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="openstockqtystorage"
                                        class="col-sm-4 col-form-label"><?php echo display('qtn_storage') ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="openstockqtystorage" class="form-control" type="text"
                                            placeholder="<?php echo display('qtn_storage') ?>" id="openstockqtystorage"
                                            value="" required="" onkeyup="canculatevaluestorage()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="openstockqty"
                                        class="col-sm-4 col-form-label"><?php echo display('qtn_ingredient') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="openstockqty" class="form-control" type="text"
                                            placeholder="<?php echo display('qtn_ingredient') ?>" id="openstockqty"
                                            value="" required="" onkeyup="canculatevalue()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unitprice"
                                        class="col-sm-4 col-form-label"><?php echo display('s_rate') ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="unitprice" class="form-control" type="text"
                                            placeholder="<?php echo display('s_rate') ?>" id="unitprice" required="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="entrydate" class="col-sm-4 col-form-label"><?php echo display('date') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="entrydate" class="form-control datepicker5" type="text"
                                            placeholder="<?php echo display('date') ?>" id="entrydate"
                                            value="<?php echo date('Y-m-d');?>" required="">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('update_stock') ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div id="add5" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload');?></strong>
            </div>
            <div class="modal-body">
                <div class="row m-0">
                    <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE): ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('You_can_export_example_csv_file_Example');?>-<br /><a
                            class="btn btn-primary btn-md"
                            href="<?php echo base_url() ?>purchase/purchase/downloadstockformat"><i
                                class="fa fa-download"
                                aria-hidden="true"></i><?php echo display('Download_CSV_Format');?></a></h3>
                    <h4>ingredientname,openstock,UnitName,Date</h4>
                    <h4>Sugar,3,Kilogram,2023-01-01</h4>
                    <h2><?php echo display('upload_food_csv')?></h2>
                    <?php echo form_open_multipart('purchase/purchase/bulkstockupload',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_attendance'))?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                    <?php echo form_close()?>



                </div>

            </div>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <div class="panel-title">
                    <h4><?php echo display(kitchen_user_stock_report);?></h4>
                </div>
                <div class="form-group text-right">
                    <?php if( empty($openstock) && $this->permission->method('purchase','create')->access()): ?>



                    <a href="<?php echo base_url("purchase/purchase/add_opening_stock_multiple") ?>" type="button"
                        class="btn btn-success btn-md"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        <?php echo display('addopeningstock')?></a>
                    <button type="button" class="btn btn-success btn-md" data-target="#add5" data-toggle="modal"
                        data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-circle"
                            aria-hidden="true"></i>
                        <?php echo display('bulk_upload')?></button>




                    <?php endif; ?>

                </div>
            </div>


            <div class="panel-body" id="printArea">
                <div class="text-center">
                    <h3> <?php echo $setting->storename;?> </h3>

                </div>
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Financial Year</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody id="addinvoiceItem">
                        <?php 
                                    $sl=1;
                                    foreach ($openstock as $details) {
                                        
                          
                                        
                                        ?>
                        <tr>
                            <td><?php echo $details->title; ?></td>
                            <td><?php echo $details->end_date; ?></td>
                            <td><?php echo $details->total_amount; ?></td>

                            <td class="center">
                                <?php if($this->permission->method('purchase','update')->access()): ?>
                                <a href="<?php echo base_url('purchase/purchase/editOpeningStock/'.$details->id); ?>"
                                    class="btn btn-info btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <?php endif; 
                                    if($this->permission->method('purchase','delete')->access()): ?>
                                <a href="<?php echo base_url("purchase/purchase/deleteopen/$details->id") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php $sl++; } ?>
                    </tbody>

                </table>
            </div>
            <div class="text-center purchase_outstock" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint"
                    value="<?php echo display('print');?>" onclick="printDiv('printArea');" />

            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/purchase/assets/js/openstock.js'); ?>" type="text/javascript">
</script>