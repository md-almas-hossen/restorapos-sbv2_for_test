<div class="form-group text-right">
    <?php if($this->permission->method('setting','create')->access()): ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_currency_note')?></button>
    <?php endif; ?>

</div>
<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add_currency_note');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?php  echo form_open('setting/currency/save_currencynote') ?>
                                <?php echo form_hidden('currencyid', (!empty($intinfo->currencyid)?$intinfo->currencyid:null)) ?>
                                <div class="form-group row">
                                    <label for="title"
                                        class="col-sm-4 col-form-label"><?php echo display('note_name') ?> *</label>
                                    <div class="col-sm-8">
                                        <input name="title" class="form-control" type="text"
                                            placeholder="<?php echo display('note_name') ?>" id="title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="amount" class="form-control" type="text"
                                            placeholder="<?php echo display('amount') ?>" id="amount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="amount"
                                        class="col-sm-4 col-form-label"><?php echo display('ordering_no');?> *</label>
                                    <div class="col-sm-8">
                                        <input name="orderno" class="form-control" min="0" value="0" type="number"
                                            placeholder="Ordering No." id="orderno" required>
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
                <strong><?php echo display('currency_note_edit');?></strong>
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
                            <th><?php echo display('note_name') ?></th>
                            <th><?php echo display('amount') ?></th>
                            <th><?php echo display('ordering'); ?></th>
                            <th class="text-center"><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($currencynotelist)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($currencynotelist as $note) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $note->title; ?></td>
                            <td><?php echo $note->amount; ?></td>
                            <td><?php echo $note->orderpos; ?></td>
                            <td class="text-center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                                <input name="url" type="hidden" id="url_<?php echo $note->id; ?>"
                                    value="<?php echo base_url("setting/currency/noteupdatefrm") ?>" />
                                <a onclick="editinfo('<?php echo $note->id; ?>')" class="btn btn-info btn-sm"
                                    data-toggle="tooltip" data-placement="left"
                                    title="<?php echo display('update')?>"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <?php endif; 
										 if($this->permission->method('setting','delete')->access()): ?>
                                <a href="<?php echo base_url("setting/currency/note_delete/$note->id") ?>"
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