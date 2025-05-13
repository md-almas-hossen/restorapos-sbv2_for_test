<div class="form-group text-right">
    <?php if($this->permission->method('setting','create')->access()): ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add')?></button>
    <?php endif; ?>

</div>
<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?php echo  form_open('setting/vattype/create') ?>
                                <?php echo form_hidden('id', (!empty($intinfo->id) ? $intinfo->countryid : null)) ?>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-4 col-form-label"><?php echo display('name') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="name" class="form-control" type="text"
                                            placeholder="Add <?php echo display('name') ?>" id="name" value="">
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
                <strong><?php echo 'Edit Vat Type';?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
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

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('name') ?></th>
                            <th class="text-center"><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vattypelist)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($vattypelist as $type) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $type->name; ?></td>
                            <td class="text-center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                                <input name="url" type="hidden" id="url_<?php echo $type->id; ?>"
                                    value="<?php echo base_url("setting/vattype/updateintfrm") ?>" />
                                <a onclick="editinfo('<?php echo $type->id; ?>')" class="btn btn-info btn-sm"
                                    data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <?php endif; 
										 if($this->permission->method('setting','delete')->access()): ?>
                                <a href="<?php echo base_url("setting/vattype/deletecountry/$type->id") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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