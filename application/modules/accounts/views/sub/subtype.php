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
                        <div class="panel panel-default thumbnail">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('accounts/subcontroller/create') ?>
                                <?php echo form_hidden('id', (!empty($intinfo->id) ? $intinfo->countryid : null)) ?>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-4 col-form-label"><?php echo display('name') ?>
                                        <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input name="name" class="form-control" type="text"
                                            placeholder="Add <?php echo display('name') ?>" id="name" value="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="code" class="col-sm-4 col-form-label"><?php echo display('code') ?><i
                                            class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <input name="code" class="form-control" type="text"
                                            placeholder="Add <?php echo display('code') ?>" id="code" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="isSystem"
                                        class="col-sm-4 col-form-label"><?php echo display('is_system');?></label>
                                    <div class="col-sm-8">
                                        <select name="isSystem" id="isSystem" class="form-control">
                                            <option value="" selected="selected">Select Option</option>
                                            <option value="1" Selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
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
                <strong><?php echo 'Edit';?></strong>
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
        <?php echo include($this->load->view('accounts/header/subcode_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title d-flex justify-content-between">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                    <div class="btn-group pull-right form-inline">
                        <div class="form-group">
                            <?php if($this->permission->method('setting','create')->access()): ?>
                            <button type="button" class="btn btn-success btn-md" data-target="#add0"
                                data-toggle="modal"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                <?php echo display('add')?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('name') ?></th>
                            <th><?php echo display('code') ?></th>
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
                            <td><?php echo $type->code; ?></td>
                            <td class="text-center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                                <input name="url" type="hidden" id="url_<?php echo $type->id; ?>"
                                    value="<?php echo base_url("accounts/subcontroller/updateintfrm") ?>" />
                                <a onclick="subtypeeditinfo('<?php echo $type->id; ?>')" class="btn btn-info btn-sm"
                                    data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <?php endif; 
										 if($this->permission->method('setting','delete')->access()): ?>
                                <a href="<?php echo base_url("accounts/subcontroller/deletesubtype/$type->id") ?>"
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

<script>
"use strict";

function subtypeeditinfo(id) {
    var geturl = $("#url_" + id).val();
    var myurl = geturl + '/' + id;
    var csrf = $('#csrfhashresarvation').val();
    var dataString = "id=" + id + "&csrf_test_name=" + csrf;

    $.ajax({
        type: "GET",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.editinfo').html(data);
            $('#edit').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy"
            });
        }
    });
}
</script>