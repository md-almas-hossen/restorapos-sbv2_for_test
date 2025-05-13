<div class="form-group text-right">
    <?php if($this->permission->method('setting','create')->access()): ?>
    <a href="<?php echo base_url('setting/InvoiceTemplate/templateForm');?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_invoice_template')?></a>
    <?php endif; ?>

</div>


<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd lobidrag ">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo (!empty($title) ? $title : null) ?>
                </div>
            </div>
            <?php 
        //    echo "<pre>";
        //    print_r($invoicelemplate);
        //    echo "</pre>";
           ?>
            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('layout_name');?></th>
                            <th><?php echo display('design_for'); ?></th>

                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoicelemplate)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($invoicelemplate as $templatelist) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $templatelist->layout_name; ?></td>
                            <td>
                                <?php 
                                if($templatelist->design_type==1){
                                 echo 'A4 (Normal)';
                                 }else{ 
                                    echo 'Pos Invoice';
                                }
                            ?>
                            </td>

                            <td class="center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                                <!-- <input name="url" type="hidden" id="url_<?php //echo $templatelist->id; ?>"
                                    value="<?php echo base_url("setting/InvoiceTemplate/updateintfrm/") ?>" /> -->
                                <a href="<?php echo base_url("setting/InvoiceTemplate/updateintfrm/$templatelist->id") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="<?php echo display('update')?>"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <?php endif; 
										 if($this->permission->method('setting','delete')->access()): ?>
                                <a href="<?php echo base_url("setting/InvoiceTemplate/delete/$templatelist->id") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="<?php echo display('delete')?> "><i class="fa fa-trash-o"
                                        aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>